<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakingRefund extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'user_id',
        'staking_id',
        'transfer_id',
        'amount',
    ];
    
    protected $casts = [
        'amount' => 'decimal:9',
    ];

    public function staking()
    {
        return $this->belongsTo(Staking::class, 'staking_id', 'id');
    }

    public function transfer()
    {
        return $this->belongsTo(AssetTransfer::class, 'transfer_id', 'id');
    }
}
