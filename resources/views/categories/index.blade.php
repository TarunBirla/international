@extends('layouts.app')

@section('title', 'Product Categories — Mart')

@section('content')
<div class="bg-white border-b border-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Categories</span>
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Industrial Product Categories</h1>
        <p class="text-slate-600 text-sm mt-1">Explore all industrial machinery, mechanical components, electrical engineering equipment, and raw materials.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="space-y-8 sm:space-y-10">
        @foreach($categories as $category)
            <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-7 shadow-sm">
                <!-- Main Category Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 sm:pb-5 border-b border-slate-100 mb-6 gap-3">
                    <div class="flex items-center space-x-3.5 sm:space-x-4">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" 
                             class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover border border-slate-200 shadow-xs flex-shrink-0"
                             loading="lazy">
                        <div>
                            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                                <a href="{{ route('categories.show', $category->slug) }}" class="hover:text-brand-700 transition">
                                    {{ $category->name }}
                                </a>
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-1 sm:line-clamp-none">{{ $category->description }}</p>
                        </div>
                    </div>
                    <a href="{{ route('categories.show', $category->slug) }}" class="px-4 py-2 bg-slate-50 hover:bg-brand-50 text-brand-700 rounded-xl text-xs font-bold transition flex items-center justify-center sm:justify-start w-full sm:w-auto border border-slate-100">
                        <span>Browse Category</span>
                        <i class="fa-solid fa-arrow-right ml-1.5 text-xs"></i>
                    </a>
                </div>

                <!-- Subcategories Grid (Image + Name Cards) -->
                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Subcategories & Sectors</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
                        @foreach($category->subcategories as $sub)
                            <a href="{{ route('products.index', ['category' => $sub->slug]) }}" 
                               class="group bg-slate-50/70 hover:bg-white p-3 rounded-2xl border border-slate-200/80 hover:border-brand-500 hover:shadow-md transition flex flex-col items-center text-center">
                                <div class="w-full aspect-square rounded-xl bg-white overflow-hidden mb-2 relative border border-slate-100 shadow-xs">
                                    <img src="{{ $sub->image_url }}" alt="{{ $sub->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                         loading="lazy">
                                </div>
                                <span class="font-bold text-slate-900 text-xs group-hover:text-brand-700 transition line-clamp-2 leading-tight">
                                    {{ $sub->name }}
                                </span>
                                @if(isset($sub->products_count) && $sub->products_count > 0)
                                    <span class="text-[10px] text-slate-400 mt-1 font-medium">{{ $sub->products_count }} Products</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
