<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use App\Models\Order;
use App\Models\AtexAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin') && !$user->hasRole('field-officer')) {
            abort(403);
        }

        $settlements = Settlement::with(['order', 'exporterProfile'])->latest()->get();

        return view('admin.settlements.index', compact('settlements'));
    }

    public function credit(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin') && !$user->hasRole('field-officer')) {
            abort(403);
        }

        $request->validate([
            'settlement_id' => 'required|exists:settlements,id',
            'notes' => 'nullable|string',
        ]);

        $settlement = Settlement::findOrFail($request->settlement_id);
        $settlement->update([
            'status' => 'credited',
            'notes' => $request->notes ?: 'Credited to seller account after commission and tax deductions.',
            'credited_at' => now(),
        ]);

        // Update Order settlement status
        $order = Order::findOrFail($settlement->order_id);
        $order->update([
            'settlement_status' => 'credited',
        ]);

        AtexAuditLog::create([
            'actor_id' => $user->id,
            'action' => 'credited_settlement',
            'auditable_type' => 'settlement',
            'auditable_id' => $settlement->id,
            'new_values' => json_encode(['status' => 'credited', 'notes' => $request->notes]),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.settlements.index')->with('success', 'Settlement payout released and exporter account credited.');
    }
}

