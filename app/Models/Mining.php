<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Mining extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'user_id',
        'asset_id',
        'refund_id',
        'reward_id',
        'policy_id',
        'status',
        'coin_amount',
        'refund_coin_amount',
        'node_amount',
        'exchange_rate',
        'period',
        'reward_count',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'coin_amount' => 'decimal:9',
        'refund_coin_amount' => 'decimal:9',
        'node_amount' => 'decimal:9',
        'exchange_rate' => 'decimal:9',
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
        return $this->belongsTo(Asset::class, 'refund_id', 'id');
    }

    public function income()
    {
        return $this->belongsTo(Income::class, 'reward_id', 'id');
    }

    public function policy()
    {
        return $this->belongsTo(MiningPolicy::class, 'policy_id', 'id');
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

    public function getDailyMining()
    {
        return ($this->policy->node_amount * $this->node_amount )/ 2 * $this->policy->split_rate / 100;
    }

    public static function distributeDaily()
    {
        Log::channel('mining')->info('distributeDaily mining');

        $today = now()->toDateString();
        $minings = self::whereDate('started_at', '<=', $today)
            ->whereDate('ended_at', '>=', $today)
            ->get();

        foreach ($minings as $mining) {
            DB::beginTransaction();

            try {
                $daily_mining = $mining->getDailyMining();

                Log::channel('mining')->info('daily mining', [
                    'user_id' => $mining->user_id,
                    'mining_id' => $mining->id,
                    'daily_mining' => $daily_mining,
                    'timestamp' => now(),
                ]);

                $income = $mining->income;

                $transfer = IncomeTransfer::create([
                    'user_id' => $mining->user_id,
                    'income_id' => $income->id,
                    'type' => 'mining_reward',
                    'status' => 'completed',
                    'amount' => $daily_mining,
                    'actual_amount' => $daily_mining,
                    'before_balance' => $income->balance,
                    'after_balance' => $income->balance + $daily_mining,
                ]);

                $income->increment('balance', $daily_mining);

                MiningReward::create([
                    'user_id' => $mining->user_id,
                    'mining_id' => $mining->id,
                    'transfer_id' => $transfer->id,
                    'reward' => $daily_mining,
                ]);

                Log::channel('mining')->info('daily mining distributed', [
                    'user_id' => $mining->user_id,
                    'staking_id' => $mining->id,
                    'transfer_id' => $transfer->id,
                    'reward' => $daily_mining,
                    'timestamp' => now(),
                ]);


                DB::commit();

            } catch (\Throwable $e) {

                DB::rollBack();

                Log::channel('staking')->error('Failed to distribute daily mining', [
                    'user_id' => $mining->user_id,
                    'mining_id' => $mining->id,
                    'error' => $e->getMessage(),
                ]);

            }
        }

    }

    public static function finalizePayout()
    {
        Log::channel('mining')->error('finalizePayOut mining');

        $today = now()->toDateString();

        $minings = self::whereDate('ended_at', '<', $today)
            ->where('status', 'pending')
            ->get();

        foreach ($minings as $mining) {

            DB::beginTransaction();

            try {

                $asset = $mining->asset;

                $transfer = AssetTransfer::create([
                    'user_id' => $mining->user_id,
                    'asset_id' => $asset->id,
                    'type' => 'mining_refund',
                    'status' => 'completed',
                    'amount' => $mining->amount,
                    'actual_amount' => $mining->amount,
                    'before_balance' => $asset->balance,
                    'after_balance' => $asset->balance + $mining->amount,
                ]);

                $asset->increment('balance', $mining->amount);

                MiningRefund::create([
                    'user_id' => $mining->user_id,
                    'staking_id' => $mining->id,
                    'transfer_id' => $transfer->id,
                    'amount' => $mining->amount,
                ]);

                Log::channel('mining')->info('Staking principal successfully paid out', [
                    'user_id' => $mining->user_id,
                    'mining_id' => $mining->id,
                    'transfer_id' => $transfer->id,
                    'timestamp' => now(),
                ]);

                $mining->update(['status' => 'completed']);

                DB::commit();

            } catch (\Throwable $e) {

                DB::rollBack();

                Log::channel('mining')->error('Failed to pay out mining principal', [
                    'user_id' => $mining->user_id,
                    'mining_id' => $mining->id,
                    'error' => $e->getMessage(),
                ]);

            }
        }
    }
}
