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
        'split_period',
        'reward_count',
        'started_at',
        'ended_at',
        'maturity_at',
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
        return $this->hasMany(MiningRefund::class, 'mining_id', 'id');
    }

    public function rewards()
    {
        return $this->hasMany(MiningReward::class, 'mining_id', 'id');
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

    public function hasInstantReward()
    {
        return $this->rewards()
            ->where('type', 'instant')
            ->exists();
    }

    public function getBaseAmount()
    {
        $base_amount = ($this->policy->node_amount * $this->node_amount);

        return $base_amount;
    }

    public function getInstantReward()
    {
        $base_amount   = $this->getBaseAmount() / 2;
        $instant_rate  = $this->policy->instant_rate / 100;
        $instant_reward = $base_amount * $instant_rate;

        return $instant_reward;
    }

    public function getDailyReward()
    {
        $base_amount  = $this->getBaseAmount() / 2;
        $split_rate   = $this->policy->split_rate / 100;

        $period = $this->policy->split_period;

        $split_amount = $base_amount * $split_rate;
        $daily_reward = $split_amount / $period;

        return $daily_reward;
    }


    public static function distributeReward()
    {
        Log::channel('mining')->info('distributeDaily mining');

        $today = now()->toDateString();
        $minings = self::whereDate('started_at', '<=', $today)
            ->whereDate('ended_at', '>=', $today)
            ->get();

        foreach ($minings as $mining) {
            DB::beginTransaction();

            try {

                if ($mining->hasInstantReward()) {
                    $type = 'daily';
                    $reward = $mining->getDailyReward();
                } else {
                    $type = 'instant';
                    $reward = $mining->getInstantReward();
                }

                $income = $mining->income;

                $transfer = IncomeTransfer::create([
                    'user_id' => $mining->user_id,
                    'income_id' => $income->id,
                    'type' => 'mining_reward',
                    'status' => 'completed',
                    'amount' => $reward,
                    'actual_amount' => $reward,
                    'before_balance' => $income->balance,
                    'after_balance' => $income->balance + $reward,
                ]);

                $income->increment('balance', $reward);

                $mining_reward = MiningReward::create([
                    'user_id' => $mining->user_id,
                    'mining_id' => $mining->id,
                    'transfer_id' => $transfer->id,
                    'type' => $type,
                    'reward' => $reward,
                ]);

                Log::channel('mining')->info('daily mining distributed', [
                    'user_id' => $mining->user_id,
                    'mining_id' => $mining->id,
                    'transfer_id' => $transfer->id,
                    'type' => $type,
                    'reward' => $reward,
                    'timestamp' => now(),
                ]);

                if ($type === 'daily') $mining->increment('reward_count');

                $mining->user->profile->levelBonus($mining_reward);

                DB::commit();

            } catch (\Throwable $e) {

                DB::rollBack();

                Log::channel('mining')->error('Failed to distribute daily mining', [
                    'user_id' => $mining->user_id,
                    'mining_id' => $mining->id,
                    'error' => $e->getMessage(),
                ]);

            }
        }

    }

    public static function finalizePayout()
    {
        Log::channel('mining')->info('finalize Pay Out mining');

        $today = now()->toDateString();

        $minings = self::whereDate('maturity_at', '<', $today)
            ->whereColumn('split_period', '=', 'reward_count')
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
                    'amount' => $mining->refund_coin_amount,
                    'actual_amount' => $mining->refund_coin_amount,
                    'before_balance' => $asset->balance,
                    'after_balance' => $asset->balance + $mining->refund_coin_amount,
                ]);

                $asset->increment('balance', $mining->refund_coin_amount);

                MiningRefund::create([
                    'user_id' => $mining->user_id,
                    'mining_id' => $mining->id,
                    'transfer_id' => $transfer->id,
                    'amount' => $mining->refund_coin_amount,
                ]);

                Log::channel('mining')->info('Mining principal successfully paid out', [
                    'user_id' => $mining->user_id,
                    'mining_id' => $mining->id,
                    'transfer_id' => $transfer->id,
                    'amount' => $mining->refund_coin_amount,
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
