<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('manage product categories')) {
            abort(403);
        }
        $categories = Category::with('parent')->orderBy('name')->get();
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('manage product categories')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'status' => true,
        ]);

        return back()->with('success', 'Category created successfully.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo('manage product categories')) {
            abort(403);
        }
        Category::findOrFail($id)->delete();
        return back()->with('success', 'Category deleted successfully.');
    }

    public function toggleStatus($id)
    {
        if (!auth()->user()->hasPermissionTo('manage product categories')) {
            abort(403);
        }
        $category = Category::findOrFail($id);
        $category->update(['status' => !$category->status]);
        return back()->with('success', 'Category status updated.');
    }
}
