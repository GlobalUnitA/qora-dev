<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\User;
use App\Models\UserGrade;
use App\Models\GradePolicy;
use App\Models\SubscriptionPolicy;
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
        switch ($request->mode) {
            case 'grade' :
                $policies = GradePolicy::all();

                $modify_logs = PolicyModifyLog::join('grade_policies', 'grade_policies.id', '=', 'policy_modify_logs.policy_id')
                    ->join('user_grades', 'user_grades.id', '=', 'grade_policies.grade_id')
                    ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
                    ->select('user_grades.name as grade_name', 'admins.name', 'policy_modify_logs.*')
                    ->where('policy_modify_logs.policy_type', 'grade_policies')
                    ->orderBy('policy_modify_logs.created_at', 'desc')
                    ->get();
               
                return view('admin.user.policy-grade', compact('policies', 'modify_logs'));
            break;

            case 'subscription' :

                $policies = SubscriptionPolicy::all();

                $modify_logs = PolicyModifyLog::join('subscription_policies', 'subscription_policies.id', '=', 'policy_modify_logs.policy_id')
                    ->join('user_grades', 'user_grades.id', '=', 'subscription_policies.grade_id')
                    ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
                    ->select('user_grades.name as grade_name', 'admins.name', 'policy_modify_logs.*')
                    ->where('policy_modify_logs.policy_type', 'subscription_policies')
                    ->orderBy('policy_modify_logs.created_at', 'desc')
                    ->get();
               
                return view('admin.user.policy-subscription', compact('policies', 'modify_logs'));
                
            break;
        } 
    }

    public function update(Request $request) 
    {

        DB::beginTransaction();

        try {
            switch ($request->mode) {
                case 'grade' :

                    $gradePolicy = GradePolicy::findOrFail($request->id);
            
                    $gradePolicy->update($request->all());

                break;

                case 'subscription' :

                    $subscriptionPolicy = SubscriptionPolicy::findOrFail($request->id);
            
                    $subscriptionPolicy->update($request->all());

                break;
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => '정책이 수정되었습니다.',
                'url' => route('admin.user.policy', ['mode' => $request->mode]),
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

    public function export()
    {
        return Excel::download(new AssetPolicyExport(), 'asset_policy.xlsx');
    }

}   