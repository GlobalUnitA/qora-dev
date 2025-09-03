<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'status',
        'nationality',
        'given_name',
        'surname',
        'id_number',
        'image_urls',
        'date_of_birth',
        'memo',
    ];

    protected $casts = [
        'image_urls' => 'array',
        'date_of_birth' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = [
        'type_text',
        'status_text',
    ];
    
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getTypeTextAttribute()
    {
        if ($this->type === 'id_card') {
            return '신분증';
        } else if ($this->type === 'passport') {
            return '여권';
        } else {
            return '운전면허증';
        }
    }

    public function getStatusTextAttribute()
    {
        if ($this->status === 'pending') {
            return '신청';
        } else if ($this->status === 'approved') {
            return '통과';
        } else {
            return '미통과';
        }
    }
}
