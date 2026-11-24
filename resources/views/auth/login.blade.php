@extends('layouts.app')

@section('title', 'Sign In — Perlcon International')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-md w-full space-y-6">
        
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-brand-700 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-md mb-3">
                <i class="fa-solid fa-cube"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Sign In to Perlcon</h1>
            <p class="text-xs text-slate-500 mt-1">Access your procurement dashboard, RFQs, quotations and orders.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                    <input type="email" id="emailInput" name="email" value="{{ old('email', $email) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-xs font-bold text-slate-700">Password</label>
                    </div>
                    <input type="password" id="passwordInput" name="password" value="password123" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-brand-700 rounded border-slate-300">
                        <span>Remember me</span>
                    </label>
                    <span class="text-slate-400">Default: password123</span>
                </div>

                <button type="submit" class="w-full py-3 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-sm transition shadow-md shadow-brand-700/20">
                    Sign In to Portal
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-bold text-brand-700 hover:underline">Register as Buyer or Supplier</a>
            </div>
        </div>

        <!-- 1-Click Demo Login Account Switcher with Exact User Roles -->
        <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-sm text-xs">
            <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                <span class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-bolt text-amber-500 mr-1.5"></i> 1-Click Fast Login Test Roles
                </span>
                <span class="text-[10px] font-mono font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded border border-amber-200">Pass: password123</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 font-medium">
                <!-- 1. Tarun Birla -->
                <button type="button" onclick="fillLogin('tarun@perlcon.com')" class="p-2.5 bg-slate-50 hover:bg-purple-50 text-slate-800 hover:text-purple-800 rounded-xl border border-slate-200/80 text-left transition flex items-center justify-between group">
                    <div>
                        <strong class="block text-xs text-slate-900 group-hover:text-purple-900">👑 Tarun Birla</strong>
                        <span class="text-[10px] text-slate-500">Super Admin (Owner)</span>
                    </div>
                    <span class="text-[10px] font-mono text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded">tarun@</span>
                </button>

                <!-- 2. Hariom Birla -->
                <button type="button" onclick="fillLogin('hariom@perlcon.com')" class="p-2.5 bg-slate-50 hover:bg-blue-50 text-slate-800 hover:text-blue-800 rounded-xl border border-slate-200/80 text-left transition flex items-center justify-between group">
                    <div>
                        <strong class="block text-xs text-slate-900 group-hover:text-blue-900">🏢 Hariom Birla</strong>
                        <span class="text-[10px] text-slate-500">Operations Admin</span>
                    </div>
                    <span class="text-[10px] font-mono text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">hariom@</span>
                </button>

                <!-- 3. Navdeep -->
                <button type="button" onclick="fillLogin('navdeep@perlcon.com')" class="p-2.5 bg-slate-50 hover:bg-sky-50 text-slate-800 hover:text-sky-800 rounded-xl border border-slate-200/80 text-left transition flex items-center justify-between group">
                    <div>
                        <strong class="block text-xs text-slate-900 group-hover:text-sky-900">🇬🇧 Navdeep</strong>
                        <span class="text-[10px] text-slate-500">UK Buyer (Procurement)</span>
                    </div>
                    <span class="text-[10px] font-mono text-sky-600 bg-sky-50 px-1.5 py-0.5 rounded">navdeep@</span>
                </button>

                <!-- 4. Rohit -->
                <button type="button" onclick="fillLogin('rohit@perlcon.com')" class="p-2.5 bg-slate-50 hover:bg-emerald-50 text-slate-800 hover:text-emerald-800 rounded-xl border border-slate-200/80 text-left transition flex items-center justify-between group">
                    <div>
                        <strong class="block text-xs text-slate-900 group-hover:text-emerald-900">🇮🇳 Rohit</strong>
                        <span class="text-[10px] text-slate-500">Supplier (Forgings & Valves)</span>
                    </div>
                    <span class="text-[10px] font-mono text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">rohit@</span>
                </button>

                <!-- 5. Tarry -->
                <button type="button" onclick="fillLogin('tarry@perlcon.com')" class="p-2.5 bg-slate-50 hover:bg-rose-50 text-slate-800 hover:text-rose-800 rounded-xl border border-slate-200/80 text-left transition flex items-center justify-between group">
                    <div>
                        <strong class="block text-xs text-slate-900 group-hover:text-rose-900">🇨🇳 Tarry</strong>
                        <span class="text-[10px] text-slate-500">Supplier (CNC & Automation)</span>
                    </div>
                    <span class="text-[10px] font-mono text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">tarry@</span>
                </button>

                <!-- 6. Harry -->
                <button type="button" onclick="fillLogin('harry@perlcon.com')" class="p-2.5 bg-slate-50 hover:bg-indigo-50 text-slate-800 hover:text-indigo-800 rounded-xl border border-slate-200/80 text-left transition flex items-center justify-between group">
                    <div>
                        <strong class="block text-xs text-slate-900 group-hover:text-indigo-900">🚢 Harry</strong>
                        <span class="text-[10px] text-slate-500">Logistics Partner (Freight)</span>
                    </div>
                    <span class="text-[10px] font-mono text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded">harry@</span>
                </button>

                <!-- 7. Nasar -->
                <button type="button" onclick="fillLogin('nasar@perlcon.com')" class="sm:col-span-2 p-2.5 bg-slate-50 hover:bg-amber-50 text-slate-800 hover:text-amber-800 rounded-xl border border-slate-200/80 text-left transition flex items-center justify-between group">
                    <div>
                        <strong class="block text-xs text-slate-900 group-hover:text-amber-900">🛡️ Nasar</strong>
                        <span class="text-[10px] text-slate-500">Customs Broker (Nasar & Co. • C88 & HMRC CDS)</span>
                    </div>
                    <span class="text-[10px] font-mono text-amber-700 bg-amber-50 px-2 py-0.5 rounded">nasar@perlcon.com</span>
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    function fillLogin(email) {
        document.getElementById('emailInput').value = email;
        document.getElementById('passwordInput').value = 'password123';
    }
</script>
@endsection
