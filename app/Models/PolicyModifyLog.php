<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyModifyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_type', 
        'policy_id', 
        'column_name', 
        'column_description',
        'old_value', 
        'new_value', 
        'admin_id'
    ];

    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
