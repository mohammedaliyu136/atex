<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LegalDocumentVersion;
use Illuminate\Support\Facades\Auth;

class LegalAcceptanceController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Get all active document versions
        $activeVersions = LegalDocumentVersion::active()->with('document')->get();
        
        // Find which ones the user has already accepted
        $acceptedVersionIds = $user->documentAcceptances()->pluck('legal_document_version_id')->toArray();
        
        // Filter out accepted ones
        $pendingVersions = $activeVersions->reject(function ($version) use ($acceptedVersionIds) {
            return in_array($version->id, $acceptedVersionIds);
        });

        // If no pending versions, they shouldn't be here
        if ($pendingVersions->isEmpty()) {
            return redirect()->route('dashboard');
        }

        return view('legal_acceptance', compact('pendingVersions'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $pendingVersionIds = $request->input('documents', []);

        // Validate that they checked all required documents
        // We could enforce that they accept exactly what is pending
        $activeVersions = LegalDocumentVersion::active()->get();
        $acceptedVersionIds = $user->documentAcceptances()->pluck('legal_document_version_id')->toArray();
        
        $requiredIds = $activeVersions->reject(function ($version) use ($acceptedVersionIds) {
            return in_array($version->id, $acceptedVersionIds);
        })->pluck('id')->toArray();

        foreach ($requiredIds as $id) {
            if (!in_array($id, $pendingVersionIds)) {
                return back()->with('error', 'You must accept all updated legal documents to continue.');
            }
        }

        // Record acceptances
        foreach ($requiredIds as $id) {
            $user->documentAcceptances()->create([
                'legal_document_version_id' => $id,
                'accepted_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return redirect()->intended(route('dashboard'))->with('success', 'Thank you for accepting the updated legal terms.');
    }
}
