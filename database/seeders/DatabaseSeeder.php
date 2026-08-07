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
        $deptSales = Department::create(['name' => 'Amigoz', 'head_name' => 'TALHA QASEEM']);
        $deptMarketing = Department::create(['name' => 'Gene', 'head_name' => 'MUNAM QURESHI']);
        $deptEnterprise = Department::create(['name' => 'Planet', 'head_name' => 'DANISH KHAN']);
        $deptRetail = Department::create(['name' => 'ADP', 'head_name' => 'SYED YASOOB']);

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

        // 3. Programmatically generate 15 salespersons per department (Total 60)
        $departmentsList = [
            ['dept' => $deptSales, 'prefix' => 'Amigoz'],
            ['dept' => $deptMarketing, 'prefix' => 'Gene'],
            ['dept' => $deptEnterprise, 'prefix' => 'Planet'],
            ['dept' => $deptRetail, 'prefix' => 'ADP'],
        ];

        $benchmarksList = [$bm10->id, $bm15->id, $bm7->id];
        $rolesList = [$roleUpsell->id, $roleFront->id];

        // Seed 15 salespeople for each department
        foreach ($departmentsList as $deptObj) {
            $dept = $deptObj['dept'];
            $prefix = $deptObj['prefix'];

            for ($i = 1; $i <= 15; $i++) {
                $name = $prefix . ' Agent ' . $i;
                $email = strtolower($prefix) . '_agent_' . $i . '@example.com';
                
                // Create user
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'department_id' => $dept->id,
                    'benchmark_id' => $benchmarksList[array_rand($benchmarksList)],
                    'is_admin' => false,
                ]);
                $user->roles()->sync([$rolesList[array_rand($rolesList)]]);

                // Create Target for user (between 3000 and 12000)
                $targetAmount = rand(30, 120) * 100;
                Target::create([
                    'user_id' => $user->id,
                    'target_amount' => $targetAmount,
                ]);

                // Create 2 to 5 random sales entries for user
                $numSales = rand(2, 5);
                for ($j = 1; $j <= $numSales; $j++) {
                    $saleAmount = rand(5, 40) * 100;
                    $day = rand(1, 28);
                    $month = rand(6, 7); // June or July
                    $date = sprintf('2026-%02d-%02d', $month, $day);

                    Sale::create([
                        'user_id' => $user->id,
                        'amount' => $saleAmount,
                        'date' => $date,
                    ]);
                }

                // Explicitly seed some sales to guarantee top performers exist for yesterday, last week, and last month
                // Yesterday (2026-07-24)
                if ($i == 3) {
                    Sale::create([
                        'user_id' => $user->id,
                        'amount' => rand(1500, 3000),
                        'date' => '2026-07-24',
                    ]);
                }
                // Last Week (e.g. 2026-07-15)
                if ($i == 7) {
                    Sale::create([
                        'user_id' => $user->id,
                        'amount' => rand(4000, 6000),
                        'date' => '2026-07-15',
                    ]);
                }
                // Last Month (e.g. 2026-06-15)
                if ($i == 12) {
                    Sale::create([
                        'user_id' => $user->id,
                        'amount' => rand(7000, 10000),
                        'date' => '2026-06-15',
                    ]);
                }
            }
        }
    }
}
