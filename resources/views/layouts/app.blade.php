<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mart — B2B Marketplace & Sourcing')</title>
    <meta name="description" content="@yield('meta_description', 'Mart connects verified manufacturers and suppliers with wholesale buyers. Simple B2B sourcing, instant quotations, and trade assurance.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            200: '#bae0fd',
                            300: '#7cc7fb',
                            400: '#38a8f7',
                            500: '#0e8ce9',
                            600: '#026ec7',
                            700: '#0358a1',
                            800: '#074a85',
                            900: '#0c3f6e',
                            950: '#082849',
                        },
                        perlcon: {
                            blue: '#0e3a6a',
                            navy: '#082342',
                            accent: '#f97316',
                            light: '#f8fafc',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js for lightweight UI interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        body {
            background-color: #ffffff;
            color: #1e293b;
        }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-white text-slate-800 antialiased flex flex-col min-h-screen">

   

    <!-- Main Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3 flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-700 to-blue-500 flex items-center justify-center text-white text-xl font-black shadow-md shadow-brand-700/20 group-hover:scale-105 transition transform">
                            <i class="fa-solid fa-bag-shopping"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-black tracking-tight text-slate-900 flex items-center">
                                Mart
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Global Search Bar with Category Dropdown -->
                <div class="hidden lg:flex flex-1 max-w-2xl mx-4">
                    <form action="{{ route('products.index') }}" method="GET" class="w-full flex rounded-xl border-2 border-slate-200 hover:border-brand-500 focus-within:border-brand-600 focus-within:ring-2 focus-within:ring-brand-100 transition bg-white overflow-hidden shadow-sm">
                        <select name="category" class="bg-slate-50 text-slate-700 text-xs font-semibold px-3 py-2.5 border-r border-slate-200 focus:outline-none cursor-pointer max-w-[170px]">
                            <option value="">All Categories</option>
                            @if(isset($globalCategories))
                                @foreach($globalCategories as $cat)
                                    <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                    @if($cat->subcategories && $cat->subcategories->count() > 0)
                                        @foreach($cat->subcategories as $sub)
                                            <option value="{{ $sub->slug }}" {{ request('category') === $sub->slug ? 'selected' : '' }}>
                                                &nbsp;&nbsp;↳ {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products, machinery, suppliers, materials..." class="flex-1 px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none">
                        <button type="submit" class="bg-brand-700 hover:bg-brand-800 text-white px-6 font-bold text-sm flex items-center justify-center transition">
                            <i class="fa-solid fa-magnifying-glass mr-2"></i> Search
                        </button>
                    </form>
                </div>

                <!-- Right Action Buttons -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    
                    <!-- Cart Link -->
                    @php
                        $cartCount = Auth::check() ? Auth::user()->cartItems()->count() : count(session('cart', []));
                    @endphp
                    <a href="{{ route('cart.index') }}" class="relative p-2.5 text-slate-600 hover:text-brand-700 rounded-xl hover:bg-slate-50 transition" title="Procurement Cart">
                        <i class="fa-solid fa-cart-shopping text-lg"></i>
                        @if($cartCount > 0)
                            <span class="absolute top-1 right-1 w-5 h-5 bg-rose-600 text-white font-bold text-[10px] rounded-full flex items-center justify-center shadow">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Auth Dropdown / Buttons -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-sm font-semibold text-slate-700 p-2 rounded-xl hover:bg-slate-100 transition focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-brand-700 text-white font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden md:inline font-bold text-slate-900 max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50">
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <p class="text-xs text-slate-500 font-medium">Signed in as</p>
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-brand-50 text-brand-700 border border-brand-200">
                                        Role: {{ str_replace('_', ' ', Auth::user()->role) }}
                                    </span>
                                </div>

                                <!-- Role Dashboard Link -->
                                <a href="
                                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()) {{ route('admin.dashboard') }}
                                    @elseif(Auth::user()->isSupplier()) {{ route('supplier.dashboard') }}
                                    @elseif(Auth::user()->isLogistics()) {{ route('logistics.dashboard') }}
                                    @elseif(Auth::user()->isCustoms()) {{ route('customs.dashboard') }}
                                    @else {{ route('buyer.dashboard') }}
                                    @endif
                                " class="flex items-center px-4 py-2.5 text-sm font-semibold text-brand-700 hover:bg-brand-50 transition">
                                    <i class="fa-solid fa-gauge-high mr-2.5 text-brand-600"></i> My Dashboard
                                </a>

                                <a href="{{ route('messages.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                    <i class="fa-solid fa-comments mr-2.5 text-slate-400"></i> Messages & Enquiries
                                </a>

                                @if(Auth::user()->isBuyer())
                                    <a href="{{ route('buyer.orders') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                        <i class="fa-solid fa-box mr-2.5 text-slate-400"></i> My Purchase Orders
                                    </a>
                                    <a href="{{ route('buyer.rfqs') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                        <i class="fa-solid fa-file-invoice mr-2.5 text-slate-400"></i> My RFQs & Quotes
                                    </a>
                                @endif

                                @if(Auth::user()->isSupplier())
                                    <a href="{{ route('supplier.products') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                        <i class="fa-solid fa-boxes-stacked mr-2.5 text-slate-400"></i> Manage Products
                                    </a>
                                    <a href="{{ route('supplier.orders') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                        <i class="fa-solid fa-truck-ramp-box mr-2.5 text-slate-400"></i> Production Orders
                                    </a>
                                @endif

                                <div class="border-t border-slate-100 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 transition font-semibold flex items-center">
                                        <i class="fa-solid fa-right-from-bracket mr-2.5"></i> Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-brand-700 px-3 py-2 rounded-xl transition">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="text-sm font-bold bg-brand-700 hover:bg-brand-800 text-white px-4 py-2.5 rounded-xl shadow-sm transition">
                                Register
                            </a>
                        </div>
                    @endauth

                    <!-- Mobile Menu Toggle Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 rounded-lg">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Secondary Subnav with Dynamic Category Dropdown -->
            <nav class="hidden lg:flex items-center space-x-6 py-2.5 border-t border-slate-100 text-xs font-semibold text-slate-600">
                <div class="relative" x-data="{ catOpen: false }">
                    <button @click="catOpen = !catOpen" 
                            @mouseenter="catOpen = true"
                            class="text-brand-700 hover:text-brand-800 flex items-center font-bold focus:outline-none py-1">
                        <i class="fa-solid fa-bars mr-1.5"></i> All Categories
                        <i class="fa-solid fa-chevron-down text-[9px] ml-1.5 transition-transform" :class="catOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="catOpen" 
                         @mouseleave="catOpen = false" 
                         @click.away="catOpen = false" 
                         x-cloak 
                         class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-slate-200 py-2 z-50 divide-y divide-slate-100">
                        @if(isset($globalCategories) && $globalCategories->count() > 0)
                            @foreach($globalCategories as $gCat)
                                <div class="p-2">
                                    <a href="{{ route('categories.show', $gCat->slug) }}" class="flex items-center px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-brand-50 hover:text-brand-700 transition">
                                        <i class="fa-solid {{ $gCat->icon ?? 'fa-cube' }} w-4 text-center mr-2 text-brand-600"></i>
                                        <span class="truncate">{{ $gCat->name }}</span>
                                    </a>
                                    @if($gCat->subcategories && $gCat->subcategories->count() > 0)
                                        <div class="pl-6 pt-1 space-y-0.5">
                                            @foreach($gCat->subcategories->take(4) as $gSub)
                                                <a href="{{ route('products.index', ['category' => $gSub->slug]) }}" class="block px-3 py-1 rounded-lg text-[11px] font-medium text-slate-500 hover:text-brand-700 hover:bg-slate-50 transition truncate">
                                                    ↳ {{ $gSub->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                        <div class="p-2 bg-slate-50/60 rounded-b-2xl">
                            <a href="{{ route('categories.index') }}" class="block text-center text-xs font-bold text-brand-700 hover:underline py-1">
                                View All Categories &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('products.index') }}" class="hover:text-brand-700 transition">
                    Products
                </a>
                <a href="{{ route('suppliers.index') }}" class="hover:text-brand-700 transition">
                    Suppliers
                </a>
                <a href="{{ route('rfqs.index') }}" class="hover:text-brand-700 transition">
                    RFQs
                </a>
                <a href="{{ route('shipping') }}" class="hover:text-brand-700 transition">
                    Shipping
                </a>
                <a href="{{ route('customs') }}" class="hover:text-brand-700 transition">
                    Customs
                </a>
                <a href="{{ route('calculator.index') }}" class="hover:text-brand-700 transition">
                    Calculator
                </a>
            </nav>
        </div>

        <!-- Mobile Menu Drawer -->
        <div x-show="mobileMenuOpen" x-cloak class="lg:hidden border-t border-slate-200 bg-white px-4 py-4 space-y-3">
            <form action="{{ route('products.index') }}" method="GET" class="flex rounded-xl border border-slate-200 overflow-hidden mb-3">
                <input type="text" name="q" placeholder="Search products..." class="flex-1 px-3 py-2 text-sm focus:outline-none">
                <button type="submit" class="bg-brand-700 text-white px-4">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
            <div class="grid grid-cols-2 gap-2 text-xs font-semibold text-slate-700">
                <a href="{{ route('categories.index') }}" class="p-2 bg-slate-50 rounded-lg text-center font-bold text-brand-700">Categories</a>
                <a href="{{ route('products.index') }}" class="p-2 bg-slate-50 rounded-lg text-center">Products</a>
                <a href="{{ route('suppliers.index') }}" class="p-2 bg-slate-50 rounded-lg text-center">Suppliers</a>
                <a href="{{ route('rfqs.index') }}" class="p-2 bg-slate-50 rounded-lg text-center">RFQs</a>
                <a href="{{ route('shipping') }}" class="p-2 bg-slate-50 rounded-lg text-center">Shipping</a>
                <a href="{{ route('calculator.index') }}" class="p-2 bg-slate-50 rounded-lg text-center">Calculator</a>
            </div>

            <!-- Dynamic Mobile Category List -->
            @if(isset($globalCategories) && $globalCategories->count() > 0)
                <div class="pt-2 border-t border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Categories</p>
                    <div class="space-y-1">
                        @foreach($globalCategories as $mCat)
                            <a href="{{ route('categories.show', $mCat->slug) }}" class="flex items-center justify-between px-3 py-1.5 text-xs text-slate-700 hover:bg-slate-50 rounded-lg">
                                <span class="font-medium truncate">{{ $mCat->name }}</span>
                                <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <a href="{{ route('rfqs.create') }}" class="block text-center py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-sm transition shadow-sm">
                <i class="fa-solid fa-file-pen mr-1"></i> Post RFQ
            </a>
        </div>
    </header>

    <!-- Global Floating Toaster (Top Right) -->
    @include('partials.toaster')

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-16 pt-14 pb-8 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
                
                <!-- Col 1: About Mart -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center text-white font-black text-xl shadow">
                            <i class="fa-solid fa-bag-shopping"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-black text-white tracking-tight">Mart</span>
                            <span class="text-[11px] font-semibold text-emerald-400 block uppercase -mt-1">B2B Marketplace</span>
                        </div>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-sm">
                        Mart is a simple, modern B2B marketplace connecting verified manufacturers and wholesale suppliers directly with buyers. Get quotations, compare prices, and order with confidence.
                    </p>
                    <div class="pt-2 flex items-center space-x-3 text-sm">
                        <span class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-blue-600 transition"><i class="fa-brands fa-linkedin-in"></i></span>
                        <span class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-blue-600 transition"><i class="fa-brands fa-twitter"></i></span>
                        <span class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-blue-600 transition"><i class="fa-brands fa-youtube"></i></span>
                    </div>
                </div>

                <!-- Col 2: Sourcing by Country -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">International Sourcing</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('products.index', ['country' => 'India']) }}" class="hover:text-white transition">🇮🇳 India Heavy Forgings & Castings</a></li>
                        <li><a href="{{ route('products.index', ['country' => 'China']) }}" class="hover:text-white transition">🇨🇳 China CNC & Robotics</a></li>
                        <li><a href="{{ route('products.index', ['country' => 'Germany']) }}" class="hover:text-white transition">🇩🇪 German High-Precision Hydraulics</a></li>
                        <li><a href="{{ route('products.index', ['country' => 'United Kingdom']) }}" class="hover:text-white transition">🇬🇧 UK Domestic Supplies</a></li>
                        <li><a href="{{ route('suppliers.index') }}" class="hover:text-white transition">Verified Supplier Directory</a></li>
                        <li><a href="{{ route('rfqs.create') }}" class="hover:text-white transition">Submit Custom RFQ</a></li>
                    </ul>
                </div>

                <!-- Col 3: Logistics & Customs -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Logistics & Customs</h4>
                    <!-- <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('calculator.index') }}" class="hover:text-white transition">UK Landed Cost Calculator</a></li>
                        <li><a href="{{ route('shipping') }}" class="hover:text-white transition">LCL & FCL Ocean Freight</a></li>
                        <li><a href="{{ route('shipping') }}" class="hover:text-white transition">Air Cargo Express (DHL/FedEx)</a></li>
                        <li><a href="{{ route('customs') }}" class="hover:text-white transition">Junaid & Co. Customs Clearance</a></li>
                        <li><a href="{{ route('customs') }}" class="hover:text-white transition">HMRC CDS & C88 Processing</a></li>
                    </ul> -->
                </div>

                <!-- Col 4: International Offices -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Global Hubs</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li class="flex items-start space-x-2">
                            <i class="fa-solid fa-location-dot mt-0.5 text-blue-500"></i>
                            <span><strong>London, UK:</strong> 25 Bank St, Canary Wharf, London E14 5JP</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fa-solid fa-location-dot mt-0.5 text-blue-500"></i>
                            <span><strong>Felixstowe Port:</strong> Dock Head House, IP11 3SY</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fa-solid fa-location-dot mt-0.5 text-blue-500"></i>
                            <span><strong>Pune, India:</strong> MIDC Industrial Complex, Bhosari</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fa-solid fa-envelope mt-0.5 text-blue-500"></i>
                            <span>support@mart.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright & Compliance Bar -->
            <div class="border-t border-slate-800 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-3">
                <p>&copy; {{ date('Y') }} Mart. All rights reserved.</p>
                <div class="flex items-center space-x-4">
                    <span>Privacy Policy</span>
                    <span>Terms of Trade</span>
                    <span>Trade Assurance Rules</span>
                    <span>ISO 9001 Certified Quality</span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
