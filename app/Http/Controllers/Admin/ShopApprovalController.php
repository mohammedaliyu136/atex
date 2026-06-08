<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopApprovalController extends Controller
{
    public function index()
    {
        $pendingShops = Shop::where('status', 'pending')->with('occupant')->get();
        return view('admin.shops.index', compact('pendingShops'));
    }

    public function approve($id)
    {
        $shop = Shop::findOrFail($id);
        
        // Generate Unique ID: LGA-WARD-XXXXXXXXXX
        $uniqueId = strtoupper($shop->lga . '-' . $shop->ward . '-' . Str::random(10));
        
        $shop->update([
            'status' => 'approved',
            'unique_id' => $uniqueId,
            // QR code generation would happen here
            'qr_code_path' => 'qrcodes/' . $uniqueId . '.png'
        ]);

        // Logic to generate and save QR code would go here
        // For MVP, we'll just set the path

        return redirect()->back()->with('success', 'Shop approved. Unique ID: ' . $uniqueId);
    }

    public function reject($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->update(['status' => 'rejected']);
        return redirect()->back()->with('error', 'Shop rejected.');
    }
}
