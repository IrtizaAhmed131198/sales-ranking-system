<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $notices = Notice::select('notices.*');
            return \Yajra\DataTables\Facades\DataTables::of($notices)
                ->addColumn('formatted_date', function ($notice) {
                    return $notice->created_at->format('M d, Y H:i');
                })
                ->addColumn('actions', function ($notice) {
                    return '
                        <div class="d-flex justify-content-end gap-2">
                            <a href="' . route('notices.edit', $notice->id) . '" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <form action="' . route('notices.destroy', $notice->id) . '" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this notice?\');" style="display:inline;">
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
        return view('notices.index');
    }

    public function create()
    {
        return view('notices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Notice::create($request->only('title', 'content'));

        return redirect()->route('notices.index')->with('success', 'Notice posted successfully.');
    }

    public function edit(Notice $notice)
    {
        return view('notices.edit', compact('notice'));
    }

    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $notice->update($request->only('title', 'content'));

        return redirect()->route('notices.index')->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('notices.index')->with('success', 'Notice deleted successfully.');
    }
}
