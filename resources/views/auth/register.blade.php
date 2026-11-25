@extends('layouts.app')

@section('title', 'Join Mart — Register as Buyer or Supplier')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-white" x-data="{ userRole: '{{ old('role', $role) }}' }">
    <div class="max-w-xl w-full space-y-6">
        
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-brand-700 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-md mb-3">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Create Your Mart Account</h1>
            <p class="text-xs text-slate-500 mt-1">Join the verified B2B marketplace as a wholesale buyer or supplier.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            
            <!-- Role Toggle Selector -->
            <div class="grid grid-cols-2 gap-3 p-1.5 bg-slate-100 rounded-2xl mb-6">
                <button type="button" @click="userRole = 'buyer'" :class="userRole === 'buyer' ? 'bg-white shadow text-slate-900 font-extrabold' : 'text-slate-600 font-semibold'" class="py-2.5 rounded-xl text-xs transition">
                    <i class="fa-solid fa-building mr-1.5"></i> I am a Buyer / Importer
                </button>
                <button type="button" @click="userRole = 'supplier'" :class="userRole === 'supplier' ? 'bg-white shadow text-slate-900 font-extrabold' : 'text-slate-600 font-semibold'" class="py-2.5 rounded-xl text-xs transition">
                    <i class="fa-solid fa-industry mr-1.5"></i> I am a Manufacturer / Supplier
                </button>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="role" :value="userRole">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Contact Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. David Sterling" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Corporate Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@company.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Password *</label>
                        <input type="password" name="password" required placeholder="Min 8 characters" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Confirm Password *</label>
                        <input type="password" name="password_confirmation" required placeholder="Repeat password" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                </div>

                <!-- Company Details -->
                <div class="pt-2 border-t border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-3">Company Registration Details</span>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Registered Company Name *</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" required placeholder="e.g. Apex Industrial Supplies Ltd" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Country *</label>
                                <select name="country" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none bg-white">
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="India">India</option>
                                    <option value="China">China</option>
                                    <option value="Germany">Germany</option>
                                    <option value="United States">United States</option>
                                    <option value="Italy">Italy</option>
                                    <option value="France">France</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">City / Region</label>
                                <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. London / Pune / Shenzhen" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tax / VAT ID (Optional)</label>
                                <input type="text" name="tax_id" value="{{ old('tax_id') }}" placeholder="GST / VAT / Tax ID" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+44 20 7946 0991" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-sm transition shadow-md shadow-brand-700/20">
                    Create Account & Go To Dashboard
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
                Already registered? 
                <a href="{{ route('login') }}" class="font-bold text-brand-700 hover:underline">Sign In</a>
            </div>
        </div>

    </div>
</div>
@endsection
