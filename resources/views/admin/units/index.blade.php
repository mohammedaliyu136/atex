@extends('layouts.admin')

@section('title', 'Units of Measurement')
@section('header_title', 'Units of Measurement')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-slate-800">Manage Units</h2>
        </div>
        
        @if(auth()->user()->hasPermissionTo('manage units'))
        <div class="p-6 bg-slate-50 border-b border-slate-200">
            <form action="{{ route('admin.units.store') }}" method="POST" class="flex gap-4 items-end max-w-lg">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Add New Unit</label>
                    <input type="text" name="name" class="form-input w-full" placeholder="e.g. Kg, MT, Ton" required>
                </div>
                <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white rounded-xl font-medium flex items-center justify-center hover:bg-primary-700 transition-colors h-[42px]">
                    <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                    Add Unit
                </button>
            </form>
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Status</th>
                        @if(auth()->user()->hasPermissionTo('manage units'))
                        <th class="px-6 py-4 text-right">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($units as $unit)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $unit->name }}</td>
                            <td class="px-6 py-4">
                                @if($unit->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Inactive</span>
                                @endif
                            </td>
                            @if(auth()->user()->hasPermissionTo('manage units'))
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.units.toggle-status', $unit->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-1 {{ $unit->status ? 'text-red-400 hover:text-red-600' : 'text-emerald-400 hover:text-emerald-600' }}" title="{{ $unit->status ? 'Deactivate' : 'Activate' }}">
                                            <i data-lucide="power" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this unit?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-600 p-1" title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasPermissionTo('manage units') ? 3 : 2 }}" class="px-6 py-8 text-center text-slate-500">
                                No units found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
