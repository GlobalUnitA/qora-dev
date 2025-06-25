<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradingPolicy extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'trading_count',
        'profit_rate',
        'trading_days',
    ];

    protected $casts = [
        'profit_rate' => 'decimal:9',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected static $columnDescriptions = [
        'trading_count' => '트레이딩 횟수',
        'profit_rate' => '수익률',
        'trading_days' => '트레이딩 가능 요일',
    ];

    public function getColumnComment($column)
    {
        return static::$columnDescriptions[$column];
    }

    public function isTradingAvailableToday()
    {
        $trading_days = explode(',', $this->trading_days ?? '');
        $today = strtolower(now()->format('D'));

        return in_array($today, $trading_days);
    }
}
