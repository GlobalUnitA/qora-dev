<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakingPolicy extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'coin_id',
        'staking_name',
        'min_quantity',
        'max_quantity',
        'daily',
        'period',
    ];

    protected $casts = [
        'daily' => 'decimal:9',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected static $columnDescriptions = [
        'staking_name' => '상품 이름',
        'min_quantity' => '최소 참여수량',
        'max_quantity' => '최대 참여수량',
        'daily' => '데일리 수익률',
        'period' => '기간',
    ];

    public function getColumnComment($column)
    {
        return static::$columnDescriptions[$column];
    }
}
