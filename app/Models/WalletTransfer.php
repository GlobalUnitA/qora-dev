<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransfer extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'type',
        'amount',
        'fee',
        'actual_amount',
        'before_balance',
        'after_balance',
    ];

    protected $casts = [
        'amount' => 'decimal:9',
        'fee' => 'decimal:9',
        'actual_amount' => 'decimal:9',
        'before_balance' => 'decimal:9',
        'after_balance' => 'decimal:9',
    ];

    protected $appends = ['status_text'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }

    public function getStatusTextAttribute()
    {
        if ($this->type === 'deposit') {
            return '입금';
        } else if ($this->type === 'withdrawal') {
            return '출금';
        }

        return '오류';
    }
}
