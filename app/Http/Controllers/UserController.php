<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::where('is_admin', false)->with(['department', 'role', 'benchmark'])->select('users.*');
            return \Yajra\DataTables\Facades\DataTables::of($users)
                ->addColumn('department_name', function ($user) {
                    return $user->department ? $user->department->name : 'No Department';
                })
                ->addColumn('role_name', function ($user) {
                    return $user->role ? $user->role->name : 'No Role';
                })
                ->addColumn('benchmark_name', function ($user) {
                    return $user->benchmark ? $user->benchmark->name : 'No Benchmark';
                })
                ->addColumn('created_date', function ($user) {
                    return $user->created_at ? $user->created_at->format('M d, Y') : '';
                })
                ->addColumn('actions', function ($user) {
                    return '
                        <div class="d-flex justify-content-end gap-2">
                            <a href="' . route('users.edit', $user->id) . '" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <form action="' . route('users.destroy', $user->id) . '" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this salesperson? All their targets and sales history will also be permanently deleted.\');" style="display:inline;">
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
        return view('users.index');
    }

    public function create()
    {
        $departments = Department::all();
        $benchmarks = \App\Models\Benchmark::all();
        $roles = \App\Models\Role::all();
        return view('users.create', compact('departments', 'benchmarks', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'department_id' => 'nullable|exists:departments,id',
            'benchmark_id' => 'nullable|exists:benchmarks,id',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'benchmark_id' => $request->benchmark_id,
            'role_id' => $request->role_id,
            'is_admin' => false,
        ]);

        return redirect()->route('users.index')->with('success', 'Salesperson created successfully.');
    }

    public function edit(User $user)
    {
        if ($user->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $departments = Department::all();
        $benchmarks = \App\Models\Benchmark::all();
        $roles = \App\Models\Role::all();
        return view('users.edit', compact('user', 'departments', 'benchmarks', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'department_id' => 'nullable|exists:departments,id',
            'benchmark_id' => 'nullable|exists:benchmarks,id',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'benchmark_id' => $request->benchmark_id,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Salesperson updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Salesperson deleted successfully.');
    }
}
