<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningReward extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'user_id',
        'mining_id',
        'transfer_id',
        'type',
        'reward',
    ];

    protected $casts = [
        'reward' => 'decimal:9',
    ];

    public function getStatusTextAttribute()
    {
        return '지급 완료';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function mining()
    {
        return $this->belongsTo(Mining::class, 'mining_id', 'id');
    }

    public function transfer()
    {
        return $this->belongsTo(IncomeTransfer::class, 'transfer_id', 'id');
    }

    public function getPayoutRate()
    {
        return $this->type === 'daily'
            ? $this->mining->policy->split_rate
            : $this->mining->policy->instant_rate;
    }

    public function getSplitDays()
    {
        return $this->type === 'daily'
            ? $this->mining->policy->split_period
            : 1;
    }

    public function checkLevelCondition()
    {
        $level_conditions = LevelConditionPolicy::orderBy('node_amount', 'desc')->get();
        $user_referral_count = $this->user->profile->referral_count;

        foreach ($level_conditions as $level_condition) {
            $node_check = $this->mining->node_amount >= $level_condition->node_amount;
            $referral_check = $level_condition->condition === 'and'
                ? $user_referral_count >= $level_condition->referral_count && $node_check
                : $user_referral_count >= $level_condition->referral_count || $node_check;

            if ($referral_check) {
                return $level_condition;
            }
        }
    }
}
