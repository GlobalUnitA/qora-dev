<?php

namespace App\Http\Controllers\Admin\Board;

use App\Models\Board;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BoardController extends Controller
{

    public function __construct()
    {
       
    }
    
    public function list(Request $request)
    {
        $list = Board::paginate(10);
        return view('admin.board.board-list', compact('list'));
    }

    public function view(Request $request)
    {
        $view = Board::find($request->id);

        return view('admin.board.board-view', compact('view'));
    }

    public function update(Request $request)
    { 

        $board = Board::find($request->id);

        if ($board) {

            DB::beginTransaction();

            try {
        
                $board->update([
                    'board_code' => $request->board_code,
                    'board_name' => $request->board_name,  
                    'board_level' => $request->board_level,
                    'is_comment' => $request->is_comment,
                    'is_popup' => $request->is_popup,
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => '수정되었습니다.',
                    'url' => route('admin.board.view', ['id' =>  $board->id]),
                ]);

            } catch (Exception $e) {
                DB::rollBack();

                \Log::error('Failed to update board', ['error' => $e->getMessage()]);

                return response()->json([
                    'status' => 'error',
                    'message' => '예기치 못한 오류가 발생했습니다.',
                ]);
            }
        }
    }
}