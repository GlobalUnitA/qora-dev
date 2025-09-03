<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class IncomeExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        
        $query = DB::table('income_transfers')
            ->leftJoin('incomes', 'income_transfers.asset_id', '=', 'incomes.id')
            ->leftJoin('coins', 'incomes.coin_id', '=', 'coins.id')
            ->leftJoin('users', 'income_transfers.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name as user_name',
                'coins.name as coin_name',
                'income_transfers.amount',
                'income_transfers.actual_amount',
                'income_transfers.status',
                'income_transfers.fee',
                'income_transfers.tax',
                'income_transfers.txid',
                'income_transfers.created_at'
            );

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
            $start = Carbon::parse($this->filters['start_date'])->startOfDay();
            $end = Carbon::parse($this->filters['end_date'])->endOfDay();
            $query->whereBetween('income_transfers.created_at', [$start, $end]);
        }
        
        $status_map = [
            'pending' => '신청',
            'waiting' => '대기',
            'completed' => '완료',
            'canceled' => '취소',
            'refunded' => '반환',
        ];

        return $query->get()->map(function ($item) use ($status_map) {
            $item->status = $status_map[$item->status] ?? $item->status;
            return $item;
        });
    }
  
    public function headings(): array
    {
        return ['UID', '이름', '종류', '수량', '실제수량', '상태', '수수료', '세금', 'USDT주소', '거래일자'];
    }
}
