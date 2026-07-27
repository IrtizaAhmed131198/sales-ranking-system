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
                ->addColumn('image', function ($notice) {
                    if ($notice->image_path) {
                        return '<img src="' . asset($notice->image_path) . '" alt="" class="rounded" style="width: 50px; height: 35px; object-fit: cover;">';
                    }
                    return '<span class="text-secondary small">No Image</span>';
                })
                ->addColumn('status', function ($notice) {
                    if ($notice->is_active) {
                        return '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1">Active</span>';
                    }
                    return '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1">Inactive</span>';
                })
                ->addColumn('formatted_date', function ($notice) {
                    return $notice->created_at->format('M d, Y H:i');
                })
                ->addColumn('actions', function ($notice) {
                    return '
                        <div class="d-flex justify-content-end gap-2">
                            <a href="' . route('notices.edit', $notice->id) . '" class="btn btn-sm btn-outline-info" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-url="' . route('notices.destroy', $notice->id) . '" data-table-id="#noticesTable" data-confirm="Are you sure you want to delete this notice?" title="Delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['image', 'actions', 'status'])
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/notices'), $filename);
            $imagePath = 'uploads/notices/' . $filename;
        }

        Notice::create([
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
        ]);

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $notice->image_path;
        if ($request->hasFile('image')) {
            if ($notice->image_path && file_exists(public_path($notice->image_path))) {
                @unlink(public_path($notice->image_path));
            }
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/notices'), $filename);
            $imagePath = 'uploads/notices/' . $filename;
        }

        $notice->update([
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('notices.index')->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        if ($notice->image_path && file_exists(public_path($notice->image_path))) {
            @unlink(public_path($notice->image_path));
        }

        $notice->delete();

        if (request()->ajax()) {
            return response()->json(['success' => 'Notice deleted successfully.']);
        }
        return redirect()->route('notices.index')->with('success', 'Notice deleted successfully.');
    }
}
