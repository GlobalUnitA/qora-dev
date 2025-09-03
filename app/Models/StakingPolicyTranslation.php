<?php

namespace App\Models;

use App\Traits\TruncatesDecimals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakingPolicyTranslation extends Model
{
    use HasFactory, TruncatesDecimals;

    protected $fillable = [
        'policy_id',
        'locale',
        'name',
        'memo',
    ];

    public function policy()
    {
        return $this->belongsTo(StakingPolicy::class, 'policy_id', 'id');
    }
}
