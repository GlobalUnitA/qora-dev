<?php

namespace App\Exports\Income;

use App\Exports\BaseIncomeExport;
use Illuminate\Support\Facades\DB;

class IncomeStakingRewardExport extends BaseIncomeExport
{
    public function collection()
    {
        $query = DB::table('income_transfers')
            ->leftJoin('incomes', 'income_transfers.income_id', '=', 'incomes.id')
            ->leftJoin('coins', 'incomes.coin_id', '=', 'coins.id')
            ->leftJoin('users', 'income_transfers.user_id', '=', 'users.id')
            ->leftJoin('staking_rewards', 'income_transfers.id', '=', 'staking_rewards.transfer_id')
            ->leftJoin('stakings', 'staking_rewards.staking_id', '=', 'stakings.id')
            ->leftJoin('staking_policies', 'stakings.staking_id', '=', 'staking_policies.id')
            ->leftJoin('staking_policy_translations', 'staking_policies.id', '=', 'staking_policy_translations.policy_id')
            ->where('staking_policy_translations.locale', 'ko')
            ->select(
                'users.id', 
                'users.name',
                'coins.name as coin_name',
                'staking_policy_translations.name as staking_name',
                'stakings.amount',
                'income_transfers.amount as profit',
                'income_transfers.status',
                'income_transfers.created_at'
            )
            ->orderBy('income_transfers.created_at', 'asc');

        $statusMap = $this->getStatusMap();

        $results = $this->applyCommonFilters($query)->get();

        return $this->formatExportRows($results);
    }

    public function headings(): array
    {
        return ['번호', 'UID', '이름', '종류', '상품이름', '참여수량', '수익', '상태', '일자'];
    }
}