@props(['field', 'label', 'value', 'reviews' => [], 'profile' => null, 'mono' => false])

@php
    $fieldReview = $reviews[$field] ?? null;
    $status = $fieldReview['status'] ?? null;
    $isApproved = $status === 'approved';
    $isRejected = $status === 'rejected';
@endphp

<div class="p-4 rounded-2xl {{ $isApproved ? 'bg-emerald-50/50 border border-emerald-200' : ($isRejected ? 'bg-red-50/50 border border-red-200' : 'bg-slate-50 border border-slate-100') }}">
    <div class="flex items-center justify-between mb-2">
        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">{{ $label }}</p>
        
        @if($profile && $profile->verification_status !== 'approved')
            <div class="flex rounded-lg border border-slate-200 overflow-hidden bg-white divide-x divide-slate-200 shrink-0">
                <input type="radio" name="fields[{{ $field }}][status]" value="approved" class="hidden peer/ok kyc-status-radio" id="reg_{{ $field }}_ok" {{ $isApproved ? 'checked' : '' }}>
                <label for="reg_{{ $field }}_ok" class="px-2 py-1 text-[10px] font-semibold cursor-pointer transition-colors peer-checked/ok:bg-emerald-600 peer-checked/ok:text-white text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 select-none">Approve</label>
                <input type="radio" name="fields[{{ $field }}][status]" value="rejected" class="hidden peer/no kyc-status-radio" id="reg_{{ $field }}_no" {{ $isRejected ? 'checked' : '' }}>
                <label for="reg_{{ $field }}_no" class="px-2 py-1 text-[10px] font-semibold cursor-pointer transition-colors peer-checked/no:bg-red-600 peer-checked/no:text-white text-slate-500 hover:text-red-700 hover:bg-red-50 select-none">Reject</label>
            </div>
        @elseif($isApproved)
            <span class="text-[10px] font-bold text-emerald-600 shrink-0 uppercase">Approved</span>
        @elseif($isRejected)
            <span class="text-[10px] font-bold text-red-600 shrink-0 uppercase">Rejected</span>
        @endif
    </div>
    
    @if($slot->isNotEmpty())
        {{ $slot }}
    @else
        <p class="text-sm font-bold text-slate-800 {{ $mono ? 'font-mono tracking-widest' : '' }} break-words">{{ $value }}</p>
    @endif
    
    @if($profile && $profile->verification_status !== 'approved')
        <textarea name="fields[{{ $field }}][comment]" rows="1" class="w-full mt-2 px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs resize-none {{ $isRejected ? 'block' : 'hidden' }} comment-input" placeholder="Reason if rejected...">{{ $fieldReview['comment'] ?? '' }}</textarea>
    @endif
</div>
