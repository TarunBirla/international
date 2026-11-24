@extends('layouts.app')

@section('title', 'Category Management — Admin Portal')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Industrial Category Management</h1>
        <p class="text-slate-600 text-xs sm:text-sm mt-0.5">Create and organize hierarchical categories and subcategories for the marketplace.</p>

        <div class="flex flex-wrap gap-2 mt-4 text-xs font-bold">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Overview</a>
            <a href="{{ route('admin.suppliers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Suppliers</a>
            <a href="{{ route('admin.buyers') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Buyers</a>
            <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Products</a>
            <a href="{{ route('admin.categories') }}" class="px-4 py-2 bg-brand-700 text-white rounded-xl shadow-sm">Categories</a>
            <a href="{{ route('admin.orders') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Orders</a>
            <a href="{{ route('admin.rfqs') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">RFQs</a>
            <a href="{{ route('admin.reports') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">Reports</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Create Form -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h2 class="text-base font-black text-slate-900">Add New Category</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Category Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Industrial Automation" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Parent Category (Leave empty for Root)</label>
                    <select name="parent_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                        <option value="">None (Top-Level Category)</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">FontAwesome Icon Class</label>
                    <input type="text" name="icon" value="fa-cogs" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs"></textarea>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-brand-700 rounded border-slate-300">
                    <span class="text-xs font-semibold text-slate-700">Show on Homepage Features</span>
                </div>

                <button type="submit" class="w-full py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition">
                    Create Category
                </button>
            </form>
        </div>

        <!-- Categories List (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h2 class="text-base font-black text-slate-900">Existing Category Hierarchy</h2>

            <div class="space-y-4 max-h-[650px] overflow-y-auto">
                @foreach($categories as $cat)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid {{ $cat->icon }} text-brand-700"></i>
                                <strong class="text-sm font-extrabold text-slate-900">{{ $cat->name }}</strong>
                                @if($cat->is_featured)
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800 uppercase">Featured</span>
                                @endif
                            </div>
                            <span class="text-xs text-slate-500 font-semibold">{{ count($cat->subcategories) }} Subcategories</span>
                        </div>

                        <div class="flex flex-wrap gap-1.5 pt-2 border-t border-slate-200">
                            @foreach($cat->subcategories as $sub)
                                <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[11px] font-medium text-slate-700">
                                    {{ $sub->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
