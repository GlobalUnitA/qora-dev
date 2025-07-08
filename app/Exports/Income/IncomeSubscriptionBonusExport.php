<?php

namespace App\Exports\Income;

use App\Exports\BaseIncomeExport;
use Illuminate\Support\Facades\DB;

class IncomeSubscriptionBonusExport extends BaseIncomeExport
{
    public function collection()
    {
        $query = DB::table('income_transfers')
            ->leftJoin('incomes', 'income_transfers.income_id', '=', 'incomes.id')
            ->leftJoin('coins', 'incomes.coin_id', '=', 'coins.id')
            ->leftJoin('users', 'income_transfers.user_id', '=', 'users.id')
            ->leftJoin('bonuses', 'income_transfers.id', '=', 'bonuses.transfer_id')
            ->select(
                'users.id', 
                'users.name',
                'coins.name as coin_name',
                'income_transfers.amount',
                'income_transfers.status',
                'bonuses.referrer_id',
                'income_transfers.created_at'
            )
            ->orderBy('income_transfers.created_at', 'asc');

        $statusMap = $this->getStatusMap();

        $results = $this->applyCommonFilters($query)->get();

        return $this->formatExportRows($results);
    }

    public function headings(): array
    {
        return ['번호', 'UID', '이름', '종류', 'DAO인센티브', '상태', '산하ID', '일자'];
    }
}