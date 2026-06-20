<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessCategory;

class BusinessCategoryController extends Controller
{
    public function index()
    {
        // Must have permission
        if (!auth()->user()->hasPermissionTo('view business category')) {
            abort(403);
        }
        $categories = BusinessCategory::orderBy('name')->get();
        return view('admin.business-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('manage business category')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required|string|max:255|unique:business_categories,name',
        ]);

        BusinessCategory::create([
            'name' => $request->name,
            'status' => true,
        ]);

        return back()->with('success', 'Business category created successfully.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo('manage business category')) {
            abort(403);
        }
        $category = BusinessCategory::findOrFail($id);
        $category->delete();
        return back()->with('success', 'Business category deleted successfully.');
    }

    public function toggleStatus($id)
    {
        if (!auth()->user()->hasPermissionTo('manage business category')) {
            abort(403);
        }
        $category = BusinessCategory::findOrFail($id);
        $category->update(['status' => !$category->status]);
        return back()->with('success', 'Business category status updated.');
    }
}
