<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staking_id',
        'transfer_id',
        'referrer_id',
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

    public function staking()
    {
        return $this->belongsTo(Staking::class, 'staking_id', 'id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

}
