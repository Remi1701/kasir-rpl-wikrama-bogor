<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SalesSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->pluck('id')->first();
        $customerId = DB::table('customers')->pluck('id')->first(); 

        for ($i = 1; $i <= 5; $i++) {
            DB::table('sales')->insert([
                'id' => Str::uuid(),
                'invoice_number' => 'INV-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'user_id' => $userId,
                'customers_id' => $customerId,
                'customer_name' => 'Customer ' . $i,
                'items_data' => json_encode([
                    [
                        'item_id' => (string) Str::uuid(),
                        'name' => 'Item A',
                        'price' => 50000,
                        'stock' => 2,
                        'subtotal' => 100000
                    ],
                    [
                        'item_id' => (string) Str::uuid(),
                        'name' => 'Item B',
                        'price' => 30000,
                        'stock' => 1,
                        'subtotal' => 30000
                    ]
                ]),
                'total_amount' => 130000,
                'payment_amount' => 150000,
                'change_amount' => 20000,
                'notes' => 'Thank you for your purchase.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
