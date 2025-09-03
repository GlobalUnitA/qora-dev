<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
  
    public function collection()
    {
        
        $query = DB::table('admins')
    
            ->select(
                'admins.account',
                'admins.name',
                'admins.admin_level',
                'admins.created_at',
            );

        return $query->get();
    }

  
    public function headings(): array
    {
        return ['아이디', '이름', '레벨', '일자'];
    }
}