<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductPackaging;

class ProductPackagingController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('manage packaging')) {
            abort(403);
        }
        $packagings = ProductPackaging::orderBy('name')->get();
        return view('admin.product-packaging.index', compact('packagings'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('manage packaging')) {
            abort(403);
        }
        $request->validate(['name' => 'required|string|max:255|unique:product_packagings,name']);
        ProductPackaging::create(['name' => $request->name, 'status' => true]);
        return back()->with('success', 'Packaging created successfully.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo('manage packaging')) {
            abort(403);
        }
        ProductPackaging::findOrFail($id)->delete();
        return back()->with('success', 'Packaging deleted successfully.');
    }

    public function toggleStatus($id)
    {
        if (!auth()->user()->hasPermissionTo('manage packaging')) {
            abort(403);
        }
        $pkg = ProductPackaging::findOrFail($id);
        $pkg->update(['status' => !$pkg->status]);
        return back()->with('success', 'Packaging status updated.');
    }
}
