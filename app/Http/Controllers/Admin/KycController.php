<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerProfile;
use App\Models\BuyerProfile;
use App\Models\LogisticsProfile;
use App\Models\AdminProfile;
use App\Models\Document;
use App\Models\AtexAuditLog;
use App\Events\KycApproved;
use App\Events\KycRejected;
use App\Notifications\KycApprovedNotification;
use App\Notifications\KycRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    private function getProfileModel(string $type)
    {
        return match ($type) {
            'seller' => new SellerProfile,
            'export' => new \App\Models\ExporterProfile,
            'buyer' => new BuyerProfile,
            'logistics' => new LogisticsProfile,
            'admin' => new AdminProfile,
            default => null,
        };
    }

    private function getProfileClass(string $type): ?string
    {
        return match ($type) {
            'seller' => SellerProfile::class,
            'export' => \App\Models\ExporterProfile::class,
            'buyer' => BuyerProfile::class,
            'logistics' => LogisticsProfile::class,
            'admin' => AdminProfile::class,
            default => null,
        };
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Check if user has permission to view ANY kyc
        $canViewAny = $user->hasPermissionTo('view seller kyc') || 
                      $user->hasPermissionTo('view buyer kyc') || 
                      $user->hasPermissionTo('view logistics kyc') || 
                      $user->hasPermissionTo('manage seller kyc') || 
                      $user->hasPermissionTo('manage buyer kyc') || 
                      $user->hasPermissionTo('manage logistics kyc');

        if (!$canViewAny) {
            abort(403);
        }

        // Dropdown filter: all | export | buyer | logistics | admin
        $filter = $request->query('type', 'all');
        $filters = [
            'all' => 'All KYC',
            'seller' => 'Seller KYC',
            'export' => 'Exporter KYC',
            'buyer' => 'Buyer KYC',
            'logistics' => 'Logistics KYC',
            'admin' => 'Admin KYC',
        ];
        if (!array_key_exists($filter, $filters)) {
            $filter = 'all';
        }

        $profiles = collect();

        $profileTypes = [];
        if ($user->hasPermissionTo('view seller kyc') || $user->hasPermissionTo('manage seller kyc')) {
            $profileTypes['seller'] = SellerProfile::class;
        }
        if ($user->hasPermissionTo('view export kyc') || $user->hasPermissionTo('manage export kyc')) {
            $profileTypes['export'] = \App\Models\ExporterProfile::class;
        }
        if ($user->hasPermissionTo('view buyer kyc') || $user->hasPermissionTo('manage buyer kyc')) {
            $profileTypes['buyer'] = BuyerProfile::class;
        }
        if ($user->hasPermissionTo('view logistics kyc') || $user->hasPermissionTo('manage logistics kyc')) {
            $profileTypes['logistics'] = LogisticsProfile::class;
        }

        if (!array_key_exists($filter, $profileTypes) && $filter !== 'all') {
            $filter = array_key_first($profileTypes);
        }
        if ($filter === 'all' && count($profileTypes) === 1) {
            $filter = array_key_first($profileTypes);
        }

        foreach ($profileTypes as $type => $class) {
            $query = $class::query();
            if ($type === 'export') {
                $query->with('sellerProfile.user');
            } else {
                $query->with('user');
            }
            $records = $query->get()->map(function ($profile) use ($type) {
                $sellerTier = $type === 'seller' ? ($profile->seller_tier ?? 'local') : null;

                $org = match ($type) {
                    'seller' => $profile->business_name,
                    'export' => $profile->sellerProfile->business_name ?? 'Exporter',
                    'buyer' => $profile->company_name ?: ($profile->user->name ?? 'Buyer Account'),
                    'logistics' => $profile->company_name,
                    'admin' => $profile->full_name ?: 'Admin',
                    default => 'Unknown',
                };

                $category = match ($type) {
                    'seller' => $profile->business_category ?: $profile->business_type,
                    'export' => 'Exporter',
                    'buyer' => 'Buyer',
                    'logistics' => 'logistics',
                    'admin' => 'admin',
                    default => null,
                };

                $location = match ($type) {
                    'seller' => $profile->state ?: $profile->lga,
                    'export' => $profile->sellerProfile->state ?? ($profile->sellerProfile->lga ?? null),
                    'buyer' => $profile->country,
                    'logistics' => $profile->coverage_regions,
                    'admin' => $profile->address,
                    default => null,
                };

                $labels = [
                    'seller' => 'Local Seller',
                    'export' => 'Exporter',
                    'buyer' => 'Buyer',
                    'logistics' => 'Logistics',
                    'admin' => 'Admin',
                ];

                $bvn = match($type) {
                    'export' => $profile->sellerProfile->bvn ?? 'N/A',
                    default => $profile->bvn ?? 'N/A',
                };

                $nin = match($type) {
                    'export' => $profile->sellerProfile->nin ?? 'N/A',
                    default => $profile->nin ?? 'N/A',
                };

                $rc_number = match($type) {
                    'export' => $profile->nepc_number ?? 'N/A',
                    default => $profile->registration_number ?? 'N/A',
                };

                $userModel = $type === 'export' ? ($profile->sellerProfile->user ?? null) : ($profile->user ?? null);

                return [
                    'id' => $profile->id,
                    'profile_type' => $type,
                    'seller_tier' => $sellerTier,
                    'profile_type_label' => $labels[$type] ?? ucfirst($type),
                    'organization' => $org,
                    'name' => $userModel->name ?? '',
                    'email' => $userModel->email ?? '',
                    'account_status' => $userModel->status ?? 'pending',
                    'verification_status' => $profile->verification_status,
                    'profile_category' => $category,
                    'location' => $location,
                    'bvn' => $bvn,
                    'nin' => $nin,
                    'rc_number' => $rc_number,
                    'documents' => Document::where('owner_type', $type)->where('owner_id', $profile->id)->get(),
                    'documents_count' => Document::where('owner_type', $type)->where('owner_id', $profile->id)->count(),
                ];
            });

            $profiles = $profiles->concat($records);
        }

        // Apply dropdown filter
        $profiles = $profiles->filter(function ($item) use ($filter) {
            return match ($filter) {
                'seller' => $item['profile_type'] === 'seller',
                'export' => $item['profile_type'] === 'export',
                'buyer' => $item['profile_type'] === 'buyer',
                'logistics' => $item['profile_type'] === 'logistics',
                'admin' => $item['profile_type'] === 'admin',
                default => true,
            };
        });

        $profiles = $profiles->sortByDesc(function ($item) {
            return in_array($item['verification_status'], ['pending', 'submitted']) ? 1 : 0;
        })->values()->toArray();

        return view('admin.kyc.index', compact('profiles', 'filters', 'filter'));
    }

    public function review(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'profile_type' => 'required|in:seller,export,buyer,logistics,admin',
            'profile_id' => 'required|integer',
            'status' => 'required|in:approved,rejected,pending',
            'reason' => 'nullable|string|max:1000',
        ]);

        $profileType = $request->profile_type;

        if (!$user->hasPermissionTo("manage {$profileType} kyc")) {
            abort(403);
        }

        $profileId = $request->profile_id;
        $status = $request->status;

        $class = $this->getProfileClass($profileType);
        if (!$class) {
            abort(404);
        }

        $profile = $class::findOrFail($profileId);
        $oldStatus = $profile->verification_status;

        $updateData = ['verification_status' => $status];

        if ($status === 'approved') {
            $updateData['approved_at'] = now();
            $updateData['rejection_reason'] = null;
            if ($profileType === 'seller') {
                $updateData['seller_program_status'] = 'approved';
            } elseif ($profileType === 'export') {
                // When an export profile is approved, update the seller's tier to export
                if ($profile->sellerProfile) {
                    $profile->sellerProfile->update(['seller_tier' => 'export']);
                    if ($profile->sellerProfile->user) {
                        $profile->sellerProfile->user->assignRole('exporter');
                    }
                }
            }
        } elseif ($status === 'rejected') {
            $updateData['rejection_reason'] = $request->reason;
            if ($profileType === 'export' && $profile->sellerProfile) {
                // If rejected, ensure the seller's tier is local
                $profile->sellerProfile->update(['seller_tier' => 'local']);
                if ($profile->sellerProfile->user) {
                    $profile->sellerProfile->user->removeRole('exporter');
                }
            }
        }

        $profile->update($updateData);


        AtexAuditLog::create([
            'actor_id' => $user->id,
            'action' => 'reviewed_kyc_status',
            'auditable_type' => $profileType . '_profile',
            'auditable_id' => $profileId,
            'old_values' => json_encode(['verification_status' => $oldStatus]),
            'new_values' => json_encode(['verification_status' => $status]),
            'ip_address' => $request->ip(),
        ]);

        // Fire events and send notifications
        $profileUser = $profile->user;
        if ($profileUser) {
            if ($status === 'approved') {
                if ($profileType === 'seller') {
                    $profileUser->assignRole('seller');
                } elseif ($profileType === 'logistics') {
                    $profileUser->assignRole('logistics');
                }
                event(new KycApproved($profileUser, $profileType, $profile, $user->name));
                
                if ($profileType === 'export') {
                    $profileUser->notify(new \App\Notifications\KycExporterApprovedNotification($profileUser));
                } else {
                    $profileUser->notify(new KycApprovedNotification($profileUser, $profileType));
                }
            } elseif ($status === 'rejected') {
                $reason = $request->reason;
                event(new KycRejected($profileUser, $profileType, $profile, $reason, $user->name));
                
                if ($profileType === 'export') {
                    $profileUser->notify(new \App\Notifications\KycExporterRejectedNotification($profileUser, $reason));
                } else {
                    $profileUser->notify(new KycRejectedNotification($profileUser, $profileType, $reason));
                }
            }
        }

        $returnTo = $request->return_to ?: 'kyc';
        if ($returnTo === 'kyc') {
            return redirect()->route('admin.kyc.index')->with('success', 'KYC verification status updated.');
        }

        return redirect()->back()->with('success', 'KYC verification status updated.');
    }

    public function show($type, $id)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo("view {$type} kyc") && !$user->hasPermissionTo("manage {$type} kyc")) {
            abort(403);
        }

        $class = $this->getProfileClass($type);
        if (!$class) {
            abort(404);
        }

        if ($type === 'export') {
            $profile = $class::with('sellerProfile.user')->findOrFail($id);
            $reviewModel = \App\Models\ExporterProfileKycItemReview::class;
        } else {
            $profile = $class::with('user')->findOrFail($id);
            $reviewModel = \App\Models\SellerProfileKycItemReview::class;
        }

        $documents = Document::where('owner_type', $type)->where('owner_id', $profile->id)->get();
        $fieldReviews = $reviewModel::where('owner_type', $type)->where('owner_id', $profile->id)->get()->keyBy('item_key');
        
        $historyTable = $type === 'export' ? null : 'seller_profile_kyc_item_reviews_hist';
        $fieldReviewHistory = collect();
        if ($historyTable) {
            $fieldReviewHistory = \Illuminate\Support\Facades\DB::table($historyTable)
                ->where('owner_type', $type)
                ->where('owner_id', $profile->id)
                ->whereNotNull('comment')
                ->orderBy('changed_at', 'desc')
                ->get();
        }

        return view('admin.kyc.show', compact('profile', 'type', 'documents', 'fieldReviews', 'fieldReviewHistory'));
    }


    public function reviewDocument(Request $request, $id)
    {
        $user = Auth::user();

        $document = Document::findOrFail($id);
        $profileType = $document->owner_type;

        if (!$user->hasPermissionTo("manage {$profileType} kyc")) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'comment' => 'nullable|string|max:1000',
        ]);

        $document->update([
            'status' => $request->status,
            'reviewed_by' => $user->id,
            'review_comment' => $request->comment,
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Document review updated.');
    }

    public function reviewAllDocuments(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin') && (!$user->hasPermissionTo("manage {$request->profile_type} kyc"))) {
            abort(403);
        }

        $request->validate([
            'profile_type' => 'required|in:seller,export,buyer,logistics,admin',
            'profile_id' => 'required|integer',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|in:approved,rejected',
            'document_comments' => 'nullable|array',
            'document_comments.*' => 'nullable|string|max:1000',
        ]);

        $selections = $request->documents ?? [];
        $comments = $request->document_comments ?? [];

        $allDocs = Document::where('owner_type', $request->profile_type)
            ->where('owner_id', $request->profile_id)
            ->get();

        foreach ($allDocs as $doc) {
            $selected = $selections[$doc->id] ?? null;
            if ($selected === 'approved') {
                $doc->update([
                    'status' => 'approved',
                    'reviewed_by' => $user->id,
                    'review_comment' => null,
                    'reviewed_at' => now(),
                ]);
            } elseif ($selected === 'rejected') {
                $doc->update([
                    'status' => 'rejected',
                    'reviewed_by' => $user->id,
                    'review_comment' => $comments[$doc->id] ?? null,
                    'reviewed_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Document reviews saved successfully.');
    }

    public function reviewRegulatory(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'profile_type' => 'required|in:seller,export,buyer,logistics,admin',
            'profile_id' => 'required|integer',
            'fields' => 'nullable|array',
            'fields.*.status' => 'nullable|in:approved,rejected',
            'fields.*.comment' => 'nullable|string|max:1000',
        ]);

        $profileType = $request->profile_type;
        $profileId = $request->profile_id;

        if (!$user->hasPermissionTo("manage {$profileType} kyc")) {
            abort(403);
        }

        $class = $this->getProfileClass($profileType);
        if (!$class) {
            abort(404);
        }

        $profile = $class::findOrFail($profileId);

        $incoming = $request->fields ?? [];
        
        $reviewModel = $profileType === 'export' ? \App\Models\ExporterProfileKycItemReview::class : \App\Models\SellerProfileKycItemReview::class;

        foreach ($incoming as $itemKey => $review) {
            if (!empty($review['status'])) {
                $reviewModel::updateOrCreate(
                    [
                        'owner_type' => $profileType,
                        'owner_id' => $profileId,
                        'item_key' => $itemKey,
                    ],
                    [
                        'status' => $review['status'],
                        'comment' => $review['status'] === 'rejected' ? ($review['comment'] ?? null) : null,
                        'reviewer_id' => $user->id,
                        'reviewed_at' => now(),
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Field reviews saved successfully.');
    }
}
