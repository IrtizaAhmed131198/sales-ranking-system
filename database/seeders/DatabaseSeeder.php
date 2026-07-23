<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Target;
use App\Models\Sale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // 2. Create Departments
        $deptSales = Department::create(['name' => 'Sales']);
        $deptMarketing = Department::create(['name' => 'Marketing']);
        $deptEnterprise = Department::create(['name' => 'Enterprise']);
        $deptRetail = Department::create(['name' => 'Retail']);

        // Create Benchmarks (Categories)
        $bm10 = \App\Models\Benchmark::create(['name' => '10000']);
        $bm15 = \App\Models\Benchmark::create(['name' => '15000']);
        $bm7 = \App\Models\Benchmark::create(['name' => '7500']);

        // Create Roles
        $roleUpsell = \App\Models\Role::create(['name' => 'upsell']);
        $roleFront = \App\Models\Role::create(['name' => 'front sale']);

        // Create Notices
        \App\Models\Notice::create([
            'title' => 'Quarterly Target Update',
            'content' => 'Please note that the general sales benchmarks have been updated for this quarter. Check your targets section for details.'
        ]);
        \App\Models\Notice::create([
            'title' => 'Top Performers Bonus',
            'content' => 'Top performers of this month will receive an extra commission of 10% on achieved targets.'
        ]);

        // 3. Create Salespersons
        $salespersons = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'department_id' => $deptSales->id,
                'benchmark_id' => $bm10->id,
                'role_id' => $roleUpsell->id,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'department_id' => $deptMarketing->id,
                'benchmark_id' => $bm15->id,
                'role_id' => $roleFront->id,
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'department_id' => $deptEnterprise->id,
                'benchmark_id' => $bm7->id,
                'role_id' => $roleUpsell->id,
            ],
            [
                'name' => 'Bob Brown',
                'email' => 'bob@example.com',
                'department_id' => $deptRetail->id,
                'benchmark_id' => $bm10->id,
                'role_id' => $roleFront->id,
            ],
            [
                'name' => 'Charlie Green',
                'email' => 'charlie@example.com',
                'department_id' => $deptSales->id,
                'benchmark_id' => $bm15->id,
                'role_id' => $roleUpsell->id,
            ],
        ];

        $users = [];
        foreach ($salespersons as $sp) {
            $users[] = User::create([
                'name' => $sp['name'],
                'email' => $sp['email'],
                'department_id' => $sp['department_id'],
                'benchmark_id' => $sp['benchmark_id'],
                'role_id' => $sp['role_id'],
                'is_admin' => false,
            ]);
        }

        // 4. Create Targets (flat, not monthly)
        $targetsConfig = [
            'John Doe' => 5000,
            'Jane Smith' => 6000,
            'Alice Johnson' => 8000,
            'Bob Brown' => 4000,
            'Charlie Green' => 7000,
        ];

        foreach ($users as $user) {
            Target::create([
                'user_id' => $user->id,
                'target_amount' => $targetsConfig[$user->name] ?? 5000,
            ]);
        }

        // 5. Create Sales Entries (multiple per user)
        // Let's create multiple sales for June and July
        $salesData = [
            // June Sales (Achieved percentages)
            // John Doe: target 4500 -> sales 5000 (111%)
            ['name' => 'John Doe', 'amount' => 2000, 'date' => '2026-06-05'],
            ['name' => 'John Doe', 'amount' => 1500, 'date' => '2026-06-12'],
            ['name' => 'John Doe', 'amount' => 1500, 'date' => '2026-06-25'],

            // Jane Smith: target 5000 -> sales 4000 (80%)
            ['name' => 'Jane Smith', 'amount' => 1500, 'date' => '2026-06-02'],
            ['name' => 'Jane Smith', 'amount' => 2500, 'date' => '2026-06-20'],

            // Alice Johnson: target 7500 -> sales 9000 (120% - top performer)
            ['name' => 'Alice Johnson', 'amount' => 4000, 'date' => '2026-06-10'],
            ['name' => 'Alice Johnson', 'amount' => 5000, 'date' => '2026-06-22'],

            // Bob Brown: target 3500 -> sales 1400 (40% - lowest performer)
            ['name' => 'Bob Brown', 'amount' => 600, 'date' => '2026-06-08'],
            ['name' => 'Bob Brown', 'amount' => 800, 'date' => '2026-06-18'],

            // Charlie Green: target 6500 -> sales 6000 (92%)
            ['name' => 'Charlie Green', 'amount' => 3000, 'date' => '2026-06-15'],
            ['name' => 'Charlie Green', 'amount' => 3000, 'date' => '2026-06-28'],


            // July Sales
            // John Doe: target 5000 -> sales 4200 (84%)
            ['name' => 'John Doe', 'amount' => 1200, 'date' => '2026-07-02'],
            ['name' => 'John Doe', 'amount' => 3000, 'date' => '2026-07-14'],

            // Jane Smith: target 6000 -> sales 6600 (110% - top performer)
            ['name' => 'Jane Smith', 'amount' => 3000, 'date' => '2026-07-05'],
            ['name' => 'Jane Smith', 'amount' => 3600, 'date' => '2026-07-12'],

            // Alice Johnson: target 8000 -> sales 6400 (80%)
            ['name' => 'Alice Johnson', 'amount' => 3400, 'date' => '2026-07-03'],
            ['name' => 'Alice Johnson', 'amount' => 3000, 'date' => '2026-07-10'],

            // Bob Brown: target 4000 -> sales 1600 (40% - lowest performer)
            ['name' => 'Bob Brown', 'amount' => 1000, 'date' => '2026-07-08'],
            ['name' => 'Bob Brown', 'amount' => 600, 'date' => '2026-07-15'],

            // Charlie Green: target 7000 -> sales 5500 (78.5%)
            ['name' => 'Charlie Green', 'amount' => 2500, 'date' => '2026-07-04'],
            ['name' => 'Charlie Green', 'amount' => 3000, 'date' => '2026-07-11'],
        ];

        foreach ($salesData as $sd) {
            $user = User::where('name', $sd['name'])->first();
            if ($user) {
                Sale::create([
                    'user_id' => $user->id,
                    'amount' => $sd['amount'],
                    'date' => $sd['date'],
                ]);
            }
        }
    }
}
