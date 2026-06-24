@extends('layouts.email')

@section('title', 'Your Account Verification Needs Attention')

@section('content')
    <div style="text-align: center; margin-bottom: 32px;">
        <div style="display: inline-block; width: 64px; height: 64px; background-color: #fef2f2; border-radius: 50%; line-height: 64px; text-align: center; margin-bottom: 24px;">
            <span style="font-size: 32px; color: #dc2626;">⚠</span>
        </div>
        <h1 style="margin: 0; color: #1e293b;">Action Required</h1>
    </div>

    <p>Hello <strong>{{ $user->name }}</strong>,</p>
    <p>Your <strong>{{ ucfirst($profileType) }}</strong> profile verification has been reviewed, but we need you to address a few things before we can approve it.</p>
    
    @if($reason)
    <div style="background-color: #f8fafc; border-left: 4px solid #ef4444; padding: 16px; margin: 24px 0;">
        <p style="margin: 0;"><strong>Reason:</strong> {{ $reason }}</p>
    </div>
    @endif

    <p>Please review your profile information, update your documents, and resubmit.</p>

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ url('/kyc/onboarding') }}" class="button" style="background-color: #ef4444;">Review Verification Status</a>
    </div>

    <p style="margin-top: 32px; border-top: 1px solid #f1f5f9; padding-top: 24px; font-size: 13px; color: #64748b;">
        If you have any questions, please contact our support team.
    </p>
@endsection
