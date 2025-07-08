<?php

namespace App\Http\Controllers\Admin\Asset;

use App\Exports\Asset\AssetPolicyExport;
use App\Models\Asset;
use App\Models\AssetPolicy;
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
        $policy = AssetPolicy::first();

        $all_days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
    
        $selected_days = explode(',', $policy->withdrawal_days ?? '');

        $modify_logs = PolicyModifyLog::join('asset_policies', 'asset_policies.id', '=', 'policy_modify_logs.policy_id')
            ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
            ->select('admins.name', 'policy_modify_logs.*')
            ->where('policy_modify_logs.policy_type', 'asset_policies')
            ->orderBy('policy_modify_logs.created_at', 'desc')
            ->get();

        return view('admin.asset.policy', compact('policy', 'all_days', 'selected_days', 'modify_logs'));
    }

    public function update(Request $request) 
    {

        DB::beginTransaction();

        try {

            $asset_policy = AssetPolicy::first();

            $days = $request->input('withdrawal_days', []);

            $request_data = $request->except('withdrawal_days');
            $request_data['withdrawal_days'] = implode(',', $days);

            $asset_policy->update($request_data);
            
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => '정책이 수정되었습니다.',
                'url' => route('admin.asset.policy'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Failed to update asset policy', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => '예기치 못한 오류가 발생했습니다.',
            ]);
        }
    }
}   