@extends('layouts.buyer')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4 text-center" x-data="{ showDetails: false }">
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
                {{ $profile && $profile->updated_at ? $profile->updated_at->format('M j, Y') : now()->format('M j, Y') }}
            </span>
        </div>
    </div>

    <div class="flex justify-center gap-4 flex-wrap">
        <a href="{{ route('buyer.dashboard') }}" class="inline-flex items-center px-6 py-3.5 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-700 transition-all shadow-lg shadow-slate-200">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Return to Dashboard
        </a>
        <button @click="showDetails = !showDetails" class="inline-flex items-center px-6 py-3.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-all shadow-sm">
            <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
            <span x-text="showDetails ? 'Hide Application Details' : 'View Application Details'"></span>
        </button>
    </div>

    @if($profile)
    <!-- Application Details Section -->
    <div x-show="showDetails" style="display: none;" x-transition class="mt-12 text-left bg-white border border-slate-100 rounded-2xl p-8 shadow-sm">
        <h2 class="text-xl font-bold text-slate-800 mb-8 border-b border-slate-100 pb-4">Submitted Information</h2>
        
        <div class="space-y-10">
            <!-- Business Info -->
            <div>
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-5 flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center mr-3"><i data-lucide="briefcase" class="w-4 h-4 text-slate-400"></i></div>
                    Business Profile
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 pl-11">
                    <div><span class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Business Name</span><span class="text-sm font-medium text-slate-800">{{ $profile->business_name }}</span></div>
                    <div><span class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Brand Name</span><span class="text-sm font-medium text-slate-800">{{ $profile->seller_brand_name ?? 'N/A' }}</span></div>
                    <div><span class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Category</span><span class="text-sm font-medium text-slate-800">{{ $profile->business_category }}</span></div>
                    <div><span class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Phone</span><span class="text-sm font-medium text-slate-800">{{ $profile->phone }}</span></div>
                    <div class="md:col-span-2"><span class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Business Address</span><span class="text-sm font-medium text-slate-800">{{ $profile->address }}, {{ $profile->city ?? '' }} {{ $profile->lga ?? '' }}, {{ $profile->state }}, {{ $profile->country }}</span></div>
                </div>
            </div>

            @if($profile->kyc)
            <!-- Personal Info -->
            <div>
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-5 flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center mr-3"><i data-lucide="user" class="w-4 h-4 text-slate-400"></i></div>
                    Personal Identity
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 pl-11">
                    <div><span class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Full Name</span><span class="text-sm font-medium text-slate-800">{{ $profile->kyc->full_name }}</span></div>
                    <div><span class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Date of Birth</span><span class="text-sm font-medium text-slate-800">{{ \Carbon\Carbon::parse($profile->kyc->date_of_birth)->format('M d, Y') }}</span></div>
                    <div><span class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Nationality</span><span class="text-sm font-medium text-slate-800">{{ $profile->kyc->nationality }}</span></div>
                    <div class="md:col-span-2"><span class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Residential Address</span><span class="text-sm font-medium text-slate-800">{{ $profile->kyc->residential_address }}</span></div>
                </div>
            </div>

            <!-- Documents -->
            <div>
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-5 flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center mr-3"><i data-lucide="file-check-2" class="w-4 h-4 text-slate-400"></i></div>
                    Verification Documents
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 pl-11">
                    <div><span class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">NIN</span><span class="text-sm font-medium text-slate-800">{{ $profile->nin }}</span></div>
                    <div><span class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">ID Document ({{ strtoupper($profile->kyc->id_type) }})</span><span class="text-sm font-medium text-slate-800">{{ $profile->kyc->id_number }}</span></div>
                    
                    @php
                        $docs = [
                            'ID Front' => $profile->kyc->id_front_path,
                            'ID Back' => $profile->kyc->id_back_path,
                            'Selfie' => $profile->kyc->selfie_path,
                            'Proof of Address' => $profile->kyc->proof_of_address_path,
                            'CAC Certificate' => $profile->kyc->cac_certificate_path,
                        ];
                    @endphp
                    @foreach($docs as $label => $path)
                        @if($path)
                            <div>
                                <span class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wider">{{ $label }}</span>
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg hover:bg-indigo-100 transition-colors">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5 mr-1.5"></i> View Document
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
