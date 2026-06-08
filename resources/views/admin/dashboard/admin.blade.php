@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Welcome Back, {{ $user->name }}</h1>
    <p class="text-slate-500 text-sm">Here's your AEM platform overview for today.</p>
</div>

<!-- Key Metrics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center">
        <div class="p-3 bg-blue-100 rounded-2xl mr-4 text-blue-600">
            <i data-lucide="package-export" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase">Exporters</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($metrics['exporters']) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center">
        <div class="p-3 bg-emerald-100 rounded-2xl mr-4 text-emerald-600">
            <i data-lucide="shopping-cart" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase">Buyers</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($metrics['buyers']) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center">
        <div class="p-3 bg-amber-100 rounded-2xl mr-4 text-amber-600">
            <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase">Pending KYC</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($metrics['pending_kyc']) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center">
        <div class="p-3 bg-purple-100 rounded-2xl mr-4 text-purple-600">
            <i data-lucide="banknote" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Export Value</p>
            <p class="text-2xl font-bold text-slate-800">₦{{ number_format($metrics['export_value'], 2) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <p class="text-xs font-semibold text-slate-400 uppercase mb-2">Total Products</p>
        <p class="text-3xl font-bold text-slate-800">{{ number_format($metrics['products']) }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <p class="text-xs font-semibold text-slate-400 uppercase mb-2">Total Orders</p>
        <p class="text-3xl font-bold text-slate-800">{{ number_format($metrics['orders']) }}</p>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <p class="text-xs font-semibold text-slate-400 uppercase mb-2">Pending Settlements</p>
        <p class="text-3xl font-bold text-slate-800">{{ number_format($metrics['pending_settlements']) }}</p>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <p class="text-xs font-semibold text-slate-400 uppercase mb-2">Open Quotes</p>
        <p class="text-3xl font-bold text-slate-800">{{ number_format($metrics['open_quotes']) }}</p>
    </div>
</div>
@endsection
