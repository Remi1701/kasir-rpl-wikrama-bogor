<?php
namespace App\Exports;

use App\Models\Sales;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    protected $filterType, $filterValue;

    public function __construct($filterType = null, $filterValue = null)
    {
        $this->filterType = $filterType;
        $this->filterValue = $filterValue;
    }

    public function collection()
    {
        $query = Sales::select('invoice_number', 'customer_name', 'total_amount', 'payment_amount', 'change_amount', 'created_at');

        switch ($this->filterType) {
            case 'daily':
                $query->whereDate('created_at', $this->filterValue);
                break;

            case 'weekly':
                $weekStart = Carbon::parse($this->filterValue)->startOfWeek();
                $weekEnd = Carbon::parse($this->filterValue)->endOfWeek();
                $query->whereBetween('created_at', [$weekStart, $weekEnd]);
                break;

            case 'monthly':
                $month = Carbon::parse($this->filterValue)->month;
                $year = Carbon::parse($this->filterValue)->year;
                $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
                break;

            case 'yearly':
                $query->whereYear('created_at', $this->filterValue);
                break;
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Customer Name',
            'Total Amount',
            'Payment Amount',
            'Change Amount',
            'Created At',
        ];
    }
}
