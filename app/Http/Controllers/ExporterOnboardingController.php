<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SellerProfile;
use App\Models\ExporterProfile;
use App\Models\Document;

class ExporterOnboardingController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user->hasRole('seller')) {
            return redirect()->route('seller.onboarding');
        }

        $profile = SellerProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            return redirect()->route('dashboard');
        }

        $exporterProfile = ExporterProfile::where('seller_profile_id', $profile->id)->first();

        if ($exporterProfile && $exporterProfile->verification_status !== 'rejected') {
            return view('exporter.onboarding.pending', compact('profile', 'exporterProfile'));
        }

        if ($profile->verification_status !== 'approved') {
            return view('seller.onboarding.pending');
        }

        $rejectedFields = collect();
        if ($exporterProfile && $exporterProfile->verification_status === 'rejected') {
            $rejectedFields = \App\Models\ExporterProfileKycItemReview::where('owner_type', 'export')
                ->where('owner_id', $exporterProfile->id)
                ->where('status', 'rejected')
                ->get()
                ->keyBy('item_key');
        }

        return view('exporter.onboarding.index', compact('profile', 'exporterProfile', 'rejectedFields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nepc_number' => 'required|string|max:255',
            'export_capacity' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'export_markets' => 'required|string|max:255',
            'nepc_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();
        $profile = SellerProfile::where('user_id', $user->id)->firstOrFail();

        $path = null;
        if ($request->hasFile('nepc_certificate')) {
            $path = $request->file('nepc_certificate')->store('kyc/exporter', 'public');
        }

        $exporterProfile = ExporterProfile::updateOrCreate(
            ['seller_profile_id' => $profile->id],
            [
                'nepc_number' => $request->nepc_number,
                'export_capacity' => $request->export_capacity,
                'years_of_experience' => $request->years_of_experience,
                'export_markets' => $request->export_markets,
                'nepc_certificate_path' => $path,
                'verification_status' => 'pending',
            ]
        );

        return redirect()->route('seller.dashboard')->with('success', 'Your exporter upgrade has been submitted for verification. We will notify you once approved.');
    }

}
