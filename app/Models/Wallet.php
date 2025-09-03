<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class Wallet extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'user_id',
        'coin_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:9',
    ];

    protected $appends = [
        'encrypted_id', 
        'deposit_fee_rate', 
        'withdrawal_fee_rate', 
        'profit_rate'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function coin()
    {
        return $this->belongsTo(Coin::class, 'coin_id', 'id');
    }

    public function transfers()
    {
        return $this->hasMany(WalletTransfer::class, 'wallet_id', 'id');
    }

    public function profits()
    {
        return $this->hasMany(WalletTransfer::class, 'wallet_id', 'id');
    }

    public function getEncryptedIdAttribute()
    {
        return Hashids::encode($this->id);
    }

    public function getDepositFeeRateAttribute()
    {

        $policy = WalletPolicy::find('1');
 
        if (!$policy) {
            return 0;
        }
 
        return $policy->deposit_fee_rate;
    }
    
    public function getWithdrawalFeeRateAttribute()
    {

        $policy = WalletPolicy::find('1');

        if (!$policy) {
            return 0;
        }

        return $policy->withdrawal_fee_rate;
    }

    public function getProfitRateAttribute()
    {

        $policy = WalletPolicy::find('1');

        if (!$policy) {
            return 0;
        }

        return $policy->profit_rate;
    }

    public function getWalletInfo()
    {
        $yesterday = Carbon::yesterday();   
        $today = Carbon::today();       
        $tomorrow = Carbon::tomorrow();

        $wallet_profits = WalletProfit::where('wallet_id', $this->id)->get();
        
        $wallet_today = $wallet_profits->where('created_at', '>=', $today)->sum('profit');
        $wallet_total = $wallet_profits->sum('profit');

        $stakings = Staking::where('wallet_id', $this->id)->get();

        $staking_today = $stakings->flatMap(function ($staking) use ($today) {
            return $staking->profits->where('created_at', '>=', $today);
        })->sum('profit');
    
        $staking_total = $stakings->flatMap(function ($staking) {
            return $staking->profits;
        })->sum('profit');

        return  [
            'balance' => $this->balance,
            'coin_name' => $this->coin->name,
            'coin_code' => $this->coin->code,
            'profit' => [
                'today' => [
                    'wallet' => $wallet_today,
                    'staking' => $staking_today,
                    'all' => $wallet_today + $staking_today,
                ],
                'total' => [
                    'wallet' => $wallet_total,
                    'staking' => $staking_total,
                    'all' => $wallet_total + $staking_total,
                ]
            ],      
        ];
    }
}
