<?php

namespace App\Http\Controllers\Admin\Asset;

use App\Models\User;
use App\Models\Asset;
use App\Models\AssetTransfer;
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

            $withdrawal = AssetTransfer::find($request->id);
            $asset = Asset::find($withdrawal->asset_id);

            if ($request->status == 'canceled') {
                $asset->increment('balance', $withdrawal->amount);

                $withdrawal->update([ 
                    'after_balance' => $withdrawal->before_balance,
                ]);
            }
                
            $withdrawal->update(['status' => $request->status, 'memo' => $request->memo]);

            DB::commit(); 

            return response()->json([
                'status' => 'success',
                'message' => '거래 정보 수정이 완료되었습니다.',
                'url' => route('admin.asset.view', ['id' => $withdrawal->id]),
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