<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transfer_id',
        'referrer_id',
        'withdrawal_id',
        'bonus',
    ];
    
    protected $casts = [
        'bonus' => 'decimal:9',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transfer()
    {
        return $this->belongsTo(IncomeTransfer::class, 'transfer_id', 'id');
    }

    public function withdrawal()
    {
        return $this->belongsTo(IncomeTransfer::class, 'withdrawal_id', 'id');
    }
}
