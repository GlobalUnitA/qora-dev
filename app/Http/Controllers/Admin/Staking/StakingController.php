<?php

namespace App\Http\Controllers\Admin\Staking;

use App\Exports\StakingPolicyExport;
use App\Models\Staking;
use App\Models\StakingPolicy;
use App\Models\PolicyModifyLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class StakingController extends Controller
{
    public function __construct()
    {
        
    }

    public function list(Request $request)
    {
        $list = Staking::with(['user'])
       
        ->when($request->filled('category') && $request->filled('keyword'), function ($query) use ($request) {
            switch ($request->category) {
                case 'mid':
                    $query->whereHas('user', function ($query) use ($request) {
                        $query->where('users.id', $request->keyword);
                    });
                    break;
                case 'account':
                    $query->whereHas('user', function ($query) use ($request) {
                        $query->where('users.account', $request->keyword);
                    });
                    break;
                case 'name':
                    $query->whereHas('user', function ($query) use ($request) {
                        $query->where('users.name', $request->keyword);
                    });
                    break;
                case 'phone':
                    $query->whereHas('userProfile', function ($query) use ($request) {
                        $query->where('user_profiles.phone', $request->keyword);
                    });
                    break;
            }
        })
        ->when($request->filled('start_date') && $request->filled('end_date'), function ($query) use ($request) {
            $start = Carbon::parse($request->start_date)->startOfDay(); 
            $end = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('stakings.created_at', [$start, $end]);
        })
        ->where('status', $request->status)
        ->latest()
        ->orderBy('id', 'desc')
        ->paginate(10);

        return view('admin.staking.list', compact('list'));
    }

}