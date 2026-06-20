@extends('layouts.email')

@section('title', ($type === 'kyc' ? 'KYC SMTP Test' : 'System SMTP Test') . ' - ' . ($system_settings['platform_name'] ?? 'ATEX'))

@section('content')
    <h1>{{ $type === 'kyc' ? 'KYC' : 'System' }} SMTP Connection Test</h1>
    <p>This is a test email sent from the <strong>{{ $system_settings['platform_name'] ?? 'ATEX' }}</strong> administrative portal to verify the <strong>{{ $type === 'kyc' ? 'KYC' : 'General' }} Mailer</strong>.</p>
    
    <div class="box" style="border-color: #10b981; background-color: #f0fdf4;">
        <p style="color: #059669; font-weight: 700; font-size: 18px; margin-bottom: 8px;">Success!</p>
        <p style="margin: 0; color: #065f46;">Your <strong>{{ $type === 'kyc' ? 'KYC' : 'System' }}</strong> email configuration is working correctly. This email confirms that the system can successfully reach the SMTP server and deliver messages.</p>
    </div>

    <p>Current Time: <strong>{{ now()->format('M d, Y @ H:i:s') }}</strong></p>
    
    <div class="divider"></div>

    <p style="font-size: 14px; color: #64748b;">If you received this message, your mail settings are properly configured and the system is ready to send notifications using the {{ $type === 'kyc' ? 'KYC' : 'General' }} channel.</p>
@endsection
