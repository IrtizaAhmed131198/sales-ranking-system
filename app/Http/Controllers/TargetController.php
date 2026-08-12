<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Models\User;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $targets = Target::with(['user.department', 'role'])->select('targets.*');
            return \Yajra\DataTables\Facades\DataTables::of($targets)
                ->addColumn('user_name', function ($target) {
                    return $target->user ? $target->user->name : 'N/A';
                })
                ->addColumn('department_name', function ($target) {
                    return ($target->user && $target->user->department) ? $target->user->department->name : 'No Department';
                })
                ->addColumn('role_name', function ($target) {
                    return $target->role ? $target->role->name : 'N/A';
                })
                ->addColumn('formatted_amount', function ($target) {
                    return '$' . number_format($target->target_amount, 2);
                })
                ->addColumn('actions', function ($target) {
                    return '
                        <div class="d-flex justify-content-end gap-2">
                            <a href="' . route('targets.edit', $target->id) . '" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-url="' . route('targets.destroy', $target->id) . '" data-table-id="#targetsTable" data-confirm="Are you sure you want to delete this target?" title="Delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('targets.index');
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
            'role_id' => 'required|exists:roles,id',
            'target_amount' => 'required|numeric|min:0.01',
        ]);

        // Enforce unique target per salesperson AND role
        $exists = Target::where('user_id', $request->user_id)
            ->where('role_id', $request->role_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['role_id' => 'A target is already assigned to this salesperson for this role.'])
                ->withInput();
        }

        Target::create($request->only('user_id', 'role_id', 'target_amount'));

        return redirect()->route('targets.index')->with('success', 'Target assigned successfully.');
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
            'role_id' => 'required|exists:roles,id',
            'target_amount' => 'required|numeric|min:0.01',
        ]);

        // Enforce unique target except for this target record itself
        $exists = Target::where('user_id', $request->user_id)
            ->where('role_id', $request->role_id)
            ->where('id', '!=', $target->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['role_id' => 'A target is already assigned to this salesperson for this role.'])
                ->withInput();
        }

        $target->update($request->only('user_id', 'role_id', 'target_amount'));

        return redirect()->route('targets.index')->with('success', 'Target updated successfully.');
    }

    public function destroy(Target $target)
    {
        $target->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Target deleted successfully.']);
        }
        return redirect()->route('targets.index')->with('success', 'Target deleted successfully.');
    }
}
