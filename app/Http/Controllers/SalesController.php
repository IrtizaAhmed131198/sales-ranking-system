<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sales = Sale::with('user.department')->select('sales.*');
            return \Yajra\DataTables\Facades\DataTables::of($sales)
                ->addColumn('user_name', function ($sale) {
                    return $sale->user ? $sale->user->name : 'N/A';
                })
                ->addColumn('department_name', function ($sale) {
                    return ($sale->user && $sale->user->department) ? $sale->user->department->name : 'No Department';
                })
                ->addColumn('formatted_date', function ($sale) {
                    return date('M d, Y', strtotime($sale->date));
                })
                ->addColumn('formatted_amount', function ($sale) {
                    return '$' . number_format($sale->amount, 2);
                })
                ->addColumn('actions', function ($sale) {
                    return '
                        <div class="d-flex justify-content-end gap-2">
                            <a href="' . route('sales.edit', $sale->id) . '" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <form action="' . route('sales.destroy', $sale->id) . '" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this sales entry?\');" style="display:inline;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('sales.index');
    }

    public function create()
    {
        $users = User::where('is_admin', false)->orderBy('name')->get();
        return view('sales.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        Sale::create($request->only('user_id', 'amount', 'date'));

        return redirect()->route('sales.index')->with('success', 'Sales entry recorded successfully.');
    }

    public function edit(Sale $sale)
    {
        $users = User::where('is_admin', false)->orderBy('name')->get();
        return view('sales.edit', compact('sale', 'users'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $sale->update($request->only('user_id', 'amount', 'date'));

        return redirect()->route('sales.index')->with('success', 'Sales entry updated successfully.');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sales entry deleted successfully.');
    }
}
