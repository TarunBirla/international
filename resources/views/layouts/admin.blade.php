<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Console — Mart')</title>

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
                        },
                        perlcon: {
                            blue: '#0F2C59',
                            gold: '#DDA853',
                            cream: '#F8F0E5',
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
<body class="h-full bg-slate-50 text-slate-800 antialiased font-sans flex overflow-hidden" x-data="{ sidebarOpen: false, userDropdown: false }">

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

    <!-- SIDEBAR -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:flex-shrink-0 shadow-lg lg:shadow-none">
        
        <!-- Sidebar Header / Logo -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 bg-white">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-900 via-brand-800 to-blue-600 flex items-center justify-center text-white shadow-md shadow-brand-500/20 font-black text-xl tracking-tight">
                    M
                </div>
                <div>
                    <span class="text-base font-black text-slate-900 tracking-tight leading-none block">Mart</span>
                    <span class="text-[10px] font-bold text-brand-700 uppercase tracking-widest block mt-0.5">Admin Console</span>
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
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-50 text-brand-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-chart-pie w-4 text-center {{ request()->routeIs('admin.dashboard') ? 'text-brand-600' : 'text-slate-400' }}"></i>
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

            <!-- User Management -->
            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Accounts</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.users.*') ? 'bg-brand-50 text-brand-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-users-gear w-4 text-center {{ request()->routeIs('admin.users.*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
                        <span>Users</span>
                    </a>
                    <a href="{{ route('admin.suppliers') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.suppliers*') ? 'bg-brand-50 text-brand-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="flex items-center space-x-3">
                            <i class="fa-solid fa-industry w-4 text-center {{ request()->routeIs('admin.suppliers*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
                            <span>Suppliers</span>
                        </span>
                        <span class="text-[10px] bg-emerald-50 text-emerald-700 font-bold px-1.5 py-0.5 rounded border border-emerald-200">KYC</span>
                    </a>
                    <a href="{{ route('admin.buyers') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.buyers*') ? 'bg-brand-50 text-brand-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-briefcase w-4 text-center {{ request()->routeIs('admin.buyers*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
                        <span>Buyers</span>
                    </a>
                </div>
            </div>

            <!-- Catalog Management -->
            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Catalog</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.categories') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.categories*') ? 'bg-brand-50 text-brand-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-folder-tree w-4 text-center {{ request()->routeIs('admin.categories*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
                        <span>Categories</span>
                    </a>
                    <a href="{{ route('admin.products') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.products*') ? 'bg-brand-50 text-brand-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-boxes-stacked w-4 text-center {{ request()->routeIs('admin.products*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
                        <span>Products</span>
                    </a>
                    <a href="{{ route('admin.products.create') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition">
                        <i class="fa-solid fa-plus-circle w-4 text-center text-slate-400"></i>
                        <span>Add Product</span>
                    </a>
                </div>
            </div>

            <!-- B2B Trade & Orders -->
            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Trade</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.orders') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.orders*') ? 'bg-brand-50 text-brand-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-receipt w-4 text-center {{ request()->routeIs('admin.orders*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
                        <span>Orders</span>
                    </a>
                    <a href="{{ route('admin.rfqs') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.rfqs*') ? 'bg-brand-50 text-brand-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-file-invoice-dollar w-4 text-center {{ request()->routeIs('admin.rfqs*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
                        <span>RFQs</span>
                    </a>
                    <a href="{{ route('admin.shipments') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.shipments*') ? 'bg-brand-50 text-brand-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-truck-fast w-4 text-center {{ request()->routeIs('admin.shipments*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
                        <span>Shipments</span>
                    </a>
                    <a href="{{ route('admin.customs') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.customs*') ? 'bg-brand-50 text-brand-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="flex items-center space-x-3">
                            <i class="fa-solid fa-stamp w-4 text-center {{ request()->routeIs('admin.customs*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
                            <span>Customs</span>
                        </span>
                        <span class="text-[10px] bg-amber-50 text-amber-700 font-bold px-1.5 py-0.5 rounded border border-amber-200">UK</span>
                    </a>
                </div>
            </div>

            <!-- System & Maintenance -->
            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">System</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.reports') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.reports*') ? 'bg-brand-50 text-brand-700 shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i class="fa-solid fa-chart-line w-4 text-center {{ request()->routeIs('admin.reports*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
                        <span>Reports</span>
                    </a>
                    
                </div>
            </div>

        </nav>

        <!-- Sidebar Footer / Current Admin -->
        <div class="p-4 border-t border-slate-200 bg-slate-50/80">
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.profile') }}" class="flex items-center space-x-3 min-w-0 hover:opacity-85 transition group" title="View Profile & Change Password">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name ?? 'Admin' }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 shadow-sm flex-shrink-0">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900 truncate group-hover:text-brand-700 transition">{{ auth()->user()->name ?? 'Super Admin' }}</p>
                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-extrabold bg-purple-100 text-purple-700 uppercase">
                            {{ auth()->user()->role ?? 'super_admin' }}
                        </span>
                    </div>
                </a>
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
                        @yield('header_title', 'Super Admin Dashboard')
                    </h1>
                    <p class="text-[11px] text-slate-500 hidden sm:block mt-0.5">
                        @yield('header_subtitle', 'Complete industrial governance & transaction control')
                    </p>
                </div>
            </div>

            <!-- Topbar Quick Actions -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                <a href="{{ route('admin.users.create') }}" class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition">
                    <i class="fa-solid fa-user-plus text-slate-500"></i>
                    <span>+ Add User</span>
                </a>
                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 bg-brand-700 hover:bg-brand-800 text-white rounded-lg text-xs font-bold transition shadow-sm">
                    <i class="fa-solid fa-plus"></i>
                    <span>+ Add Product</span>
                </a>
                
                <!-- Public Store Link -->
                <a href="{{ route('home') }}" target="_blank" title="Go to storefront" class="p-2 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition">
                    <i class="fa-solid fa-globe text-base"></i>
                </a>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-lg object-cover border border-slate-200">
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50" 
                         x-cloak>
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('admin.profile') }}" class="flex items-center space-x-2 px-4 py-2 text-xs font-bold text-slate-800 hover:bg-slate-50">
                            <i class="fa-solid fa-user-shield text-brand-600 w-4"></i>
                            <span>Admin Profile & Security</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-2 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                            <i class="fa-solid fa-users text-slate-400 w-4"></i>
                            <span>Manage Users</span>
                        </a>
                        <a href="{{ route('setup.index') }}" class="flex items-center space-x-2 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                            <i class="fa-solid fa-database text-slate-400 w-4"></i>
                            <span>Database Setup Tool</span>
                        </a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center space-x-2 px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50">
                                <i class="fa-solid fa-arrow-right-from-bracket w-4"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
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
