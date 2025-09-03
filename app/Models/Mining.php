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
        'staking_id',
        'status',
        'amount',
        'period',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'amount' => 'decimal:9',
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
        return $this->belongsTo(StakingPolicy::class, 'staking_id', 'id');
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
