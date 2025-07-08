<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

abstract class BaseIncomeExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    protected function applyCommonFilters($query)
    {
        if (!empty($this->filters['type'])) {
            $query->where('income_transfers.type', $this->filters['type']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('income_transfers.status', $this->filters['status']);
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
            $query->whereBetween('income_transfers.created_at', [$start, $end]);
        }

        return $query;
    }

    protected function formatExportRows($results)
    {
        $statusMap = $this->getStatusMap();

        return $results->map(function ($item, $index) use ($statusMap) {
            if (isset($item->status)) {
                $item->status = $statusMap[$item->status] ?? $item->status;
            }

            return collect(['번호' => $index + 1])->merge((array) $item);
        });
    }

    protected function getStatusMap(): array
    {
        return [
            'pending'   => '신청',
            'waiting'   => '대기',
            'completed' => '완료',
            'canceled'  => '취소',
            'refunded'  => '환불',
        ];
    }

    abstract public function collection();
    abstract public function headings(): array;
}