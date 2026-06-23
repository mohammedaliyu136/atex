<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'Adamawa Ecommerce platform') }}</title>
    <link rel="preconnect" href="https://images.unsplash.com" />
    <link rel="stylesheet" href="{{ asset('assets/landing.css') }}" />
    @isset($marketplaceProducts)
    <script>
      window.marketplaceProducts = {!! json_encode($marketplaceProducts) !!};
    </script>
    @endisset
    <script src="{{ asset('assets/landing.js') }}" defer></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" async onload="window.lucide && window.lucide.createIcons()"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .amazon-nav { background: #131921; font-family: 'Inter', sans-serif; }
        .amazon-subnav { background: #232f3e; font-family: 'Inter', sans-serif; }
        .amazon-gold { color: #febd69; }
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.25); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.4); }
        .custom-scrollbar { scrollbar-width: thin; scrollbar-color: rgba(255, 255, 255, 0.25) transparent; }
        [x-cloak] { display: none !important; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false },
            theme: {
                extend: {
                    colors: {
                        amazon: { dark: '#131921', navy: '#232f3e', gold: '#febd69' }
                    }
                }
            }
        }
    </script>
    @yield('styles')
  </head>
  <body>
    <!-- Top Nav - Amazon style -->
    <header class="amazon-nav text-white sticky top-0 z-50">
        <div class="max-w-[1500px] mx-auto px-4">
            <div class="flex items-center h-[60px] gap-4">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-1 shrink-0 hover:outline hover:outline-1 hover:outline-white/30 px-2 py-1 rounded">
                    <span class="text-2xl font-bold tracking-tight" style="color: white !important; font-family: 'Inter', sans-serif !important; font-size: 1.5rem !important;">ATEX</span>
                    <span class="text-xs amazon-gold font-medium leading-none mt-2" style="font-family: 'Inter', sans-serif !important;">.ng</span>
                </a>

                <!-- Search Bar -->
                <div class="flex-1 group">
                    <div class="flex h-[40px] items-center gap-1.5">
                        <select class="w-[50px] bg-white text-black text-xs px-2 rounded-lg border border-gray-300 outline-none cursor-pointer h-full" style="color: black !important;">
                            <option>All</option>
                        </select>
                        <input type="text" id="search-input" placeholder="Search ATEX"
                               class="flex-1 h-full px-3 text-sm text-black outline-none rounded-lg border border-gray-300 focus:border-[#febd69] transition-colors"
                               autocomplete="off" style="color: black !important; font-family: 'Inter', sans-serif !important; margin: 0 !important;">
                        <button class="h-full rounded-lg amazon-gold bg-[#febd69] hover:bg-[#f3a847] text-black px-4 flex items-center justify-center transition-colors" onclick="window.location.href='{{ route('buyer.products.index') }}'">
                            <i data-lucide="search" class="w-5 h-5" style="color: black !important;"></i>
                        </button>
                    </div>
                </div>

                <!-- Right Links -->
                <div class="flex items-center gap-2 text-white/90">
                    @auth
                        <!-- Account Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex flex-col leading-tight px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded text-xs text-left" style="background: transparent; border: none; color: white;">
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
                                
                                @php
                                    $dashboardRoute = 'admin.dashboard';
                                    if(Auth::user()->hasRole('seller')) $dashboardRoute = 'seller.dashboard';
                                    elseif(Auth::user()->hasRole('buyer')) $dashboardRoute = 'buyer.dashboard';
                                    elseif(Auth::user()->hasRole('logistics')) $dashboardRoute = 'logistics.dashboard';
                                @endphp
                                <a href="{{ route($dashboardRoute) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" style="color: #374151 !important; text-decoration: none !important;">My Dashboard</a>
                                <a href="{{ route('buyer.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" style="color: #374151 !important; text-decoration: none !important;">My Profile</a>
                                
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" style="background: transparent; border: none; color: #374151 !important;">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex flex-col leading-tight px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded text-xs" style="color: white !important; text-decoration: none !important;">
                            <span class="text-[11px] text-white/60">Hello, sign in</span>
                            <span class="text-sm font-bold">Account & Lists</span>
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center gap-1 px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded" style="color: white !important; text-decoration: none !important;">
                            <span class="text-sm font-bold">Register</span>
                        </a>
                    @endauth
                    <a href="{{ route('buyer.products.index') }}" class="flex items-center gap-1 px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded" style="color: white !important; text-decoration: none !important;">
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
                <a href="{{ route('home') }}" class="flex items-center gap-1.5 whitespace-nowrap font-medium px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded" style="color: white !important; text-decoration: none !important;">
                    <i data-lucide="home" class="w-[18px] h-[18px]"></i>
                </a>
                <a href="{{ route('buyer.products.index') }}" class="whitespace-nowrap font-medium px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded hidden sm:block" style="color: white !important; text-decoration: none !important;">All Products</a>
                
                @auth
                    @hasrole('seller')
                    <a href="{{ route('seller.dashboard') }}" class="whitespace-nowrap font-medium px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded text-[#febd69]" style="color: #febd69 !important; text-decoration: none !important;">My Store</a>
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
                    <a href="{{ route('buyer.products.index', ['category' => $cat->slug]) }}" class="whitespace-nowrap hover:outline hover:outline-1 hover:outline-white/30 px-2 py-1 rounded" style="color: white !important; text-decoration: none !important;">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
            
            <!-- Fixed Right Items -->
            <div class="inline shrink-0 pl-1 md:pl-2 border-l border-white/20">
                <a href="{{ route('home') }}#marketplace" class="whitespace-nowrap font-medium px-2 py-1 hover:outline hover:outline-1 hover:outline-white/30 rounded flex items-center gap-1 text-white/80 hover:text-white" style="color: white !important; text-decoration: none !important;">
                    <i data-lucide="grid" class="w-4 h-4"></i>
                    <span class="hidden md:inline">Browse</span>
                </a>
            </div>
        </div>
    </nav>

    <main>
      @yield('content')
    </main>

    @yield('scripts')
  </body>
</html>
