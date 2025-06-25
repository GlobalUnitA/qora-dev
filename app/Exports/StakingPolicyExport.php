<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StakingPolicyExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
  
    public function collection()
    {
        
        $query = DB::table('staking_policies')
            ->select(
                'staking_name',
                'min_quantity',
                'max_quantity',
                'daily',
                'period',
                'updated_at',
            );

        return $query->get();
    }

  
    public function headings(): array
    {
        return ['상품', '최소 참여수량', '최대 참여수량', '수익률', '기간', '수정일자'];
    }
}