<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = [
        'name', 
        'account', 
        'password',
        'admin_level',
    ];

    public function otp()
    {
        return $this->hasOne(AdminOtp::class, 'admin_id', 'id');
    }

}