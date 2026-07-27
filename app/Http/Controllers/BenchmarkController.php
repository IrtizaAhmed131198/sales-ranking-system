<?php

namespace App\Http\Controllers;

use App\Models\Benchmark;
use Illuminate\Http\Request;

class BenchmarkController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $benchmarks = Benchmark::withCount('users');
            return \Yajra\DataTables\Facades\DataTables::of($benchmarks)
                ->addColumn('actions', function ($bm) {
                    return '
                        <div class="d-flex justify-content-end gap-2">
                            <a href="' . route('benchmarks.edit', $bm->id) . '" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-url="' . route('benchmarks.destroy', $bm->id) . '" data-table-id="#benchmarksTable" data-confirm="Are you sure you want to delete this benchmark? Associated salespersons will have their benchmark reset to none." title="Delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('benchmarks.index');
    }

    public function create()
    {
        return view('benchmarks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:benchmarks,name',
        ]);

        Benchmark::create($request->only('name'));

        return redirect()->route('benchmarks.index')->with('success', 'Benchmark created successfully.');
    }

    public function edit(Benchmark $benchmark)
    {
        return view('benchmarks.edit', compact('benchmark'));
    }

    public function update(Request $request, Benchmark $benchmark)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:benchmarks,name,' . $benchmark->id,
        ]);

        $benchmark->update($request->only('name'));

        return redirect()->route('benchmarks.index')->with('success', 'Benchmark updated successfully.');
    }

    public function destroy(Benchmark $benchmark)
    {
        $benchmark->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Benchmark deleted successfully.']);
        }
        return redirect()->route('benchmarks.index')->with('success', 'Benchmark deleted successfully.');
    }
}
