<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asset;
use App\Models\Income;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
 
    public function __construct()
    {
        
    }

   
    public function index()
    {
        $notice = Post::where('board_id', 1)->latest()->first();
        $assets = Asset::where('user_id', Auth::user()->id)
        ->whereHas('coin', function ($query) {
            $query->where('is_active', 'y');
        })
        ->get();
        $incomes = Income::where('user_id', Auth::user()->id)
        ->whereHas('coin', function ($query) {
            $query->where('is_active', 'y');
        })
        ->get();
        
        return view('home', compact('notice', 'assets', 'incomes'));
    }


    protected function getPopup()
    {
        $popup = DB::table('policies')   
            ->select('*')
            ->where('type', 'popup_policy')
            ->first();
        
        $data = json_decode($popup->content);

        $today = Carbon::today();

        if (isset($data->start_date, $data->end_date)) {
            $startDate = Carbon::parse($data->start_date);
            $endDate = Carbon::parse($data->end_date);
    
            if ($today->between($startDate, $endDate)) {
                return $data;
            }
        }

        return null;
    }
}
