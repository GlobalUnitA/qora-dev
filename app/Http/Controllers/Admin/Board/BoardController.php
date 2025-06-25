<?php

namespace App\Http\Controllers\Admin\Board;

use App\Models\Board;
use App\Models\User;
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

}