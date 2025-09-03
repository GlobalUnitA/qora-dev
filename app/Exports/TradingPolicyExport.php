<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TradingPolicyExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
  
    public function collection()
    {
        
        $query = DB::table('trading_policies')
            ->select(
                'vip_level',
                'first_recommends',
                'second_recommends',
                'third_recommends',
                'trading_count',
                'profit_rate',
                'min_quantity',
                'max_quantity',
                'updated_at',
            );

        return $query->get();
    }

  
    public function headings(): array
    {
        return ['vip', '1대', '2대', '3대', '거래 횟수', '수익률', '최소 참여수량', '최대 참여수량', '수정일자'];
    }
}