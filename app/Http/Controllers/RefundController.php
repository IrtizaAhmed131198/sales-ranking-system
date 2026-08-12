<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\RankingUpdated;
use Yajra\DataTables\Facades\DataTables;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $refunds = Refund::with('user.department')->select('refunds.*');
            return DataTables::of($refunds)
                ->addColumn('user_name', function ($refund) {
                    return $refund->user ? $refund->user->name : 'N/A';
                })
                ->addColumn('department_name', function ($refund) {
                    return ($refund->user && $refund->user->department) ? $refund->user->department->name : 'No Department';
                })
                ->addColumn('formatted_month', function ($refund) {
                    return date('F Y', strtotime($refund->refund_month));
                })
                ->addColumn('formatted_amount', function ($refund) {
                    return '-$' . number_format($refund->amount, 2);
                })
                ->addColumn('actions', function ($refund) {
                    return '
                        <div class="d-flex justify-content-end gap-2">
                            <a href="' . route('refunds.edit', $refund->id) . '" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-url="' . route('refunds.destroy', $refund->id) . '" data-table-id="#refundsTable" data-confirm="Are you sure you want to delete this refund entry?" title="Delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('refunds.index');
    }

    public function create()
    {
        $users = User::where('is_admin', false)->orderBy('name')->get();
        return view('refunds.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'refund_month' => 'required|date_format:Y-m',
        ]);

        Refund::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'refund_month' => $request->refund_month . '-01',
        ]);

        event(new RankingUpdated());

        return redirect()->route('refunds.index')->with('success', 'Refund entry recorded successfully.');
    }

    public function edit(Refund $refund)
    {
        $users = User::where('is_admin', false)->orderBy('name')->get();
        return view('refunds.edit', compact('refund', 'users'));
    }

    public function update(Request $request, Refund $refund)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'refund_month' => 'required|date_format:Y-m',
        ]);

        $refund->update([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'refund_month' => $request->refund_month . '-01',
        ]);

        event(new RankingUpdated());

        return redirect()->route('refunds.index')->with('success', 'Refund entry updated successfully.');
    }

    public function destroy(Refund $refund)
    {
        $refund->delete();

        event(new RankingUpdated());

        if (request()->ajax()) {
            return response()->json(['success' => 'Refund entry deleted successfully.']);
        }
        return redirect()->route('refunds.index')->with('success', 'Refund entry deleted successfully.');
    }
}
