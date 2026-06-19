<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SellerProfile;

class SellerOnboardingController extends Controller
{
    public function show()
    {
        // If already a seller, redirect to dashboard
        if (Auth::user()->hasRole('seller')) {
            return redirect()->route('dashboard');
        }

        return view('seller.onboarding.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'lga' => 'required|string|max:255',
            'address' => 'required|string',
            'registration_number' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // Assign seller role
        $user->assignRole('seller');

        // Create or update SellerProfile
        $profile = SellerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => $request->business_name,
                'business_type' => $request->business_type,
                'lga' => $request->lga,
                'address' => $request->address,
                'registration_number' => $request->registration_number,
                'tax_number' => $request->tax_number,
                'verification_status' => 'pending',
                'seller_program_status' => 'pending',
                'readiness_score' => 60,
                'fulfillment_model' => 'seller_direct'
            ]
        );

        // Redirect to seller dashboard or KYC onboarding
        return redirect()->route('dashboard')->with('status', 'You are now registered as a seller! Please complete your KYC to start selling.');
    }
}
