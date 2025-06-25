<?php

namespace App\Http\Controllers\Board;

use App\Models\Board;
use App\Models\Post;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BoardController extends Controller
{  
    protected $board;

    public function __construct()
    {
       
    }
    
    public function list(Request $request)
    {
        $board = Board::where('board_code', $request->code)->first();

        $list = Post::when($request->code == 'qna', function ($query) {
            $query->where('posts.user_id', auth()->id());
        })
        ->where('posts.board_id', $board->id)
        ->orderBy('posts.created_at', 'desc')
        ->paginate(10);

        $list->appends(request()->all());

        $data = [
            'board' => $board,
            'list' => $list,
        ];

        return view('board.list', $data);

    }
}
