<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CustomersSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('customers')->insert([
                'id' => Str::uuid(),
                'name' => 'Member ' . $i,
                'address' => 'Jl. Contoh Alamat No. ' . $i,
                'no_hp' => '08' . rand(1000000000, 9999999999),
                'points' => rand(0, 1000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
