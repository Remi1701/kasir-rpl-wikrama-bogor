<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Items;
use App\Models\Customers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;

class SalesController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sales::query();

        // Search functionality
        if ($request->has('search') && $request->search !== null) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(invoice_number) LIKE ?', ['%' . $search . '%']);
        }

        // Filter by date range (daily, weekly, monthly, yearly)
        if ($request->has('filter_type')) {
            $filterType = $request->input('filter_type');
            $date = now();

            switch ($filterType) {
                case 'daily':
                    if ($request->has('date')) {
                        $query->whereDate('created_at', $request->date);
                    }
                    break;

                case 'weekly':
                    if ($request->has('week')) {
                        $weekStart = \Carbon\Carbon::parse($request->week)->startOfWeek();
                        $weekEnd = \Carbon\Carbon::parse($request->week)->endOfWeek();
                        $query->whereBetween('created_at', [$weekStart, $weekEnd]);
                    }
                    break;

                case 'monthly':
                    if ($request->has('month')) {
                        $query->whereMonth('created_at', \Carbon\Carbon::parse($request->month)->month)
                            ->whereYear('created_at', \Carbon\Carbon::parse($request->month)->year);
                    }
                    break;

                case 'yearly':
                    if ($request->has('year')) {
                        $query->whereYear('created_at', $request->year);
                    }
                    break;

                default:
                    // No filter applied, show all
                    break;
            }
        }

        $sales = $query->latest()->paginate(10)->appends($request->only('search', 'filter_type', 'date', 'week', 'month', 'year'));

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Items::all();
        $customers = Customers::all();
        return view('sales.create', compact('items', 'customers'));
    }

    /**
     * Confirms an incoming created Sales
     */
    public function confirmationStore(Request $request) {
        $filteredStock = [];

        foreach ($request->input('stock', []) as $key => $value) {
            if ($value > 0) {
                $filteredStock[$key] = $value;
            }
        }

        $items = Items::whereIn('id', array_keys($filteredStock))->get();
        $totalAmount = $items->sum(function ($item) use ($filteredStock) {
            return $item->price * $filteredStock[$item->id];
        });

        $customers = Customers::all();

        return view('sales.confirmation', compact('items', 'totalAmount', 'customers', 'filteredStock'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $itemsData = json_decode($request->input('items_data'), true);
        $totalPay = $request->input('total_pay');
        $totalAmount = $request->input('total_amount');
        $invoiceNumber = 'INV-' . strtoupper(Str::random(8));

        $customersName = $invoiceNumber;
        $customersId = null;

        if (!empty($request->customers_id)) {
            $customers = Customers::find($request->customers_id);
            if ($customers) {
                $customersId = $customers->id;
                $customersName = $customers->name;
            }
        }

        if ($request->is_customers == 'yes') {
            $items = $itemsData;
            return view('sales.customers', compact('customers', 'items', 'totalAmount', 'totalPay'));
        }

        if ($request->use_point == 1) {
            $totalAmount = $totalAmount - $request->total_point;
            Customers::where('id', $customersId)->decrement('points', $request->total_point);
        } else {
            $addPoint = $totalAmount / 750;
            Customers::where('id', $customersId)->increment('points', $addPoint);
        }

        Sales::create([
            'id' => Str::uuid(),
            'invoice_number' => $invoiceNumber,
            'customer_name' => $customersName,
            'user_id' => Auth::user()->id,
            'customers_id' => $customersId,
            'items_data' => json_encode($itemsData),
            'total_amount' => $totalAmount,
            'payment_amount' => $totalPay,
            'change_amount' => $totalPay - $totalAmount,
            'notes' => '-',
        ]);

        foreach ($itemsData as $items) {
            Items::where('id', $items['id'])->decrement('stock', $items['stock']);
        }

        if ($request->use_point == 1) {
            $totalAmount = $totalAmount + $request->total_point;
            $discount = $request->total_point;
        } else {
            $discount = 0;
        }

        return view('sales.invoice', compact('invoiceNumber', 'totalAmount', 'totalPay', 'customersName', 'customersId', 'itemsData', 'discount'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $filterType = $request->input('filter_type');
        $filterValue = null;
        switch ($filterType) {
            case 'daily':
                $filterValue = $request->input('date');
                break;
            case 'weekly':
                $filterValue = $request->input('week');
                break;
            case 'monthly':
                $filterValue = $request->input('month');
                break;
            case 'yearly':
                $filterValue = $request->input('year');
                break;
        }
        return Excel::download(new SalesExport($filterType, $filterValue), 'sales_export.xlsx');
    }


    /**
     * Shows the invoice of a specific sales
     */
    public function showInvoice($id)
    {
        $sales = Sales::where('id', $id)->firstOrFail();

        $itemsData = is_string($sales->items_data) ? json_decode($sales->items_data, true) : $sales->items_data;

        $totalItemsPrice = array_reduce($itemsData, function ($carry, $item) {
            return $carry + ($item['price'] * $item['stock']);
        }, 0);

        $discount = $totalItemsPrice - $sales->total_amount;

        return view('sales.invoice-detail', [
            'invoiceNumber' => $sales->invoice_number,
            'customersName' => $sales->customer_name,
            'customersId'   => $sales->customers_id,
            'itemsData'     => $itemsData,
            'totalAmount'   => $sales->total_amount,
            'totalPay'      => $sales->payment_amount,
            'changeAmount'  => $sales->change_amount,
            'discount'      => $discount,
            'createdAt'     => $sales->created_at
        ]);
    }
}
