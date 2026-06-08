<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycOnboardingController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Check if user already submitted KYC
        if ($user->hasRole('exporter')) {
            $profile = \App\Models\ExporterProfile::where('user_id', $user->id)->first();
            if ($profile && $profile->verification_status !== 'pending' && $profile->verification_status !== 'rejected') {
                return redirect()->route('admin.dashboard');
            }
        } elseif ($user->hasRole('buyer')) {
            $profile = \App\Models\BuyerProfile::where('user_id', $user->id)->first();
            if ($profile && $profile->verification_status !== 'pending' && $profile->verification_status !== 'rejected') {
                return redirect()->route('admin.dashboard');
            }
        } elseif ($user->hasRole('logistics')) {
            $profile = \App\Models\LogisticsProfile::where('user_id', $user->id)->first();
            if ($profile && $profile->verification_status !== 'pending' && $profile->verification_status !== 'rejected') {
                return redirect()->route('admin.dashboard');
            }
        } else {
            // Admins or general users don't need KYC onboarding
            return redirect()->route('admin.dashboard');
        }

        return view('auth.kyc');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'business_name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'address' => 'required|string',
        ]);

        if ($user->hasRole('exporter')) {
            \App\Models\ExporterProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'business_name' => $request->business_name,
                    'registration_number' => $request->registration_number,
                    'tax_number' => $request->tax_number,
                    'address' => $request->address,
                    'verification_status' => 'pending',
                ]
            );
        } elseif ($user->hasRole('buyer')) {
            \App\Models\BuyerProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $request->business_name,
                    'registration_number' => $request->registration_number,
                    'tax_number' => $request->tax_number,
                    'address' => $request->address,
                    'verification_status' => 'pending',
                ]
            );
        } elseif ($user->hasRole('logistics')) {
            \App\Models\LogisticsProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $request->business_name,
                    'registration_number' => $request->registration_number,
                    'tax_number' => $request->tax_number,
                    'address' => $request->address,
                    'verification_status' => 'pending',
                ]
            );
        }

        return redirect()->route('kyc.onboarding')->with('success', 'KYC application submitted successfully. Please wait for admin approval.');
    }
}
