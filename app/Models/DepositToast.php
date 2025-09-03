<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositToast extends Model
{
    use HasFactory;

    protected $fillable = [
        'deposit_id',
        'is_read',
    ];

    public function deposit()
    {
        return $this->belongsTo(AssetTransfer::class, 'deposit_id', 'id');
    }
}