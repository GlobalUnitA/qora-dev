<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_code',
        'board_name',
        'board_level',
        'is_comment',
    ];

    protected $appends = [
        'locale_name',
    ];

    public function getLocaleNameAttribute()
    {
        switch ($this->board_code) {
            case 'notice' :
                return __('layout.notice');
            break;

            case 'qna' :
                return __('layout.qna');
            break;
        
            case 'about' :
                return __('layout.company_about');
            break;

            case 'terms' :
                return __('layout.terms');
            break;

            case 'product' :
                return __('layout.product_intro');
            break;

            case 'guide' :
                return __('layout.guidebook');
            break;

            case 'default' :
                return $this->board_code;
            break;
        }
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'board_id', 'id');
    }
}
