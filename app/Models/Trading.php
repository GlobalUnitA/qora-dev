<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trading extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'user_id',
        'coin_id',
        'balance',
        'daily',
        'profit_rate',
        'current_count',
        'max_count',
    ];

    protected $casts = [
        'balance' => 'decimal:9',
        'daily' => 'decimal:9',
        'profit_rate' => 'decimal:9',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    public function profits()
    {
        return $this->hasMany(TradingProfit::class, 'trading_id', 'id');
    }
}
