@extends('layouts.app')

@section('title', 'Product Categories — Mart')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-8">
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

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="space-y-10">
        @foreach($categories as $category)
            <div class="bg-white rounded-3xl border border-slate-200 p-7 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-700 flex items-center justify-center text-xl">
                            <i class="fa-solid {{ $category->icon }}"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900">
                                <a href="{{ route('categories.show', $category->slug) }}" class="hover:text-brand-700 transition">
                                    {{ $category->name }}
                                </a>
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $category->description }}</p>
                        </div>
                    </div>
                    <a href="{{ route('categories.show', $category->slug) }}" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-brand-700 rounded-xl text-xs font-bold transition flex items-center">
                        <span>Browse Products</span>
                        <i class="fa-solid fa-arrow-right ml-1.5"></i>
                    </a>
                </div>

                <!-- Subcategories Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($category->subcategories as $sub)
                        <a href="{{ route('categories.show', $sub->slug) }}" class="p-3 bg-slate-50 hover:bg-brand-50 rounded-xl border border-slate-100 hover:border-brand-200 text-xs font-semibold text-slate-700 hover:text-brand-700 transition flex items-center justify-between group">
                            <span>{{ $sub->name }}</span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400 group-hover:text-brand-600"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
