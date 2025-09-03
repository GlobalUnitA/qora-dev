<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BonusPolicyExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
  
    public function collection()
    {
        
        $query = DB::table('bonus_policies')
            ->select(
                'count',
                'bonus',
                'updated_at',
            );

        return $query->get();
    }

  
    public function headings(): array
    {
        return ['직추천 인원', '보너스', '수정일자'];
    }
}