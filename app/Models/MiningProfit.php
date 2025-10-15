<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MiningProfit extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'user_id',
        'reward_id',
        'transfer_id',
        'type',
        'profit',
        'node_amount',
        'reward_rate',
    ];

    protected $casts = [
        'profit' => 'decimal:9',
        'node_amount' => 'decimal:9',
        'reward_rate' => 'decimal:9',
    ];

    public function getStatusTextAttribute()
    {
        return '지급 완료';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reward()
    {
        return $this->belongsTo(MiningReward::class, 'reward_id', 'id');
    }

    public function transfer()
    {
        return $this->belongsTo(IncomeTransfer::class, 'transfer_id', 'id');
    }

    public function levelBonus()
    {
        return $this->hasOne(LevelBonus::class, 'profit_id', 'id');
    }

    public function getPayoutRate()
    {
        return $this->type === 'daily'
            ? $this->mining->policy->split_rate
            : $this->mining->policy->instant_rate;
    }

    public function getSplitDays()
    {
        return $this->type === 'daily'
            ? $this->mining->policy->split_period
            : 1;
    }

    public static function distributeProfit()
    {
        Log::channel('mining')->info('distributeDaily mining');

        $today = now()->toDateString();
        $minings = self::whereDate('started_at', '<=', $today)
            ->whereDate('ended_at', '>=', $today)
            ->get();

        foreach ($minings as $mining) {
            DB::beginTransaction();

            try {

                $mining_reward = $mining->getMiningReward();

                $type = $mining_reward['type'];
                $reward = $mining_reward['reward'];

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

                $mining_profit= MiningProfit::create([
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

                $mining->user->profile->levelBonus($mining_profit);

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

}
