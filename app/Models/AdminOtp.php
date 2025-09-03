<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminOtp extends Model
{
    protected $fillable = [
        'admin_id', 
        'secret_key', 
        'last_verified_at'
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }
}
