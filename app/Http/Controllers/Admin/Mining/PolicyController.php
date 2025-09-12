<?php

namespace App\Http\Controllers\Admin\Mining;

use App\Exports\StakingPolicyExport;
use App\Models\Coin;
use App\Models\Mining;
use App\Models\MiningPolicy;
use App\Models\MiningPolicyTranslation;
use App\Models\LanguagePolicy;
use App\Models\PolicyModifyLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PolicyController extends Controller
{

    public function index(Request $request)
    {
        $coins = Coin::all();
        $policies = MiningPolicy::get();

        return view('admin.mining.policy.list', compact('coins', 'policies'));

    }

    public function view(Request $request)
    {
        $coins = Coin::all();
        $locale = LanguagePolicy::where('type', 'locale')->first()->content;

        switch  ($request->mode) {
            case 'create' :

                return view('admin.mining.policy.create', compact('coins', 'locale'));

            case 'translation' :

                $policy = MiningPolicy::find($request->id);
                $view = MiningPolicyTranslation::where('policy_id', $policy->id)->get();

                return view('admin.mining.policy.view-translation', compact( 'policy','view'));

            case 'policy' :

                $view = MiningPolicy::find($request->id);

                $modify_logs = PolicyModifyLog::join('mining_policies', 'mining_policies.id', '=', 'policy_modify_logs.policy_id')
                    ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
                    ->select('admins.name', 'policy_modify_logs.*')
                    ->where('policy_modify_logs.policy_type', 'mining_policies')
                    ->where('policy_modify_logs.policy_id', $request->id)
                    ->whereNotIn('policy_modify_logs.column_name', ['exchange_rate', 'node_amount'])
                    ->orderBy('policy_modify_logs.created_at', 'desc')
                    ->get();

                return view('admin.mining.policy.view-policy', compact('coins', 'view', 'modify_logs'));

            default :

                $view = MiningPolicy::find($request->id);

                $modify_logs = PolicyModifyLog::join('mining_policies', 'mining_policies.id', '=', 'policy_modify_logs.policy_id')
                    ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
                    ->select('admins.name', 'policy_modify_logs.*')
                    ->where('policy_modify_logs.policy_type', 'mining_policies')
                    ->where('policy_modify_logs.policy_id', $request->id)
                    ->whereIn('policy_modify_logs.column_name', ['exchange_rate', 'node_amount'])
                    ->orderBy('policy_modify_logs.created_at', 'desc')
                    ->get();

                return view('admin.mining.policy.view-mining', compact( 'view' , 'modify_logs'));

        }
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            $data = $request->except('translation');
            $mining_policy = MiningPolicy::create($data);

            $locales = $request->translation;

            foreach ($locales as $code => $locale) {
                MiningPolicyTranslation::create([
                    'policy_id' => $mining_policy->id,
                    'locale' => $code,
                    'name' => $locale['name'],
                    'memo' => $locale['memo'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => '노드 마이닝 상품이 추가되었습니다.',
                'url' => route('admin.mining.policy'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to create mining policy', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => '예기치 못한 오류가 발생했습니다.',
            ]);
        }

    }

    public function update(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $mining_policy = MiningPolicy::findOrFail($request->id);

                switch ($request->mode) {
                    case 'exchange' :

                        $mining_policy->update(['exchange_rate' => $request->exchange_rate]);

                        return response()->json([
                            'status' => 'success',
                            'message' => '환율이 변경되었습니다.',
                            'url' => route('admin.mining.policy.view', ['mode' => 'mining', 'id' => $mining_policy->id]),
                        ]);

                    case 'node' :

                        $mining_policy->update(['node_amount' => $request->node_amount]);

                        return response()->json([
                            'status' => 'success',
                            'message' => '채굴값이 변경되었습니다.',
                            'url' => route('admin.mining.policy.view', ['mode' => 'mining', 'id' => $mining_policy->id]),
                        ]);

                    case 'translation' :

                        $locales = $request->translation;

                        foreach ($locales as $code => $locale) {

                            $translation = MiningPolicyTranslation::where('policy_id', $request->id)
                                ->where('locale', $code)
                                ->first();
                            $translation->update([
                                'name' => $locale['name'],
                                'memo' => $locale['memo'],
                            ]);
                        }

                        return response()->json([
                            'status' => 'success',
                            'message' => '다국어 번역이 수정되었습니다.',
                            'url' => route('admin.mining.policy.view', ['mode' => 'translation', 'id' => $mining_policy->id]),
                        ]);

                    default :

                        $data = $request->except(['exchange_rate', 'node_amount', 'mode']);

                        $mining_policy->update($data);

                        return response()->json([
                            'status' => 'success',
                            'message' => '정책이 수정되었습니다.',
                            'url' => route('admin.mining.policy.view', ['mode' => 'policy', 'id' => $mining_policy->id]),
                        ]);
                }
            });

        } catch (\Exception $e) {

            Log::error('Failed to update mining policy', ['error' => $e->getMessage()]);

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
