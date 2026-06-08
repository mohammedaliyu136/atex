@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Welcome Back, {{ $user->name }}</h1>
    <p class="text-slate-500 text-sm">Here's your logistics overview for today.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center">
        <div class="p-3 bg-blue-100 rounded-2xl mr-4 text-blue-600">
            <i data-lucide="package" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase">Assigned</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($metrics['assigned_shipments']) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center">
        <div class="p-3 bg-amber-100 rounded-2xl mr-4 text-amber-600">
            <i data-lucide="truck" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase">In Transit</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($metrics['in_transit_shipments']) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center">
        <div class="p-3 bg-emerald-100 rounded-2xl mr-4 text-emerald-600">
            <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase">Delivered</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($metrics['delivered_shipments']) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center">
        <div class="p-3 bg-purple-100 rounded-2xl mr-4 text-purple-600">
            <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase">Pending Assignment</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($metrics['pending_assignment']) }}</p>
        </div>
    </div>
</div>
@endsection
