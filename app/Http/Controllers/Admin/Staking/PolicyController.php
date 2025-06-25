<?php

namespace App\Http\Controllers\Admin\Staking;

use App\Exports\StakingPolicyExport;
use App\Models\Coin;
use App\Models\Staking;
use App\Models\StakingPolicy;
use App\Models\PolicyModifyLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PolicyController extends Controller
{

    public function index(Request $request)
    {
        $coin_code = optional(Coin::find($request->id))->code;
        $coins = Coin::all();
        $policies = StakingPolicy::where('coin_id', $request->id)->get();

        $modify_logs = PolicyModifyLog::join('staking_policies', 'staking_policies.id', '=', 'policy_modify_logs.policy_id')
            ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
            ->select('staking_policies.staking_name', 'admins.name', 'policy_modify_logs.*')
            ->where('policy_modify_logs.policy_type', 'staking_policies_'.$coin_code)
            ->orderBy('policy_modify_logs.created_at', 'desc')
            ->get();

        return view('admin.staking.policy', compact('coins', 'policies', 'modify_logs'));
        
    }

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'staking_name' => 'required|string',
            'min_quantity' => 'required|numeric',
            'max_quantity' => 'required|numeric',
            'daily' => 'required|numeric',
            'period' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {

            StakingPolicy::create([
                'coin_id' => $request->coin_id,
                'staking_name' => $validated['staking_name'],
                'min_quantity' => $validated['min_quantity'],
                'max_quantity' => $validated['max_quantity'],
                'daily' => $validated['daily'],
                'period' => $validated['period'],
            ]);
            
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

            $stakingPolicy = StakingPolicy::findOrFail($request->id); 
            $stakingPolicy->update($request->all());
            
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => '정책이 수정되었습니다.',
                'url' => route('admin.staking.policy', ['id' => $stakingPolicy->coin_id]),
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
        return Excel::download(new StakingPolicyExport(), 'staking_policy.xlsx');
    }
}