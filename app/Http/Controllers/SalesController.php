<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\RankingUpdated;

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
                    if ($sale->is_refunded) {
                        return '<span class="text-danger text-decoration-line-through">$' . number_format($sale->amount, 2) . '</span> <span class="badge bg-danger ms-1">Refunded</span>';
                    }
                    return '$' . number_format($sale->amount, 2);
                })
                ->addColumn('actions', function ($sale) {
                    $refundBtnTitle = $sale->is_refunded ? 'Restore Sale' : 'Refund Sale';
                    $refundBtnIcon = $sale->is_refunded ? 'fa-solid fa-rotate-left' : 'fa-solid fa-hand-holding-dollar';
                    $refundBtnClass = $sale->is_refunded ? 'btn-outline-success' : 'btn-outline-warning';
                    $refundConfirmMsg = $sale->is_refunded ? 'Are you sure you want to restore this refunded sale?' : 'Are you sure you want to mark this sale as refunded?';

                    return '
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-sm ' . $refundBtnClass . ' refund-btn" data-url="' . route('sales.refund', $sale->id) . '" data-table-id="#salesTable" data-confirm="' . $refundConfirmMsg . '" title="' . $refundBtnTitle . '">
                                <i class="' . $refundBtnIcon . '"></i>
                            </button>
                            <a href="' . route('sales.edit', $sale->id) . '" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-url="' . route('sales.destroy', $sale->id) . '" data-table-id="#salesTable" data-confirm="Are you sure you want to delete this sales entry?" title="Delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['formatted_amount', 'actions'])
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

        event(new RankingUpdated());

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

        event(new RankingUpdated());

        if (request()->ajax()) {
            return response()->json(['success' => 'Sales entry deleted successfully.']);
        }
        return redirect()->route('sales.index')->with('success', 'Sales entry deleted successfully.');
    }

    public function refund(Sale $sale)
    {
        $sale->update(['is_refunded' => !$sale->is_refunded]);

        event(new RankingUpdated());

        $status = $sale->is_refunded ? 'marked as refunded' : 'restored';
        
        if (request()->ajax()) {
            return response()->json(['success' => "Sales entry $status successfully."]);
        }
        return redirect()->route('sales.index')->with('success', "Sales entry $status successfully.");
    }
}
