<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Benchmark;

class BenchmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $benchmarks = [
            ['name' => 'Titan',    'front_sale_value' => 15000, 'upsell_value' => 20000],
            ['name' => 'Legend',   'front_sale_value' => 10000, 'upsell_value' => 15000],
            ['name' => 'Champion', 'front_sale_value' => 7500,  'upsell_value' => 10000],
        ];

        foreach ($benchmarks as $benchmark) {
            Benchmark::updateOrCreate(
                ['name' => $benchmark['name']],
                ['front_sale_value' => $benchmark['front_sale_value'], 'upsell_value' => $benchmark['upsell_value']]
            );
        }
    }
}
