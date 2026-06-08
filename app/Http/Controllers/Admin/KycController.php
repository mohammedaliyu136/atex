<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExporterProfile;
use App\Models\BuyerProfile;
use App\Models\LogisticsProfile;
use App\Models\AtexAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin') && !$user->hasRole('field-officer')) {
            abort(403);
        }

        $exporters = ExporterProfile::with('user')->get()->map(function($profile) {
            return [
                'id' => $profile->id,
                'profile_type' => 'exporter',
                'organization' => $profile->business_name,
                'name' => $profile->user->name ?? '',
                'email' => $profile->user->email ?? '',
                'account_status' => $profile->user->status ?? 'pending',
                'verification_status' => $profile->verification_status,
                'profile_category' => $profile->business_type,
                'location' => $profile->lga,
                'documents_count' => \App\Models\Document::where('owner_type', 'exporter')->where('owner_id', $profile->id)->count(),
            ];
        });

        $buyers = BuyerProfile::with('user')->get()->map(function($profile) {
            return [
                'id' => $profile->id,
                'profile_type' => 'buyer',
                'organization' => $profile->company_name ?: 'Buyer Account',
                'name' => $profile->user->name ?? '',
                'email' => $profile->user->email ?? '',
                'account_status' => $profile->user->status ?? 'pending',
                'verification_status' => $profile->verification_status,
                'profile_category' => $profile->buyer_type,
                'location' => $profile->country,
                'documents_count' => \App\Models\Document::where('owner_type', 'buyer')->where('owner_id', $profile->id)->count(),
            ];
        });

        $logistics = LogisticsProfile::with('user')->get()->map(function($profile) {
            return [
                'id' => $profile->id,
                'profile_type' => 'logistics',
                'organization' => $profile->company_name,
                'name' => $profile->user->name ?? '',
                'email' => $profile->user->email ?? '',
                'account_status' => $profile->user->status ?? 'pending',
                'verification_status' => $profile->verification_status,
                'profile_category' => 'logistics',
                'location' => $profile->coverage_regions,
                'documents_count' => \App\Models\Document::where('owner_type', 'logistics')->where('owner_id', $profile->id)->count(),
            ];
        });

        $profiles = $exporters->concat($buyers)->concat($logistics)->sortByDesc(function ($item) {
            return in_array($item['verification_status'], ['pending', 'submitted']) ? 1 : 0;
        })->values()->toArray();

        return view('admin.kyc.index', compact('profiles'));
    }

    public function review(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin') && !$user->hasRole('field-officer')) {
            abort(403);
        }

        $request->validate([
            'profile_type' => 'required|in:exporter,buyer,logistics',
            'profile_id' => 'required|integer',
            'status' => 'required|in:approved,rejected,pending',
        ]);

        $profileType = $request->profile_type;
        $profileId = $request->profile_id;
        $status = $request->status;
        
        $oldStatus = '';

        if ($profileType === 'exporter') {
            $profile = ExporterProfile::findOrFail($profileId);
            $oldStatus = $profile->verification_status;
            $profile->update([
                'verification_status' => $status,
                'seller_program_status' => $status === 'approved' ? 'approved' : $profile->seller_program_status,
                'approved_at' => $status === 'approved' ? now() : $profile->approved_at,
            ]);
        } elseif ($profileType === 'buyer') {
            $profile = BuyerProfile::findOrFail($profileId);
            $oldStatus = $profile->verification_status;
            $profile->update([
                'verification_status' => $status,
            ]);
        } elseif ($profileType === 'logistics') {
            $profile = LogisticsProfile::findOrFail($profileId);
            $oldStatus = $profile->verification_status;
            $profile->update([
                'verification_status' => $status,
            ]);
        }

        AtexAuditLog::create([
            'actor_id' => $user->id,
            'action' => 'reviewed_kyc_status',
            'auditable_type' => $profileType . '_profile',
            'auditable_id' => $profileId,
            'old_values' => json_encode(['verification_status' => $oldStatus]),
            'new_values' => json_encode(['verification_status' => $status]),
            'ip_address' => $request->ip(),
        ]);

        $returnTo = $request->return_to ?: 'kyc';
        if ($returnTo === 'kyc') {
            return redirect()->route('admin.kyc.index')->with('success', 'KYC verification status updated.');
        }

        return redirect()->back()->with('success', 'KYC verification status updated.');
    }
}

