<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class Asset extends Model
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
        'fee_rate', 
        'tax_rate',
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
        return $this->hasMany(AssetTransfer::class, 'asset_id', 'id');
    }

    public function profits()
    {
        return $this->hasMany(TradingProfit::class, 'asset_id', 'id');
    }

    public function getEncryptedIdAttribute()
    {
        return Hashids::encode($this->id);
    }

    public function getFeeRateAttribute()
    {
        $policy = AssetPolicy::first();

        if (!$policy) {
            return 0;
        }
        
        $first_deposit = $this->transfers()
            ->whereIn('type', ['deposit', 'manual_deposit'])
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$first_deposit) {
            return 0; 
        }

        $days = now()->diffInDays($first_deposit->created_at);
        
        return ($days >= $policy->period) ? $policy->fee_rate : 0;
    }
    
    public function getTaxRateAttribute()
    {
    
        $policy = AssetPolicy::first();

        if (!$policy) {
            return 0;
        }

        return $policy->tax_rate;
    }


    public function getAssetInfo()
    {
        $yesterday = Carbon::yesterday();   
        $today = Carbon::today();       
        $tomorrow = Carbon::tomorrow();

        $user_profile = UserProfile::where('user_id', $this->user_id)->first();

        $trading_policy = TradingPolicy::first();

        $current_count = 0;
        $max_count = $trading_policy->trading_count;

        $trading = Trading::where('user_id', $this->user_id)
                ->where('coin_id', $this->coin_id)
                ->whereBetween('created_at', [$today, $tomorrow->copy()->subSecond()])
                ->first();

        if ($trading) {
            $current_count = $trading->current_count;
            $max_count = $trading->max_count;
        }
        
        $direct_count = 0;
        $childrens = $user_profile->getChildrenTree(21);

        if ($childrens) {
            $direct_count = count($childrens[1]);
        }
        
        $referral_count = 0; 
        $group_sales = 0;
        $group_sales_expected = 0;

        foreach ($childrens as $level => $profiles) {
            foreach ($profiles as $profile) {
                $user = $profile->user;
                if(!$user) continue;

                $referral_count++;
                   
                $group_sales += AssetTransfer::where('user_id', $user->id)
                    ->whereIn('type', ['deposit', 'internal', 'manual_deposit'])
                    ->where('status', 'completed')
                    ->get()
                    ->sum(fn($deposit) => (float) $deposit->getAmountInUsdt());

                $group_sales_expected += AssetTransfer::where('user_id', $user->id)
                    ->whereIn('type', ['deposit', 'internal'])
                    ->where('status', 'waiting')
                    ->get()
                    ->sum(fn($deposit) => (float) $deposit->getAmountInUsdt());
            }
        }

        $income = Income::where('user_id', $this->user_id)
                ->where('coin_id', $this->coin_id)
                ->first();
        
        $deposits = IncomeTransfer::where('income_id', $income->id)
            ->where('type', 'deposit')
            ->get();
        
        $withdrawal = IncomeTransfer::where('income_id', $income->id)
            ->where('type', 'withdrawal')
            ->get();

        $profits =  IncomeTransfer::where('income_id', $income->id)
            ->where('type', 'trading_profit')
            ->get();

        $profit_today = $profits->where('created_at', '>=', $today)->sum('amount');
        $profit_yesterday = $profits->where('created_at', '>=', $yesterday)->where('created_at', '<', $today)->sum('amount');
        $profit_total = $profits->sum('amount');


        $bonuses =  IncomeTransfer::where('income_id', $income->id)
            ->where('type', 'subscription_bonus')
            ->get();

        $bonus_today = $bonuses->where('created_at', '>=', $today)->sum('amount');
        $bonus_yesterday = $bonuses->where('created_at', '>=', $yesterday)->where('created_at', '<', $today)->sum('amount');
        $bonus_total = $bonuses->sum('amount');

        return [
            'encrypted_id' => $this->encrypted_id,
            'coin_name' => $this->coin->name,
            'balance' => $this->balance,
            'grade' => $user_profile->grade->name,
            'profit' => [
                'today' => $profit_today,
                'yesterday' => $profit_yesterday,
                'total' => $profit_total,
            ],
            'bonus' => [
                'today' => $bonus_today,
                'yesterday' => $bonus_yesterday,
                'total' => $bonus_total,
            ],
            'current_count' => $current_count,
            'max_count' => $max_count,
            'direct_count' => $direct_count,
            'referral_count' => $referral_count,
            'group_sales' => $group_sales,  
            'group_sales_expected'  => $group_sales_expected,
        ];
    }
}
