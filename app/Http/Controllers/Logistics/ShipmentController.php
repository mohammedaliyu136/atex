<?php

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Order;
use App\Models\LogisticsProfile;
use App\Models\AtexAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    public function assign(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin') && !$user->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'logistics_profile_id' => 'required|exists:logistics_profiles,id',
            'tracking_number' => 'nullable|string|max:100',
            'status' => 'required|string|max:30',
            'order_shipment_status' => 'required|string|max:30',
        ]);

        $shipment = Shipment::updateOrCreate(
            ['order_id' => $request->order_id],
            [
                'logistics_profile_id' => $request->logistics_profile_id,
                'tracking_number' => $request->tracking_number,
                'origin_location' => $request->origin_location ?: 'Adamawa Export Hub',
                'destination_location' => $request->destination_location ?: 'Buyer destination',
                'status' => $request->status,
                'notes' => $request->notes ?: 'Assigned to logistics partner',
                'assigned_at' => now(),
            ]
        );

        $order = Order::findOrFail($request->order_id);
        $order->update([
            'shipment_status' => $request->order_shipment_status,
        ]);

        AtexAuditLog::create([
            'actor_id' => $user->id,
            'action' => 'assigned_logistics_partner',
            'auditable_type' => 'shipment',
            'auditable_id' => $shipment->id,
            'new_values' => json_encode(['logistics_profile_id' => $request->logistics_profile_id, 'tracking_number' => $request->tracking_number]),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Logistics partner assigned and shipment tracking initiated.');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasRole('logistics')) {
            abort(403);
        }

        $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'status' => 'required|string|max:30',
            'order_shipment_status' => 'required|string|max:30',
            'notes' => 'nullable|string',
        ]);

        $shipment = Shipment::findOrFail($id);
        
        $profile = LogisticsProfile::where('user_id', $user->id)->first();
        if ($shipment->logistics_profile_id !== $profile->id) {
            abort(403);
        }

        $shipment->update([
            'tracking_number' => $request->tracking_number,
            'status' => $request->status,
            'notes' => $request->notes,
            'delivered_at' => $request->status === 'delivered' ? now() : $shipment->delivered_at,
        ]);

        $order = Order::findOrFail($shipment->order_id);
        $order->update([
            'shipment_status' => $request->order_shipment_status,
            'status' => $request->status === 'delivered' ? 'completed' : $order->status,
        ]);

        AtexAuditLog::create([
            'actor_id' => $user->id,
            'action' => 'updated_shipment_status',
            'auditable_type' => 'shipment',
            'auditable_id' => $shipment->id,
            'new_values' => json_encode(['status' => $request->status, 'order_shipment_status' => $request->order_shipment_status]),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Shipment status updated successfully.');
    }
}

