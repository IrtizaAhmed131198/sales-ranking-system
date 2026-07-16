<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sale::with('user.department')->orderBy('date', 'desc')->get();
        return view('sales.index', compact('sales'));
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
