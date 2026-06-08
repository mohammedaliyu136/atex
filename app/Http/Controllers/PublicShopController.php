<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class PublicShopController extends Controller
{
    public function show($uniqueId)
    {
        $shop = Shop::where('unique_id', $uniqueId)->with('occupant')->firstOrFail();
        
        // Mock outstanding payments for now
        $outstandingAmount = 5000.00; 

        return view('public.shop', compact('shop', 'outstandingAmount'));
    }
}
