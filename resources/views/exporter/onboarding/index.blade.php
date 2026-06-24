@extends('layouts.seller')

@section('content')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('exporterOnboardingForm', () => ({
        rejectedFields: {!! isset($rejectedFields) ? json_encode($rejectedFields->keys()->toArray()) : '[]' !!},
        hasChanges: false,
        
        init() {
            setTimeout(() => {
                const form = document.getElementById('exporterOnboardingForm');
                if (form) {
                    this.rejectedFields.forEach(field => {
                        let inputName = field === 'nepc_certificate_path' ? 'nepc_certificate' : field;
                        const el = form.querySelector('[name=\'' + inputName + '\']');
                        if (el && el.type !== 'file') {
                            el.dataset.initial = el.value;
                        }
                    });
                    this.checkChanges();
                }
            }, 100);
        },
        
        checkChanges() {
            if (this.rejectedFields.length === 0) {
                this.hasChanges = true;
                return;
            }
            
            let allChanged = true;
            const form = document.getElementById('exporterOnboardingForm');
            if (!form) return;
            
            this.rejectedFields.forEach(field => {
                let inputName = field === 'nepc_certificate_path' ? 'nepc_certificate' : field;
                const el = form.querySelector('[name=\'' + inputName + '\']');
                if (el) {
                    if (el.type === 'file') {
                        if (el.files.length === 0) allChanged = false;
                    } else {
                        if (el.value === el.dataset.initial) allChanged = false;
                    }
                }
            });
            
            this.hasChanges = allChanged;
        }
    }));
});
</script>

