<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakingReward extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'user_id',
        'staking_id',
        'transfer_id',
        'profit',
    ];
    
    protected $casts = [
        'profit' => 'decimal:9',
    ];

    public function getStatusTextAttribute()
    {
        return '지급 완료';
    }

    public function staking()
    {
        return $this->belongsTo(Staking::class, 'staking_id', 'id');
    }

    public function transfer()
    {
        return $this->belongsTo(IncomeTransfer::class, 'transfer_id', 'id');
    }
}
