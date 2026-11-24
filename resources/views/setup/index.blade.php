<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup & Server Migration — Perlcon International B2B</title>
    <!-- Tailwind CSS CDN for guaranteed instant rendering on any server -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Top Navigation Header -->
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
            <div class="flex items-center space-x-3">
                <a href="{{ url('/') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition flex items-center">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Marketplace
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow">
        <div class="mb-8 text-center sm:text-left">
            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 mb-3">
                <i class="fa-solid fa-server mr-1.5"></i> Server 1-Click Database Setup & Migrations
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Database & Server Initializer</h1>
            <p class="text-slate-600 mt-2 text-base max-w-2xl">
                Upload this project to your cPanel, Plesk, VPS or shared hosting, then hit this route (<code class="bg-slate-100 text-blue-700 px-2 py-0.5 rounded text-sm font-mono">/setup-db</code>) to create tables, seed data, and launch Perlcon International immediately.
            </p>
        </div>

        <!-- Two Column Setup Card -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Setup Execution & DB Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Box -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
                        <i class="fa-solid fa-database text-blue-600 mr-2.5"></i> Detected Database Configuration (.env)
                    </h2>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-5">
                        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                            <span class="text-xs font-medium text-slate-500 block">DB Driver</span>
                            <span class="text-sm font-bold text-slate-900 uppercase">{{ $connection }}</span>
                        </div>
                        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                            <span class="text-xs font-medium text-slate-500 block">Database Name</span>
                            <span class="text-sm font-bold text-slate-900 font-mono truncate block">{{ $dbConfig['database'] ?? 'laravel' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                            <span class="text-xs font-medium text-slate-500 block">DB Host</span>
                            <span class="text-sm font-bold text-slate-900 font-mono">{{ $dbConfig['host'] ?? 'Local' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                            <span class="text-xs font-medium text-slate-500 block">Port</span>
                            <span class="text-sm font-bold text-slate-900 font-mono">{{ $dbConfig['port'] ?? '3306' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                            <span class="text-xs font-medium text-slate-500 block">DB User</span>
                            <span class="text-sm font-bold text-slate-900 font-mono truncate block">{{ $dbConfig['username'] ?? 'root' }}</span>
                        </div>
                        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                            <span class="text-xs font-medium text-slate-500 block">Current Tables</span>
                            <span class="text-sm font-bold text-slate-900 font-mono">{{ $tablesCount }} tables</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl border {{ $databaseExists ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-amber-50 border-amber-200 text-amber-900' }} flex items-start space-x-3">
                        <div class="mt-0.5 text-lg">
                            <i class="fa-solid {{ $databaseExists ? 'fa-circle-check text-emerald-600' : 'fa-triangle-exclamation text-amber-600' }}"></i>
                        </div>
                        <div class="text-sm flex-1">
                            <span class="font-bold block">{{ $connectionStatus }}</span>
                            <span class="opacity-90">{{ $connectionMessage }}</span>
                        </div>
                    </div>
                </div>

                <!-- Run Setup Form -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-2 flex items-center">
                        <i class="fa-solid fa-play text-blue-600 mr-2.5"></i> Run 1-Click Database Setup
                    </h2>
                    <p class="text-sm text-slate-600 mb-6">
                        This action will connect to your database, create the schema, run all 17 migrations, and seed complete initial data (Admin, Suppliers, Buyers, Categories, Products, RFQs, Logistics & Customs).
                    </p>

                    <form action="{{ route('setup.execute') }}" method="POST">
                        @csrf
                        <div class="mb-6 bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="force_fresh" value="1" checked class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-semibold text-slate-800">Clean Fresh Installation (Recommended)</span>
                            </label>
                            <p class="text-xs text-slate-500 pl-7">
                                Drops any existing corrupted or empty tables and builds the complete fresh database with ready-to-use demo accounts & product catalog.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 transition duration-150 flex items-center justify-center space-x-2 text-base">
                                <i class="fa-solid fa-bolt"></i>
                                <span>Execute 1-Click Setup Now</span>
                            </button>

                            <a href="{{ route('setup.quick', ['fresh' => 1]) }}" class="w-full sm:w-auto px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-center text-sm transition">
                                Direct GET URL Trigger
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Col: Default Login Credentials Card -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-base font-bold text-slate-900 mb-3 flex items-center">
                        <i class="fa-solid fa-key text-blue-600 mr-2"></i> Ready-to-Use Accounts
                    </h3>
                    <p class="text-xs text-slate-500 mb-4">
                        All seeded accounts use password: <code class="font-mono bg-blue-50 text-blue-700 font-bold px-1.5 py-0.5 rounded">password123</code>
                    </p>

                    <div class="space-y-3 text-xs">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-slate-900">Super Admin</span>
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-700 font-semibold rounded text-[10px]">Full Access</span>
                            </div>
                            <span class="text-slate-600 font-mono block">admin@perlcon.com</span>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-slate-900">Supplier (India)</span>
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 font-semibold rounded text-[10px]">Manufacturer</span>
                            </div>
                            <span class="text-slate-600 font-mono block">supplier.india@perlcon.com</span>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-slate-900">Supplier (China)</span>
                                <span class="px-2 py-0.5 bg-rose-100 text-rose-700 font-semibold rounded text-[10px]">CNC & Automation</span>
                            </div>
                            <span class="text-slate-600 font-mono block">supplier.china@perlcon.com</span>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-slate-900">Supplier (Germany)</span>
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 font-semibold rounded text-[10px]">Hydraulics & Drives</span>
                            </div>
                            <span class="text-slate-600 font-mono block">supplier.europe@perlcon.com</span>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-slate-900">UK Buyer</span>
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 font-semibold rounded text-[10px]">Procurement</span>
                            </div>
                            <span class="text-slate-600 font-mono block">buyer@perlcon.com</span>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-slate-900">Logistics Partner</span>
                                <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 font-semibold rounded text-[10px]">Air & Sea Freight</span>
                            </div>
                            <span class="text-slate-600 font-mono block">logistics@perlcon.com</span>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-slate-900">Customs Partner</span>
                                <span class="px-2 py-0.5 bg-cyan-100 text-cyan-700 font-semibold rounded text-[10px]">Junaid & Co.</span>
                            </div>
                            <span class="text-slate-600 font-mono block">customs@perlcon.com</span>
                        </div>
                    </div>
                </div>

                <!-- Server Instructions Card -->
                <div class="bg-blue-50 rounded-2xl border border-blue-200 p-5 text-xs text-blue-900">
                    <h4 class="font-bold mb-2 flex items-center text-blue-900">
                        <i class="fa-solid fa-cloud-arrow-up mr-2 text-blue-600"></i> cPanel / Server Instructions
                    </h4>
                    <ol class="list-decimal pl-4 space-y-1.5 text-blue-800">
                        <li>Upload project files to your server (e.g. <code class="font-mono bg-white px-1 rounded">public_html</code> or subdomain).</li>
                        <li>Edit <code class="font-mono bg-white px-1 rounded">.env</code> to set your MySQL database credentials.</li>
                        <li>Visit <code class="font-mono bg-white px-1 rounded">https://yourdomain.com/setup-db</code> in any browser.</li>
                        <li>Click <strong>Execute 1-Click Setup</strong>!</li>
                    </ol>
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
