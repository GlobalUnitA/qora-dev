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

    public function getParentTree($max_level = 20)
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


    public function getChildrenTree($max_level = 20)
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
        $childrens = $this->getChildrenTree(20);
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
        $parents = $this->getParentTree(20);

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

    public function referralBonus($deposit)
    {
        try {

            DB::beginTransaction();

            $parents = $this->getParentTree(20);

            foreach ($parents as $level => $parent_profile) {

                if ($parent_profile->is_valid === 'n') continue;

                $policy = ReferralPolicy::where('grade_id', $parent_profile->grade->id)->first();

                if (!$policy) continue;

                $rate_key = "level_{$level}_rate";

                $bonus = $deposit->amount * $policy->$rate_key / 100;

                if ($bonus <= 0) continue;
                
                $income = Income::where('user_id', $parent_profile->user_id)->where('coin_id', 1)->first();

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

                $referral_bonus = ReferralBonus::create([
                    'user_id'   => $parent_profile->user_id,
                    'referrer_id' => $this->user_id,
                    'deposit_id'   => $deposit->id,
                    'transfer_id'  => $transfer->id,
                    'bonus' => $bonus,
                ]);

                $income->increment('balance', $bonus);

                Log::channel('bonus')->info('Success referral bonus', [
                    'user_id' => $parent_profile->user_id, 
                    'referrer_id' => $this->user_id,
                    'bonus_id' => $referral_bonus->id,
                    'transfer_id' => $transfer->id,
                    'bonus' => $bonus,
                    'before_balance' => $transfer->before_balance,
                    'after_balance' => $transfer->after_balance,
                ]);

                $this->referralMatching($referral_bonus);
            }
     
            DB::commit();

        }  catch (\Exception $e) {

            DB::rollBack();

            Log::channel('bonus')->error('Referral bonus transaction failed', [
                'deposit_id' => $deposit->id,
                'user_id' => $this->user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function referralMatching($bonus)
    {

        $parents = $this->getParentTree(20);
        
        foreach ($parents as $level => $parent_profile) {

            if ($parent_profile->is_valid === 'n') continue;
            
            $policy = ReferralMatchingPolicy::where('grade_id', $parent_profile->grade->id)->first();

            if (!$policy) continue;

            $rate_key = "level_{$level}_rate";

            $matching = $bonus->transfer->amount * $policy->$rate_key / 100;

            if ($matching <= 0) continue;
            
            $income = Income::where('user_id', $parent_profile->user_id)
                ->where('coin_id', 1)
                ->first();

            $transfer = IncomeTransfer::create([
                'user_id'   => $parent_profile->user_id,
                'income_id'  => $income->id,
                'type' => 'referral_matching',
                'status' => 'completed',
                'amount'    => $matching,
                'actual_amount' => $matching,
                'before_balance' => $income->balance,
                'after_balance' => $income->balance + $matching,
            ]);

            $referral_matching = ReferralMatching::create([
                'user_id'   => $parent_profile->user_id,
                'referrer_id' => $this->user_id,
                'bonus_id'   => $bonus->id,
                'transfer_id'  => $transfer->id,
                'matching' => $matching,
            ]);

            $income->increment('balance', $matching);

            Log::channel('bonus')->info('Success referral matching', [
                'user_id' => $parent_profile->user_id, 
                'referrer_id' => $this->user_id,
                'bonus_id' => $bonus->id, 
                'matching_id' => $referral_matching->id,
                'transfer_id' => $transfer->id, 
                'matching' => $matching, 
                'before_balance' => $transfer->before_balance,
                'after_balance' => $transfer->after_balance,
            ]);
        }
    }

    public function rankBonus()
    {
        $policies = RankPolicy::join('user_grades', 'rank_policies.grade_id', '=', 'user_grades.id')
            ->where('user_grades.level', '<=', $this->grade->level)
            ->select('rank_policies.*')
            ->get();

        foreach ($policies as $policy) {
            if (!$policy) {
                continue;
            }

            $bonus_given = RankBonus::where('user_id', $this->user_id)
                ->where('policy_id', $policy->id)
                ->exists();

            if ($bonus_given) {
                continue;
            }

            $direct_children = $this->getChildrenTree(1);
            $direct_count = isset($direct_children[1]) ? $direct_children[1]->count() : 0;

            $direct_min_level = (int) $policy->conditions['direct']['min_level'];
            $direct_required_count = (int) $policy->conditions['direct']['referral_count'];

            $direct_met_count = $direct_children[1]->filter(function ($child) use ($direct_min_level) {
                $level = $child->grade->level;
                return $level >= $direct_min_level;
            })->count();
            
            if ($direct_met_count < $direct_required_count) {
                Log::channel('bonus')->info("Rank bonus not paid - User ID: {$this->user_id}, Reason: Insufficient qualified directs for required levels.");
                continue;
            }

            $all_children = collect($this->getChildrenTree(20))->flatten(1);
            $all_count = $all_children->count();

            $all_min_level = (int) $policy->conditions['all']['min_level'];
            $all_required_count = (int) $policy->conditions['all']['referral_count'];

            $all_met_count = $all_children->filter(function ($child) use ($all_min_level) {
                $level = $child->grade->level;
                return $level >= $all_min_level;
            })->count();

            if ($all_met_count < $all_required_count) {
                Log::channel('bonus')->info("Rank bonus not paid - User ID: {$this->user_id}, Reason: Insufficient qualified downline members.");
                continue;
            }

            DB::beginTransaction();

            try {
                $bonus = $policy->bonus;

                $self_sales = $this->getSelfSales();
                $group_sales = $this->getGroupSales();

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
                    'direct_count' => $direct_count,
                    'referral_count' => $all_count,
                    'bonus'          => $bonus,
                ]);

                $income->increment('balance', $bonus);  

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

        $parent_tree = $this->getParentTree(20);

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

        $children_tree = $this->getChildrenTree(20);
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

        $this->checkLevelUp($this->grade->level, $this->referral_count, $self_sales, $group_sales);
    }

    private function checkLevelUp($current_level, $referral_count, $self_sales, $group_sales)
    {

        $next_level = $current_level + 1;
        $next_grade = UserGrade::where('level', $next_level)->first();
        $next_policy = GradePolicy::where('grade_id', $next_grade->id)->first();

        if (!$next_policy) {
            return;
        }

        if (
            $referral_count >= $next_policy->referral_count &&
            $self_sales >= $next_policy->self_sales &&
            $group_sales >= $next_policy->group_sales  
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
