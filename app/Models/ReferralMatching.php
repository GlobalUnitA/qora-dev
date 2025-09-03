<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralMatching extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bonus_id',
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

    public function bonus()
    {
        return $this->belongsTo(ReferralBonus::class, 'bonus_id', 'id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

}
