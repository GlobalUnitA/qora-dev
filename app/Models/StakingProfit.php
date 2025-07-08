<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StakingProfit extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'staking_id',
        'principal',
        'profit',
    ];

    protected $casts = [
        'profit' => 'decimal:9',
    ];

    protected $appends = [
        'status_text',
    ];

    public function staking()
    {
        return $this->belongsTo(Staking::class, 'staking_id', 'id');
    }

    public function getStatusTextAttribute()
    {
        return '지급 완료';
    }
}