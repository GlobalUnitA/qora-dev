<?php

namespace App\Exports\Income;

use App\Exports\BaseIncomeExport;
use Illuminate\Support\Facades\DB;

class IncomeTradingProfitExport extends BaseIncomeExport
{
    public function collection()
    {
        $query = DB::table('income_transfers')
            ->leftJoin('incomes', 'income_transfers.income_id', '=', 'incomes.id')
            ->leftJoin('coins', 'incomes.coin_id', '=', 'coins.id')
            ->leftJoin('users', 'income_transfers.user_id', '=', 'users.id')
            ->leftJoin('trading_profits', 'income_transfers.id', '=', 'trading_profits.transfer_id')
            ->leftJoin('tradings', 'trading_profits.trading_id', '=', 'tradings.id')
            ->select(
                'users.id', 
                'users.name',
                'coins.name as coin_name',
                'tradings.balance',
                'income_transfers.amount',
                'income_transfers.status',
                'income_transfers.fee',
                'income_transfers.tax',
                'income_transfers.created_at'
            )
            ->orderBy('income_transfers.created_at', 'asc');
        
        $statusMap = $this->getStatusMap();
        $results = $this->applyCommonFilters($query)->get();

        return $this->formatExportRows($results);
    }

    public function headings(): array
    {
        return ['번호', 'UID', '이름', '종류', '보유자산', '수익', '상태', '수수료', '세금', '신청일자'];
    }
}