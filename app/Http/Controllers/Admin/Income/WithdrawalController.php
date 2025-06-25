<?php

namespace App\Http\Controllers\Admin\Income;

use App\Models\Income;
use App\Models\IncomeTransfer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class WithdrawalController extends Controller
{
    public function __construct()
    {
        
    }

    public function update(Request $request)
    {
       
        DB::beginTransaction();

        try {
            
            $withdrawal = IncomeTransfer::find($request->id);
            $income = Income::find($withdrawal->income_id);
 
            switch ($request->status) {
               
                case 'completed' :
                    
                    $income->user->profile->subscriptionBonus($withdrawal);

                    break;

                case 'canceled' : 
                
                    $income->increment('balance', $withdrawal->amount);
                    $withdrawal->update([ 
                        'after_balance' => $withdrawal->before_balance,
                    ]);

                    break;
            } 

            $withdrawal->update([
                'status' => $request->status ?? $withdrawal->status, 
                'memo' => $request->memo
            ]);

            DB::commit(); 

            return response()->json([
                'status' => 'success',
                'message' => '거래 정보 수정이 완료되었습니다.',
                'url' => route('admin.income.view', ['id' => $withdrawal->id]),
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Failed to update withdrawal info', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => '예기치 못한 오류가 발생했습니다.',
            ]);
        }
        
    }
}