<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use App\Models\Product;
use App\Models\BuyerProfile;
use App\Models\SellerProfile;
use App\Models\AtexAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            $quotes = QuoteRequest::with(['buyerProfile', 'product.sellerProfile'])->latest()->get();
            return view('buyer.quotes.admin', compact('quotes'));
        }

        if ($user->hasRole('seller')) {
            $profile = SellerProfile::where('user_id', $user->id)->first();
            $profileId = $profile->id ?? 0;
            $quotes = QuoteRequest::whereHas('product', function ($query) use ($profileId) {
                $query->where('seller_profile_id', $profileId);
            })->with(['buyerProfile', 'product'])->latest()->get();
            return view('buyer.quotes.seller', compact('quotes'));
        }

        if ($user->hasRole('buyer')) {
            $profile = BuyerProfile::where('user_id', $user->id)->first();
            $quotes = QuoteRequest::where('buyer_profile_id', $profile->id ?? 0)->with('product.sellerProfile')->latest()->get();
            return view('buyer.quotes.buyer', compact('quotes'));
        }

        return redirect()->route('admin.dashboard');
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('buyer')) {
            return redirect()->route('admin.dashboard')->with('error', 'Only buyers can request quotes.');
        }

        $productId = $request->product_id;
        $product = Product::with('sellerProfile')->findOrFail($productId);
        
        return view('buyer.quotes.create', compact('product'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('buyer')) {
            return redirect()->route('admin.dashboard')->with('error', 'Only buyers can request quotes.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|string|max:100',
            'destination_country' => 'required|string|max:255',
            'destination_port' => 'nullable|string|max:255',
            'incoterm' => 'required|in:FOB,CIF,EXW',
            'message' => 'nullable|string',
        ]);

        $profile = BuyerProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            $profile = BuyerProfile::create([
                'user_id' => $user->id,
                'country' => $request->destination_country,
                'verification_status' => 'approved',
            ]);
        }

        $quote = QuoteRequest::create([
            'buyer_profile_id' => $profile->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'destination_country' => $request->destination_country,
            'destination_port' => $request->destination_port,
            'incoterm' => $request->incoterm,
            'message' => $request->message,
            'status' => 'open',
        ]);

        AtexAuditLog::create([
            'actor_id' => $user->id,
            'action' => 'created_quote_request',
            'auditable_type' => 'quote_request',
            'auditable_id' => $quote->id,
            'new_values' => json_encode(['product_id' => $request->product_id]),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.quotes.index')->with('success', 'Quote request submitted successfully.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $quote = QuoteRequest::with(['buyerProfile.user', 'product.sellerProfile.user'])->findOrFail($id);

        // Security check
        if ($user->hasRole('seller')) {
            $profile = SellerProfile::where('user_id', $user->id)->first();
            if ($quote->product->seller_profile_id !== $profile->id) {
                abort(403);
            }
        } elseif ($user->hasRole('buyer')) {
            $profile = BuyerProfile::where('user_id', $user->id)->first();
            if ($quote->buyer_profile_id !== $profile->id) {
                abort(403);
            }
        }

        return view('buyer.quotes.show', compact('quote', 'user'));
    }

    public function respond(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasRole('seller')) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'response_amount' => 'required|string|max:100',
            'response_message' => 'required|string',
        ]);

        $quote = QuoteRequest::findOrFail($id);
        
        $profile = SellerProfile::where('user_id', $user->id)->first();
        if ($quote->product->seller_profile_id !== $profile->id) {
            abort(403);
        }

        $quote->update([
            'response_amount' => $request->response_amount,
            'response_message' => $request->response_message,
            'responded_at' => now(),
            'status' => 'responded',
        ]);

        AtexAuditLog::create([
            'actor_id' => $user->id,
            'action' => 'responded_to_quote_request',
            'auditable_type' => 'quote_request',
            'auditable_id' => $quote->id,
            'new_values' => json_encode(['response_amount' => $request->response_amount]),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.quotes.show', $quote->id)->with('success', 'Quote response submitted successfully.');
    }
}

