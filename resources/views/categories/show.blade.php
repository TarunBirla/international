@extends('layouts.app')

@section('title', $category->name . ' — Mart')

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
            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" 
                 class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover border border-slate-200 shadow-sm flex-shrink-0"
                 loading="lazy">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $category->name }}</h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-0.5">{{ $category->description ?? 'Browse verified industrial suppliers for ' . $category->name }}</p>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Subcategories Grid (Image + Name Cards) -->
    @if($category->subcategories->isNotEmpty())
        <div class="mb-10 bg-white p-5 sm:p-6 rounded-3xl border border-slate-200 shadow-xs">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3.5">Subcategories in {{ $category->name }}</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3 sm:gap-4">
                @foreach($category->subcategories as $sub)
                    <a href="{{ route('categories.show', $sub->slug) }}" 
                       class="group bg-slate-50/70 hover:bg-white p-2.5 rounded-2xl border border-slate-200/80 hover:border-brand-500 hover:shadow-md transition flex flex-col items-center text-center">
                        <div class="w-full aspect-square rounded-xl bg-white overflow-hidden mb-2 relative border border-slate-100 shadow-xs">
                            <img src="{{ $sub->image_url }}" alt="{{ $sub->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                 loading="lazy">
                        </div>
                        <span class="font-bold text-slate-900 text-xs group-hover:text-brand-700 transition line-clamp-1 leading-tight">
                            {{ $sub->name }}
                        </span>
                        @if(isset($sub->products_count) && $sub->products_count > 0)
                            <span class="text-[10px] text-slate-400 mt-0.5 font-medium">{{ $sub->products_count }} Products</span>
                        @endif
                    </a>
                @endforeach
            </div>
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
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="relative h-40 sm:h-48 bg-slate-100 overflow-hidden">
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                            <div class="absolute top-2 sm:top-2.5 left-2 sm:left-2.5 bg-white/95 backdrop-blur-xs px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-[11px] font-bold text-slate-800 shadow-xs flex items-center space-x-1">
                                <span>{{ $product->country_flag }}</span>
                                <span class="truncate max-w-[80px] sm:max-w-none">{{ $product->origin_country }}</span>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm leading-snug hover:text-brand-700 transition line-clamp-2 mb-1">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            <span class="text-[11px] sm:text-xs text-slate-500 block mb-2 sm:mb-3 line-clamp-1">{{ $product->company->company_name ?? 'Supplier' }}</span>
                            <div class="bg-slate-50 p-2 sm:p-2.5 rounded-xl border border-slate-100 flex items-baseline justify-between">
                                <span class="font-black text-slate-900 text-xs sm:text-base">£{{ number_format($product->base_price, 2) }}</span>
                                <span class="text-[10px] sm:text-xs text-slate-500">MOQ: {{ $product->moq }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4 pt-0">
                        <a href="{{ route('products.show', $product->slug) }}" class="block text-center py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition">
                            View Details
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
