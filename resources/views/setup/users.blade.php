<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role-based Demo Accounts — Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen py-10 px-4 sm:px-6 lg:px-8">

    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm text-center sm:text-left flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full mb-2 border border-emerald-200">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Users Table Initialized</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Mart Role Accounts</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Sabhi roles ke official test login credentials niche diye gaye hain.</p>
            </div>
            <div class="flex items-center justify-center sm:justify-end space-x-3">
                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center space-x-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Login Page</span>
                </a>
                <a href="{{ route('home') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center space-x-2">
                    <i class="fa-solid fa-store"></i>
                    <span>Live Store</span>
                </a>
            </div>
        </div>

        @if($success)
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center space-x-3 text-xs text-emerald-800 font-bold">
                <i class="fa-solid fa-circle-check text-base text-emerald-600 shrink-0"></i>
                <span>{{ $message }}</span>
            </div>
        @else
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center space-x-3 text-xs text-rose-800 font-bold">
                <i class="fa-solid fa-triangle-exclamation text-base text-rose-600 shrink-0"></i>
                <span>{{ $message }}</span>
            </div>
        @endif

        <!-- Accounts Table -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="text-base font-black text-slate-900">All Role Accounts & Passwords</h2>
                    <p class="text-xs text-slate-500">Sabhi users ka default password: <strong class="text-slate-900 font-mono bg-slate-100 px-2 py-0.5 rounded">password123</strong></p>
                </div>
                <span class="text-xs font-bold text-slate-400">Total: {{ count($users) }} Accounts</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="p-4 font-bold">Role</th>
                            <th class="p-4 font-bold">Name / Entity</th>
                            <th class="p-4 font-bold">Login Email</th>
                            <th class="p-4 font-bold">Password</th>
                            <th class="p-4 text-right font-bold">Quick Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($users as $u)
                            <tr class="hover:bg-slate-50/70 transition">
                                <!-- Role Badge -->
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase
                                        @if($u['role'] === 'super_admin') bg-purple-50 text-purple-700 border border-purple-200
                                        @elseif($u['role'] === 'admin') bg-indigo-50 text-indigo-700 border border-indigo-200
                                        @elseif($u['role'] === 'supplier') bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @elseif($u['role'] === 'buyer') bg-sky-50 text-sky-700 border border-sky-200
                                        @elseif($u['role'] === 'logistics_partner') bg-amber-50 text-amber-700 border border-amber-200
                                        @elseif($u['role'] === 'customs_partner') bg-blue-50 text-blue-700 border border-blue-200
                                        @else bg-slate-100 text-slate-700 border border-slate-200
                                        @endif">
                                        {{ $u['badge'] ?? ucwords(str_replace('_', ' ', $u['role'])) }}
                                    </span>
                                </td>

                                <!-- Name -->
                                <td class="p-4 font-bold text-slate-900">
                                    {{ $u['name'] }}
                                </td>

                                <!-- Email -->
                                <td class="p-4 font-mono font-bold text-blue-700">
                                    <span class="bg-blue-50/80 px-2.5 py-1 rounded-lg border border-blue-100 select-all">{{ $u['email'] }}</span>
                                </td>

                                <!-- Password -->
                                <td class="p-4 font-mono font-bold text-slate-600">
                                    <span class="bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">{{ $u['password'] }}</span>
                                </td>

                                <!-- Action -->
                                <td class="p-4 text-right">
                                    <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-[11px] inline-flex items-center space-x-1.5 transition shadow-xs">
                                        <span>Login</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer Note -->
            <div class="p-4 bg-slate-50/70 border-t border-slate-100 text-center text-xs text-slate-500">
                <i class="fa-solid fa-circle-info text-blue-500 mr-1"></i>
                Aap inme se kisi bhi email aur password <strong>password123</strong> se direct login kar sakte hain. Har user apne designated dashboard par automatically redirect ho jata hai.
            </div>
        </div>

    </div>

</body>
</html>
