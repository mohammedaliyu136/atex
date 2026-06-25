@extends('layouts.email')

@section('title', 'Your Exporter Verification is Complete')

@section('content')
    <div style="text-align: center; margin-bottom: 32px;">
        <div style="display: inline-block; width: 64px; height: 64px; background-color: #f0fdf4; border-radius: 50%; line-height: 64px; text-align: center; margin-bottom: 24px;">
            <span style="font-size: 32px; color: #16a34a;">✓</span>
        </div>
        <h1 style="margin: 0; color: #1e293b;">Verification Complete!</h1>
    </div>

    <p>Hello <strong>{{ $user->name }}</strong>,</p>
    <p>Congratulations! Your Exporter profile verification has been successfully completed.</p>
    
    <p>You can now access the full suite of export features on the <strong>Adamawa Ecommerce platform</strong>.</p>

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ url('/seller/dashboard') }}" class="button">Go to Dashboard</a>
    </div>

    <p style="margin-top: 32px; border-top: 1px solid #f1f5f9; padding-top: 24px; font-size: 13px; color: #64748b;">
        Thank you for expanding your horizons with us!
    </p>
@endsection
