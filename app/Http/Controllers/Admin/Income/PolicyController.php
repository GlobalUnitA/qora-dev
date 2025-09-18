<?php

namespace App\Http\Controllers\Admin\Income;

use App\Models\UserGrade;
use App\Models\SubscriptionPolicy;
use App\Models\ReferralPolicy;
use App\Models\ReferralMatchingPolicy;
use App\Models\RankPolicy;
use App\Models\LevelPolicy;
use App\Models\LevelConditionPolicy;
use App\Models\PolicyModifyLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PolicyController extends Controller
{

    public function index(Request $request)
    {
        switch ($request->mode) {

            case 'referral' :

                $policies = ReferralPolicy::all();

                $modify_logs = PolicyModifyLog::join('referral_policies', 'referral_policies.id', '=', 'policy_modify_logs.policy_id')
                    ->join('user_grades', 'user_grades.id', '=', 'referral_policies.grade_id')
                    ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
                    ->select('user_grades.name as grade_name', 'admins.name', 'policy_modify_logs.*')
                    ->where('policy_modify_logs.policy_type', 'referral_policies')
                    ->orderBy('policy_modify_logs.created_at', 'desc')
                    ->get();

                return view('admin.income.policy.referral', compact('policies', 'modify_logs'));

            case 'referral_matching' :

                $policies = ReferralMatchingPolicy::all();

                $modify_logs = PolicyModifyLog::join('referral_matching_policies', 'referral_matching_policies.id', '=', 'policy_modify_logs.policy_id')
                    ->join('user_grades', 'user_grades.id', '=', 'referral_matching_policies.grade_id')
                    ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
                    ->select('user_grades.name as grade_name', 'admins.name', 'policy_modify_logs.*')
                    ->where('policy_modify_logs.policy_type', 'referral_matching_policies')
                    ->orderBy('policy_modify_logs.created_at', 'desc')
                    ->get();

                return view('admin.income.policy.referral-matching', compact('policies', 'modify_logs'));

            case 'level' :

                $policies = LevelPolicy::all();

                $modify_logs = PolicyModifyLog::join('level_policies', 'level_policies.id', '=', 'policy_modify_logs.policy_id')
                    ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
                    ->select('level_policies.depth', 'admins.name', 'policy_modify_logs.*')
                    ->where('policy_modify_logs.policy_type', 'level_policies')
                    ->orderBy('policy_modify_logs.created_at', 'desc')
                    ->get();

                return view('admin.income.policy.level', compact('policies', 'modify_logs'));

            case 'level_condition' :

                $policies = LevelConditionPolicy::all();

                $modify_logs = PolicyModifyLog::join('level_condition_policies', 'level_condition_policies.id', '=', 'policy_modify_logs.policy_id')
                    ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
                    ->select('level_condition_policies.node_amount', 'admins.name', 'policy_modify_logs.*')
                    ->where('policy_modify_logs.policy_type', 'level_condition_policies')
                    ->orderBy('policy_modify_logs.created_at', 'desc')
                    ->get();

                return view('admin.income.policy.level-condition', compact('policies', 'modify_logs'));

            default :

                $policies = RankPolicy::all();
                $user_grades = UserGrade::all();

                $modify_logs = PolicyModifyLog::join('rank_policies', 'rank_policies.id', '=', 'policy_modify_logs.policy_id')
                    ->join('user_grades', 'user_grades.id', '=', 'rank_policies.grade_id')
                    ->join('admins', 'admins.id', '=', 'policy_modify_logs.admin_id')
                    ->select('user_grades.name as grade_name', 'admins.name', 'policy_modify_logs.*')
                    ->where('policy_modify_logs.policy_type', 'rank_policies')
                    ->orderBy('policy_modify_logs.created_at', 'desc')
                    ->get();

                return view('admin.income.policy.rank', compact('policies', 'user_grades', 'modify_logs'));

        }
    }

    public function store(Request $request)
    {
        try {
            switch ($request->mode) {

                case 'rank' :
                    if (RankPolicy::where('grade_id', $request->grade_id)->exists()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => '이미 해당 등급에 대한 정책이 존재합니다.',
                        ]);
                    }

                    DB::transaction(function () use ($request) {
                        RankPolicy::create([
                            'grade_id' => $request->grade_id,
                            'bonus' => $request->bonus,
                            'conditions' => $request->conditions,
                        ]);
                    });

                    return response()->json([
                        'status' => 'success',
                        'message' => '정책이 추가되었습니다.',
                        'url' => route('admin.income.policy', ['mode' => 'rank']),
                    ]);

                case 'level' :
                    if (LevelPolicy::where('depth', $request->depth)->exists()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => '이미 해당 뎁스에 대한 정책이 존재합니다.',
                        ]);
                    }

                    DB::transaction(function () use ($request) {
                        LevelPolicy::create([
                            'depth' => $request->depth,
                            'bonus' => $request->bonus ?? 0,
                            'matching' => $request->matching ?? 0,
                        ]);
                    });

                    return response()->json([
                        'status' => 'success',
                        'message' => '정책이 추가되었습니다.',
                        'url' => route('admin.income.policy', ['mode' => 'level']),
                    ]);

                case 'level_condition' :

                    DB::transaction(function () use ($request) {
                        LevelConditionPolicy::create([
                            'node_amount' => $request->node_amount,
                            'max_depth' => $request->max_depth,
                            'referral_count' => $request->referral_count,
                            'condition' => $request->condition,
                        ]);
                    });

                    return response()->json([
                        'status' => 'success',
                        'message' => '정책이 추가되었습니다.',
                        'url' => route('admin.income.policy', ['mode' => 'level_condition']),
                    ]);

                default :
                    return response()->json([
                        'status' => 'error',
                        'message' => '잘못된 요청입니다.',
                    ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to create income policy', [
                'mode'  => $request->mode,
                'error' => $e->getMessage(),
            ]);

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
            switch ($request->mode) {

                case 'referral' :

                    $referral_policy = ReferralPolicy::findOrFail($request->id);
                    $referral_policy->update($request->all());

                break;

                case 'referral_matching' :

                    $referral_matching_policy = ReferralMatchingPolicy::findOrFail($request->id);
                    $referral_matching_policy->update($request->all());

                    break;

                case 'rank' :

                    $rank_policy = RankPolicy::findOrFail($request->id);

                    $data = $request->all();
                    $data['conditions'] = array_values($request->conditions ?? []);

                    if (is_null($data['conditions'][0]['min_level']) || is_null($data['conditions'][0]['max_level']) || is_null($data['conditions'][0]['referral_count'])) {
                        $data['conditions'] = null;
                    }
                    $rank_policy->update($data);

                break;

                case 'level' :

                    $Level_policy = LevelPolicy::findOrFail($request->id);
                    $Level_policy->update($request->all());

                    break;

                case 'level_condition' :

                    $Level_condition_policy = LevelConditionPolicy::findOrFail($request->id);
                    $Level_condition_policy->update($request->all());

                    break;
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => '정책이 수정되었습니다.',
                'url' => route('admin.income.policy', ['mode' => $request->mode]),
            ]);


        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update '.$request->mode.' policy', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => '예기치 못한 오류가 발생했습니다.',
            ]);
        }
    }
}
