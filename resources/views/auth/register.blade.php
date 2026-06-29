@extends('layouts.landing')

@section('styles')
<style>
.auth-page { display: flex; min-height: calc(100vh - 100px); align-items: center; justify-content: center; padding: 40px 24px; }
.auth-card { background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,.1); width: 100%; max-width: 520px; padding: 40px; }
.auth-card h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 4px; color: #0f172a; }
.auth-card .sub { color: #64748b; font-size: .9rem; margin-bottom: 28px; }
.auth-card .form-row-g2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.auth-card .form-group { margin-bottom: 18px; }
.auth-card label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: 6px; color: #0f172a; }
.auth-card input { width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: .9rem; outline: none; transition: border-color .25s ease; background: #fff; }
.auth-card input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
.auth-card .hint { font-size: .78rem; color: #64748b; margin-top: 4px; }
.auth-btn { width: 100%; padding: 12px; background: #febd69; border: none; border-radius: 8px; font-size: 1rem; font-weight: 700; color: #131921; cursor: pointer; transition: background .25s ease; }
.auth-btn:hover { background: #f3a847; }
.auth-btn:disabled { opacity: .5; cursor: not-allowed; }
.auth-switch { text-align: center; margin-top: 24px; font-size: .9rem; color: #64748b; }
.auth-switch a { color: #2563eb; font-weight: 600; }
.auth-switch a:hover { text-decoration: underline; }
.error-box { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 8px; font-size: .85rem; margin-bottom: 18px; display: none; }
.error-box.show { display: block; }
.success-box { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 10px 14px; border-radius: 8px; font-size: .85rem; margin-bottom: 18px; display: none; }
.success-box.show { display: block; }
.pw-strength { height: 4px; border-radius: 2px; margin-top: 6px; transition: all .3s; }
.toast { position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%) translateY(20px); background: #131921; color: #fff; padding: 12px 24px; border-radius: 8px; font-size: .9rem; opacity: 0; transition: all .3s; z-index: 999; pointer-events: none; }
.toast.visible { opacity: 1; transform: translateX(-50%) translateY(0); }
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <h1>Create Account</h1>
        <p class="sub">Join Adamawa Export Platform and start trading globally</p>

        @if($errors->any())
        <div class="error-box show">
            @foreach($errors->all() as $error) {{ $error }} @endforeach
        </div>
        @endif

        @if(session('success'))
        <div class="success-box show">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-row-g2">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+234 800 000 0000">
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="you@company.com" required>
            </div>
            <div class="form-row-g2">
                <div class="form-group">
                    <label for="password">Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" placeholder="Min. 8 characters" required oninput="checkPwStrength(this.value)" style="padding-right: 40px;">
                        <button type="button" onclick="togglePasswordVisibility('password')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 0; color: #64748b; display: flex; align-items: center; justify-content: center;">
                            <svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <div class="pw-strength" id="pwStrength"></div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat password" required oninput="checkPwMatch()" style="padding-right: 40px;">
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 0; color: #64748b; display: flex; align-items: center; justify-content: center;">
                            <svg id="eye-icon-password_confirmation" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <div class="hint" id="pwMatchHint"></div>
                </div>
            </div>

            @if(isset($legalDocuments) && $legalDocuments->count() > 0)
            <div style="font-size: .8rem; color: #64748b; text-align: center; line-height: 1.5; margin-bottom: 18px;">
                By creating an account, you agree to our
                @foreach($legalDocuments as $index => $doc)
                    <a href="javascript:void(0)" style="color: #2563eb; font-weight: 600; text-decoration: none;">{{ $doc->title }}</a>@if(!$loop->last)@if($loop->remaining == 1) and @else, @endif @endif
                @endforeach.
            </div>
            @endif

            <button type="submit" class="auth-btn" id="regBtn">Create Account</button>
        </form>

        <div class="auth-switch">Already have an account? <a href="{{ route('login') }}">Sign In</a></div>
    </div>
</div>

<div class="toast" id="toast"></div>
@endsection

@section('scripts')
<script>
function showToast(msg) { var t = document.getElementById('toast'); if (t) { t.textContent = msg; t.classList.add('visible'); setTimeout(function() { t.classList.remove('visible'); }, 2500); } }

function checkPwStrength(val) {
    var bar = document.getElementById('pwStrength');
    if (!bar) return;
    if (!val) { bar.style.background = 'transparent'; bar.style.width = '0'; return; }
    var score = 0;
    if (val.length >= 8) score++;
    if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
    if (/\d/.test(val)) score++;
    if (/[^a-zA-Z0-9]/.test(val)) score++;
    var colors = ['#ef4444', '#f59e0b', '#3b82f6', '#16a34a'];
    var widths = ['25%', '50%', '75%', '100%'];
    bar.style.background = colors[score - 1] || 'transparent';
    bar.style.width = widths[score - 1] || '0';
}

function checkPwMatch() {
    var p = document.getElementById('password');
    var c = document.getElementById('password_confirmation');
    var hint = document.getElementById('pwMatchHint');
    if (!p || !c || !hint) return;
    if (!c.value) { hint.textContent = ''; return; }
    hint.textContent = p.value === c.value ? '✓ Passwords match' : '✗ Passwords do not match';
    hint.style.color = p.value === c.value ? '#16a34a' : '#ef4444';
}

function togglePasswordVisibility(id) {
    var input = document.getElementById(id);
    var icon = document.getElementById('eye-icon-' + id);
    if (!input || !icon) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/>';
    }
}
</script>
@endsection
