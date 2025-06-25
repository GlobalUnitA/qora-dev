<?php

namespace App\Http\Controllers\Board;

use App\Models\Board;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PostController extends Controller
{  

    public function view(Request $request)
    {

        $mode = $request->mode;
        $board = Board::where('board_code', $request->code)->first();
        $view = Post::find($request->id);
        
        if($mode == 'view') {
            $user = User::find($view->user_id);
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
        $uploaded = $request->input('image_urls', []);
        $board = Board::find($request->board_id);

        $used_content = $this->extractImageUrlsFromContent($content);
        $final_images = [];

        foreach ($used_content as $url) {
            if (str_contains($url, '/uploads/tmp/')) {
                $relative_tmp = str_replace(asset('storage') . '/', '', $url);
                $new_path = str_replace('uploads/tmp/', 'uploads/post/', $relative_tmp);

                if (Storage::disk('public')->exists($relative_tmp)) {
                    Storage::disk('public')->move($relative_tmp, $new_path);
                }

                $new_url = asset('storage/' . $new_path);
                $content = str_replace($url, $new_url, $content);

                $content = preg_replace('/<img(.*?)src=["\']' . preg_quote($new_url, '/') . '["\'](.*?)>/', 
                '<img$1src="' . $new_url . '"$2 style="width:100%">', $content);

                $final_images[] = $new_url;
            } else {
                $final_images[] = $url;
            }
        }

        DB::beginTransaction();
    
        try{

            $is_popup = $request->has('is_popup') ? $request->is_popup : 'n';
            $is_banner = $request->has('is_banner') ? $request->is_banner : 'n';

            $post = Post::create([
                'user_id' => auth()->id(),
                'board_id' => $board->id,
                'subject' => $request->subject,
                'content' => $content,
                'image_urls' => $final_images,
                'is_popup' => $is_popup,
                'is_banner' => $is_banner,         
            ]);
    
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => __('layout.write_success_notice'),
                'url' => route('board.list', ['code' =>  $board->board_code]),
            ]);

        } catch (Exception $e) {
          
            DB::rollBack();

            \Log::error('Failed to write post', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => __('system.error_notice'),
            ]);
        }        
    }

    private function extractImageUrlsFromContent($content)
    {
        preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);
        return $matches[1] ?? [];
    }

}