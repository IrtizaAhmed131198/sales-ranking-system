<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Models\User;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    public function index()
    {
        $targets = Target::with('user.department')->get();
        return view('targets.index', compact('targets'));
    }

    public function create()
    {
        $users = User::where('is_admin', false)->orderBy('name')->get();
        return view('targets.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'target_amount' => 'required|numeric|min:0.01',
            'month' => 'required|string|regex:/^\d{4}-\d{2}$/',
        ]);

        // Enforce unique target per salesperson per month
        $exists = Target::where('user_id', $request->user_id)
            ->where('month', $request->month)
            ->exists();

        if ($exists) {
            return back()->withErrors(['month' => 'A monthly target is already assigned to this salesperson for the selected month.'])
                ->withInput();
        }

        Target::create($request->only('user_id', 'target_amount', 'month'));

        return redirect()->route('targets.index')->with('success', 'Monthly target assigned successfully.');
    }

    public function edit(Target $target)
    {
        $users = User::where('is_admin', false)->orderBy('name')->get();
        return view('targets.edit', compact('target', 'users'));
    }

    public function update(Request $request, Target $target)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'target_amount' => 'required|numeric|min:0.01',
            'month' => 'required|string|regex:/^\d{4}-\d{2}$/',
        ]);

        // Enforce unique target except for this target record itself
        $exists = Target::where('user_id', $request->user_id)
            ->where('month', $request->month)
            ->where('id', '!=', $target->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['month' => 'A monthly target is already assigned to this salesperson for the selected month.'])
                ->withInput();
        }

        $target->update($request->only('user_id', 'target_amount', 'month'));

        return redirect()->route('targets.index')->with('success', 'Monthly target updated successfully.');
    }

    public function destroy(Target $target)
    {
        $target->delete();
        return redirect()->route('targets.index')->with('success', 'Monthly target deleted successfully.');
    }
}
