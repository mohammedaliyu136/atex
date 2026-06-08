@extends('layouts.landing')

@section('styles')
<style>
  .kyc-container {
    min-height: calc(100vh - 70px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
    position: relative;
    overflow: hidden;
  }

  /* Stunning background */
  .kyc-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%);
    z-index: -2;
  }

  .kyc-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    z-index: -1;
    opacity: 0.6;
    animation: float 10s infinite ease-in-out alternate;
  }

  .blob-1 {
    width: 400px;
    height: 400px;
    background: rgba(16, 185, 129, 0.3);
    top: -100px;
    left: -100px;
  }

  .blob-2 {
    width: 500px;
    height: 500px;
    background: rgba(5, 150, 105, 0.2);
    bottom: -150px;
    right: -100px;
    animation-delay: -5s;
  }

  @keyframes float {
    0% { transform: translate(0, 0) scale(1); }
    100% { transform: translate(30px, 50px) scale(1.1); }
  }

  /* Glassmorphism Card */
  .kyc-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 24px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(255, 255, 255, 0.3) inset;
    width: 100%;
    max-width: 520px;
    padding: 3rem;
    position: relative;
    z-index: 10;
  }

  .kyc-header {
    text-align: center;
    margin-bottom: 2.5rem;
  }

  .kyc-header h1 {
    font-size: 2rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.025em;
    margin-bottom: 0.5rem;
  }

  .kyc-header p {
    color: #64748b;
    font-size: 1rem;
    line-height: 1.5;
  }

  /* Custom Input Styles */
  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
  }

  .form-input {
    width: 100%;
    padding: 0.875rem 1rem;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    background: rgba(255, 255, 255, 0.9);
    font-size: 1rem;
    color: #0f172a;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .form-input:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    transform: translateY(-1px);
  }

  textarea.form-input {
    resize: vertical;
    min-height: 100px;
  }

  .input-error {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 500;
  }

  /* Submit Button */
  .btn-submit {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px -10px rgba(16, 185, 129, 0.6);
    margin-top: 1rem;
  }

  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 25px -10px rgba(16, 185, 129, 0.7);
  }

  .btn-logout {
    width: 100%;
    padding: 1rem;
    background: white;
    color: #64748b;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 1rem;
  }

  .btn-logout:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #cbd5e1;
  }

  @media (max-width: 640px) {
    .kyc-card {
      padding: 2rem 1.5rem;
      border-radius: 20px;
    }
  }

  /* Alert boxes */
  .alert-success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
    color: #059669;
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
    font-weight: 500;
  }

  .alert-error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: #dc2626;
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
    font-weight: 500;
  }

  /* Pending State */
  .pending-state {
    text-align: center;
    padding: 2rem 1rem;
  }
  .pending-icon {
    width: 64px;
    height: 64px;
    color: #f59e0b;
    margin: 0 auto 1.5rem;
  }
</style>
@endsection

@section('content')
<div class="kyc-container">
  <div class="kyc-bg"></div>
  <div class="kyc-blob blob-1"></div>
  <div class="kyc-blob blob-2"></div>

  <div class="kyc-card">
    @php
        $user = Auth::user();
        $profile = null;
        if ($user->hasRole('exporter')) {
            $profile = \App\Models\ExporterProfile::where('user_id', $user->id)->first();
        } elseif ($user->hasRole('buyer')) {
            $profile = \App\Models\BuyerProfile::where('user_id', $user->id)->first();
        } elseif ($user->hasRole('logistics')) {
            $profile = \App\Models\LogisticsProfile::where('user_id', $user->id)->first();
        }
    @endphp

    @if($profile && $profile->verification_status === 'pending')
        <div class="pending-state">
            <i data-lucide="clock" class="pending-icon"></i>
            <div class="kyc-header" style="margin-bottom: 1.5rem;">
                <h1>Under Review</h1>
                <p>Your KYC application has been submitted and is currently being reviewed by our administrators. You will be notified once it is approved.</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    Logout for now
                </button>
            </form>
        </div>
    @else
        <div class="kyc-header">
            <h1>Complete Your Profile</h1>
            <p>Please provide your business details to finalize your account setup.</p>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($profile && $profile->verification_status === 'rejected')
            <div class="alert-error">
                Your previous KYC submission was rejected. Please review your details and submit again.
            </div>
        @endif

        <form method="POST" action="{{ route('kyc.onboarding.store') }}">
            @csrf

            <div class="form-group">
                <label for="business_name" class="form-label">Business/Company Name</label>
                <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}" class="form-input" required autofocus placeholder="e.g. Acme Corporation">
                @error('business_name')
                    <p class="input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="registration_number" class="form-label">Business Registration Number (Optional)</label>
                <input id="registration_number" type="text" name="registration_number" value="{{ old('registration_number') }}" class="form-input" placeholder="e.g. RC-123456">
                @error('registration_number')
                    <p class="input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="tax_number" class="form-label">Tax Identification Number (Optional)</label>
                <input id="tax_number" type="text" name="tax_number" value="{{ old('tax_number') }}" class="form-input" placeholder="TIN Number">
                @error('tax_number')
                    <p class="input-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Physical Business Address</label>
                <textarea id="address" name="address" class="form-input" required placeholder="Enter full address...">{{ old('address') }}</textarea>
                @error('address')
                    <p class="input-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Submit Application</button>
        </form>
            
        <div style="margin-top: 1rem;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout" style="margin-top: 0;">Logout</button>
            </form>
        </div>
    @endif
  </div>
</div>
@endsection
