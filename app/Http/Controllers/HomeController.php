<?php

namespace App\Http\Controllers;
use App\Models\Sales;
use App\Models\Items;
use App\Models\Customers;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $itemQuantities = [];

        foreach (Sales::all() as $sale) {
            $items = is_array($sale->items_data) ? $sale->items_data : json_decode($sale->items_data, true);

            // Make sure $items is actually an array
            if (!is_array($items)) {
                continue;
            }

            foreach ($items as $item) {
                // Safely check if item_id and stock exist
                if (!isset($item['item_id'], $item['stock'])) {
                    continue;
                }

                $itemId = $item['item_id'];
                $qty = $item['stock']; // how many were sold

                if (!isset($itemQuantities[$itemId])) {
                    $itemQuantities[$itemId] = 0;
                }

                $itemQuantities[$itemId] += $qty;
            }
        }

        $topItems = collect($itemQuantities)
            ->sortDesc()
            ->take(5)
            ->map(function ($quantity, $itemId) {
                $item = Items::find($itemId);

                if ($item) {
                    $item->sold_quantity = $quantity;
                    return $item;
                }

                return (object)[
                    'id' => $itemId,
                    'name' => 'Unknown Item',
                    'price' => 0,
                    'stock' => 0,
                    'sold_quantity' => $quantity,
                ];
            });

        return view('home', [
            'totalSales' => Sales::sum('total_amount'),
            'totalItems' => Items::count(),
            'totalCustomers' => Customers::count(),
            'totalUsers' => User::count(),
            'salesChartLabels' => $this->getLast7Days(),
            'salesChartData' => $this->getSalesDataForLast7Days(),
            'topItems' => $topItems,
        ]);
    }
    
    private function getLast7Days()
    {
        return collect(range(-6, 0))->map(function ($day) {
            return now()->addDays($day)->format('D');
        });
    }

    private function getSalesDataForLast7Days()
    {
        return collect(range(-6, 0))->map(function ($day) {
            return Sales::whereDate('created_at', now()->addDays($day)->toDateString())->sum('total_amount');
        });
    }

}
