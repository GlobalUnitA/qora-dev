<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetPolicy extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'deposit_period',
        'tax_rate',
        'fee_rate',
        'min_valid',
        'min_withdrawal',
        'withdrawal_days',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:9',
        'fee_rate' => 'decimal:9',
        'min_valid' => 'decimal:9',
        'min_withdrawal' => 'decimal:9',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected static $columnDescriptions = [
        'deposit_period' => '입금 반영 기간',
        'tax_rate' => '세금 비율',
        'fee_rate' => '수수료 비율',
        'min_valid' => '최소 보유 금액',
        'min_withdrawal' => '최소 출금 금액',
        'withdrawal_days' => '출금 가능 요일',
    ];

    public function getColumnComment($column)
    {
        return static::$columnDescriptions[$column];
    }

    public function isWithdrawalAvailableToday()
    {
        $withdrawal_days = explode(',', $this->withdrawal_days ?? '');
        $today = strtolower(now()->format('D'));

        return in_array($today, $withdrawal_days);
    }
}
