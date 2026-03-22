<?php

namespace App\Exports\Income;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncomeLevelBonusExport implements FromQuery, WithMapping, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    protected function applyCommonFilters($query)
    {
        if (!empty($this->filters['type'])) {
            $query->where('income_transfers.type', $this->filters['type']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('income_transfers.status', $this->filters['status']);
        }

        if (!empty($this->filters['keyword']) && $this->filters['category'] == 'mid') {
            $query->where('users.id', $this->filters['keyword']);
        }

        if (!empty($this->filters['keyword']) && $this->filters['category'] == 'account') {
            $query->where('users.account', $this->filters['keyword']);
        }

        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $query->whereBetween('income_transfers.created_at', [
                $this->filters['start_date'] . ' 00:00:00',
                $this->filters['end_date'] . ' 23:59:59'
            ]);
        }

        return $query;
    }

    protected function getStatusMap(): array
    {
        return [
            'pending'   => '신청',
            'waiting'   => '대기',
            'completed' => '완료',
            'canceled'  => '취소',
            'refunded'  => '환불',
        ];
    }

    public function query()
    {
        $query = DB::table('income_transfers')
            ->leftJoin('incomes', 'income_transfers.income_id', '=', 'incomes.id')
            ->leftJoin('coins', 'incomes.coin_id', '=', 'coins.id')
            ->leftJoin('users', 'income_transfers.user_id', '=', 'users.id')
            ->leftJoin('user_profiles', 'income_transfers.user_id', '=', 'user_profiles.user_id')
            ->leftJoin('user_grades', 'user_profiles.grade_id', '=', 'user_grades.id')
            ->leftJoin('level_bonuses', 'income_transfers.id', '=', 'level_bonuses.transfer_id')
            ->leftJoin('mining_profits', 'level_bonuses.profit_id', '=', 'mining_profits.id')
            ->select(
                'users.id',
                'users.name',
                'user_grades.name as grade_name',
                'coins.name as coin_name',
                'income_transfers.amount as bonus',
                'income_transfers.status',
                'level_bonuses.referrer_id',
                'mining_profits.profit',
                'income_transfers.created_at'
            )
            ->orderBy('income_transfers.created_at', 'asc');

        return $this->applyCommonFilters($query);
    }

    public function map($row): array
    {
        static $i = 0;
        $i++;

        $statusMap = $this->getStatusMap();

        return [
            $i,
            $row->id,
            $row->name,
            $row->grade_name,
            $row->coin_name,
            $row->bonus,
            $statusMap[$row->status] ?? $row->status,
            $row->referrer_id,
            $row->profit,
            $row->created_at,
        ];
    }

    public function headings(): array
    {
        return ['번호', 'UID', '이름', '등급', '종류', '보너스', '상태','산하ID', '데일리', '일자'];
    }
}
