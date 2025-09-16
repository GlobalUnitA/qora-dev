<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Mining extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'user_id',
        'asset_id',
        'refund_id',
        'reward_id',
        'policy_id',
        'status',
        'coin_amount',
        'refund_coin_amount',
        'node_amount',
        'exchange_rate',
        'period',
        'reward_count',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'coin_amount' => 'decimal:9',
        'refund_coin_amount' => 'decimal:9',
        'node_amount' => 'decimal:9',
        'exchange_rate' => 'decimal:9',
    ];

    protected $appends = [
        'status_text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'refund_id', 'id');
    }

    public function income()
    {
        return $this->belongsTo(Income::class, 'reward_id', 'id');
    }

    public function policy()
    {
        return $this->belongsTo(MiningPolicy::class, 'policy_id', 'id');
    }

    public function refunds()
    {
        return $this->hasMany(StakingRefund::class, 'staking_id', 'id');
    }

    public function rewards()
    {
        return $this->hasMany(StakingReward::class, 'staking_id', 'id');
    }

    public function getStatusTextAttribute()
    {
        if ($this->status === 'pending') {
            return '진행중';
        } else if ($this->status === 'completed') {
            return '만료';
        }
        return '오류';
    }
}
