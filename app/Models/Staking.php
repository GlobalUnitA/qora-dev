<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staking extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'user_id',
        'wallet_id',
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
        'daily_profit',
        'status_text',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }

    public function policy()
    {
        return $this->belongsTo(StakingPolicy::class, 'staking_id', 'id');
    }

    public function profits()
    {
        return $this->hasMany(StakingProfit::class, 'staking_id', 'id');
    }

    public function getDailyProfitAttribute()
    {

        $profit = $this->amount * ($this->policy->daily / 100);

        return $profit;
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
