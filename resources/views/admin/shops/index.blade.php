@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Shop Approvals</h1>
    <p class="text-slate-500 text-sm">Review and approve new shop registrations from the field.</p>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center">
        <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-xs font-semibold text-slate-400 uppercase bg-slate-50">
                    <th class="px-6 py-4">Shop Name</th>
                    <th class="px-6 py-4">LGA / Ward</th>
                    <th class="px-6 py-4">Occupant</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($pendingShops as $shop)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-slate-800">{{ $shop->name }}</p>
                        <p class="text-xs text-slate-400 uppercase">{{ $shop->type }} - {{ $shop->size }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $shop->lga }} / {{ $shop->ward }}
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-slate-700">{{ $shop->occupant->name ?? 'N/A' }}</p>
                        <p class="text-xs text-slate-400">{{ $shop->occupant->phone ?? '' }}</p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <form action="{{ route('admin.shops.approve', $shop->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-500 text-white text-xs font-bold rounded-xl hover:bg-emerald-600 transition-colors shadow-sm shadow-emerald-200">
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.shops.reject', $shop->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-colors">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-slate-400 text-sm">
                        <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 opacity-20"></i>
                        No pending registrations.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

