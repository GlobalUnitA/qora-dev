<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'address',
        'image_urls',
        'is_active',
        'is_asset',
        'is_income',
        'is_mining',
    ];

    protected $casts = [
        'image_urls' => 'array',
    ];
}
