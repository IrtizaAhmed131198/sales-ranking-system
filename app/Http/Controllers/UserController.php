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
            $users = User::where('is_admin', false)->with(['department', 'roles', 'benchmark'])->select('users.*');
            return \Yajra\DataTables\Facades\DataTables::of($users)
                ->addColumn('photo', function ($user) {
                    $url = $user->image_path ? asset($user->image_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff';
                    return '<img src="' . $url . '" alt="' . $user->name . '" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">';
                })
                ->addColumn('department_name', function ($user) {
                    return $user->department ? $user->department->name : 'No Department';
                })
                ->addColumn('role_name', function ($user) {
                    return $user->roles->isNotEmpty() ? $user->roles->pluck('name')->implode(', ') : 'No Role';
                })
                ->addColumn('benchmark_name', function ($user) {
                    return $user->benchmark ? $user->benchmark->name : 'No Benchmark';
                })
                ->addColumn('status', function ($user) {
                    if ($user->is_active) {
                        return '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1">Active</span>';
                    }
                    return '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1">Inactive</span>';
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
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-url="' . route('users.destroy', $user->id) . '" data-table-id="#usersTable" data-confirm="Are you sure you want to delete this salesperson? All their targets and sales history will also be permanently deleted." title="Delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['photo', 'actions', 'status'])
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
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/users'), $filename);
            $imagePath = 'uploads/users/' . $filename;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'benchmark_id' => $request->benchmark_id,
            'image_path' => $imagePath,
            'is_admin' => false,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

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
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $imagePath = $user->image_path;
        if ($request->hasFile('image')) {
            if ($user->image_path && file_exists(public_path($user->image_path))) {
                @unlink(public_path($user->image_path));
            }
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/users'), $filename);
            $imagePath = 'uploads/users/' . $filename;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'benchmark_id' => $request->benchmark_id,
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        } else {
            $user->roles()->detach();
        }

        return redirect()->route('users.index')->with('success', 'Salesperson updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->image_path && file_exists(public_path($user->image_path))) {
            @unlink(public_path($user->image_path));
        }

        $user->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Salesperson deleted successfully.']);
        }
        return redirect()->route('users.index')->with('success', 'Salesperson deleted successfully.');
    }

    public function search(Request $request)
    {
        $term = $request->input('q');
        $page = $request->input('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $query = User::where('is_admin', false)
            ->where('is_active', true)
            ->with('department');

        if ($term) {
            $query->where(function($q) use ($term) {
                $q->where('name', 'LIKE', '%' . $term . '%')
                  ->orWhere('email', 'LIKE', '%' . $term . '%');
            });
        }

        $totalCount = $query->count();
        $users = $query->skip($offset)->take($limit)->get();

        $results = [];
        foreach ($users as $user) {
            $deptName = $user->department ? $user->department->name : 'No Dept';
            $results[] = [
                'id' => $user->id,
                'text' => $user->name . ' (' . $deptName . ')'
            ];
        }

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => ($offset + $limit) < $totalCount
            ]
        ]);
    }
}
