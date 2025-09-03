<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletProfit extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'wallet_id',
        'profit',
    ];

    protected $casts = [
        'profit' => 'decimal:9',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }

    public static function distributeDaily()
    {
        $wallets = Wallet::where('balance', '>', 0)->get();

        foreach ($wallets as $wallet) {
            DB::beginTransaction();

            try {
    
                $profit = $wallet->balance * ($wallet->profit_rate / 100);
       
                self::create([
                    'wallet_id' => $wallet->id,
                    'profit' => $profit,
                ]);

                Log::channel('wallet')->info('Wallet profit distributed', [
                    'user_id' => $wallet->user_id,
                    'wallet_id' => $wallet->id,
                    'profit' => $profit,
                    'timestamp' => now(),
                ]);

                DB::commit();

            } catch (\Throwable $e) {

                DB::rollBack();

                Log::channel('wallet')->error('Failed to distribute wallet profit', [
                    'wallet_id' => $wallet->id,
                    'error' => $e->getMessage(),
                ]);

            }
        }
    }
}
