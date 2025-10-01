<?php

namespace App\Http\Controllers\Admin\Board;

use App\Models\Board;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\S3Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PostController extends Controller
{
    protected S3Service $s3Service;

    public function __construct(S3Service $s3Service)
    {
        $this->s3Service = $s3Service;
    }

    public function list(Request $request)
    {
        $boards = Board::all();
        $selected_board = $boards->where('board_code', $request->code)->first();

        $list = Post::when(request('category') != '', function ($query) {
            if(request('category') == 'mid'){
                $query->where('users.id', request('keyword'));
            } else {
                $query->where('users.account', request('keyword'));
            }
        })
        ->when(request('start_date'), function ($query) {
            $start_date = Carbon::parse(request('start_date'))->startOfDay();
            $query->where('posts.created_at', '>=', $start_date);
        })
        ->when(request('end_date'), function ($query) {
            $end_date = Carbon::parse(request('end_date'))->endOfDay();
            $query->where('posts.created_at', '<=', $end_date);
        })
        ->where('board_id', $selected_board->id)
        ->orderBy('posts.created_at', 'desc')
        ->paginate(10);

        $list->appends(request()->all());

        $data = [
            'boards' => $boards,
            'selected_board' => $selected_board,
            'list' => $list,
        ];

        return view('admin.board.post-list', $data);

    }

    public function view(Request $request)
    {
        $mode = $request->mode;
        $board = Board::where('board_code', $request->code)->first();
        $view = Post::find($request->id);

        $download_urls = null;
        if (!empty($view->image_urls)) {
            foreach ($view->image_urls as $image_url) {
                if (!$image_url) continue;
                $download_urls[] = $this->s3Service->generateDownloadUrl($image_url, 600);
            }
        }

        if($mode == 'view') {
            $user = User::find($view->user_id);
            $comments = Comment::where('board_id', $board->id)
            ->where('post_id', $view->id)
            ->get();

            $data = [
                'mode' => $mode,
                'board' => $board,
                'view' => $view,
                'user' => $user,
                'comments' => $comments,
                'download_urls' => $download_urls,
            ];

            return view('admin.board.post-view', $data);

        } else if($mode == 'comment') {
            $user = User::find($view->user_id);
            $comments = Comment::where('board_id', $board->id)
            ->where('post_id', $view->id)
            ->get();

            $data = [
                'mode' => $mode,
                'board' => $board,
                'view' => $view,
                'user' => $user,
                'comments' => $comments,
                'download_urls' => $download_urls,
            ];

            return view('admin.board.comment', $data);

        } else {

            $data = [
                'mode' => $mode,
                'board' => $board,
                'view' => $view,
            ];

            return view('admin.board.write', $data);
        }
    }

    public function write(Request $request)
    {
        $content = $request->input('content');
        $file_url = array_filter($request->input('file_key', []));

        $board = Board::find($request->board_id);

        DB::beginTransaction();

        try{

            if ($board->is_popup === 'y') {

                preg_match_all('/\/storage\/uploads\/tmp\/([^"\']+)/', $content, $matches);

                if (!empty($matches[1])) {
                    foreach ($matches[1] as $file) {
                        $tmp_path  = "uploads/tmp/{$file}";
                        $post_path = "uploads/post/{$file}";

                        if (Storage::disk('public')->exists($tmp_path)) {
                            Storage::disk('public')->move($tmp_path, $post_path);

                            $new_url = "/storage/{$post_path}";

                            $content = str_replace("/storage/{$tmp_path}", $new_url, $content);

                            $content = preg_replace(
                                '/<img(.*?)src=["\']' . preg_quote($new_url, '/') . '["\'](.*?)>/i',
                                '<img$1src="' . $new_url . '"$2 style="width:100%">',
                                $content
                            );
                        }
                    }
                }
            }

            $is_popup = $request->has('is_popup') ? $request->is_popup : 'n';
            $is_banner = $request->has('is_banner') ? $request->is_banner : 'n';

            $post = Post::create([
                'admin_id' => Auth::guard('admin')->id(),
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
                'message' => '작성이 완료되었습니다.',
                'url' => route('admin.post.view', ['code' => $board->board_code, 'mode' => 'view', 'id' =>  $post->id]),
            ]);

        } catch (Exception $e) {

            DB::rollBack();

            \Log::error('Failed to write post', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => '예기치 못한 오류가 발생했습니다.',
            ]);
        }
    }


    public function modify(Request $request)
    {
        $board = Board::find($request->board_id);
        $post = Post::find($request->post_id);

        if ($post) {
            DB::beginTransaction();

            try {
                $content = $request->input('content');
                $file_url = array_filter($request->input('file_key', []));

                if ($board->is_popup === 'y') {

                    preg_match_all('/\/storage\/uploads\/tmp\/([^"\']+)/', $content, $matches);

                    if (!empty($matches[1])) {
                        foreach ($matches[1] as $file) {
                            $tmp_path  = "uploads/tmp/{$file}";
                            $post_path = "uploads/post/{$file}";

                            if (Storage::disk('public')->exists($tmp_path)) {
                                Storage::disk('public')->move($tmp_path, $post_path);

                                $new_url = "/storage/{$post_path}";

                                $content = str_replace("/storage/{$tmp_path}", $new_url, $content);

                                $content = preg_replace(
                                    '/<img(.*?)src=["\']' . preg_quote($new_url, '/') . '["\'](.*?)>/i',
                                    '<img$1src="' . $new_url . '"$2 style="width:100%">',
                                    $content
                                );
                            }
                        }
                    }
                }

                $is_popup = $request->has('is_popup') ? $request->is_popup : 'n';
                $is_banner = $request->has('is_banner') ? $request->is_banner : 'n';

                $post->update([
                    'subject' => $request->subject,
                    'content' => $content,
                    'image_urls' => $file_url,
                    'is_popup' => $is_popup,
                    'is_banner' => $is_banner,
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => '수정되었습니다.',
                    'url' => route('admin.post.view', ['code' => $board->board_code, 'mode' => 'view', 'id' =>  $post->id]),
                ]);

            } catch (Exception $e) {
                DB::rollBack();

                \Log::error('Failed to update post', ['error' => $e->getMessage()]);

                return response()->json([
                    'status' => 'error',
                    'message' => '예기치 못한 오류가 발생했습니다.',
                ]);
            }
        }
    }

    public function delete(Request $request)
    {
        $post_ids = $request->input('check', []);

         if (count($post_ids) > 0) {
            try {
                DB::transaction(function () use ($post_ids) {
                    Post::whereIn('id', $post_ids)->delete();
                });

                return response()->json([
                    'status' => 'success',
                    'message' => '삭제되었습니다.',
                    'url' => route('admin.post.list', ['code' => $request->code]),
                ]);
            } catch (\Exception $e) {
                Log::error('게시글 삭제 실패: ' . $e->getMessage());

                return response()->json([
                    'status' => 'error',
                    'message' => '삭제 중 오류가 발생했습니다.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => '선택된 게시글이 없습니다.',
            ]);
        }
    }

    private function extractImageUrlsFromContent($content)
    {
        preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);
        return $matches[1] ?? [];
    }
}
