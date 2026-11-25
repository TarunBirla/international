@extends('layouts.admin')

@section('title', 'Manage Marketplace Users — Mart Admin')
@section('header_title', 'All Marketplace Users')
@section('header_subtitle', 'Full CRUD control over administrators, buyers, manufacturers, freight and customs partners')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Search Bar -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            
            <!-- Role Filter Pills -->
            <div class="flex flex-wrap gap-1.5 text-xs font-bold">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-3.5 py-1.5 rounded-xl transition {{ !request('role') ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All Roles
                </a>
                @foreach($roles as $roleKey)
                    <a href="{{ route('admin.users.index', ['role' => $roleKey, 'search' => request('search')]) }}" 
                       class="px-3.5 py-1.5 rounded-xl transition {{ request('role') === $roleKey ? 'bg-brand-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ ucwords(str_replace('_', ' ', $roleKey)) }}
                    </a>
                @endforeach
            </div>

            <!-- Search and Create Button -->
            <div class="flex items-center space-x-3 w-full lg:w-auto">
                <form action="{{ route('admin.users.index') }}" method="GET" class="relative flex-1 lg:w-64">
                    @if(request('role'))
                        <input type="hidden" name="role" value="{{ request('role') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, city..." 
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </form>

                <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm flex items-center space-x-1.5 flex-shrink-0">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>+ Add User</span>
                </a>
            </div>

        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4">User / Contact</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Company Name</th>
                        <th class="p-4">Country & City</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Registered Date</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70 transition">
                            <!-- User Name & Email -->
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-slate-200 to-slate-100 text-slate-800 font-black flex items-center justify-center text-xs shadow-xs border border-slate-200">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $user->name }}</p>
                                        <p class="text-[11px] text-slate-500">{{ $user->email }}</p>
                                        @if($user->phone)
                                            <p class="text-[10px] text-slate-400 mt-0.5"><i class="fa-solid fa-phone mr-1"></i>{{ $user->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Role Badge -->
                            <td class="p-4">
                                @php
                                    $roleBadgeClasses = [
                                        'super_admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'admin' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'buyer' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'supplier' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'logistics_partner' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'customs_partner' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ][$user->role] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black uppercase border {{ $roleBadgeClasses }}">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </td>

                            <!-- Company -->
                            <td class="p-4">
                                @if($user->company)
                                    <span class="font-bold text-slate-900 block">{{ $user->company->company_name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $user->company->business_type ?? 'Industrial' }}</span>
                                @else
                                    <span class="text-slate-400 italic">No Company Linked</span>
                                @endif
                            </td>

                            <!-- Country & City -->
                            <td class="p-4">
                                <span class="font-semibold text-slate-800 block">{{ $user->country ?? 'Global' }}</span>
                                <span class="text-[10px] text-slate-400">{{ $user->city ?? 'N/A' }}</span>
                            </td>

                            <!-- Status -->
                            <td class="p-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $user->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-emerald-500' : 'bg-rose-500' }} mr-1.5"></span>
                                    {{ ucfirst($user->status ?? 'active') }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="p-4 text-slate-500 text-[11px]">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <!-- Actions (Edit & Delete) -->
                            <td class="p-4 text-right">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                                       title="Edit User"
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-brand-50 text-slate-600 hover:text-brand-700 flex items-center justify-center transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete user {{ $user->name }}? This will permanently delete their account.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Delete User"
                                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">
                                <i class="fa-solid fa-users text-3xl mb-3 text-slate-300 block"></i>
                                <p class="text-sm font-semibold">No users found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
