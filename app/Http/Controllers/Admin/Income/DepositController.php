<?php

namespace App\Http\Controllers\Admin\Income;

use App\Models\Income;
use App\Models\IncomeTransfer;
use App\Models\AssetPolicy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class DepositController extends Controller
{
    public function __construct()
    {
        
    }

    public function update(Request $request)
    {
       DB::beginTransaction();

        try {

            $deposit = IncomeTransfer::find($request->id);
            $income = Income::find($deposit->income_id);
            
            if ($request->status == 'canceled') {

                $income->increment('balance', $deposit->amount);

                $deposit->update([ 
                    'after_balance' => $deposit->before_balance,
                ]);
            } 

            $deposit->update([
                'status' => $request->status ?? $deposit->status, 
                'memo' => $request->memo
            ]);
            
            DB::commit(); 

            return response()->json([
                'status' => 'success',
                'message' => '거래 정보 수정이 완료되었습니다.',
                'url' => route('admin.income.view', ['id' => $deposit->id]),
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
}