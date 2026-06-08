<?php

namespace App\Http\Controllers\Atex;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $products = Product::where('status', 'approved')
            ->with(['exporterProfile', 'category'])
            ->latest()
            ->get();

        $marketplaceProducts = $products->map(function ($product) use ($user) {
            $isBuyer = $user && $user->hasRole('buyer');
            
            $quoteUrl = route('login') . '?redirect=' . urlencode('admin/quotes/create?product_id=' . $product->id);
            $orderUrl = route('login') . '?redirect=' . urlencode('admin/orders/create?product_id=' . $product->id);
            
            if ($user) {
                if ($isBuyer) {
                    $quoteUrl = route('admin.quotes.create', ['product_id' => $product->id]);
                    $orderUrl = route('admin.orders.create', ['product_id' => $product->id]);
                } else {
                    $quoteUrl = route('admin.dashboard');
                    $orderUrl = route('admin.dashboard');
                }
            }

            return [
                'id' => (int) $product->id,
                'name' => $product->name,
                'category' => $product->category->name ?? '',
                'exporter' => $product->exporterProfile->business_name ?? 'Verified Exporter',
                'origin' => ($product->origin_lga ?: 'Adamawa') . ', Adamawa',
                'moq' => $product->moq,
                'price' => $product->unit_price ?: 'Request quote',
                'readiness' => ((int) $product->readiness_score) . '%',
                'image' => $product->image_path ? asset($product->image_path) : $this->marketplaceProductImage($product->category->name ?? ''),
                'quoteUrl' => $quoteUrl,
                'orderUrl' => $orderUrl,
                'badges' => array_values(array_filter([
                    'Approved',
                    $product->hs_code ? 'HS ' . $product->hs_code : null,
                    $product->packaging ?: null,
                ])),
            ];
        });

        return view('welcome', compact('user', 'marketplaceProducts'));
    }

    private function marketplaceProductImage(string $category): string
    {
        $images = [
            'Agriculture' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?auto=format&fit=crop&w=900&q=80',
            'Agricultural Produce' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?auto=format&fit=crop&w=900&q=80',
            'Food Processing' => 'https://images.unsplash.com/photo-1611071526480-f6f8613f7d4b?auto=format&fit=crop&w=900&q=80',
            'Textiles' => 'https://images.unsplash.com/photo-1528404021824-577c0f3b0f4a?auto=format&fit=crop&w=900&q=80',
            'Minerals' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?auto=format&fit=crop&w=900&q=80',
        ];

        return $images[$category] ?? 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&w=900&q=80';
    }
}
