<?php

namespace App\Exports\Income;

use App\Exports\BaseIncomeExport;
use Illuminate\Support\Facades\DB;

class IncomeReferralBonusExport extends BaseIncomeExport
{
    public function collection()
    {
        $query = DB::table('income_transfers')
            ->leftJoin('incomes', 'income_transfers.income_id', '=', 'incomes.id')
            ->leftJoin('coins', 'incomes.coin_id', '=', 'coins.id')
            ->leftJoin('users', 'income_transfers.user_id', '=', 'users.id')
            ->leftJoin('user_profiles', 'income_transfers.user_id', '=', 'user_profiles.user_id')
            ->leftJoin('user_grades', 'user_profiles.grade_id', '=', 'user_grades.id')
            ->leftJoin('referral_bonuses', 'income_transfers.id', '=', 'referral_bonuses.transfer_id')
            ->leftJoin('stakings', 'referral_bonuses.staking_id', '=', 'stakings.id')
            ->leftJoin('user_profiles as referrer_profiles', 'referrer_profiles.user_id', '=', 'referral_bonuses.referrer_id')
            ->select(
                'users.id', 
                'users.name',
                'user_grades.name as grade_name',
                'coins.name as coin_name',
                'income_transfers.amount as bonus',
                'referral_bonuses.referrer_id',
                'stakings.amount as staking_amount',
                'income_transfers.created_at',
                DB::raw('referrer_profiles.level - user_profiles.level as depth')
            )
            ->orderBy('income_transfers.created_at', 'asc');

        $statusMap = $this->getStatusMap();

        $results = $this->applyCommonFilters($query)->get();

        return $this->formatExportRows($results);
    }

    public function headings(): array
    {
        return ['번호', 'UID', '이름', '등급', '종류', '보너스', '산하ID', '스테이킹 수량', '스테이킹 일자', '뎁스'];
    }
}
