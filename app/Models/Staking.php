<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Staking extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'user_id',
        'asset_id',
        'income_id',
        'staking_id',
        'status',
        'amount',
        'period',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'amount' => 'decimal:9',
    ];

    protected $appends = [
        'status_text',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id');
    }

    public function income()
    {
        return $this->belongsTo(Income::class, 'income_id', 'id');
    }

    public function policy()
    {
        return $this->belongsTo(StakingPolicy::class, 'staking_id', 'id');
    }

    public function refunds()
    {
        return $this->hasMany(StakingRefund::class, 'staking_id', 'id');
    }

    public function rewards()
    {
        return $this->hasMany(StakingReward::class, 'staking_id', 'id');
    }

    public function getStatusTextAttribute()
    {
        if ($this->status === 'pending') {
            return '진행중';
        } else if ($this->status === 'completed') {
            return '만료';
        }
        return '오류';
    }

    public function getDailyProfit()
    {
        return round($this->amount * $this->policy->daily / 100, 9);
    }

    public static function distributeDaily()
    {
        $today = now()->toDateString();
        $stakings = self::whereDate('started_at', '<=', $today)
            ->whereDate('ended_at', '>=', $today)
            ->get();

        foreach ($stakings as $staking) {
            DB::beginTransaction();

            try {
                $profit = $staking->getDailyProfit();
                $principal = 0;

                Log::channel('staking')->info('Staking profit', [
                    'user_id' => $staking->user_id,
                    'staking_id' => $staking->id,
                    'profit' => $profit,
                    'timestamp' => now(),
                ]);

                if ($staking->policy->staking_type === 'daily') {

                    $principal = $staking->amount / $staking->period;
                    $profit -= $principal; 

                    Log::channel('staking')->info('Staking profit - principal', [
                        'user_id' => $staking->user_id,
                        'staking_id' => $staking->id,
                        'principal' => $principal,
                        'profit' => $profit,
                        'timestamp' => now(),
                    ]);

                    $asset = $staking->asset;    

                    $asset_transfer = AssetTransfer::create([
                        'user_id' => $staking->user_id,
                        'asset_id' => $asset->id,
                        'type' => 'staking_refund',
                        'status' => 'completed',
                        'amount' => $principal,
                        'actual_amount' => $principal,
                        'before_balance' => $asset->balance,
                        'after_balance' => $asset->balance + $principal,
                    ]);

                    $asset->increment('balance', $principal);

                    StakingRefund::create([
                        'user_id' => $staking->user_id,
                        'staking_id' => $staking->id,
                        'transfer_id' => $asset_transfer->id,
                        'amount' => $principal,
                    ]);

                    Log::channel('staking')->info('Staking principal distributed', [
                        'user_id' => $staking->user_id,
                        'staking_id' => $staking->id,
                        'transfer_id' => $asset_transfer->id,
                        'principal' => $principal,
                        'timestamp' => now(),
                    ]);

                }
                $income = $staking->income;

                $income_transfer = IncomeTransfer::create([
                    'user_id' => $staking->user_id,
                    'income_id' => $income->id,
                    'type' => 'staking_reward',
                    'status' => 'completed',
                    'amount' => $profit,
                    'actual_amount' => $profit,
                    'before_balance' => $income->balance,
                    'after_balance' => $income->balance + $profit,
                ]);

                $income->increment('balance', $profit);

                StakingReward::create([
                    'user_id' => $staking->user_id,
                    'staking_id' => $staking->id,
                    'transfer_id' => $income_transfer->id,
                    'profit' => $profit,
                ]);

                Log::channel('staking')->info('Staking profit distributed', [
                    'user_id' => $staking->user_id,
                    'staking_id' => $staking->id,
                    'transfer_id' => $income_transfer->id,
                    'profit' => $profit,
                    'timestamp' => now(),
                ]);
                

                DB::commit();

            } catch (\Throwable $e) {

                DB::rollBack();

                Log::channel('staking')->error('Failed to distribute staking profit', [
                    'user_id' => $staking->user_id,
                    'staking_id' => $staking->id,
                    'error' => $e->getMessage(),
                ]);

            }
        }
    }
    
    public static function finalizePayout()
    {
        $today = now()->toDateString();

        $stakings = self::with(['asset', 'profits'])
            ->whereDate('ended_at', '<', $today)
            ->where('status', 'pending')
            ->get();

        foreach ($stakings as $staking) {

            DB::beginTransaction();

            try {

                if ($staking->policy->staking_type === 'maturity') {
                    $asset = $staking->asset;

                    $asset_transfer = AssetTransfer::create([
                        'user_id' => $staking->user_id,
                        'asset_id' => $asset->id,
                        'type' => 'staking_refund',
                        'status' => 'completed',
                        'amount' => $staking->amount,
                        'actual_amount' => $staking->amount,
                        'before_balance' => $asset->balance,
                        'after_balance' => $asset->balance + $staking->amount,
                    ]);

                    $asset->increment('balance', $staking->amount);

                    StakingRefund::create([
                        'user_id' => $staking->user_id,
                        'staking_id' => $staking->id,
                        'transfer_id' => $asset_transfer->id,
                        'amount' => $staking->amount,
                    ]);
                    
                    Log::channel('staking')->info('Staking principal successfully paid out', [
                        'user_id' => $staking->user_id,
                        'staking_id' => $staking->id,
                        'transfer_id' => $asset_transfer->id,
                        'timestamp' => now(),
                    ]);

                }

                $staking->update(['status' => 'completed']);

                DB::commit();

            } catch (\Throwable $e) {

                DB::rollBack();

                Log::channel('staking')->error('Failed to pay out staking principal', [
                    'user_id' => $staking->user_id,
                    'staking_id' => $staking->id,
                    'error' => $e->getMessage(),
                ]);

            }
        }
    }
}
