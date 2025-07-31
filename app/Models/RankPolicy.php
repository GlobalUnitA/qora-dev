<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_id',
        'self_sales',
        'bonus',
        'conditions',
    ];

    protected $casts = [
        'self_sales' => 'decimal:9',
        'bonus' => 'decimal:9',
        'conditions' => 'array',
    ];

    public function grade()
    {
        return $this->belongsTo(UserGrade::class, 'grade_id', 'id');
    }

    protected static $columnDescriptions = [
        'grade_id' => '레벨',
        'self_sales' => '개인매출(추가자격)',
        'bonus' => '보너스',
        'conditions' => '조건',
    ];

    public function getColumnComment($column)
    {
        return static::$columnDescriptions[$column];
    }

}
