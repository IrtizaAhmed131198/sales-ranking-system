<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $departments = Department::withCount('users');
            return \Yajra\DataTables\Facades\DataTables::of($departments)
                ->addColumn('actions', function ($dept) {
                    return '
                        <div class="d-flex justify-content-end gap-2">
                            <a href="' . route('departments.edit', $dept->id) . '" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-url="' . route('departments.destroy', $dept->id) . '" data-table-id="#departmentsTable" data-confirm="Are you sure you want to delete this department? All associated users will remain but without a department assigned." title="Delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('departments.index');
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:departments,name',
            'target' => 'nullable|numeric|min:0',
        ]);

        Department::create($request->only('name', 'target'));

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:departments,name,' . $department->id,
            'target' => 'nullable|numeric|min:0',
        ]);

        $department->update($request->only('name', 'target'));

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Department deleted successfully.']);
        }
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
