<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup Status — Perlcon International B2B</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between py-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-700 flex items-center justify-center text-white font-bold text-xl shadow-md">
                    <i class="fa-solid fa-cube"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-tight text-slate-900">PERLCON</span>
                    <span class="text-xs font-semibold uppercase tracking-wider text-blue-600 block -mt-1">International B2B</span>
                </div>
            </div>
            <a href="{{ url('/') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                Go to Homepage <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow">
        @if($success)
            <!-- Success Banner -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-8 mb-8 text-center shadow-sm">
                <div class="w-16 h-16 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 shadow-md shadow-emerald-500/20">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-emerald-950">Database Setup Completed Successfully!</h1>
                <p class="text-emerald-800 mt-2 max-w-xl mx-auto text-sm sm:text-base">
                    {{ $message }}
                </p>
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('login') }}" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm shadow-md shadow-emerald-600/20 transition flex items-center space-x-2">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Login to Dashboard</span>
                    </a>
                    <a href="{{ url('/') }}" class="px-6 py-3 bg-white hover:bg-slate-50 text-slate-800 font-bold border border-slate-200 rounded-xl text-sm transition">
                        Explore Marketplace
                    </a>
                </div>
            </div>
        @else
            <!-- Error Banner -->
            <div class="bg-rose-50 border border-rose-200 rounded-3xl p-8 mb-8 text-center shadow-sm">
                <div class="w-16 h-16 bg-rose-600 text-white rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 shadow-md shadow-rose-500/20">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-rose-950">Setup Failed</h1>
                <p class="text-rose-800 mt-2 max-w-xl mx-auto text-sm sm:text-base">
                    {{ $message }}
                </p>
                <div class="mt-6">
                    <a href="{{ route('setup.index') }}" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-sm transition inline-flex items-center space-x-2">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                        <span>Return to Setup Configuration</span>
                    </a>
                </div>
            </div>
        @endif

        <!-- Execution Progress Log -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mb-8">
            <h2 class="text-base font-bold text-slate-900 mb-3 flex items-center">
                <i class="fa-solid fa-terminal text-blue-600 mr-2"></i> Execution Output Log
            </h2>
            <div class="bg-slate-900 text-slate-200 rounded-xl p-4 font-mono text-xs max-h-64 overflow-y-auto space-y-1.5 leading-relaxed">
                @foreach($log as $entry)
                    <div class="{{ str_starts_with($entry, '✓') ? 'text-emerald-400 font-semibold' : (str_starts_with($entry, 'ERROR') ? 'text-rose-400 font-bold' : 'text-slate-300') }}">
                        {{ $entry }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Access Login Credentials Grid -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 mb-1 flex items-center">
                <i class="fa-solid fa-users text-blue-600 mr-2"></i> Ready-to-Use Demo Accounts
            </h2>
            <p class="text-xs text-slate-500 mb-5">Click any account below to copy email. Password for all: <strong class="text-blue-700 font-mono">password123</strong></p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                <!-- Admin -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-slate-900">Super Admin</span>
                            <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded font-bold text-[10px]">Super Admin</span>
                        </div>
                        <span class="text-slate-600 font-mono block select-all">admin@perlcon.com</span>
                    </div>
                    <a href="{{ route('login') }}?email=admin@perlcon.com" class="mt-3 text-blue-600 font-semibold hover:underline block text-[11px]">Auto Fill & Login &rarr;</a>
                </div>

                <!-- Supplier India -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-slate-900">Supplier (India)</span>
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded font-bold text-[10px]">Forging & Valves</span>
                        </div>
                        <span class="text-slate-600 font-mono block select-all">supplier.india@perlcon.com</span>
                    </div>
                    <a href="{{ route('login') }}?email=supplier.india@perlcon.com" class="mt-3 text-blue-600 font-semibold hover:underline block text-[11px]">Auto Fill & Login &rarr;</a>
                </div>

                <!-- Supplier China -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-slate-900">Supplier (China)</span>
                            <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded font-bold text-[10px]">CNC & Robotics</span>
                        </div>
                        <span class="text-slate-600 font-mono block select-all">supplier.china@perlcon.com</span>
                    </div>
                    <a href="{{ route('login') }}?email=supplier.china@perlcon.com" class="mt-3 text-blue-600 font-semibold hover:underline block text-[11px]">Auto Fill & Login &rarr;</a>
                </div>

                <!-- Supplier Germany -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-slate-900">Supplier (Germany)</span>
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded font-bold text-[10px]">Hydraulics</span>
                        </div>
                        <span class="text-slate-600 font-mono block select-all">supplier.europe@perlcon.com</span>
                    </div>
                    <a href="{{ route('login') }}?email=supplier.europe@perlcon.com" class="mt-3 text-blue-600 font-semibold hover:underline block text-[11px]">Auto Fill & Login &rarr;</a>
                </div>

                <!-- UK Buyer -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-slate-900">UK Buyer</span>
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded font-bold text-[10px]">Apex Contracting</span>
                        </div>
                        <span class="text-slate-600 font-mono block select-all">buyer@perlcon.com</span>
                    </div>
                    <a href="{{ route('login') }}?email=buyer@perlcon.com" class="mt-3 text-blue-600 font-semibold hover:underline block text-[11px]">Auto Fill & Login &rarr;</a>
                </div>

                <!-- Customs Partner -->
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-slate-900">Customs Partner</span>
                            <span class="px-2 py-0.5 bg-cyan-100 text-cyan-700 rounded font-bold text-[10px]">Junaid & Co.</span>
                        </div>
                        <span class="text-slate-600 font-mono block select-all">customs@perlcon.com</span>
                    </div>
                    <a href="{{ route('login') }}?email=customs@perlcon.com" class="mt-3 text-blue-600 font-semibold hover:underline block text-[11px]">Auto Fill & Login &rarr;</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} Perlcon International B2B Marketplace. All rights reserved.
    </footer>

</body>
</html>
