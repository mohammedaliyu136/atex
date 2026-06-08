<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ExporterProfile;
use App\Models\BuyerProfile;
use App\Models\LogisticsProfile;
use App\Models\Product;
use App\Models\Document;
use App\Models\QuoteRequest;
use App\Models\Order;
use App\Models\FulfillmentInventory;
use App\Models\Settlement;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('super-admin') || $user->hasRole('field-officer')) {
            return $this->adminDashboard($user);
        }

        if ($user->hasRole('exporter')) {
            return $this->exporterDashboard($user);
        }

        if ($user->hasRole('logistics')) {
            return $this->logisticsDashboard($user);
        }

        if ($user->hasRole('buyer')) {
            return redirect('/#marketplace');
        }

        return redirect('/');
    }

    protected function adminDashboard($user)
    {
        $metrics = [
            'users' => User::count(),
            'pending_users' => User::where('status', 'pending')->count(),
            'exporters' => ExporterProfile::count(),
            'buyers' => BuyerProfile::count(),
            'pending_kyc' => ExporterProfile::whereIn('verification_status', ['pending', 'submitted'])->count()
                + BuyerProfile::whereIn('verification_status', ['pending', 'submitted'])->count(),
            'products' => Product::count(),
            'pending_documents' => Document::where('status', 'pending')->count(),
            'open_quotes' => QuoteRequest::where('status', 'open')->count(),
            'orders' => Order::count(),
            'inventory_items' => FulfillmentInventory::count(),
            'pending_settlements' => Settlement::whereIn('status', ['pending', 'processing'])->count(),
            'logistics_partners' => LogisticsProfile::count(),
            'export_value' => Order::sum('total_amount'),
        ];

        return view('admin.dashboard.admin', compact('metrics', 'user'));
    }

    protected function exporterDashboard($user)
    {
        $profile = ExporterProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            // Create profile dynamically if missing
            $profile = ExporterProfile::create([
                'user_id' => $user->id,
                'business_name' => $user->name,
                'lga' => 'Yola North',
                'verification_status' => 'pending',
            ]);
        }

        $profileId = $profile->id;

        $metrics = [
            'products' => Product::where('exporter_profile_id', $profileId)->count(),
            'pending_products' => Product::where('exporter_profile_id', $profileId)->where('status', 'pending_review')->count(),
            'documents' => Document::where('owner_type', 'exporter')->where('owner_id', $profileId)->count(),
            'pending_documents' => Document::where('owner_type', 'exporter')->where('owner_id', $profileId)->where('status', 'pending')->count(),
            'quotes' => QuoteRequest::whereHas('product', function ($query) use ($profileId) {
                $query->where('exporter_profile_id', $profileId);
            })->count(),
            'orders' => Order::where('exporter_profile_id', $profileId)->count(),
            'inventory_items' => FulfillmentInventory::where('exporter_profile_id', $profileId)->count(),
            'payouts' => Settlement::where('exporter_profile_id', $profileId)->count(),
            'pending_payout' => Settlement::where('exporter_profile_id', $profileId)->whereIn('status', ['pending', 'processing'])->sum('net_payout_amount'),
            'credited_payout' => Settlement::where('exporter_profile_id', $profileId)->where('status', 'credited')->sum('net_payout_amount'),
            'export_value' => Order::where('exporter_profile_id', $profileId)->sum('total_amount'),
            'readiness' => $profile->readiness_score,
        ];

        return view('admin.dashboard.exporter', compact('metrics', 'profile', 'user'));
    }

    protected function logisticsDashboard($user)
    {
        $profile = LogisticsProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            $profile = LogisticsProfile::create([
                'user_id' => $user->id,
                'company_name' => $user->name,
                'verification_status' => 'approved',
            ]);
        }

        $profileId = $profile->id;

        $metrics = [
            'assigned_shipments' => Shipment::where('logistics_profile_id', $profileId)->count(),
            'in_transit_shipments' => Shipment::where('logistics_profile_id', $profileId)
                ->whereIn('status', ['picked_up', 'customs_cleared', 'departed', 'in_transit'])
                ->count(),
            'delivered_shipments' => Shipment::where('logistics_profile_id', $profileId)->where('status', 'delivered')->count(),
            'pending_assignment' => Shipment::whereNull('logistics_profile_id')->count(),
        ];

        return view('admin.dashboard.logistics', compact('metrics', 'profile', 'user'));
    }
}

