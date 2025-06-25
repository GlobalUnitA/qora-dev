<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transfer_id',
        'referrer_id',
        'type',
        'bonus',
    ];
    
    protected $casts = [
        'bonus' => 'decimal:9',
    ];

    protected $appends = ['type_text'];

    public function getTypeTextAttribute()
    {
        if ($this->type === 'asset') {
            return '보유자산';
        } else if ($this->type === 'income') {
            return '수익지갑';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transfer()
    {
        return $this->belongsTo(IncomeTransfer::class, 'transfer_id', 'id');
    }
}
