@extends('layouts.admin')

@section('title', 'KYC Details | Adamawa Ecommerce platform')
@section('header_title', 'KYC Details')

@section('content')
@php
    $userModel = $type === 'export' ? ($profile->sellerProfile->user ?? null) : ($profile->user ?? null);
    $sellerProfile = $type === 'export' ? $profile->sellerProfile : null;

    $kycLabel = match ($type) {
        'buyer' => 'Buyer',
        'seller' => 'Seller',
        'export' => 'Exporter',
        'logistics' => 'Logistics',
        'admin' => 'Admin',
        default => ucfirst($type),
    };
    $kycIcon = match ($type) {
        'buyer' => 'shopping-bag',
        'seller' => 'store',
        'export' => 'globe',
        'logistics' => 'truck',
        default => 'building-2',
    };
    $isLocalSeller = $type === 'seller';
@endphp
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">{{ $kycLabel }} KYC Verification</h1>
        <p class="text-slate-500 text-sm">Detailed {{ $kycLabel }} KYC profile for {{ $profile->business_name ?? $sellerProfile->business_name ?? $profile->company_name ?? ($userModel->name ?? 'User') }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.kyc.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl font-medium flex items-center hover:bg-slate-50 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
            Back to KYC List
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Basic Info & Actions -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Profile Summary -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 text-center relative overflow-hidden">
            @if($profile->verification_status === 'approved')
                <div class="absolute top-0 right-0 left-0 h-2 bg-emerald-500"></div>
            @elseif($profile->verification_status === 'rejected')
                <div class="absolute top-0 right-0 left-0 h-2 bg-red-500"></div>
            @else
                <div class="absolute top-0 right-0 left-0 h-2 bg-amber-500"></div>
            @endif

            <div class="w-24 h-24 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 mx-auto mb-6 flex items-center justify-center">
                <i data-lucide="{{ $kycIcon }}" class="w-10 h-10"></i>
            </div>
            <div class="inline-flex items-center px-3 py-1 mb-3 rounded-full text-[11px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100">
                <i data-lucide="{{ $kycIcon }}" class="w-3.5 h-3.5 mr-1.5"></i>
                {{ $kycLabel }}
            </div>
            
            <h2 class="text-xl font-bold text-slate-800 mb-1">{{ $profile->business_name ?? $sellerProfile->business_name ?? $profile->company_name ?? $profile->full_name ?? $userModel->name ?? 'N/A' }}</h2>
            <p class="text-slate-500 text-sm mb-4">{{ $userModel->email ?? 'N/A' }}</p>
            
            <div class="inline-flex px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-6
                {{ $profile->verification_status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                {{ $profile->verification_status === 'rejected' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                {{ $profile->verification_status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
            ">
                @if($profile->verification_status === 'approved')
                    <i data-lucide="check-circle" class="w-4 h-4 mr-1.5"></i>
                @elseif($profile->verification_status === 'rejected')
                    <i data-lucide="x-circle" class="w-4 h-4 mr-1.5"></i>
                @else
                    <i data-lucide="clock" class="w-4 h-4 mr-1.5"></i>
                @endif
                {{ ucfirst($profile->verification_status) }}
            </div>

            <div class="pt-6 border-t border-slate-50 text-left space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Account Owner</p>
                    <p class="text-sm text-slate-700 font-medium flex items-center">
                        <i data-lucide="user" class="w-4 h-4 mr-2 text-slate-400"></i>
                        {{ $userModel->name ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Profile Type</p>
                    <p class="text-sm text-slate-700 font-medium flex items-center">
                        <i data-lucide="tag" class="w-4 h-4 mr-2 text-slate-400"></i>
                        {{ $kycLabel }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Country</p>
                    <p class="text-sm text-slate-700 font-medium flex items-start">
                        <i data-lucide="globe" class="w-4 h-4 mr-2 text-slate-400 mt-0.5 shrink-0"></i>
                        {{ $profile->country ?? $sellerProfile->country ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">State</p>
                    <p class="text-sm text-slate-700 font-medium flex items-start">
                        <i data-lucide="map" class="w-4 h-4 mr-2 text-slate-400 mt-0.5 shrink-0"></i>
                        {{ $profile->state ?? $sellerProfile->state ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Address</p>
                    <p class="text-sm text-slate-700 font-medium flex items-start">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-slate-400 mt-0.5 shrink-0"></i>
                        {{ $profile->address ?? $sellerProfile->address ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Address</p>
                    <p class="text-sm text-slate-700 font-medium flex items-start">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-slate-400 mt-0.5 shrink-0"></i>
                        {{ $profile->address ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Verification Actions -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
            <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider border-b border-slate-100 pb-3">Verification Actions</h3>
            
            <form action="{{ route('admin.kyc.review') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="profile_type" value="{{ $type }}">
                <input type="hidden" name="profile_id" value="{{ $profile->id }}">
                <input type="hidden" name="return_to" value="kyc">
                
                @if($profile->verification_status !== 'approved')
                    <button type="submit" name="status" value="approved" id="global-approve-btn" class="w-full px-4 py-3 bg-emerald-600 text-white rounded-xl font-bold flex items-center justify-center hover:bg-emerald-700 transition-colors shadow-sm shadow-emerald-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i data-lucide="shield-check" class="w-5 h-5 mr-2"></i>
                        Approve KYC
                    </button>
                    <p id="global-approve-warning" class="text-[10px] text-amber-600 text-center font-bold mt-1">You must approve all fields first.</p>
                @endif
                
                @if($profile->verification_status !== 'pending')
                    <button type="submit" name="status" value="pending" class="w-full px-4 py-3 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl font-bold flex items-center justify-center hover:bg-amber-100 transition-colors">
                        <i data-lucide="clock" class="w-5 h-5 mr-2"></i>
                        Put on Hold
                    </button>
                @endif
                
                @if($profile->verification_status !== 'rejected')
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <label for="rejection_reason" class="block text-sm font-bold text-slate-700 mb-2">Rejection Reason</label>
                        <textarea id="rejection_reason" name="reason" rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm resize-none focus:border-red-300 focus:ring-1 focus:ring-red-200" placeholder="Specify which documents/issues need attention..."></textarea>
                        <button type="submit" name="status" value="rejected" class="w-full mt-2 px-4 py-3 bg-red-50 text-red-700 border border-red-200 rounded-xl font-bold flex items-center justify-center hover:bg-red-100 transition-colors">
                            <i data-lucide="x-circle" class="w-5 h-5 mr-2"></i>
                            Reject KYC
                        </button>
                    </div>
                @endif
            </form>
        </div>

        @php
            $allComments = collect();
            foreach($fieldReviews as $r) {
                if($r->comment) $allComments->push((object)[
                    'field_name' => $r->item_key,
                    'status' => $r->status,
                    'comment' => $r->comment,
                    'date' => $r->updated_at
                ]);
            }
            if (isset($fieldReviewHistory)) {
                foreach($fieldReviewHistory as $h) {
                    if($h->comment) $allComments->push((object)[
                        'field_name' => $h->item_key,
                        'status' => $h->status,
                        'comment' => $h->comment,
                        'date' => \Carbon\Carbon::parse($h->changed_at)
                    ]);
                }
            }
            $allComments = $allComments->sortByDesc('date');
        @endphp

        @if($allComments->count() > 0)
        <!-- Previous Reviews Context -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 mt-6">
            <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center">
                <i data-lucide="history" class="w-4 h-4 mr-2 text-slate-500"></i> Feedback History
            </h3>
            <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                @foreach($allComments as $review)
                    <div class="p-3 rounded-xl border {{ $review->status === 'rejected' ? 'border-red-100 bg-red-50/50' : 'border-emerald-100 bg-emerald-50/50' }}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ str_replace('_', ' ', $review->field_name) }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $review->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">{{ ucfirst($review->status) }}</span>
                        </div>
                        <p class="text-xs text-slate-600 mt-1 italic">"{{ $review->comment }}"</p>
                        <p class="text-[9px] text-slate-400 mt-2 text-right">{{ $review->date->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column: Verification Data & Docs -->
    <div class="lg:col-span-2 space-y-6">
        <form id="kyc-review-form" action="{{ route('admin.kyc.review-regulatory') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="profile_type" value="{{ $type }}">
            <input type="hidden" name="profile_id" value="{{ $profile->id }}">
            
            @if($profile->verification_status !== 'approved')
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200 flex justify-between items-center">
                <div>
                    <h4 class="font-bold text-slate-800">Field Reviews</h4>
                    <p class="text-xs text-slate-500">Approve or reject individual items</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" id="approve-all-fields" class="px-4 py-2 border border-emerald-300 text-emerald-700 rounded-xl text-xs font-bold hover:bg-emerald-50 transition-colors">Approve All Fields</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-colors shadow-sm">Save Reviews</button>
                </div>
            </div>
            @endif

        @if($type === 'buyer')
        <!-- Buyer Contact & Shipping Details -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center mr-4">
                    <i data-lucide="contact" class="w-5 h-5 text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Contact &amp; Shipping Details</h3>
            </div>
            @php
                $buyerFields = [
                    'phone_number' => ['label' => 'Phone Number', 'value' => $profile->phone_number ?? 'Not provided'],
                    'gender' => ['label' => 'Gender', 'value' => $profile->gender ?? 'Not provided'],
                    'shipping_address' => ['label' => 'Shipping Address', 'value' => $profile->shipping_address ?? 'Not provided'],
                    'billing_address' => ['label' => 'Billing Address', 'value' => $profile->billing_address ?? 'Not provided'],
                    'city' => ['label' => 'City', 'value' => $profile->city ?? 'Not provided'],
                    'state' => ['label' => 'State', 'value' => $profile->state ?? 'Not provided'],
                    'zip_code' => ['label' => 'Zip Code', 'value' => $profile->zip_code ?? 'Not provided'],
                    'country' => ['label' => 'Country', 'value' => $profile->country ?? 'Not provided'],
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($buyerFields as $field => $info)
                    <x-kyc-field :field="$field" :label="$info['label']" :value="$info['value']" :reviews="$fieldReviews" :profile="$profile" />
                @endforeach
            </div>
        </div>
        @endif

        @if($type === 'seller')
        <!-- Business & Export Details -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mr-4">
                    <i data-lucide="briefcase" class="w-5 h-5 text-amber-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">{{ $kycLabel === 'Exporter' ? 'Business & Export Details' : 'Business Details' }}</h3>
            </div>
            @php
                $businessFields = [
                    'profile_type' => ['label' => 'Profile Type', 'value' => isset($profile->seller_tier) ? ($profile->seller_tier === 'local' ? 'Local Seller' : 'Export Seller') : ucfirst($type)],
                    'country' => ['label' => 'Country', 'value' => $profile->country ?? 'Not provided'],
                    'state' => ['label' => 'State', 'value' => $profile->state ?? 'Not provided'],
                    'address' => ['label' => 'Address', 'value' => $profile->address ?? 'Not provided'],
                    'business_name' => ['label' => 'Business Name', 'value' => $profile->business_name ?? 'Not provided'],
                    'business_category' => ['label' => 'Business Category', 'value' => $profile->business_category ?? 'Not provided'],
                    'seller_brand_name' => ['label' => 'Brand Name', 'value' => $profile->seller_brand_name ?? 'Not provided'],
                    'phone' => ['label' => 'Phone Number', 'value' => $profile->phone ?? 'Not provided'],
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($businessFields as $field => $info)
                    <x-kyc-field :field="$field" :label="$info['label']" :value="$info['value']" :reviews="$fieldReviews" :profile="$profile" />
                @endforeach
            </div>
            @if($profile->business_description)
                <div class="mt-4">
                    <x-kyc-field field="business_description" label="Business Description" :value="$profile->business_description" :reviews="$fieldReviews" :profile="$profile" />
                </div>
            @endif
        </div>
        @endif

        @if($type === 'export')
        <!-- Export Details -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mr-4">
                    <i data-lucide="globe" class="w-5 h-5 text-amber-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Export Application Details</h3>
            </div>
            @php
                $exportFields = [
                    'nepc_number' => ['label' => 'NEPC Registration Number', 'value' => $profile->nepc_number ?? 'Not provided'],
                    'export_capacity' => ['label' => 'Monthly Export Capacity', 'value' => $profile->export_capacity ?? 'Not provided'],
                    'export_markets' => ['label' => 'Export Markets', 'value' => $profile->export_markets ?? 'Not provided'],
                    'years_of_experience' => ['label' => 'Years of Experience', 'value' => ($profile->years_of_experience !== null && $profile->years_of_experience !== '') ? $profile->years_of_experience : 'Not provided'],
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($exportFields as $field => $info)
                    <x-kyc-field :field="$field" :label="$info['label']" :value="$info['value']" :reviews="$fieldReviews" :profile="$profile" />
                @endforeach
            </div>
            @if($profile->nepc_certificate_path)
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <x-kyc-field field="nepc_certificate_path" label="NEPC Certificate" value="" :reviews="$fieldReviews" :profile="$profile">
                        <a href="{{ asset('storage/' . $profile->nepc_certificate_path) }}" target="_blank" class="flex items-center p-3 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 transition-colors group bg-white max-w-sm">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3 text-indigo-600 group-hover:bg-indigo-200 shrink-0">
                                <i data-lucide="file-check-2" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">View Certificate</p>
                                <p class="text-xs text-indigo-600 font-medium">Click to open &rarr;</p>
                            </div>
                        </a>
                    </x-kyc-field>
                </div>
            @endif
        </div>
        @endif

        @if($type === 'seller' && $profile->kyc)
        <!-- Personal Identity Information -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mr-4">
                    <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Personal Identity Information</h3>
            </div>
            @php
                $personalFields = [
                    'full_name' => ['label' => 'Full Name', 'value' => $profile->kyc->full_name ?? 'Not provided'],
                    'date_of_birth' => ['label' => 'Date of Birth', 'value' => $profile->kyc->date_of_birth ? \Carbon\Carbon::parse($profile->kyc->date_of_birth)->format('M d, Y') : 'Not provided'],
                    'nationality' => ['label' => 'Nationality', 'value' => $profile->kyc->nationality ?? 'Not provided'],
                    'id_type' => ['label' => 'ID Type', 'value' => $profile->kyc->id_type ? strtoupper(str_replace('_', ' ', $profile->kyc->id_type)) : 'Not provided'],
                    'id_number' => ['label' => 'ID Number', 'value' => $profile->kyc->id_number ?? 'Not provided'],
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($personalFields as $field => $info)
                    <x-kyc-field :field="$field" :label="$info['label']" :value="$info['value']" :reviews="$fieldReviews" :profile="$profile" />
                @endforeach
            </div>
            @if($profile->kyc->residential_address)
                <div class="mt-4">
                    <x-kyc-field field="residential_address" label="Residential Address" :value="$profile->kyc->residential_address" :reviews="$fieldReviews" :profile="$profile" />
                </div>
            @endif

            <div class="mt-6 pt-6 border-t border-slate-100">
                <h4 class="text-sm font-bold text-slate-700 mb-4 uppercase tracking-wider">KYC Document Uploads</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $kycDocs = [
                            'ID Front' => $profile->kyc->id_front_path,
                            'ID Back' => $profile->kyc->id_back_path,
                            'Selfie' => $profile->kyc->selfie_path,
                            'Proof of Address' => $profile->kyc->proof_of_address_path,
                            'CAC Certificate' => $profile->kyc->cac_certificate_path,
                        ];
                    @endphp
                    @foreach($kycDocs as $docName => $docPath)
                        @if($docPath)
                            <x-kyc-field :field="Str::slug($docName, '_')" :label="$docName" value="" :reviews="$fieldReviews" :profile="$profile">
                                <a href="{{ asset('storage/' . $docPath) }}" target="_blank" class="flex items-center p-3 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 transition-colors group bg-white">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3 text-indigo-600 group-hover:bg-indigo-200 shrink-0">
                                        <i data-lucide="file-check-2" class="w-5 h-5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ $docName }}</p>
                                        <p class="text-xs text-indigo-600 font-medium">View Document &rarr;</p>
                                    </div>
                                </a>
                            </x-kyc-field>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @unless(in_array($type, ['buyer', 'export']))
        <!-- Nigerian KYC Info -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center mr-4">
                        <i data-lucide="fingerprint" class="w-5 h-5 text-indigo-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">{{ $isLocalSeller ? 'Identity Verification' : 'Regulatory Information' }}</h3>
                </div>
                @php
                    $regulatoryFields = $isLocalSeller
                        ? [
                            'nin' => ['label' => 'National Identity (NIN)', 'icon' => 'user-check', 'value' => $profile->nin ?? 'Not provided'],
                        ]
                        : [
                            'registration_number' => ['label' => 'CAC Registration', 'icon' => 'file-text', 'value' => $profile->registration_number ?? 'Not provided'],
                            'tax_number' => ['label' => 'Tax Identification (TIN)', 'icon' => 'landmark', 'value' => $profile->tax_number ?? 'Not provided'],
                            'bvn' => ['label' => 'Bank Verification (BVN)', 'icon' => 'credit-card', 'value' => $profile->bvn ?? 'Not provided'],
                            'nin' => ['label' => 'National Identity (NIN)', 'icon' => 'user-check', 'value' => $profile->nin ?? 'Not provided'],
                        ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($regulatoryFields as $field => $info)
                        <x-kyc-field :field="$field" :label="$info['label']" :value="$info['value']" :reviews="$fieldReviews" :profile="$profile" :mono="in_array($field, ['bvn','nin'])" />
                    @endforeach
                </div>

                <!-- Bank Details -->
                @unless($isLocalSeller)
                <div class="mt-6 pt-5 border-t border-slate-100">
                    <h4 class="text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider flex items-center">
                        <i data-lucide="wallet" class="w-4 h-4 mr-2 text-emerald-500"></i> Bank Details
                    </h4>
                    @php
                        $bankFields = [
                            'bank_name' => ['label' => 'Bank Name', 'value' => $profile->bank_name ?? 'Not provided'],
                            'account_number' => ['label' => 'Account Number', 'value' => $profile->account_number ?? 'Not provided', 'mono' => true],
                            'account_name' => ['label' => 'Account Name', 'value' => $profile->account_name ?? 'Not provided'],
                        ];
                    @endphp
                    <div class="space-y-3">
                        @foreach($bankFields as $field => $info)
                            <x-kyc-field :field="$field" :label="$info['label']" :value="$info['value']" :reviews="$fieldReviews" :profile="$profile" :mono="!empty($info['mono'])" />
                        @endforeach
                    </div>
                </div>
                @endunless
        </div>
        @endunless
        </form>

        @if(($documents->count() > 0 || $type !== 'seller') && $type !== 'export')
        <!-- Uploaded Documents -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center mr-4">
                        <i data-lucide="folder-open" class="w-5 h-5 text-sky-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Uploaded Documents</h3>
                </div>
                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg">{{ $documents->count() }} Files</span>
            </div>

            @if($documents->count() > 0)
                <form action="{{ route('admin.kyc.document.review-all') }}" method="POST">
                    @csrf
                    <input type="hidden" name="profile_type" value="{{ $type }}">
                    <input type="hidden" name="profile_id" value="{{ $profile->id }}">

                    @if($profile->verification_status !== 'approved')
                        <div class="flex justify-between items-center mb-5">
                            <div>
                                <h4 class="font-bold text-slate-800">Document Reviews</h4>
                                <p class="text-xs text-slate-500">Approve or reject individual documents</p>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-colors shadow-sm">Save Document Reviews</button>
                        </div>
                    @endif

                    <div class="space-y-4">
                        @foreach($documents as $doc)
                            @php
                                $isApproved = $doc->status === 'approved';
                                $isRejected = $doc->status === 'rejected';
                                $borderClass = $isApproved ? 'border-emerald-200 bg-emerald-50/30' : ($isRejected ? 'border-red-200 bg-red-50/30' : 'border-slate-200 bg-white');
                            @endphp
                            <div class="rounded-xl border {{ $borderClass }} transition-colors overflow-hidden">
                                <div class="flex items-center justify-between p-3">
                                    <div class="flex items-center min-w-0 gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shrink-0 shadow-sm">
                                            @php
                                                $ext = pathinfo($doc->path, PATHINFO_EXTENSION);
                                                $icon = 'file-text';
                                                $color = 'text-slate-400';
                                                if($isApproved) $color = 'text-emerald-500';
                                                if($isRejected) $color = 'text-red-400';
                                                if(in_array(strtolower($ext), ['jpg','jpeg','png'])) $icon = 'image';
                                                if(in_array(strtolower($ext), ['pdf'])) $icon = 'file-check-2';
                                            @endphp
                                            <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $color }}"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $doc->title }}</p>
                                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                                <span class="uppercase tracking-wider">{{ $ext }}</span>
                                                @if($profile->verification_status !== 'approved')
                                                    <div class="flex rounded-lg border border-slate-200 overflow-hidden bg-white divide-x divide-slate-200">
                                                        <input type="radio" name="documents[{{ $doc->id }}]" value="approved" class="hidden peer/ok" id="doc_ok_{{ $doc->id }}" {{ $isApproved ? 'checked' : '' }} onchange="document.getElementById('doc_comment_container_{{ $doc->id }}').classList.add('hidden')">
                                                        <label for="doc_ok_{{ $doc->id }}" class="px-2.5 py-1 text-xs font-semibold cursor-pointer transition-colors peer-checked/ok:bg-emerald-600 peer-checked/ok:text-white text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 select-none">Approve</label>
                                                        <input type="radio" name="documents[{{ $doc->id }}]" value="rejected" class="hidden peer/no" id="doc_no_{{ $doc->id }}" {{ $isRejected ? 'checked' : '' }} onchange="document.getElementById('doc_comment_container_{{ $doc->id }}').classList.remove('hidden')">
                                                        <label for="doc_no_{{ $doc->id }}" class="px-2.5 py-1 text-xs font-semibold cursor-pointer transition-colors peer-checked/no:bg-red-600 peer-checked/no:text-white text-slate-500 hover:text-red-700 hover:bg-red-50 select-none">Reject</label>
                                                    </div>
                                                @endif
                                            </div>
                                            @if($doc->review_comment)
                                                <p class="text-xs text-red-600 mt-0.5">{{ $doc->review_comment }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $doc->path) }}" target="_blank" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-all shrink-0">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </a>
                                </div>
                                @if($profile->verification_status !== 'approved')
                                    <div id="doc_comment_container_{{ $doc->id }}" class="px-3 pb-3 pt-1 border-t border-slate-100 {{ $isRejected ? '' : 'hidden' }}">
                                        <label for="doc_comment_{{ $doc->id }}" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Reason for Rejection</label>
                                        <input type="text" id="doc_comment_{{ $doc->id }}" name="document_comments[{{ $doc->id }}]" value="{{ $doc->review_comment }}" class="w-full text-xs px-3 py-2 border border-slate-200 rounded-lg focus:border-red-300 focus:ring-1 focus:ring-red-200 placeholder:text-slate-400" placeholder="e.g., Image is too blurry to read">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </form>
            @else
                <div class="text-center py-12 px-6 border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50/50">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i data-lucide="file-x" class="w-8 h-8 text-slate-300"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-600 mb-1">No Documents Uploaded</p>
                    <p class="text-xs text-slate-500">This user has not provided any verification documents.</p>
                </div>
            @endif
        </div>
        @endif
    </div>
</div>
@push('scripts')
<script>
function checkGlobalApprove() {
    var globalBtn = document.getElementById('global-approve-btn');
    var warning = document.getElementById('global-approve-warning');
    if (!globalBtn) return;

    var allRadios = document.querySelectorAll('.kyc-status-radio[value="approved"]');
    var allChecked = true;

    allRadios.forEach(function(radio) {
        if (!radio.checked) allChecked = false;
    });

    if (allRadios.length > 0 && allChecked) {
        globalBtn.disabled = false;
        if(warning) warning.style.display = 'none';
    } else {
        globalBtn.disabled = true;
        if(warning) warning.style.display = 'block';
    }
}

document.querySelectorAll('input[type="radio"][name^="fields["]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var container = this.closest('.p-4, .p-3');
        var textarea = container ? container.querySelector('textarea.comment-input') : null;
        if (textarea) {
            textarea.classList.toggle('hidden', this.value !== 'rejected');
            if (this.value === 'rejected') textarea.focus();
        }
        checkGlobalApprove();
    });
});

document.getElementById('approve-all-fields')?.addEventListener('click', function() {
    document.querySelectorAll('#kyc-review-form input[type="radio"][value="approved"]').forEach(function(radio) {
        radio.checked = true;
        var container = radio.closest('.p-4, .p-3');
        var textarea = container ? container.querySelector('textarea.comment-input') : null;
        if (textarea) textarea.classList.add('hidden');
    });
    checkGlobalApprove();
});

// Run on load
checkGlobalApprove();
</script>
@endpush
@endsection
