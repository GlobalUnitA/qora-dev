<?php

namespace App\Http\Controllers\Admin\Income;

use App\Exports\Income\IncomeDepositExport;
use App\Exports\Income\IncomeWithdrawalExport;
use App\Exports\Income\IncomeTradingProfitExport;
use App\Exports\Income\IncomeSubscriptionBonusExport;
use App\Exports\Income\IncomeStakingRewardExport;
use App\Models\UserProfile;
use App\Models\Income;
use App\Models\IncomeTransfer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class IncomeController extends Controller
{

    public function __construct()
    {
        
    }
   
    public function list(Request $request)
    {
        $list = IncomeTransfer::where('income_transfers.type', $request->input('type', 'deposit'))
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('income_transfers.status', $request->status);
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

            $query->whereBetween('income_transfers.created_at', [$start, $end]);
        })
        ->latest()
        ->orderBy('id', 'desc')
        ->paginate(10);
    
        switch ($request->type) {
            case 'withdrawal' :
                return view('admin.income.withdrawal-list', compact('list'));
            break;

            case 'trading_profit' :
                return view('admin.income.profit-list', compact('list'));
            break;

            case 'subscription_bonus' :
                return view('admin.income.bonus-list', compact('list'));
            break;

            case 'staking_reward' :
                return view('admin.income.reward-list', compact('list'));
            break;
        
            default :
                return view('admin.income.deposit-list', compact('list'));
            break;
        }        
    }

    public function view($id)
    {
        $view = IncomeTransfer::find($id);

        return view('admin.income.view', compact('view'));
    }

    public function update(Request $request)
    {
        
        DB::beginTransaction();

        try {

            $transfer = IncomeTransfer::find($request->id);
        
            $transfer->update(['memo' => $request->memo]);
            
            DB::commit(); 

            return response()->json([
                'status' => 'success',
                'message' => '거래 정보 수정이 완료되었습니다.',
                'url' => route('admin.income.view', ['id' => $transfer->id]),
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Failed to update incomeTranfer info', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => '예기치 못한 오류가 발생했습니다.',
            ]);
        }
        
    }

    public function export(Request $request)
    {
        $current = now()->toDateString();

        switch ($request->type) {
            case 'deposit' :
                return Excel::download(new IncomeDepositExport($request->all()), '회원 내부이체 내역 '.$current.'.xlsx');
            break;

            case 'withdrawal' :
                return Excel::download(new IncomeWithdrawalExport($request->all()), '회원 외부출금 내역 '.$current.'.xlsx');
            break;

            case 'trading_profit' :
                return Excel::download(new IncomeTradingProfitExport($request->all()), '회원 트레이딩 수익 내역 '.$current.'.xlsx');
            break;

            case 'subscription_bonus' :
                return Excel::download(new IncomeSubscriptionBonusExport($request->all()), '회원 DAO 인센티브 내역 '.$current.'.xlsx');
            break;

            case 'staking_reward' :
                return Excel::download(new IncomeStakingRewardExport($request->all()), '회원 스테이킹 수익 내역 '.$current.'.xlsx');
            break;
        }
    }
}
