<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Setting;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $buyerProfile = $user->buyerProfile ?? new \App\Models\BuyerProfile();
        return view('buyer.profile.index', compact('user', 'buyerProfile'));
    }

    public function updateInfo(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Male,Female,Other',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $buyerProfile = $user->buyerProfile;
        if (!$buyerProfile) {
            $buyerProfile = new \App\Models\BuyerProfile(['user_id' => $user->id]);
        }

        $buyerProfile->fill($request->only([
            'phone_number',
            'gender',
            'shipping_address',
            'billing_address',
            'city',
            'state',
            'zip_code',
            'country',
        ]));
        $buyerProfile->save();

        return back()->with('success', 'Profile information updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $settings = Setting::pluck('value', 'key');
        
        // Build password rules dynamically based on settings
        $passwordRules = Password::min($settings['password_min_length'] ?? 8);
        
        if (($settings['password_require_uppercase'] ?? '0') == '1') {
            $passwordRules->letters()->mixedCase();
        }
        if (($settings['password_require_lowercase'] ?? '0') == '1') {
            $passwordRules->letters(); // Mixed case handles both, but let's be explicit if needed
        }
        if (($settings['password_require_number'] ?? '0') == '1') {
            $passwordRules->numbers();
        }
        if (($settings['password_require_special'] ?? '0') == '1') {
            $passwordRules->symbols();
        }

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', $passwordRules],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        \App\Models\AuthenticationLog::log($request->user(), 'password_change');

        return back()->with('success', 'Password updated successfully.');
    }

    public function showTwoFactor()
    {
        $user = auth()->user();
        $qrCode = null;
        $secret = null;

        if (!$user->hasTwoFactorEnabled()) {
            $google2fa = new Google2FA();
            
            // If user doesn't have a secret yet, generate one but don't save yet
            if (!$user->two_factor_secret) {
                $secret = $google2fa->generateSecretKey();
                $user->forceFill(['two_factor_secret' => $secret])->save();
            } else {
                $secret = $user->two_factor_secret;
            }

            $qrCodeUrl = $google2fa->getQRCodeUrl(
                Setting::get('platform_name', 'URCS'),
                $user->email,
                $secret
            );

            $renderer = new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $qrCode = $writer->writeString($qrCodeUrl);
        }

        return view('buyer.profile.two-factor', compact('user', 'qrCode', 'secret'));
    }

    public function confirmTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = auth()->user();
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            $user->forceFill([
                'two_factor_confirmed_at' => now(),
            ])->save();

            // Generate Recovery Codes
            $recoveryCodes = $user->generateRecoveryCodes();

            \App\Models\AuthenticationLog::log($user, '2fa_enabled');

            return back()->with('success', 'Two-factor authentication enabled successfully.')
                       ->with('recovery_codes', $recoveryCodes);
        }

        return back()->with('error', 'Invalid verification code. Please try again.');
    }

    public function disableTwoFactor(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
        ]);

        $user = auth()->user();
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        \App\Models\AuthenticationLog::log($user, '2fa_disabled');

        return back()->with('success', 'Two-factor authentication has been disabled.');
    }
}
