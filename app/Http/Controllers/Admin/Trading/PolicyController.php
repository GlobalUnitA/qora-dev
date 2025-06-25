<?php

namespace App\Http\Controllers\Admin\Trading;

use App\Exports\TradingPolicyExport;
use App\Models\Trading;
use App\Models\TradingPolicy;
use App\Models\PolicyModifyLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PolicyController extends Controller
{

    public function index()
    {
        
        $policy = TradingPolicy::first();

        $all_days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
        
        $selected_days = explode(',', $policy->trading_days ?? '');

        $modify_logs = PolicyModifyLog::join('trading_policies', 'trading_policies.id', '=', 'policy_modify_logs.policy_id')
            ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
            ->select('admins.name', 'policy_modify_logs.*')
            ->where('policy_modify_logs.policy_type', 'trading_policies')
            ->orderBy('policy_modify_logs.created_at', 'desc')
            ->get();

        return view('admin.trading.policy', compact('policy', 'all_days', 'selected_days', 'modify_logs'));
        
    }

    public function update(Request $request) 
    {

        DB::beginTransaction();

        try {

            $trading_policy = TradingPolicy::first(); 

            $days = $request->input('trading_days', []);

            $request_data = $request->except('trading_days');
            $request_data['trading_days'] = implode(',', $days);

            $trading_policy->update($request_data);
            
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => '정책이 수정되었습니다.',
                'url' => route('admin.trading.policy'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Failed to update trading policy', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => '예기치 못한 오류가 발생했습니다.',
            ]);
        }
    }

    public function export()
    {
        return Excel::download(new TradingPolicyExport(), 'trading_policy.xlsx');
    }
}