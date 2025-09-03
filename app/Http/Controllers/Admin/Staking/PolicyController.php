<?php

namespace App\Http\Controllers\Admin\Staking;

use App\Exports\StakingPolicyExport;
use App\Models\Coin;
use App\Models\Staking;
use App\Models\StakingPolicy;
use App\Models\StakingPolicyTranslation;
use App\Models\LanguagePolicy;
use App\Models\PolicyModifyLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PolicyController extends Controller
{

    public function index(Request $request)
    {
        $coin_code = optional(Coin::find($request->id))->code;
        $coins = Coin::all();
        $policies = StakingPolicy::where('coin_id', $request->id)->get();
/*
        $modify_logs = PolicyModifyLog::join('staking_policies', 'staking_policies.id', '=', 'policy_modify_logs.policy_id')
            ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
            ->select('staking_policies.staking_name', 'admins.name', 'policy_modify_logs.*')
            ->where('policy_modify_logs.policy_type', 'staking_policies_'.$coin_code)
            ->orderBy('policy_modify_logs.created_at', 'desc')
            ->get();
*/
        return view('admin.staking.policy', compact('coins', 'policies'));
        
    }

    public function view(Request $request)
    {
        $coins = Coin::all();
        $locale = LanguagePolicy::where('type', 'locale')->first()->content;
        
        switch  ($request->mode) {
            case 'create' :
                return view('admin.staking.policy-create', compact('coins', 'locale'));
            break;

            case 'view' :
                $view = StakingPolicy::with('translations')->find($request->id);

                return view('admin.staking.policy-view', compact('coins', 'locale', 'view'));
            break;
        }
    }

    public function store(Request $request) 
    {

        DB::beginTransaction();

        try {
              
            $data = $request->except('translation');
            $staking_policy = StakingPolicy::create($data);

            $locales = $request->translation;
            
            foreach ($locales as $code => $locale) {
                StakingPolicyTranslation::create([
                    'policy_id' => $staking_policy->id,
                    'locale' => $code,
                    'name' => $locale['name'],
                    'memo' => $locale['memo'],
                ]);
            }
                
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => '스테이킹 상품이 추가되었습니다.',
                'url' => route('admin.staking.policy', ['id' => $request->coin_id]),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Failed to create staking policy', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => '예기치 못한 오류가 발생했습니다.',
            ]);
        }

    }

    public function update(Request $request) 
    {
        DB::beginTransaction();

        try {
            
            $staking_policy = StakingPolicy::findOrFail($request->id); 
            
            $data = $request->except('translation');
            $staking_policy->update($data);

            $locales = $request->translation;
            
            foreach ($locales as $code => $locale) {
            
                $translation = StakingPolicyTranslation::where('policy_id', $request->id)
                    ->where('locale', $code)
                    ->first();

                $translation->update([
                    'name' => $locale['name'],
                    'memo' => $locale['memo'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => '정책이 수정되었습니다.',
                'url' => route('admin.staking.policy', ['id' => $staking_policy->coin_id]),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Failed to update staking policy', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => '예기치 못한 오류가 발생했습니다.',
            ]);
        }
    }

    public function export()
    { 
        $current = now()->toDateString();

        return Excel::download(new StakingPolicyExport(), '스테이킹 상품 내역 '.$current.'.xlsx');
    }
}