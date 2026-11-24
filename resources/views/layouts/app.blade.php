<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Perlcon International — Global B2B Marketplace & Industrial Procurement')</title>
    <meta name="description" content="@yield('meta_description', 'Connect with verified industrial manufacturers from India, China and Europe. Heavy machinery, spare parts, automation, customs clearance and UK delivery.')">

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

    <!-- Top Announcement Bar -->
    <div class="bg-perlcon-navy text-white text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center space-x-3 text-slate-300">
                <span class="inline-flex items-center text-amber-400 font-semibold">
                    <i class="fa-solid fa-shield-halved mr-1.5"></i> Perlcon Trade Assurance Escrow
                </span>
                <span class="hidden md:inline">|</span>
                <span class="hidden md:inline text-slate-300">
                    Sourcing from <strong class="text-white">India, China & Europe</strong> with Integrated UK Customs Clearance
                </span>
            </div>
            <div class="flex items-center space-x-4 text-slate-300">
                <a href="{{ route('setup.index') }}" class="hover:text-amber-300 text-amber-400 font-semibold flex items-center transition">
                    <i class="fa-solid fa-server mr-1"></i> Server DB Setup Route
                </a>
                <a href="{{ route('calculator.index') }}" class="hover:text-white transition flex items-center">
                    <i class="fa-solid fa-calculator mr-1"></i> UK Landed Cost Calculator
                </a>
                <div class="hidden sm:flex items-center space-x-1 text-slate-300 font-medium">
                    <span>Currency:</span>
                    <strong class="text-white">GBP (£)</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3 flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-brand-900 via-brand-700 to-blue-500 flex items-center justify-center text-white text-2xl font-black shadow-md shadow-brand-900/20 group-hover:scale-105 transition transform">
                            <i class="fa-solid fa-cube"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-black tracking-tight text-slate-900 flex items-center">
                                PERLCON
                                <span class="ml-1.5 px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-500 text-white tracking-normal uppercase">B2B</span>
                            </span>
                            <span class="text-[11px] font-semibold text-slate-500 block tracking-wider uppercase -mt-1">International Trade</span>
                        </div>
                    </a>
                </div>

                <!-- Global Search Bar with Category Dropdown -->
                <div class="hidden lg:flex flex-1 max-w-2xl mx-4">
                    <form action="{{ route('products.index') }}" method="GET" class="w-full flex rounded-xl border-2 border-slate-200 hover:border-brand-500 focus-within:border-brand-600 focus-within:ring-2 focus-within:ring-brand-100 transition bg-white overflow-hidden shadow-sm">
                        <select name="category" class="bg-slate-50 text-slate-700 text-xs font-semibold px-3 py-2.5 border-r border-slate-200 focus:outline-none cursor-pointer">
                            <option value="">All Categories</option>
                            <option value="industrial-machinery">Industrial Machinery</option>
                            <option value="electrical-equipment">Electrical Equipment</option>
                            <option value="mechanical-components">Mechanical Components</option>
                            <option value="spare-parts">Spare Parts</option>
                            <option value="industrial-automation">Automation & Robotics</option>
                            <option value="construction-equipment">Construction</option>
                        </select>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search industrial machinery, CNC, valves, flanges, HS codes, manufacturers..." class="flex-1 px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none">
                        <button type="submit" class="bg-brand-700 hover:bg-brand-800 text-white px-6 font-bold text-sm flex items-center justify-center transition">
                            <i class="fa-solid fa-magnifying-glass mr-2"></i> Search
                        </button>
                    </form>
                </div>

                <!-- Right Action Buttons -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    
                    <!-- Post RFQ Button -->
                    <a href="{{ route('rfqs.create') }}" class="hidden sm:inline-flex items-center px-4 py-2.5 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white shadow-sm transition">
                        <i class="fa-solid fa-file-pen mr-1.5"></i> Post RFQ
                    </a>

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

            <!-- Secondary Category Subnav -->
            <nav class="hidden lg:flex items-center space-x-6 py-2.5 border-t border-slate-100 text-xs font-semibold text-slate-600">
                <a href="{{ route('categories.index') }}" class="text-brand-700 hover:text-brand-800 flex items-center font-bold">
                    <i class="fa-solid fa-bars mr-1.5"></i> All Categories
                </a>
                <a href="{{ route('products.index', ['country' => 'India']) }}" class="hover:text-brand-700 transition flex items-center">
                    <span class="mr-1">🇮🇳</span> India Manufacturers
                </a>
                <a href="{{ route('products.index', ['country' => 'China']) }}" class="hover:text-brand-700 transition flex items-center">
                    <span class="mr-1">🇨🇳</span> China CNC & Machinery
                </a>
                <a href="{{ route('products.index', ['country' => 'Germany']) }}" class="hover:text-brand-700 transition flex items-center">
                    <span class="mr-1">🇩🇪</span> European Engineering
                </a>
                <a href="{{ route('suppliers.index') }}" class="hover:text-brand-700 transition">
                    Supplier Directory
                </a>
                <a href="{{ route('rfqs.index') }}" class="hover:text-brand-700 transition">
                    Open RFQs
                </a>
                <a href="{{ route('shipping') }}" class="hover:text-brand-700 transition">
                    Freight & Logistics
                </a>
                <a href="{{ route('customs') }}" class="hover:text-brand-700 transition">
                    UK Customs Clearance
                </a>
            </nav>
        </div>

        <!-- Mobile Menu Drawer -->
        <div x-show="mobileMenuOpen" x-cloak class="lg:hidden border-t border-slate-200 bg-white px-4 py-4 space-y-3">
            <form action="{{ route('products.index') }}" method="GET" class="flex rounded-xl border border-slate-200 overflow-hidden mb-3">
                <input type="text" name="q" placeholder="Search industrial products..." class="flex-1 px-3 py-2 text-sm focus:outline-none">
                <button type="submit" class="bg-brand-700 text-white px-4">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
            <div class="grid grid-cols-2 gap-2 text-xs font-semibold text-slate-700">
                <a href="{{ route('categories.index') }}" class="p-2 bg-slate-50 rounded-lg">Categories</a>
                <a href="{{ route('suppliers.index') }}" class="p-2 bg-slate-50 rounded-lg">Suppliers</a>
                <a href="{{ route('rfqs.index') }}" class="p-2 bg-slate-50 rounded-lg">Browse RFQs</a>
                <a href="{{ route('calculator.index') }}" class="p-2 bg-slate-50 rounded-lg">Duty Calculator</a>
                <a href="{{ route('shipping') }}" class="p-2 bg-slate-50 rounded-lg">Logistics</a>
                <a href="{{ route('customs') }}" class="p-2 bg-slate-50 rounded-lg">Customs</a>
            </div>
            <a href="{{ route('rfqs.create') }}" class="block text-center py-2.5 bg-amber-500 text-white font-bold rounded-xl text-sm">
                <i class="fa-solid fa-file-pen mr-1"></i> Post Request for Quotation (RFQ)
            </a>
        </div>
    </header>

    <!-- Global Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl p-4 flex items-center space-x-3 shadow-sm mb-4">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg flex-shrink-0"></i>
                <div class="text-sm font-semibold flex-1">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-900 rounded-xl p-4 flex items-center space-x-3 shadow-sm mb-4">
                <i class="fa-solid fa-circle-xmark text-rose-600 text-lg flex-shrink-0"></i>
                <div class="text-sm font-semibold flex-1">{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-900 rounded-xl p-4 shadow-sm mb-4">
                <div class="flex items-center space-x-2 font-bold text-sm mb-1 text-rose-800">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                    <span>Please review the following errors:</span>
                </div>
                <ul class="list-disc pl-5 text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-16 pt-14 pb-8 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
                
                <!-- Col 1: About Perlcon -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-xl shadow">
                            <i class="fa-solid fa-cube"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-black text-white tracking-tight">PERLCON</span>
                            <span class="text-[11px] font-semibold text-blue-400 block uppercase -mt-1">International B2B Marketplace</span>
                        </div>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-sm">
                        Perlcon connects UK and European industrial buyers with verified manufacturing plants in India, China and Germany. End-to-end procurement with RFQ negotiation, multi-supplier order consolidation, sea & air freight logistics, and seamless UK HMRC customs clearance.
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
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('calculator.index') }}" class="hover:text-white transition">UK Landed Cost Calculator</a></li>
                        <li><a href="{{ route('shipping') }}" class="hover:text-white transition">LCL & FCL Ocean Freight</a></li>
                        <li><a href="{{ route('shipping') }}" class="hover:text-white transition">Air Cargo Express (DHL/FedEx)</a></li>
                        <li><a href="{{ route('customs') }}" class="hover:text-white transition">Junaid & Co. Customs Clearance</a></li>
                        <li><a href="{{ route('customs') }}" class="hover:text-white transition">HMRC CDS & C88 Processing</a></li>
                        <li><a href="{{ route('setup.index') }}" class="text-amber-400 font-semibold hover:text-amber-300 transition">Server 1-Click DB Setup</a></li>
                    </ul>
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
                            <span>contact@perlcon.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright & Compliance Bar -->
            <div class="border-t border-slate-800 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-3">
                <p>&copy; {{ date('Y') }} Perlcon International B2B Marketplace. All rights reserved.</p>
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
