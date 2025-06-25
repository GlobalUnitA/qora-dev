<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WalletPolicyExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
  
    public function collection()
    {
        
        $query = DB::table('wallet_policies')
            ->select(
                'min_quantity',
                'profit_rate',
                'deposit_fee_rate',
                'withdrawal_fee_rate',
                'updated_at',
            );

        return $query->get();
    }

  
    public function headings(): array
    {
        return ['참여수량', '수익률', '입금 수수료', '출금 수수료', '수정일자'];
    }
}