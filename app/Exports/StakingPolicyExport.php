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
            ->leftJoin('coins', 'coins.id', '=', 'staking_policies.coin_id')
            ->leftJoin('staking_policy_translations', function ($join) {
                $join->on('staking_policy_translations.policy_id', '=', 'staking_policies.id')
                    ->where('staking_policy_translations.locale', 'ko');
            })
            ->select(
                'staking_policy_translations.name as staking_name',
                'staking_policies.staking_type',
                'coins.name as coin_name',
                'staking_policies.min_quantity',
                'staking_policies.max_quantity',
                'staking_policies.daily',
                'staking_policies.period',
                'staking_policies.updated_at',
            );

        $type_map = [
            'maturity' => '원금 반환형',
            'daily' => '원리금 지급형',
        ];

        return $query->get()->map(function ($item) use ($type_map) {
            $item->staking_type = $type_map[$item->staking_type] ?? $item->staking_type;
            return $item;
        });
    }

  
    public function headings(): array
    {
        return ['상품', '타입', '자산', '최소 참여수량', '최대 참여수량', '수익률', '기간', '수정일자'];
    }
}