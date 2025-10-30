<?php

namespace App\Exports;

use App\Models\Transactions;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Transactions::query();

        if (!empty($this->filters['dairy_id'])) {
            $query->where('dairy_id', $this->filters['dairy_id']);
        }

        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['reference_no'])) {
            $query->where('reference_no', 'like', "%{$this->filters['reference_no']}%");
        }

        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $query->whereBetween('transaction_date', [$this->filters['start_date'], $this->filters['end_date']]);
        }

        return $query->orderBy('transaction_date', 'desc')->get([
            'id', 'dairy_id', 'type', 'amount', 'status', 'reference_no', 'transaction_date'
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Dairy ID', 'Type', 'Amount', 'Status', 'Reference No', 'Transaction Date'];
    }
}
