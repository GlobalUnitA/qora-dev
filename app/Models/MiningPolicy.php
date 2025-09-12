<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

class MiningPolicy extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'coin_id',
        'refund_coin_id',
        'reward_coin_id',
        'instant_rate',
        'split_rate',
        'exchange_rate',
        'period',
        'node_amount',
        'node_limit',
    ];

    protected $casts = [
        'instant_rate' => 'decimal:9',
	    'split_rate' => 'decimal:9',
	    'exchange_rate' => 'decimal:9',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = [
        'mining_locale_name',
        'mining_locale_memo',
    ];

    public function coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    public function refundCoin()
    {
        return $this->belongsTo(Coin::class, 'refund_coin_id', 'id');
    }

    public function rewardCoin()
    {
        return $this->belongsTo(Coin::class, 'reward_coin_id', 'id');
    }

    public function translations()
    {
        return $this->hasMany(MiningPolicyTranslation::class, 'policy_id', 'id');
    }

    public function getMiningLocaleNameAttribute()
    {
        return optional($this->translationForLocale())->name;
    }

    public function getMiningLocaleMemoAttribute()
    {
        return optional($this->translationForLocale())->memo;
    }

    public function translationForLocale($locale = null)
    {
        $locale = $locale ?? Cookie::get('app_locale', 'en');

        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        return $this->translations->firstWhere('locale', $locale);
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected static $columnDescriptions = [
        'coin_id' => '입금 코인',
        'refund_coin_id' => '원금 코인',
        'reward_coin_id' => '수익 코인',
        'instant_rate' => '즉시 지급 비율',
        'split_rate' => '분할 지급 비율',
        'exchange_rate' => '환율',
        'period' => '기간',
        'node_amount' => '채굴값',
        'node_limit' => '최대 노드 수량',
    ];

    public function getColumnComment($column)
    {
        return static::$columnDescriptions[$column];
    }
}
