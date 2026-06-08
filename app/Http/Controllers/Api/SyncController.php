<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Occupant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    public function sync(Request $request)
    {
        $shopsData = $request->input('shops', []);
        $results = [];

        foreach ($shopsData as $data) {
            try {
                DB::transaction(function () use ($data, &$results) {
                    $occupantId = null;
                    if (!empty($data['occupant_name'])) {
                        $occupant = Occupant::create([
                            'name' => $data['occupant_name'],
                            'phone' => $data['occupant_phone'] ?? null,
                        ]);
                        $occupantId = $occupant->id;
                    }

                    $shop = Shop::create([
                        'name' => $data['name'],
                        'type' => $data['type'],
                        'size' => $data['size'],
                        'lga' => $data['lga'],
                        'ward' => $data['ward'],
                        'lat' => $data['lat'],
                        'lng' => $data['lng'],
                        'occupant_id' => $occupantId,
                        'status' => 'pending',
                    ]);

                    $results[] = [
                        'local_id' => $data['local_id'] ?? null,
                        'server_id' => $shop->id,
                        'status' => 'synced'
                    ];
                });
            } catch (\Exception $e) {
                $results[] = [
                    'local_id' => $data['local_id'] ?? null,
                    'status' => 'failed',
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}
