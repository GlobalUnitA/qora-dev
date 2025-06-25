<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_id',
        'post_id',
        'tab',
        'user_id',
        'admin_id',
        'content',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }
}
