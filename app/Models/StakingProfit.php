<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StakingProfit extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'staking_id',
        'profit',
    ];

    protected $casts = [
        'profit' => 'decimal:9',
    ];

    protected $appends = [
        'status_text',
    ];

    public function staking()
    {
        return $this->belongsTo(Staking::class, 'staking_id', 'id');
    }

    public function getStatusTextAttribute()
    {
        if ($this->staking->status === 'pending') {
            return '지급예정';
        } else if ($this->staking->status === 'completed') {
            return '지급완료';
        }
        return '오류';
    }

    public static function distributeDaily()
    {
        $today = now()->toDateString();
        $stakings = Staking::whereDate('started_at', '<=', $today)
                    ->whereDate('ended_at', '>=', $today)
                    ->get();

        foreach ($stakings as $staking) {
            DB::beginTransaction();

            try {
    
                $profit = $staking->daily_profit;

                self::create([
                    'staking_id' => $staking->id,
                    'profit' => $profit,
                ]);

                Log::channel('staking')->info('Staking profit distributed', [
                    'user_id' => $staking->user_id,
                    'staking_id' => $staking->id,
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
        $stakings = Staking::whereDate('ended_at', '<', $today)->where('status', 'pending')->get();

        foreach ($stakings as $staking) {

            DB::beginTransaction();

            try {
                $wallet = $staking->wallet;    
                $total_profit = $staking->profits->sum('profit');

                $wallet->increment('balance', $total_profit);

                $staking->update(['status' => 'completed']);
                
                Log::channel('staking')->info('Staking profit successfully paid out', [
                    'user_id' => $staking->user_id,
                    'staking_id' => $staking->id,
                    'timestamp' => now(),
                ]);

                DB::commit();

            } catch (\Throwable $e) {

                DB::rollBack();

                Log::channel('staking')->error('Failed to pay out staking profit', [
                    'user_id' => $staking->user_id,
                    'staking_id' => $staking->id,
                    'error' => $e->getMessage(),
                ]);

            }
        }
    }
}
