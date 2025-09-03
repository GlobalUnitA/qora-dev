<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletPolicy extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'min_quantity',
        'profit_rate',
        'deposit_fee_rate',
        'withdrawal_fee_rate',
    ];

    protected $casts = [
        'profit_rate' => 'decimal:9',
        'deposit_fee_rate' => 'decimal:9',
        'withdrawal_fee_rate' => 'decimal:9',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected static $columnDescriptions = [
        'min_quantity' => '참여 수량',
        'profit_rate' => '수익률',
        'deposit_fee_rate' => '입금 수수료',
        'withdrawal_fee_rate' => '출금 수수료',
    ];

    public function getColumnComment($column)
    {
        return static::$columnDescriptions[$column];
    }
}
