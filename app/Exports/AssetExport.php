<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class AssetExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        
        $query = DB::table('asset_transfers')
            ->leftJoin('assets', 'asset_transfers.asset_id', '=', 'assets.id')
            ->leftJoin('coins', 'assets.coin_id', '=', 'coins.id')
            ->leftJoin('users', 'asset_transfers.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name as user_name',
                'coins.name as coin_name',
                'asset_transfers.amount',
                'asset_transfers.actual_amount',
                'asset_transfers.status',
                'asset_transfers.fee',
                'asset_transfers.tax',
                'asset_transfers.txid',
                'asset_transfers.created_at'
            );

        if (!empty($this->filters['type'])) {
            $query->where('asset_transfers.type', $this->filters['type']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('asset_transfers.status', $this->filters['status']);
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
            $query->whereBetween('asset_transfers.created_at', [$start, $end]);
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
