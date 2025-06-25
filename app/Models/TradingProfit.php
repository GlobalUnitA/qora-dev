<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradingProfit extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'user_id',
        'trading_id',
        'transfer_id',
        'profit',
    ];
    
    protected $casts = [
        'profit' => 'decimal:9',
    ];

    public function trading()
    {
        return $this->belongsTo(Trading::class, 'trading_id', 'id');
    }

    public function transfer()
    {
        return $this->belongsTo(IncomeTransfer::class, 'transfer_id', 'id');
    }
}
