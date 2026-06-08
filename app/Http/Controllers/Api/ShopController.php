<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Occupant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'size' => 'required|string',
            'lga' => 'required|string',
            'ward' => 'required|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'occupant_name' => 'nullable|string',
            'occupant_phone' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $occupantId = null;
            if (!empty($validated['occupant_name'])) {
                $occupant = Occupant::create([
                    'name' => $validated['occupant_name'],
                    'phone' => $validated['occupant_phone'] ?? null,
                ]);
                $occupantId = $occupant->id;
            }

            $shop = Shop::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'size' => $validated['size'],
                'lga' => $validated['lga'],
                'ward' => $validated['ward'],
                'lat' => $validated['lat'],
                'lng' => $validated['lng'],
                'occupant_id' => $occupantId,
                'status' => 'pending',
            ]);

            return response()->json([
                'message' => 'Shop registered successfully',
                'shop' => $shop
            ], 201);
        });
    }

    public function show($id)
    {
        $shop = Shop::with('occupant')->where('unique_id', $id)->orWhere('id', $id)->firstOrFail();
        return response()->json($shop);
    }
}
