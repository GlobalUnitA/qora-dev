<?php

namespace App\Http\Controllers\Board;

use App\Models\Board;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CommentController extends Controller
{  
    public function __construct()
    {
       
    }
    
    public function store(Request $request)
    {

        $board = Board::find($request->board_id);
        $post = Post::find($request->post_id);
       
        if ($post) {

            DB::beginTransaction();

            try {

                $max_tab = Comment::where('board_id', $board->id)
                ->where('post_id', $post->id)
                ->max('tab') ?? 0;
                
                Comment::create([
                    'board_id' => $board->id,
                    'post_id' => $post->id,
                    'tab' => $max_tab + 1,
                    'user_id' => auth()->id(),
                    'content' => $request->content,
                ]);
                
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => '답글이 작성되었습니다.',
                    'url' => route('board.view', ['code' =>  $board->board_code, 'mode' => 'view', 'id' => $post->id]),
                ]);

            } catch (\Exception $e) {
                DB::rollBack();

                \Log::error('Failed to create comment', ['error' => $e->getMessage()]);

                return response()->json([
                    'status' => 'error',
                    'message' => '예기치 못한 오류가 발생했습니다.',
                ]);
            }
        }
    }

}