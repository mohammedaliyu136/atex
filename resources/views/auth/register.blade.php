@extends('layouts.landing')

@section('styles')
<style>
  .register-container {
    min-height: calc(100vh - 70px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
    position: relative;
    overflow: hidden;
  }

  /* Stunning background */
  .register-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%);
    z-index: -2;
  }

  .register-blob {
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
  .register-card {
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

  .register-header {
    text-align: center;
    margin-bottom: 2.5rem;
  }

  .register-header h1 {
    font-size: 2rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.025em;
    margin-bottom: 0.5rem;
  }

  .register-header p {
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

  .input-error {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 500;
  }

  /* Account Type Cards */
  .account-type-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
    margin-top: 0.5rem;
  }

  .account-type-card {
    position: relative;
    cursor: pointer;
  }

  .account-type-card input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
  }

  .account-type-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1rem 0.5rem;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    background: rgba(255, 255, 255, 0.7);
    transition: all 0.2s ease;
    text-align: center;
  }

  .account-type-content svg {
    width: 24px;
    height: 24px;
    margin-bottom: 0.5rem;
    color: #64748b;
    transition: all 0.2s ease;
  }

  .account-type-content span {
    font-size: 0.8125rem;
    font-weight: 700;
    color: #475569;
  }

  .account-type-card:hover .account-type-content {
    background: white;
    border-color: #cbd5e1;
    transform: translateY(-2px);
  }

  .account-type-card input:checked + .account-type-content {
    background: white;
    border-color: #10b981;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
  }

  .account-type-card input:checked + .account-type-content svg {
    color: #10b981;
  }

  .account-type-card input:checked + .account-type-content span {
    color: #059669;
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

  .login-link {
    display: block;
    text-align: center;
    margin-top: 1.5rem;
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
  }

  .login-link a {
    color: #10b981;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.2s;
  }

  .login-link a:hover {
    color: #059669;
    text-decoration: underline;
  }

  @media (max-width: 640px) {
    .register-card {
      padding: 2rem 1.5rem;
      border-radius: 20px;
    }
    .account-type-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
@endsection

@section('content')
<div class="register-container">
  <div class="register-bg"></div>
  <div class="register-blob blob-1"></div>
  <div class="register-blob blob-2"></div>

  <div class="register-card">
    <div class="register-header">
      <h1>Join the Marketplace</h1>
      <p>Create your account to start trading verified non-oil exports globally.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="form-group">
        <label for="name" class="form-label">Name / Business Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-input" required autofocus placeholder="e.g. Ganye Agro Cooperative">
        @error('name')
          <p class="input-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">I am joining as an:</label>
        <div class="account-type-grid">
          <label class="account-type-card">
            <input type="radio" name="account_type" value="exporter" {{ old('account_type') == 'exporter' ? 'checked' : '' }} required>
            <div class="account-type-content">
              <i data-lucide="package-export"></i>
              <span>Exporter</span>
            </div>
          </label>
          <label class="account-type-card">
            <input type="radio" name="account_type" value="buyer" {{ old('account_type') == 'buyer' ? 'checked' : '' }} required>
            <div class="account-type-content">
              <i data-lucide="shopping-cart"></i>
              <span>Buyer</span>
            </div>
          </label>
          <label class="account-type-card">
            <input type="radio" name="account_type" value="logistics" {{ old('account_type') == 'logistics' ? 'checked' : '' }} required>
            <div class="account-type-content">
              <i data-lucide="truck"></i>
              <span>Logistics</span>
            </div>
          </label>
        </div>
        @error('account_type')
          <p class="input-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required placeholder="you@company.com">
        @error('email')
          <p class="input-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" name="password" class="form-input" required placeholder="••••••••">
        @error('password')
          <p class="input-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required placeholder="••••••••">
      </div>

      <button type="submit" class="btn-submit">Create Account</button>

      <div class="login-link">
        Already have an account? <a href="{{ route('login') }}">Sign in here</a>
      </div>
    </form>
  </div>
</div>
@endsection
