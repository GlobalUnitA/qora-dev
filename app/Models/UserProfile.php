<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'level',
        'grade_id',
        'email',
        'phone',
        'post_code',
        'address',
        'detail_address',
        'meta_uid',
        'is_valid',
        'is_frozen',
        'is_kyc_verified',
        'memo'
    ];

    protected $appends = [
        'referral_count',
        'total_bonus',
        'is_referral',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(UserProfile::class, 'parent_id', 'user_id');
    }

    public function children()
    {
        return $this->hasMany(UserProfile::class, 'parent_id', 'user_id');
    }

    public function grade()
    {
        return $this->belongsTo(UserGrade::class, 'grade_id', 'id');
    }

    public function getReferralCountAttribute()
    {
        return $this->children()->where('is_valid', 'y')->count();
    }

    public function getTotalBonusAttribute()
    {
        return SubscriptionBonus::where('user_id', $this->user_id)->sum('bonus');
    }

    public function getIsReferralAttribute()
    {
        $is_valid = 'n';
        $min_valid = AssetPolicy::first()->min_valid;

        $max_amount_in_usdt = AssetTransfer::where('user_id', $this->user_id)
            ->whereIn('type', ['deposit', 'internal', 'manual_deposit'])
            ->whereIn('status', ['waiting', 'completed'])
            ->get()
            ->sum(fn($deposit) => (float) $deposit->getAmountInUsdt());

        if ($max_amount_in_usdt >= $min_valid) {
            $is_valid = 'y';
        }

        return $is_valid;
    }

    public function getParentTree($max_level = 21)
    {
        $levels = [];
        $current = $this;

        for ($i = 1; $i <= $max_level; $i++) {
            $parent = $current->parent;

            if (!$parent) {
                break;
            }

            $levels[$i] = $parent;
            $current = $parent;
        }

        return $levels;
    }


    public function getChildrenTree($max_level = 21)
    {
        $levels = [];
        $current_level_users = collect([$this]);

        for ($i = 1; $i <= $max_level; $i++) {
            $next_level = $current_level_users
                ->flatMap(function ($user) {
                    return $user->children;
                });

            if ($next_level->isEmpty()) {
                break;
            }

            $levels[$i] = $next_level;
            $current_level_users = $next_level;
        }

        return $levels;
    }

    public function getSelfSales()
    {
        $self_sales = AssetTransfer::where('user_id', $this->user_id)
            ->whereIn('type', ['deposit', 'internal', 'manual_deposit'])
            ->where('status', 'completed')
            ->get()
            ->sum(fn($deposit) => (float) $deposit->getAmountInUsdt());

        return $self_sales;
    }

    public function getGroupSales()
    {
        $childrens = $this->getChildrenTree(21);
        $group_sales = 0;

        foreach ($childrens as $level => $profiles) {
            foreach ($profiles as $profile) {
                $user = $profile->user;
                if(!$user) continue;

                $group_sales += AssetTransfer::where('user_id', $user->id)
                    ->whereIn('type', ['deposit', 'internal', 'manual_deposit'])
                    ->where('status', 'completed')
                    ->get()
                    ->sum(fn($deposit) => (float) $deposit->getAmountInUsdt());
            }
        }

        return $group_sales;
    }

    public function subscriptionBonus($withdrawal)
    {
        $parents = $this->getParentTree(21);

        foreach ($parents as $level => $parent_profile) {

            if ($parent_profile->is_valid === 'n') {
                continue;
            }

            $policy = subscriptionPolicy::where('grade_id', $parent_profile->grade->id)->first();

            $rate_key = "level_{$level}_rate";

            $bonus = $withdrawal->fee * $policy->$rate_key / 100;

            if ($bonus <= 0) {
                continue;
            }

            $income = Income::where('user_id', $parent_profile->user_id)->where('coin_id', $withdrawal->income->coin_id)->first();

            $transfer = IncomeTransfer::create([
                'user_id'   => $parent_profile->user_id,
                'income_id'  => $income->id,
                'type' => 'subscription_bonus',
                'status' => 'completed',
                'amount'    => $bonus,
                'actual_amount' => $bonus,
                'before_balance' => $income->balance,
                'after_balance' => $income->balance + $bonus,
            ]);

            SubscriptionBonus::create([
                'user_id'   => $parent_profile->user_id,
                'referrer_id'   => $this->user_id,
                'transfer_id'  => $transfer->id,
                'withdrawal_id' => $withdrawal->id,
                'bonus' => $bonus,
            ]);

            $income->increment('balance', $bonus);

            Log::channel('bonus')->info('Success subscription bonus', ['user_id' => $this->user_id, 'bonus' => $bonus, 'transfer_id' => $transfer->id]);
        }

    }

    public function referralBonus($staking)
    {
        $parents = $this->getParentTree(21);

        foreach ($parents as $level => $parent_profile) {

            if ($parent_profile->is_valid === 'n') {
                continue;
            }

            $policy = ReferralPolicy::where('grade_id', $parent_profile->grade->id)->first();

            $rate_key = "level_{$level}_rate";

            $bonus = $staking->amount * $policy->$rate_key / 100;

            if ($bonus <= 0) {
                continue;
            }

            $income = Income::where('user_id', $parent_profile->user_id)->where('coin_id', $staking->income->coin_id)->first();

            $transfer = IncomeTransfer::create([
                'user_id'   => $parent_profile->user_id,
                'income_id'  => $income->id,
                'type' => 'referral_bonus',
                'status' => 'completed',
                'amount'    => $bonus,
                'actual_amount' => $bonus,
                'before_balance' => $income->balance,
                'after_balance' => $income->balance + $bonus,
            ]);

            ReferralBonus::create([
                'user_id'   => $parent_profile->user_id,
                'referrer_id' => $this->user_id,
                'staking_id'   => $staking->id,
                'transfer_id'  => $transfer->id,
                'bonus' => $bonus,
            ]);

            $income->increment('balance', $bonus);

            Log::channel('bonus')->info('Success referral bonus', ['user_id' => $this->user_id, 'bonus' => $bonus, 'transfer_id' => $transfer->id]);
        }
    }

    public function rankBonus()
    {
        $policy = RankPolicy::where('grade_id', $this->grade_id)->first();

        if (!$policy) {
            return;
        }

        $bonus_given = RankBonus::where('user_id', $this->user_id)
            ->where('policy_id', $policy->id)
            ->exists();

        if ($bonus_given) {
            return;
        }

        $self_sales = $this->getSelfSales();
        $group_sales = $this->getGroupSales();

        if ($self_sales < $policy->self_sales) {
            Log::channel('bonus')->info("Rank bonus payment failed - User ID: {$this->user_id}, Grade ID: {$this->grade_id}, Reason: Insufficient personal sales volume");
            return;
        }

        $direct_children = $this->getChildrenTree(1);

        $direct_count = isset($direct_children[1]) ? $direct_children[1]->count() : 0;

        if (!empty($policy->conditions)) {
            $all_conditions_met = true;

            foreach ($policy->conditions as $condition) {
                $min_level = (int) $condition['min_level'];
                $max_level = (int) $condition['max_level'];
                $required_count = (int) $condition['referral_count'];

                $count = 0;

                if (isset($direct_children[1])) {
                    $count = $direct_children[1]->filter(function ($child) use ($min_level, $max_level) {
                        $level = $child->grade->level;
                        return $level >= $min_level && $level <= $max_level;
                    })->count();
                }

                if ($count < $required_count) {
                    $all_conditions_met = false;
                    break;
                }
            }

            if (!$all_conditions_met) {
                Log::channel('bonus')->info("Rank bonus not paid - User ID: {$this->user_id}, Reason: Insufficient qualified referrals for required levels.");
                return;
            }
        }

        DB::beginTransaction();

        try {
            $bonus = $policy->bonus;

            $income = Income::where('user_id', $this->user_id)->where('coin_id', 1)->first();

            $transfer = IncomeTransfer::create([
                'user_id'        => $this->user_id,
                'income_id'      => $income->id,
                'type'           => 'rank_bonus',
                'status'         => 'completed',
                'amount'         => $bonus,
                'actual_amount'  => $bonus,
                'before_balance' => $income->balance,
                'after_balance'  => $income->balance + $bonus,
            ]);

            $rank_bonus = RankBonus::create([
                'user_id'        => $this->user_id,
                'policy_id'      => $policy->id,
                'transfer_id'    => $transfer->id,
                'self_sales'     => $self_sales,
                'group_sales'    => $group_sales,
                'referral_count' => $direct_count,
                'bonus'          => $bonus,
            ]);

            $income->increment('balance', $bonus);

            if (isset($direct_children[1])) {
                foreach ($direct_children[1] as $profile) {
                    $self_sales = $profile->getSelfSales();
                    $group_sales = $profile->getGroupSales();

                    RankBonusReferral::create([
                        'user_id'     => $profile->user_id,
                        'bonus_id'    => $rank_bonus->id,
                        'self_sales'  => $self_sales,
                        'group_sales' => $group_sales,
                    ]);
                }
            }

            DB::commit();

            Log::channel('bonus')->info('Success rank bonus', [
                'user_id'     => $this->user_id,
                'bonus'       => $bonus,
                'transfer_id' => $transfer->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::channel('bonus')->error('Failed rank bonus', [
                'user_id' => $this->user_id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    public function checkUserValidity()
    {
        if ($this->is_valid === 'y') return;

        $asset_policy = AssetPolicy::first();

        $max_amount_in_usdt = AssetTransfer::where('user_id', $this->user_id)
            ->whereIn('type', ['deposit', 'internal', 'manual_deposit'])
            ->where('status', 'completed')
            ->get()
            ->sum(fn($deposit) => (float) $deposit->getAmountInUsdt());

        if ($asset_policy && $asset_policy->min_valid <= $max_amount_in_usdt) {
            $this->update(['is_valid' => 'y']);
            Log::channel('user')->info('Success to change is_valid', ['user_id' => $this->user_id]);
        } else {
            Log::channel('user')->info('Failed to change is_valid', ['user_id' => $this->user_id, 'max_amount' => $max_amount_in_usdt]);
        }
    }

    public function checkUserGrade()
    {
        $this->evaluateUserGrade();

        $parent_tree = $this->getParentTree(21);

        foreach ($parent_tree as $parent_profile) {
            if ($parent_profile) {
                $parent_profile->evaluateUserGrade();
            }
        }
    }

    public function evaluateUserGrade()
    {

        $self_sales = AssetTransfer::where('user_id', $this->user_id)
            ->whereIn('type', ['deposit', 'internal', 'manual_deposit'])
            ->where('status', 'completed')
            ->get()
            ->sum(fn($deposit) => (float) $deposit->getAmountInUsdt());

        $children_tree = $this->getChildrenTree(21);
        $group_sales = 0;
        foreach ($children_tree as $profiles) {
            foreach ($profiles as $child_profile) {
                if ($child_profile->user) {

                    $group_sales += AssetTransfer::where('user_id', $child_profile->user_id)
                        ->whereIn('type', ['deposit', 'internal', 'manual_deposit'])
                        ->where('status', 'completed')
                        ->get()
                        ->sum(fn($deposit) => (float) $deposit->getAmountInUsdt());
                }
            }
        }

        $this->checkLevelUp($this->grade->level, $self_sales, $group_sales);
    }

    private function checkLevelUp($current_level, $self_sales, $group_sales)
    {

        $next_level = $current_level + 1;
        $next_grade = UserGrade::where('level', $next_level)->first();
        $next_policy = GradePolicy::where('grade_id', $next_grade->id)->first();

        if (!$next_policy) {
            return;
        }

        if (
            $self_sales >= $next_policy->base_sales &&
            (
                $self_sales >= $next_policy->self_sales ||
                $group_sales >= $next_policy->group_sales
            )
        ) {
            $result = UserProfile::where('id', $this->id)->update([
                'grade_id' => $next_grade->id
            ]);

            if (!$result) {
                throw new \Exception("Failed to update grade_id for user_id {$this->user_id}");
            }

            Log::channel('user')->info("User ID {$this->user_id} level up: {$current_level} → {$next_level}, self_sales : {$self_sales}, group_sales : {$group_sales}");

            $this->checkLevelUp($next_level, $self_sales, $group_sales);
        }

        return;
    }


}
