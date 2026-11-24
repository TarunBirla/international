@extends('layouts.app')

@section('title', $category->name . ' — Perlcon International')

@section('content')
<div class="bg-slate-50 border-b border-slate-200 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
            <span>/</span>
            <a href="{{ route('categories.index') }}" class="hover:text-brand-700">Categories</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">{{ $category->name }}</span>
        </div>
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-brand-700 flex items-center justify-center text-2xl shadow-sm">
                <i class="fa-solid {{ $category->icon }}"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $category->name }}</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-0.5">{{ $category->description ?? 'Browse verified industrial suppliers for ' . $category->name }}</p>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Subcategory Pills -->
    @if($category->subcategories->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-8">
            @foreach($category->subcategories as $sub)
                <a href="{{ route('categories.show', $sub->slug) }}" class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:border-brand-500 hover:text-brand-700 transition">
                    {{ $sub->name }}
                </a>
            @endforeach
        </div>
    @endif

    <!-- Products Grid -->
    @if($products->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
            <i class="fa-solid fa-box-open text-4xl text-slate-300 mb-3 block"></i>
            <h3 class="text-lg font-bold text-slate-800">No products currently listed in this category</h3>
            <p class="text-slate-500 text-xs mt-1">Post an RFQ to invite manufacturers worldwide to quote for items in this category.</p>
            <a href="{{ route('rfqs.create') }}?category={{ $category->id }}" class="inline-block mt-4 px-6 py-2.5 bg-amber-500 text-white font-bold rounded-xl text-xs shadow">
                Post RFQ in {{ $category->name }}
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="relative h-48 bg-slate-100 overflow-hidden">
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute top-2.5 left-2.5 bg-white/95 px-2.5 py-1 rounded-full text-[11px] font-bold text-slate-800 shadow-sm">
                                {{ $product->origin_country }}
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-slate-900 text-sm leading-snug hover:text-brand-700 transition line-clamp-2 mb-1">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            <span class="text-xs text-slate-500 block mb-3">{{ $product->company->company_name ?? 'Supplier' }}</span>
                            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 flex items-baseline justify-between">
                                <span class="font-black text-slate-900 text-base">£{{ number_format($product->base_price, 2) }}</span>
                                <span class="text-xs text-slate-500">MOQ: {{ $product->moq }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <a href="{{ route('products.show', $product->slug) }}" class="block text-center py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition">
                            View Product Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
