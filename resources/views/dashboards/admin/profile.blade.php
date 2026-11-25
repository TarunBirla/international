@extends('layouts.admin')

@section('title', 'Admin Profile & Security Settings — Mart Admin')
@section('header_title', 'Admin Profile & Security')
@section('header_subtitle', 'Manage your administrator account credentials, avatar, and password')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Info Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
        <div class="w-20 h-20 rounded-2xl bg-brand-900 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0 shadow-md">
            <img id="headerAvatarPreview" 
                 src="{{ $user->avatar_url }}" 
                 alt="{{ $user->name }}" 
                 class="w-full h-full object-cover">
        </div>
        <div class="text-center sm:text-left flex-1">
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 mb-1">
                <h2 class="text-xl font-black text-slate-900">{{ $user->name }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-purple-50 text-purple-700 border border-purple-200 w-fit mx-auto sm:mx-0">
                    {{ ucwords(str_replace('_', ' ', $user->role)) }}
                </span>
            </div>
            <p class="text-xs text-slate-500">{{ $user->email }}</p>
            <p class="text-[11px] text-slate-400 mt-1">
                <i class="fa-solid fa-shield-halved text-brand-600 mr-1"></i> Full administrator privileges over marketplace catalog, trade, and users.
            </p>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Avatar -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Profile Avatar / Photo</label>
                <div class="flex items-center space-x-6 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                    <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 overflow-hidden flex items-center justify-center shrink-0 shadow-sm">
                        <img id="adminProfilePreview" 
                             src="{{ $user->avatar_url }}" 
                             alt="{{ $user->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <input type="file" name="avatar" id="adminAvatarInput" accept="image/*" class="hidden" onchange="previewAdminAvatar(event)">
                        <button type="button" onclick="document.getElementById('adminAvatarInput').click()" class="px-4 py-2 bg-white border border-slate-300 hover:border-slate-400 text-slate-700 font-bold rounded-xl text-xs transition shadow-sm inline-flex items-center space-x-2">
                            <i class="fa-solid fa-cloud-arrow-up text-brand-600"></i>
                            <span>Choose Photo</span>
                        </button>
                        <p class="text-[11px] text-slate-500 mt-1.5">PNG, JPG, WEBP, or SVG up to 4MB.</p>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 2: Contact & Identity -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Account Information</h3>
                <p class="text-xs text-slate-500 mb-4">Official name and login email for this admin account</p>

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

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone / WhatsApp Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                               placeholder="e.g. +44 20 7946 0991"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 3: Password Reset -->
            <div>
                <h3 class="text-sm font-black text-slate-900 mb-1">Security & Password</h3>
                <p class="text-xs text-slate-500 mb-4">Update login password (leave blank if keeping your current password)</p>

                <div class="max-w-md" x-data="{ showPass: false }">
                    <label class="block text-xs font-bold text-slate-700 mb-1">New Password</label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" 
                               placeholder="Minimum 6 characters" 
                               class="w-full px-4 py-2.5 pr-10 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" title="Toggle password">
                            <i class="fa-solid text-xs" :class="showPass ? 'fa-eye-slash text-brand-600' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Save Changes</span>
                </button>
            </div>

        </form>
    </div>

</div>

<script>
function previewAdminAvatar(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('adminProfilePreview').src = e.target.result;
            const headerPreview = document.getElementById('headerAvatarPreview');
            if (headerPreview) headerPreview.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
