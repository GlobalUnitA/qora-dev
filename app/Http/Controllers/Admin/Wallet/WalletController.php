<?php

namespace App\Http\Controllers\Admin\Wallet;

use App\Exports\WalletExport;
use App\Models\Wallet;
use App\Models\WalletTransfer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class WalletController extends Controller
{

    public function __construct()
    {
        
    }

    public function list(Request $request)
    {
        $list = WalletTransfer::where('wallet_transfers.type', $request->input('type', 'deposit'))
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

            $query->whereBetween('wallet_transfers.created_at', [$start, $end]);
        })
        ->latest()
        ->paginate(10);
    
        return view('admin.wallet.list', compact('list'));
    }

    public function view($id)
    {
        $view = WalletTransfer::find($id);

        return view('admin.wallet.view', compact('view'));
    }

    public function export(Request $request)
    {
        return Excel::download(new WalletExport($request->all()), 'wallet.xlsx');
    }
}
