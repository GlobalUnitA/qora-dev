<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class WalletExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        
        $query = DB::table('wallet_transfers')
            ->leftJoin('wallets', 'wallet_transfers.wallet_id', '=', 'wallets.id')
            ->leftJoin('coins', 'wallets.coin_id', '=', 'coins.id')
            ->leftJoin('users', 'wallet_transfers.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name as user_name',
                'coins.name as coin_name',
                'wallet_transfers.amount',
                'wallet_transfers.created_at'
            );

        if (!empty($this->filters['type'])) {
            $query->where('wallet_transfers.type', $this->filters['type']);
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
            $query->whereBetween('wallet_transfers.created_at', [$start, $end]);
        }

        return $query->get();
    }

  
    public function headings(): array
    {
        return ['UID', '이름', '종류', '수량', '거래일자'];
    }
}
