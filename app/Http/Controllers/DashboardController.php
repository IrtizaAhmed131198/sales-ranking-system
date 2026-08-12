<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Target;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedDeptId = $request->input('department_id');

        // 1. Fetch salespersons with their flat targets, and sum all their sales
        $query = User::where('is_admin', false)
            ->with(['targets', 'sales' => function($q) {
                $q->where('is_refunded', false);
            }, 'refunds']);

        if ($selectedDeptId) {
            $query->where('department_id', $selectedDeptId);
        }

        $salespersons = $query->get()->map(function ($user) {
            $user->target_amount = $user->targets->sum('target_amount');
            $user->total_sales = $user->sales->sum('amount') - $user->refunds->sum('amount');
            return $user;
        });

        // 2. Compute performance percentage and sort descending
        $ranked = $salespersons->map(function ($sp) {
            $sp->performance_percentage = $sp->target_amount > 0
                ? round(($sp->total_sales / $sp->target_amount) * 100, 2)
                : 0;
            return $sp;
        })->sortByDesc('performance_percentage')->values();

        // 3. Identify top and lowest performer
        $topPerformer = $ranked->first();
        $lowestPerformer = $ranked->count() > 1 ? $ranked->last() : null;
        if ($ranked->count() === 1) {
            $lowestPerformer = $ranked->first();
        }

        // 4. Gather summary metrics
        $totalSales = $ranked->sum('total_sales');
        $totalTarget = $ranked->sum('target_amount');
        $averagePerformance = $ranked->count() > 0 ? round($ranked->avg('performance_percentage'), 2) : 0;

        $departments = Department::orderBy('name')->get();

        return view('dashboard', compact(
            'ranked',
            'topPerformer',
            'lowestPerformer',
            'totalSales',
            'totalTarget',
            'averagePerformance',
            'departments',
            'selectedDeptId'
        ));
    }
}
