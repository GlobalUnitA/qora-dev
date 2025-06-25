<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class KycExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
  
    public function collection()
    {
        
        $query = DB::table('kyc_verifications')
            ->leftJoin('users', 'users.id', '=', 'kyc_verifications.user_id')
            ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'kyc_verifications.user_id')
            ->select(
                'users.account',
                'users.id',
                'users.name',
                'user_profiles.level',
                'user_profiles.phone',
                'user_profiles.email',
                'kyc_verifications.status',
                'users.created_at',
            );

      
        if (!empty($this->filters['keyword']) && $this->filters['category'] == 'mid') {
            $query->where('users.id', $this->filters['keyword']);
        }

        if (!empty($this->filters['keyword']) && $this->filters['category'] == 'account') {
            $query->where('users.account', $this->filters['keyword']);
        }

        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $query->whereBetween('users.created_at', [$this->filters['start_date'], $this->filters['end_date']]);
        }

        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $start = Carbon::parse($this->filters['start_date'])->startOfDay();
            $end = Carbon::parse($this->filters['end_date'])->endOfDay();
            $query->whereBetween('kyc_verifications.created_at', [$start, $end]);
        }

        $status_map = [
            'pending' => '신청',
            'approved' => '통과',
            'rejected' => '미통과',
        ];

        return $query->get()->map(function ($item) use ($status_map) {
            $item->status = $status_map[$item->status] ?? $item->status;
            return $item;
        });

        return $query->get();
    }

  
    public function headings(): array
    {
        return ['아이디', 'MID', '회원명', '레벨', '연락처', '이메일', '가입일자'];
    }
}