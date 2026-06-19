@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Welcome Back, {{ $user->name }}</h1>
    <p class="text-slate-500 text-sm">Here's your buyer overview for today.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center">
        <div class="p-3 bg-blue-100 rounded-2xl mr-4 text-blue-600">
            <i data-lucide="shopping-bag" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Orders</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($metrics['total_orders']) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center">
        <div class="p-3 bg-emerald-100 rounded-2xl mr-4 text-emerald-600">
            <i data-lucide="message-square" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase">Active RFQs</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($metrics['active_rfqs']) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center">
        <div class="p-3 bg-amber-100 rounded-2xl mr-4 text-amber-600">
            <i data-lucide="heart" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase">Saved Items</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($metrics['saved_items']) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center">
        <div class="p-3 bg-purple-100 rounded-2xl mr-4 text-purple-600">
            <i data-lucide="credit-card" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Spent</p>
            <p class="text-2xl font-bold text-slate-800">₦{{ number_format($metrics['total_spent'], 2) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Orders Dummy Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-800">Recent Orders</h2>
            <a href="#" class="text-sm text-primary-600 hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase border-b border-slate-100">
                        <th class="pb-3 font-semibold">Order ID</th>
                        <th class="pb-3 font-semibold">Date</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="py-4 text-slate-800 font-medium">#ORD-12345</td>
                        <td class="py-4 text-slate-500">Today, 10:23 AM</td>
                        <td class="py-4">
                            <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full text-xs font-medium">Processing</span>
                        </td>
                        <td class="py-4 text-right font-medium">₦45,000</td>
                    </tr>
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="py-4 text-slate-800 font-medium">#ORD-12344</td>
                        <td class="py-4 text-slate-500">Yesterday</td>
                        <td class="py-4">
                            <span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full text-xs font-medium">Delivered</span>
                        </td>
                        <td class="py-4 text-right font-medium">₦120,500</td>
                    </tr>
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="py-4 text-slate-800 font-medium">#ORD-12343</td>
                        <td class="py-4 text-slate-500">Oct 12, 2023</td>
                        <td class="py-4">
                            <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-xs font-medium">Cancelled</span>
                        </td>
                        <td class="py-4 text-right font-medium">₦15,000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Suggested Products Dummy List -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-800">Recommended for You</h2>
            <a href="#" class="text-sm text-primary-600 hover:underline">Explore</a>
        </div>
        <div class="space-y-4">
            <div class="flex items-center p-3 hover:bg-slate-50 rounded-2xl transition-colors cursor-pointer border border-transparent hover:border-slate-100">
                <div class="w-16 h-16 bg-slate-200 rounded-xl mr-4 flex-shrink-0 flex items-center justify-center">
                    <i data-lucide="image" class="text-slate-400"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-slate-800">Premium Cocoa Beans (100kg)</h3>
                    <p class="text-xs text-slate-500 mt-1">Supplier: West Africa Agri</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-primary-600">₦250,000</p>
                </div>
            </div>
            
            <div class="flex items-center p-3 hover:bg-slate-50 rounded-2xl transition-colors cursor-pointer border border-transparent hover:border-slate-100">
                <div class="w-16 h-16 bg-slate-200 rounded-xl mr-4 flex-shrink-0 flex items-center justify-center">
                    <i data-lucide="image" class="text-slate-400"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-slate-800">Organic Shea Butter (50kg)</h3>
                    <p class="text-xs text-slate-500 mt-1">Supplier: Nature's Best</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-primary-600">₦180,000</p>
                </div>
            </div>

            <div class="flex items-center p-3 hover:bg-slate-50 rounded-2xl transition-colors cursor-pointer border border-transparent hover:border-slate-100">
                <div class="w-16 h-16 bg-slate-200 rounded-xl mr-4 flex-shrink-0 flex items-center justify-center">
                    <i data-lucide="image" class="text-slate-400"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-slate-800">Handwoven Textiles (Bulk)</h3>
                    <p class="text-xs text-slate-500 mt-1">Supplier: Crafts Co.</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-primary-600">₦75,000</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
