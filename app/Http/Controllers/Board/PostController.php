<?php

namespace App\Http\Controllers\Board;

use App\Models\Board;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{

    public function view(Request $request)
    {

        $mode = $request->mode;
        $board = Board::where('board_code', $request->code)->first();
        $view = Post::find($request->id);

        if ($mode == 'view') {
            $user = User::find($view->user_id);

           if ($view->user_id && $view->user_id !== auth()->id()) {
               return redirect()->route('home');
           }

           $comments = Comment::where('board_id', $board->id)
               ->where('post_id', $view->id)
               ->get();

           $data = [
               'mode' => $mode,
               'board' => $board,
               'view' => $view,
               'comments' => $comments,
               'user' => $user,
           ];

            return view('board.view', $data);
        } else {

            $data = [
                'mode' => $mode,
                'board' => $board,
                'view' => $view,
            ];

            return view('board.write', $data);
        }
    }

    public function write(Request $request)
    {
        $content = $request->input('content');
        $file_url = $request->input('file_key', []);
        $board = Board::find($request->board_id);

        DB::beginTransaction();

        try {

            $is_popup = $request->has('is_popup') ? $request->is_popup : 'n';
            $is_banner = $request->has('is_banner') ? $request->is_banner : 'n';

            Post::create([
                'user_id' => auth()->id(),
                'board_id' => $board->id,
                'subject' => $request->subject,
                'content' => $content,
                'image_urls' => $file_url,
                'is_popup' => $is_popup,
                'is_banner' => $is_banner,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => __('layout.write_success_notice'),
                'url' => route('board.list', ['code' =>  $board->board_code]),
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Failed to write post', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => __('system.error_notice'),
            ]);
        }
    }
}
