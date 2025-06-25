<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetPolicyExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
  
    public function collection()
    {
        
        $query = DB::table('asset_policies')
            ->select(
                'vip_level',
                'tax_rate',
                'fee_rate',
                'total_rate',
                'after_total_rate',
                'trading_count',
                'min_withdrawal',
                'updated_at',
            );

        return $query->get();
    }

  
    public function headings(): array
    {
        return ['vip', '세금', '수수료', '공제', '기간 이후', '거래 횟수', '최소 출금금액', '수정일자'];
    }
}