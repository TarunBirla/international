<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Supplier Portal — Mart')</title>

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#172554',
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased font-sans flex overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden" 
         x-cloak></div>

    <!-- SUPPLIER SIDEBAR -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:flex-shrink-0 shadow-lg lg:shadow-none">
        
        <!-- Sidebar Header / Logo -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 bg-white">
            <a href="{{ route('supplier.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-800 to-emerald-600 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 font-black text-xl tracking-tight">
                    M
                </div>
                <div>
                    <span class="text-base font-black text-slate-900 tracking-tight leading-none block">Mart</span>
                    <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest block mt-0.5">Supplier Console</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-600 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Sidebar Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-6">
            
            <!-- Core -->
            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">General</p>
                <div class="space-y-1">
                    <a href="{{ route('supplier.dashboard') }}" 
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('supplier.dashboard') ? 'bg-emerald-50 text-emerald-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-chart-pie w-4 text-center {{ request()->routeIs('supplier.dashboard') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition">
                        <span class="flex items-center space-x-3">
                            <i class="fa-solid fa-arrow-up-right-from-square w-4 text-center text-slate-400"></i>
                            <span>Live Store</span>
                        </span>
                        <span class="text-[10px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded">View</span>
                    </a>
                </div>
            </div>

            <!-- Products CRUD -->
            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Catalog</p>
                <div class="space-y-1">
                    <a href="{{ route('supplier.products') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('supplier.products') ? 'bg-emerald-50 text-emerald-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-boxes-stacked w-4 text-center {{ request()->routeIs('supplier.products') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                        <span>Products</span>
                    </a>
                    <a href="{{ route('supplier.products.create') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition">
                        <i class="fa-solid fa-plus-circle w-4 text-center text-slate-400"></i>
                        <span>Add Product</span>
                    </a>
                </div>
            </div>

            <!-- Trade & Quotations -->
            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Trade</p>
                <div class="space-y-1">
                    <a href="{{ route('supplier.enquiries') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('supplier.enquiries*') ? 'bg-emerald-50 text-emerald-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-inbox w-4 text-center {{ request()->routeIs('supplier.enquiries*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                        <span>Enquiries</span>
                    </a>
                    <a href="{{ route('supplier.quotations') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('supplier.quotations*') ? 'bg-emerald-50 text-emerald-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-comments-dollar w-4 text-center {{ request()->routeIs('supplier.quotations*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                        <span>Quotations</span>
                    </a>
                    <a href="{{ route('supplier.orders') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('supplier.orders*') ? 'bg-emerald-50 text-emerald-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-receipt w-4 text-center {{ request()->routeIs('supplier.orders*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                        <span>Orders</span>
                    </a>
                    <a href="{{ route('rfqs.index') }}" target="_blank"
                       class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition">
                        <span class="flex items-center space-x-3">
                            <i class="fa-solid fa-gavel w-4 text-center text-slate-400"></i>
                            <span>Open RFQs</span>
                        </span>
                        <span class="text-[10px] bg-amber-50 text-amber-700 font-bold px-1.5 py-0.5 rounded border border-amber-200">Bid</span>
                    </a>
                </div>
            </div>

            <!-- Profile & Subscriptions -->
            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Account</p>
                <div class="space-y-1">
                    <a href="{{ route('supplier.profile') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('supplier.profile*') ? 'bg-emerald-50 text-emerald-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-industry w-4 text-center {{ request()->routeIs('supplier.profile*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                        <span>Profile</span>
                    </a>
                    <a href="{{ route('supplier.subscriptions') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('supplier.subscriptions*') ? 'bg-emerald-50 text-emerald-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-crown w-4 text-center {{ request()->routeIs('supplier.subscriptions*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                        <span>Plans</span>
                    </a>
                    <a href="{{ route('messages.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('messages.*') ? 'bg-emerald-50 text-emerald-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-comments w-4 text-center {{ request()->routeIs('messages.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                        <span>Messages</span>
                    </a>
                </div>
            </div>

        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-200 bg-slate-50/80">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-emerald-700 text-white font-black flex items-center justify-center text-xs shadow-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->company->company_name ?? auth()->user()->name }}</p>
                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-extrabold bg-emerald-100 text-emerald-700 uppercase">
                            {{ auth()->user()->country }} Supplier
                        </span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout" class="text-slate-400 hover:text-rose-600 p-2 rounded-lg hover:bg-white transition">
                        <i class="fa-solid fa-power-off text-sm"></i>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-white">
        
        <!-- TOPBAR -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 z-20 flex-shrink-0">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700 p-2 rounded-lg hover:bg-slate-100">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div>
                    <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight leading-none">
                        @yield('header_title', 'Supplier Console')
                    </h1>
                    <p class="text-[11px] text-slate-500 hidden sm:block mt-0.5">
                        @yield('header_subtitle', 'Manage manufacturing capabilities, submit quotation bids, and fulfill purchase orders')
                    </p>
                </div>
            </div>

            <!-- Topbar Quick Actions -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                <a href="{{ route('supplier.products.create') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                    <i class="fa-solid fa-plus"></i>
                    <span>+ Add Product</span>
                </a>
                
                <a href="{{ route('home') }}" target="_blank" title="Go to storefront" class="p-2 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition">
                    <i class="fa-solid fa-globe text-base"></i>
                </a>
            </div>
        </header>

        <!-- Global Floating Toaster (Top Right) -->
        @include('partials.toaster')

        <!-- MAIN SCROLLABLE CONTENT -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>
