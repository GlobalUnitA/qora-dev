<?php

namespace App\Http\Controllers\Admin\Wallet;

use App\Exports\WalletPolicyExport;
use App\Models\Wallet;
use App\Models\WalletPolicy;
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
        $policies = WalletPolicy::all();

        $modify_logs = PolicyModifyLog::join('wallet_policies', 'wallet_policies.id', '=', 'policy_modify_logs.policy_id')
            ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
            ->select('admins.name', 'policy_modify_logs.*')
            ->where('policy_modify_logs.policy_type', 'wallet_policies')
            ->orderBy('policy_modify_logs.created_at', 'desc')
            ->get();

        return view('admin.wallet.policy', compact('policies', 'modify_logs'));

    }

    public function update(Request $request) 
    {

        DB::beginTransaction();

        try {

            $walletPolicy = WalletPolicy::findOrFail($request->id); 
            $walletPolicy->update($request->all());
            
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => '정책이 수정되었습니다.',
                'url' => route('admin.wallet.policy'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Failed to update wallet policy', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => '예기치 못한 오류가 발생했습니다.',
            ]);
        }
    }

    public function export()
    {
        return Excel::download(new WalletPolicyExport(), 'wallet_policy.xlsx');
    }
}