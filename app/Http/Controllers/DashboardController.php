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
        // 1. Get distinct months with targets for filter dropdown
        $availableMonths = Target::select('month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->toArray();

        // Default to current month, or the first available month, or current month if empty
        $currentMonth = date('Y-m');
        $selectedMonth = $request->input('month', (!empty($availableMonths) ? $availableMonths[0] : $currentMonth));
        $selectedDeptId = $request->input('department_id');

        // Calculate start and end date for the selected month (for DB agnostic querying)
        $startDate = $selectedMonth . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        // 2. Fetch salespersons with targets for the selected month, and sum their sales
        $query = User::where('is_admin', false)
            ->join('targets', function ($join) use ($selectedMonth) {
                $join->on('users.id', '=', 'targets.user_id')
                    ->where('targets.month', '=', $selectedMonth);
            })
            ->leftJoin('sales', function ($join) use ($startDate, $endDate) {
                $join->on('users.id', '=', 'sales.user_id')
                    ->whereBetween('sales.date', [$startDate, $endDate]);
            })
            ->select(
                'users.id',
                'users.name',
                'users.department_id',
                'targets.target_amount',
                DB::raw('COALESCE(SUM(sales.amount), 0) as total_sales')
            )
            ->groupBy('users.id', 'users.name', 'users.department_id', 'targets.target_amount');

        if ($selectedDeptId) {
            $query->where('users.department_id', $selectedDeptId);
        }

        $salespersons = $query->get();

        // 3. Compute percentage and sort desc
        $ranked = $salespersons->map(function ($sp) {
            $sp->performance_percentage = $sp->target_amount > 0
                ? round(($sp->total_sales / $sp->target_amount) * 100, 2)
                : 0;
            return $sp;
        })->sortByDesc('performance_percentage')->values();

        // 4. Identify top and lowest performer
        $topPerformer = $ranked->first();
        $lowestPerformer = $ranked->count() > 1 ? $ranked->last() : ($ranked->count() === 1 ? null : null);
        // If there's only one performer, we don't need to duplicate them in both cards unless they fit both.
        // Let's just set lowestPerformer to last() if they exist, to be clear.
        if ($ranked->count() === 1) {
            $lowestPerformer = $ranked->first();
        }

        // 5. Gather summary metrics
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
            'availableMonths',
            'selectedMonth',
            'selectedDeptId'
        ));
    }
}
