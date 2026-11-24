@extends('layouts.admin')

@section('title', 'Edit User — Perlcon Admin')
@section('header_title', 'Edit User: ' . $user->name)
@section('header_subtitle', 'Update role, contact details, authentication password, or company associations')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Users List</span>
        </a>

        @if($user->id !== auth()->id())
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Permanently delete this user?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center space-x-1.5 px-3 py-1.5 rounded-lg hover:bg-rose-50 transition">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Delete User</span>
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: User Identity & Account -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Account & Credentials</h3>
                <p class="text-xs text-slate-500 mb-4">Core contact details and authentication info</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone / WhatsApp Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Reset Password (Leave blank to keep current)</label>
                        <input type="password" name="password" 
                               placeholder="New password (optional)"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">User Role <span class="text-rose-500">*</span></label>
                        <select name="role" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ old('role', $user->role) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Account Status <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Country <span class="text-rose-500">*</span></label>
                        <input type="text" name="country" value="{{ old('country', $user->country) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 2: Company / Business Entity Details -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Company & Enterprise Profile</h3>
                <p class="text-xs text-slate-500 mb-4">Linked commercial entity details</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Company / Plant Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $user->company->company_name ?? '') }}" 
                               placeholder="e.g. Apex Industrial Machinery Ltd"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Business Type</label>
                        <select name="business_type" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            @php $bType = old('business_type', $user->company->business_type ?? 'Manufacturer'); @endphp
                            <option value="Manufacturer" {{ $bType === 'Manufacturer' ? 'selected' : '' }}>Manufacturer / Factory</option>
                            <option value="Wholesaler / Distributor" {{ $bType === 'Wholesaler / Distributor' ? 'selected' : '' }}>Wholesaler / Distributor</option>
                            <option value="Procurement Contractor" {{ $bType === 'Procurement Contractor' ? 'selected' : '' }}>Procurement Contractor</option>
                            <option value="EPC Contractor" {{ $bType === 'EPC Contractor' ? 'selected' : '' }}>EPC Contractor</option>
                            <option value="Customs Broker" {{ $bType === 'Customs Broker' ? 'selected' : '' }}>Customs Broker</option>
                            <option value="Freight Forwarder" {{ $bType === 'Freight Forwarder' ? 'selected' : '' }}>Freight Forwarder</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="flex items-center space-x-2.5 cursor-pointer mt-2">
                            <input type="checkbox" name="is_verified" value="1" {{ old('is_verified', $user->company->is_verified ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                            <span class="text-xs font-bold text-slate-700">Verified Badge (Displays Gold Verified Supplier/Buyer Seal on Marketplace)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Save Changes</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
