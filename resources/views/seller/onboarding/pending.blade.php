@extends('layouts.buyer')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4 text-center">
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-8 flex items-center justify-center max-w-lg mx-auto">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="w-24 h-24 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6 border-8 border-amber-50/50">
        <i data-lucide="clock" class="w-10 h-10"></i>
    </div>
    
    <h1 class="text-3xl font-extrabold text-slate-800 mb-4 tracking-tight">Application Under Review</h1>
    
    <p class="text-slate-500 max-w-md mx-auto mb-8 leading-relaxed">
        Your application to become a seller is currently being reviewed by our compliance team. This process usually takes 24-48 hours. We will notify you via email as soon as your account is approved.
    </p>

    <div class="bg-white border border-slate-100 rounded-2xl p-6 max-w-md mx-auto mb-8 shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-50 pb-4 mb-4">
            <span class="text-slate-500 font-medium">Application Status</span>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                Pending Review
            </span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-slate-500 font-medium">Date Submitted</span>
            <span class="text-slate-800 font-bold">
                @php
                    $profile = \App\Models\SellerProfile::where('user_id', Auth::id())->first();
                @endphp
                {{ $profile && $profile->updated_at ? $profile->updated_at->format('M j, Y') : now()->format('M j, Y') }}
            </span>
        </div>
    </div>

    <a href="{{ route('buyer.dashboard') }}" class="inline-flex items-center px-6 py-3.5 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-700 transition-all shadow-lg shadow-slate-200">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
        Return to Dashboard
    </a>
</div>
@endsection
