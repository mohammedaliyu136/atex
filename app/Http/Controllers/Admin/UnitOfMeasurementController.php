<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitOfMeasurement;

class UnitOfMeasurementController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('manage units')) {
            abort(403);
        }
        $units = UnitOfMeasurement::orderBy('name')->get();
        return view('admin.units.index', compact('units'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('manage units')) {
            abort(403);
        }
        $request->validate(['name' => 'required|string|max:255|unique:unit_of_measurements,name']);
        UnitOfMeasurement::create(['name' => $request->name, 'status' => true]);
        return back()->with('success', 'Unit created successfully.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo('manage units')) {
            abort(403);
        }
        UnitOfMeasurement::findOrFail($id)->delete();
        return back()->with('success', 'Unit deleted successfully.');
    }

    public function toggleStatus($id)
    {
        if (!auth()->user()->hasPermissionTo('manage units')) {
            abort(403);
        }
        $unit = UnitOfMeasurement::findOrFail($id);
        $unit->update(['status' => !$unit->status]);
        return back()->with('success', 'Unit status updated.');
    }
}