<div class="max-w-3xl mx-auto" x-data="exporterOnboardingForm()">
    <div class="mb-6">
        <h1 class="text-xl font-bold text-[#0f1111]">Become an Exporter</h1>
        <p class="text-sm text-[#565959]">Complete additional verification to sell internationally on {{ $system_settings['platform_name'] ?? 'ATEX' }}.</p>
    </div>

    <div class="bg-[#f0f8f0] border border-[#007600] rounded-lg px-4 py-3 mb-4 flex items-start gap-3">
        <i data-lucide="info" class="w-5 h-5 text-[#007600] shrink-0 mt-0.5"></i>
        <div>
            <p class="text-sm font-medium text-[#0f1111]">Exporter KYC Verification</p>
            <p class="text-xs text-[#565959]">Your local seller profile is active. Provide your export-specific details to reach international buyers. Your application will be reviewed by our team.</p>
        </div>
    </div>

    <div class="bg-[#f0f2f2] rounded-lg border border-[#e7e7e7] p-4 mb-4 flex items-center gap-3">
        <i data-lucide="store" class="w-5 h-5 text-[#007185]"></i>
        <div>
            <p class="text-sm font-medium text-[#0f1111]">{{ $profile->business_name }}</p>
            <p class="text-xs text-[#565959]">{{ $profile->country }} &middot; {{ $profile->state }}</p>
        </div>
        <span class="ml-auto text-[10px] font-bold text-[#007600] bg-[#f0f8f0] px-2 py-0.5 rounded">Local Seller</span>
    </div>

    @if(session('error'))
        <div class="bg-[#fff5f0] border border-[#ff8f00] text-[#c45500] px-4 py-3 rounded-lg text-sm mb-4">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="bg-[#fff5f0] border border-[#ff8f00] text-[#c45500] px-4 py-3 rounded-lg text-sm mb-4">
            <ul class="list-disc pl-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(isset($exporterProfile) && $exporterProfile->verification_status === 'rejected')
        <div class="bg-[#fff5f0] border border-[#ff8f00] px-4 py-3 rounded-lg mb-4">
            <div class="flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-[#c45500] shrink-0 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-bold text-[#c45500]">Application Rejected</h3>
                    <p class="text-xs text-[#c45500] mt-1">Your previous application was reviewed but needs corrections. Please update the necessary details below and resubmit.</p>
                </div>
            </div>
        </div>
    @endif

    <form id="exporterOnboardingForm" action="{{ route('exporter.onboarding.store') }}" method="POST" enctype="multipart/form-data" @input="checkChanges" @change="checkChanges">
        @csrf

        <div class="bg-white rounded-lg border border-[#e7e7e7] overflow-hidden mb-4">
            <div class="px-6 py-4 border-b border-[#e7e7e7] flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-[#f0f2f2] flex items-center justify-center">
                    <i data-lucide="globe" class="w-4 h-4 text-[#007185]"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[#0f1111]">Export Profile Details</h2>
                    <p class="text-xs text-[#565959]">Tell us about your export capacity.</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#565959] mb-1.5">NEPC Certificate Number <span class="text-[#c45500]">*</span></label>
                        <input type="text" name="nepc_number" value="{{ old('nepc_number', $exporterProfile->nepc_number ?? '') }}" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-[#0f1111] focus:border-[#007185] focus:ring-1 focus:ring-[#007185] outline-none transition-colors" placeholder="e.g. NEPC-123456">
                        @if(isset($rejectedFields) && $rejectedFields->has('nepc_number'))
                            <p class="text-xs text-red-600 mt-2 font-semibold flex items-start"><i data-lucide="alert-circle" class="w-3.5 h-3.5 mr-1 shrink-0 mt-0.5"></i> <span>{{ $rejectedFields['nepc_number']->comment }}</span></p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#565959] mb-1.5">Monthly Export Capacity <span class="text-[#c45500]">*</span></label>
                        <input type="text" name="export_capacity" value="{{ old('export_capacity', $exporterProfile->export_capacity ?? '') }}" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-[#0f1111] focus:border-[#007185] focus:ring-1 focus:ring-[#007185] outline-none transition-colors" placeholder="e.g. 500 Metric Tons">
                        @if(isset($rejectedFields) && $rejectedFields->has('export_capacity'))
                            <p class="text-xs text-red-600 mt-2 font-semibold flex items-start"><i data-lucide="alert-circle" class="w-3.5 h-3.5 mr-1 shrink-0 mt-0.5"></i> <span>{{ $rejectedFields['export_capacity']->comment }}</span></p>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#565959] mb-1.5">Years of Export Experience <span class="text-[#c45500]">*</span></label>
                        <input type="number" name="years_of_experience" value="{{ old('years_of_experience', $exporterProfile->years_of_experience ?? '') }}" required min="0"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-[#0f1111] focus:border-[#007185] focus:ring-1 focus:ring-[#007185] outline-none transition-colors" placeholder="Years in business">
                        @if(isset($rejectedFields) && $rejectedFields->has('years_of_experience'))
                            <p class="text-xs text-red-600 mt-2 font-semibold flex items-start"><i data-lucide="alert-circle" class="w-3.5 h-3.5 mr-1 shrink-0 mt-0.5"></i> <span>{{ $rejectedFields['years_of_experience']->comment }}</span></p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#565959] mb-1.5">Target Export Markets <span class="text-[#c45500]">*</span></label>
                        <input type="text" name="export_markets" value="{{ old('export_markets', $exporterProfile->export_markets ?? '') }}" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-[#0f1111] focus:border-[#007185] focus:ring-1 focus:ring-[#007185] outline-none transition-colors" placeholder="e.g. UK, USA, China">
                        @if(isset($rejectedFields) && $rejectedFields->has('export_markets'))
                            <p class="text-xs text-red-600 mt-2 font-semibold flex items-start"><i data-lucide="alert-circle" class="w-3.5 h-3.5 mr-1 shrink-0 mt-0.5"></i> <span>{{ $rejectedFields['export_markets']->comment }}</span></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-[#e7e7e7] overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-[#e7e7e7] flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-[#f0f2f2] flex items-center justify-center">
                    <i data-lucide="file-check" class="w-4 h-4 text-[#007185]"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[#0f1111]">Required Document</h2>
                    <p class="text-xs text-[#565959]">Please upload your export certification.</p>
                </div>
            </div>
            <div class="p-6">
                <div>
                    <label class="block text-xs font-bold text-[#565959] mb-1.5">NEPC Certificate <span class="text-[#c45500]">*</span></label>
                    <p class="text-[11px] text-[#565959] mb-2">Upload your valid Nigerian Export Promotion Council Certificate (PDF/JPG/PNG max 5MB)</p>
                    <div class="border-2 border-dashed border-[#e7e7e7] rounded-lg p-6 text-center hover:bg-[#f8f8f8] transition-colors relative" x-data="{ fileName: '' }">
                        <input type="file" name="nepc_certificate" {{ (isset($exporterProfile) && $exporterProfile->nepc_certificate_path) ? '' : 'required' }} accept=".pdf,.jpg,.jpeg,.png"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                               @change="fileName = $event.target.files[0]?.name">
                        <div class="flex flex-col items-center">
                            <i data-lucide="upload-cloud" class="w-8 h-8 text-[#007185] mb-2"></i>
                            <span class="text-sm font-medium text-[#0f1111]" x-text="fileName || '{{ (isset($exporterProfile) && $exporterProfile->nepc_certificate_path) ? 'Upload new file to replace existing' : 'Click or drag file to upload' }}'"></span>
                        </div>
                    </div>
                    @if(isset($rejectedFields) && $rejectedFields->has('nepc_certificate_path'))
                        <p class="text-xs text-red-600 mt-2 font-semibold flex items-start"><i data-lucide="alert-circle" class="w-3.5 h-3.5 mr-1 shrink-0 mt-0.5"></i> <span>{{ $rejectedFields['nepc_certificate_path']->comment }}</span></p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-[#e7e7e7] p-6 mb-6">
            <div class="flex items-start gap-3">
                <div class="flex items-center h-5">
                    <input id="attestation" type="checkbox" required
                           class="w-4 h-4 text-[#007185] bg-white border-gray-300 rounded focus:ring-[#007185]">
                </div>
                <div class="text-sm">
                    <label for="attestation" class="font-medium text-[#0f1111]">Attestation & Agreement <span class="text-[#c45500]">*</span></label>
                    <p class="text-[#565959] text-xs mt-1">
                        By submitting this application, I confirm that all provided information is accurate and authentic. I understand that submitting false information may result in the suspension of my account. I also agree to the {{ $system_settings['platform_name'] ?? 'ATEX' }} Export Compliance Terms of Service.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('seller.dashboard') }}" class="text-[#007185] font-semibold py-2 px-4 hover:underline">
                &larr; Cancel
            </a>
            <button type="submit" class="px-8 py-3 bg-primary-600 text-white rounded-xl font-bold hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all"
                    :disabled="!hasChanges" :class="!hasChanges ? 'opacity-50 cursor-not-allowed grayscale' : ''">
                Submit Exporter Application
            </button>
        </div>
    </form>
</div>
@endsection
