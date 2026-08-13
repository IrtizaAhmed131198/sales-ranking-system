<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Role;
use App\Models\Notice;
use App\Models\Benchmark;
use App\Models\Sale;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $currentMonthStart = now()->startOfMonth()->toDateString();
        $currentMonthEnd = now()->endOfMonth()->toDateString();

        // 1. Fetch departments with their top 3 sellers, sorted by total sales descending
        $departments = Department::with(['users' => function ($q) {
                $q->where('is_active', true);
            }, 'users.sales' => function ($q) use ($currentMonthStart, $currentMonthEnd) {
                $q->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
                  ->where('is_refunded', false);
            }, 'users.refunds' => function ($q) use ($currentMonthStart) {
                $q->where('refund_month', date('Y-m-01', strtotime($currentMonthStart)));
            }])->get()->map(function ($dept) {
                $dept->total_sales_sum  = 0;
                $dept->total_target_sum = (float) ($dept->target ?? 0);

                foreach ($dept->users as $user) {
                    if (!$user->is_admin) {
                        $user->total_sales = $user->sales->sum('amount') - $user->refunds->sum('amount');
                        $dept->total_sales_sum += $user->total_sales;
                    }
                }

                $dept->top_sellers = $dept->users->where('is_admin', false)
                    ->sortByDesc('total_sales')
                    ->take(3)
                    ->values();

                $dept->dept_performance_percentage = $dept->total_target_sum > 0
                    ? round(($dept->total_sales_sum / $dept->total_target_sum) * 100, 2)
                    : 0;

                return $dept;
            })->sortByDesc('dept_performance_percentage')->values();

        // 2. Fetch leaderboards grouped by benchmark (each slide contains both roles stacked)
        $benchmarks = \App\Models\Benchmark::all()->sortByDesc('front_sale_value');
        $rolesList = Role::all()->sortBy(function ($role) {
            return strtolower($role->name) === 'front sale' ? 0 : 1;
        })->values();

        $leaderboards = [];
        foreach ($benchmarks as $bm) {
            $tables = [];
            foreach ($rolesList as $role) {
                $targetValue = strtolower($role->name) === 'upsell'
                    ? $bm->upsell_value
                    : $bm->front_sale_value;

                $salespersons = User::where('is_admin', false)
                    ->where('is_active', true)
                    ->where('benchmark_id', $bm->id)
                    ->whereHas('roles', function($q) use ($role) {
                        $q->where('roles.id', $role->id);
                    })
                    ->with(['targets', 'sales' => function($q) use ($currentMonthStart, $currentMonthEnd, $role) {
                        $q->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
                          ->where('is_refunded', false)
                          ->where('role_id', $role->id);
                    }, 'refunds' => function($q) use ($currentMonthStart) {
                        $q->where('refund_month', date('Y-m-01', strtotime($currentMonthStart)));
                    }, 'department', 'roles'])
                    ->get()
                    ->map(function ($user) use ($role) {
                        $user->total_target = $user->targets->where('role_id', $role->id)->sum('target_amount');
                        $user->total_sales = $user->sales->sum('amount') - $user->refunds->sum('amount');
                        $user->performance_percentage = $user->total_target > 0
                            ? round(($user->total_sales / $user->total_target) * 100, 2)
                            : 0;
                        return $user;
                    })
                    ->sortByDesc('total_sales')
                    ->take(5)
                    ->values();

                if ($salespersons->count() > 0) {
                    $tables[] = [
                        'role' => $role,
                        'salespersons' => $salespersons,
                        'target_value' => $targetValue,
                    ];
                }
            }
            if (!empty($tables)) {
                $leaderboards[] = [
                    'benchmark' => $bm,
                    'tables' => $tables
                ];
            }
        }

        // 3. Fetch Star Performers (Monthly, Weekly, Daily)
        $prevMonthStart = now()->subMonth()->startOfMonth()->toDateString();
        $prevMonthEnd = now()->subMonth()->endOfMonth()->toDateString();

        $prevWeekStart = now()->subWeek()->startOfWeek()->toDateString();
        $prevWeekEnd = now()->subWeek()->endOfWeek()->toDateString();

        $prevDay = now()->subDay()->toDateString();

        $getTopSeller = function ($startDate, $endDate, $label, $desc, $isMonthly = false) {
            return User::where('is_admin', false)
                ->where('is_active', true)
                ->whereHas('sales', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate])
                      ->where('is_refunded', false);
                })
                ->with(['targets', 'department', 'roles', 'sales' => function($q) {
                    $q->where('is_refunded', false);
                }, 'refunds' => function($q) use ($startDate, $isMonthly) {
                    if ($isMonthly) {
                        $q->where('refund_month', date('Y-m-01', strtotime($startDate)));
                    } else {
                        $q->where('id', 0);
                    }
                }])
                ->get()
                ->map(function ($user) use ($startDate, $endDate, $isMonthly) {
                    $user->total_target = $user->targets->sum('target_amount');
                    $salesAmount = $user->sales->whereBetween('date', [$startDate, $endDate])->sum('amount');
                    $refundsAmount = $isMonthly ? $user->refunds->sum('amount') : 0;
                    $user->total_sales = $salesAmount - $refundsAmount;
                    $user->performance_percentage = $user->total_target > 0
                        ? round(($user->total_sales / $user->total_target) * 100, 2)
                        : 0;
                    return $user;
                })
                ->sortByDesc('total_sales')
                ->map(function ($user) use ($label, $desc) {
                    $user->category_label = $label;
                    $user->category_desc = $desc;
                    $user->role_names = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->implode(', ') : 'Salesperson';
                    return $user;
                })
                ->first();
        };

        $starPerformers = [];

        $monthlyTop = $getTopSeller($prevMonthStart, $prevMonthEnd, 'Star Performer of the Month', 'PREVIOUS MONTH', true);
        if ($monthlyTop) {
            $starPerformers[] = $monthlyTop;
        }

        $weeklyTop = $getTopSeller($prevWeekStart, $prevWeekEnd, 'Star Performer of the Week', 'PREVIOUS WEEK');
        if ($weeklyTop) {
            $starPerformers[] = $weeklyTop;
        }

        $dailyTop = $getTopSeller($prevDay, $prevDay, 'Star Performer of the Day', 'PREVIOUS DAY');
        if ($dailyTop) {
            $starPerformers[] = $dailyTop;
        }

        // 4. Notice Board
        $notices = Notice::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // 5. Marquee Data — direct from existing records, no separate events table

        // New Sale — already latest sale
        $latestSale = Sale::with('user')
            ->where('is_refunded', false)
            ->orderBy('id', 'desc')
            ->first();

        $salesText = $latestSale
            ? "💰 New Sale by {$latestSale->user->name}! (\$".number_format($latestSale->amount, 2).")"
            : "No sales recorded yet.";

        // Target Completed — jis user ne sabse recently 100%+ cross kiya (based on last sale date)
        $completedUser = User::where('is_admin', false)->where('is_active', true)
            ->with(['targets', 'sales' => function($q) {
                $q->where('is_refunded', false);
            }, 'refunds' => function($q) {
                $q->where('refund_month', date('Y-m-01'));
            }])
            ->get()
            ->map(function ($u) {
                $u->total_target = $u->targets->sum('target_amount');
                $u->total_sales = $u->sales->sum('amount') - $u->refunds->sum('amount');
                $u->performance_percentage = $u->total_target > 0
                    ? round(($u->total_sales / $u->total_target) * 100, 2) : 0;
                $u->last_sale_date = optional($u->sales->sortByDesc('date')->first())->date;
                return $u;
            })
            ->filter(fn ($u) => $u->performance_percentage >= 100 && $u->last_sale_date)
            ->sortByDesc('last_sale_date')
            ->first();

        $targetCompletedText = $completedUser
            ? "🎯 Target Completed by {$completedUser->name}!"
            : "No targets completed yet.";

        // Current #1 Top Performer — Overall highest sales for the current month
        $topPerformerText = "No top performer yet.";
        $overallTopPerformer = $departments->flatMap(function($dept) {
            return $dept->users->where('is_admin', false);
        })->sortByDesc('total_sales')->first();

        if ($overallTopPerformer) {
            $topPerformerText = "🌟 Current Top Performer: {$overallTopPerformer->name}!";
        }

        // Current #1 Department — departments already sortByDesc('dept_performance_percentage')
        $topDeptText = "No department data yet.";
        if ($departments->isNotEmpty()) {
            $topDeptText = "🏢 Leading Department: {$departments->first()->name}!";
        }

        return view('welcome', compact('departments', 'leaderboards', 'starPerformers', 'notices', 'salesText', 'targetCompletedText', 'topPerformerText', 'topDeptText'));
    }
}
