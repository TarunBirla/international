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

        <!-- 1-Click Demo Login Account Switcher -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm text-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">1-Click Auto Fill Demo Accounts</span>
            <div class="grid grid-cols-2 gap-1.5 font-medium">
                <button type="button" onclick="fillLogin('admin@perlcon.com')" class="p-2 bg-slate-50 hover:bg-brand-50 text-slate-800 hover:text-brand-700 rounded-lg border border-slate-100 text-left transition truncate">
                    👑 Super Admin
                </button>
                <button type="button" onclick="fillLogin('buyer@perlcon.com')" class="p-2 bg-slate-50 hover:bg-brand-50 text-slate-800 hover:text-brand-700 rounded-lg border border-slate-100 text-left transition truncate">
                    🇬🇧 UK Buyer
                </button>
                <button type="button" onclick="fillLogin('supplier.india@perlcon.com')" class="p-2 bg-slate-50 hover:bg-brand-50 text-slate-800 hover:text-brand-700 rounded-lg border border-slate-100 text-left transition truncate">
                    🇮🇳 Supplier (India)
                </button>
                <button type="button" onclick="fillLogin('supplier.china@perlcon.com')" class="p-2 bg-slate-50 hover:bg-brand-50 text-slate-800 hover:text-brand-700 rounded-lg border border-slate-100 text-left transition truncate">
                    🇨🇳 Supplier (China)
                </button>
                <button type="button" onclick="fillLogin('supplier.europe@perlcon.com')" class="p-2 bg-slate-50 hover:bg-brand-50 text-slate-800 hover:text-brand-700 rounded-lg border border-slate-100 text-left transition truncate">
                    🇩🇪 Supplier (Germany)
                </button>
                <button type="button" onclick="fillLogin('customs@perlcon.com')" class="p-2 bg-slate-50 hover:bg-brand-50 text-slate-800 hover:text-brand-700 rounded-lg border border-slate-100 text-left transition truncate">
                    🛡️ Customs (Junaid)
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
