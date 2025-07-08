<?php

namespace App\Http\Controllers\Admin\Asset;

use App\Exports\Asset\AssetDepositExport;
use App\Exports\Asset\AssetWithdrawalExport;
use App\Exports\Asset\AssetStakingRefundExport;
use App\Exports\Asset\AssetManualDepositExport;
use App\Models\UserProfile;
use App\Models\Asset;
use App\Models\AssetTransfer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AssetController extends Controller
{

    public function __construct()
    {
        
    }
   
    public function list(Request $request)
    {
        $list = AssetTransfer::where('asset_transfers.type', $request->input('type', 'deposit'))
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('asset_transfers.status', $request->status);
        })
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
                case 'amount':
                    $query->where('amount', $request->keyword);
                    break;
                case 'fee':
                    $query->where('fee', $request->keyword);
                    break;
                case 'tax':
                    $query->where('tax', $request->keyword);
                    break;
            }
        })
        ->when($request->filled('start_date') && $request->filled('end_date'), function ($query) use ($request) {
            $start = Carbon::parse($request->start_date)->startOfDay(); 
            $end = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('asset_transfers.created_at', [$start, $end]);
        })
        ->latest()
        ->orderBy('id', 'desc')
        ->paginate(10);
  
        return view('admin.asset.list', compact('list'));
    }

    public function view($id)
    {
        $view = AssetTransfer::find($id);

        return view('admin.asset.view', compact('view'));
    }


    public function export(Request $request)
    {
        $current = now()->toDateString();

        switch ($request->type) {
            case 'deposit' :
                return Excel::download(new AssetDepositExport($request->all()), '회원 입금 내역 '.$current.'.xlsx');
            break;

            case 'withdrawal' :
                return Excel::download(new AssetWithdrawalExport($request->all()), '회원 출금 내역 '.$current.'.xlsx');
            break;

            case 'staking_refund' :
                return Excel::download(new AssetStakingRefundExport($request->all()), '회원 원금반환 내역 '.$current.'.xlsx');
            break;

            case 'manual_deposit' :
                return Excel::download(new AssetManualDepositExport($request->all()), '회원 수동입금 내역 '.$current.'.xlsx');
            break;
        }
    }
}
