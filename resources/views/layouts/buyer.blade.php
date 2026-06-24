<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Adamawa Ecommerce platform' }} - Adamawa Ecommerce platform</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="{{ asset('assets/enterprise.css') }}">

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .amazon-nav { background: #131921; }
        .amazon-subnav { background: #232f3e; }
        .amazon-gold { color: #febd69; }
        .amazon-btn {
            background: #ffd814;
            border-color: #fcd200;
            color: #0f1111;
        }
        .amazon-btn:hover { background: #f7ca00; }
        .amazon-btn-secondary {
            background: #ffa41c;
            border-color: #ff8f00;
            color: #0f1111;
        }
        .amazon-btn-secondary:hover { background: #fa8900; }
        .product-card {
            background: #fff;
            border: 1px solid #e7e7e7;
            transition: all 0.15s ease;
        }
        .product-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .product-card img { mix-blend-mode: multiply; }
        .rating-star { color: #de7921; }
        .price-symbol { font-size: 0.7rem; vertical-align: super; }
        .link-blue { color: #007185; }
        .link-blue:hover { color: #c7511f; text-decoration: underline; }
        .section-title { font-size: 1.25rem; font-weight: 700; color: #0f1111; }

        /* Custom scrollbar for dark backgrounds */
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.25); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.4); }
        .custom-scrollbar { scrollbar-width: thin; scrollbar-color: rgba(255, 255, 255, 0.25) transparent; }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        amazon: { dark: '#131921', navy: '#232f3e', gold: '#febd69' }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full bg-[#eaeded]">
    <div class="flex flex-col min-h-full">
        <!-- Top Nav - Amazon style -->
        <header class="amazon-nav text-white sticky top-0 z-50">
            <div class="max-w-[1500px] mx-auto px-4">
                <div class="flex items-center h-[60px] gap-4">
                    <!-- Logo -->
                    <a href="{{ route('buyer.products.index') }}" class="flex items-center gap-1 shrink-0 hover:outline hover:outline-1 hover:outline-white/30 px-2 py-1 rounded">
                        <span class="text-2xl font-bold tracking-tight">ATEX</span>
                        <span class="text-xs amazon-gold font-medium leading-none mt-2">.ng</span>
                    </a>

                    <!-- Search Bar -->
                    <div class="flex-1 group">
                        <div class="flex h-[40px] items-center gap-1.5">
                            <select class="w-[50px] bg-white text-black text-xs px-2 rounded-lg border border-gray-300 outline-none cursor-pointer h-full">
                                <option>All</option>
                            </select>
                            <input type="text" id="search-input" placeholder="Search {{ $system_settings['platform_name'] ?? 'ATEX' }}"
                                   class="flex-1 h-full px-3 text-sm text-black outline-none rounded-lg border border-gray-300 focus:border-[#febd69] transition-colors"
                                   autocomplete="off">
                            <button class="h-full rounded-lg amazon-gold bg-[#febd69] hover:bg-[#f3a847] text-black px-4 flex items-center justify-center transition-colors" onclick="performSearch()">
                                <i data-lucide="search" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Right Links -->
                    <div class="flex items-center gap-2 text-white/90">
                        @auth
                        <!-- Account Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex flex-col leading-tight px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded text-xs text-left">
                                <span class="text-[11px] text-white/60">Hello, {{ Str::words(auth()->user()->name ?? 'Guest', 1, '') }}</span>
                                <span class="text-sm font-bold flex items-center gap-1">Account <i data-lucide="chevron-down" class="w-3 h-3 text-white/60"></i></span>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100" 
                                 x-transition:enter-start="transform opacity-0 scale-95" 
                                 x-transition:enter-end="transform opacity-100 scale-100" 
                                 x-transition:leave="transition ease-in duration-75" 
                                 x-transition:leave-start="transform opacity-100 scale-100" 
                                 x-transition:leave-end="transform opacity-0 scale-95" 
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50 py-1" 
                                 style="display: none;" x-cloak>
                                
                                <a href="{{ route('buyer.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">My Dashboard</a>
                                <a href="{{ route('buyer.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">My Profile</a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">Logout</button>
                                </form>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="flex flex-col leading-tight px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded text-xs">
                            <span class="text-[11px] text-white/60">Hello, sign in</span>
                            <span class="text-sm font-bold">Account & Lists</span>
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center gap-1 px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded">
                            <span class="text-sm font-bold">Register</span>
                        </a>
                        @endauth
                        <a href="#" class="flex flex-col leading-tight px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded text-xs">
                            <span class="text-[11px] text-white/60">Returns</span>
                            <span class="text-sm font-bold">& Orders</span>
                        </a>
                        <a href="#" class="flex items-center gap-1 px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded">
                            <i data-lucide="shopping-cart" class="w-[22px] h-[22px]"></i>
                            <span class="text-sm font-bold">Cart</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sub Nav -->
        <nav class="amazon-subnav text-white text-sm sticky top-[60px] z-40">
            <div class="max-w-[1500px] mx-auto px-4 flex items-center h-[40px] gap-2 md:gap-5 pb-1 -mb-1">
                <!-- Fixed Left Items -->
                <div class="flex items-center gap-1 md:gap-3 shrink-0">
                    <a href="{{ route('buyer.products.index') }}" class="flex items-center gap-1.5 whitespace-nowrap font-medium px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded">
                        <i data-lucide="home" class="w-[18px] h-[18px]"></i>
                    </a>
                    <a href="{{ route('buyer.products.index') }}" class="whitespace-nowrap font-medium px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded hidden sm:block">All Products</a>
                    
                    @auth
                        @hasrole('seller')
                        <a href="{{ route('seller.dashboard') }}" class="whitespace-nowrap font-medium px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded text-[#febd69]">My Store</a>
                        @endhasrole
                    @endauth
                </div>
                
                <!-- Separator -->
                <div class="h-4 w-px bg-white/30 shrink-0"></div>
                
                <!-- Scrollable Categories -->
                @php
                    $navCategories = \App\Models\Category::where('status', true)->whereNull('parent_id')->orderBy('name')->get();
                @endphp
                <div class="flex-1 min-w-0 overflow-x-auto custom-scrollbar flex items-center gap-2 md:gap-4 pr-2">
                    @foreach($navCategories as $cat)
                        <a href="{{ route('buyer.products.index', ['category' => $cat->slug]) }}" class="whitespace-nowrap hover:outline hover:outline-1 hover:outline-white/30 px-2 py-1 rounded">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
                
                <!-- Fixed Right Items -->
                @auth
                <form action="{{ route('logout') }}" method="POST" class="inline shrink-0 pl-1 md:pl-2 border-l border-white/20">
                    @csrf
                    <button type="submit" class="whitespace-nowrap font-medium px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded flex items-center gap-1 text-white/80 hover:text-white">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span class="hidden md:inline">Logout</span>
                    </button>
                </form>
                @else
                <div class="inline shrink-0 pl-1 md:pl-2 border-l border-white/20">
                    <a href="{{ route('login') }}" class="whitespace-nowrap font-medium px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded flex items-center gap-1 text-white/80 hover:text-white">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        <span class="hidden md:inline">Login</span>
                    </a>
                </div>
                @endauth
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="max-w-[1500px] mx-auto px-4 py-6">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-[#232f3e] text-white text-xs">
            <div class="border-b border-white/10">
                <div class="max-w-[1500px] mx-auto px-4 py-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        <div>
                            <h4 class="font-bold text-sm mb-3">Get to Know Us</h4>
                            <ul class="space-y-1.5 text-white/60">
                                <li><a href="#" class="hover:underline">About {{ $system_settings['platform_name'] ?? 'ATEX' }}</a></li>
                                <li><a href="#" class="hover:underline">Careers</a></li>
                                <li><a href="#" class="hover:underline">Press Center</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm mb-3">Make Money with Us</h4>
                            <ul class="space-y-1.5 text-white/60">
                                @if(auth()->check() && auth()->user()->hasRole('seller'))
                                    <li><a href="{{ route('exporter.onboarding') }}" class="hover:underline">Upgrade to Exporter</a></li>
                                @else
                                    <li><a href="{{ route('seller.onboarding') }}" class="hover:underline">Sell on {{ $system_settings['platform_name'] ?? 'ATEX' }}</a></li>
                                @endif
                                <li><a href="#" class="hover:underline">Become a Logistics Partner</a></li>
                                <li><a href="#" class="hover:underline">Advertise Your Products</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm mb-3">Let Us Help You</h4>
                            <ul class="space-y-1.5 text-white/60">
                                <li><a href="#" class="hover:underline">Your Account</a></li>
                                <li><a href="#" class="hover:underline">Your Orders</a></li>
                                <li><a href="#" class="hover:underline">Shipping Rates & Policies</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm mb-3">Connect</h4>
                            <ul class="space-y-1.5 text-white/60">
                                <li><a href="#" class="hover:underline">Facebook</a></li>
                                <li><a href="#" class="hover:underline">Twitter</a></li>
                                <li><a href="#" class="hover:underline">Instagram</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-4 text-center text-white/40 text-[11px]">
                &copy; {{ date('Y') }} Adamawa Ecommerce platform. All rights reserved.
            </div>
        </footer>
    </div>

    <!-- Notifications Toast -->
    <div x-data="{ 
            show: false, 
            message: '', 
            type: 'success',
            init() {
                @if(session('success'))
                    this.notify('{{ session('success') }}', 'success');
                @endif
                @if(session('error'))
                    this.notify('{{ session('error') }}', 'error');
                @endif
                @if(session('info'))
                    this.notify('{{ session('info') }}', 'info');
                @endif
                @if(session('warning'))
                    this.notify('{{ session('warning') }}', 'warning');
                @endif
            },
            notify(msg, type) {
                this.message = msg;
                this.type = type;
                this.show = true;
                setTimeout(() => { this.show = false }, 5000);
            }
         }" 
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-10 opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-10 opacity-0"
         class="fixed bottom-8 right-8 z-[100] max-w-sm w-full"
         style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl border border-slate-100 p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                 :class="{
                    'bg-emerald-50 text-emerald-600': type === 'success',
                    'bg-red-50 text-red-600': type === 'error',
                    'bg-indigo-50 text-indigo-600': type === 'info',
                    'bg-amber-50 text-amber-600': type === 'warning'
                 }">
                <i data-lucide="check-circle" class="w-6 h-6" x-show="type === 'success'"></i>
                <i data-lucide="alert-circle" class="w-6 h-6" x-show="type === 'error'"></i>
                <i data-lucide="info" class="w-6 h-6" x-show="type === 'info'"></i>
                <i data-lucide="alert-triangle" class="w-6 h-6" x-show="type === 'warning'"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-slate-800" x-text="message"></p>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function performSearch() {
            const query = document.getElementById('search-input')?.value;
            if (query) {
                window.location.href = '{{ route("buyer.products.index") }}?search=' + encodeURIComponent(query);
            }
        }

        document.getElementById('search-input')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') performSearch();
        });

        document.addEventListener('livewire:navigated', () => lucide.createIcons());
    </script>
    @stack('scripts')
</body>
</html>
