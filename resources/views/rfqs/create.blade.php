@extends(auth()->check() ? auth()->user()->getDashboardLayout() : 'layouts.app')

@section('title', 'Post Request for Quotation (RFQ) — Perlcon International')
@section('header_title', 'Post Request for Quotation (RFQ)')
@section('header_subtitle', 'Submit your engineering specifications to verified international manufacturers in India, China, and Europe')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    @if(!auth()->check())
        <div class="bg-slate-50 border-b border-slate-200 py-6 px-4 sm:px-6 rounded-2xl mb-6">
            <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-brand-700">Home</a>
                <span>/</span>
                <a href="{{ route('rfqs.index') }}" class="hover:text-brand-700">RFQs</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Post RFQ</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Create Request for Quotation</h1>
            <p class="text-slate-600 text-xs sm:text-sm mt-1">Submit your engineering and procurement requirements to receive verified quotations from manufacturers in India, China and Europe.</p>
        </div>
    @else
        <div class="flex items-center justify-between">
            <a href="{{ auth()->user()->isBuyer() ? route('buyer.rfqs') : (auth()->user()->isAdmin() ? route('admin.rfqs') : route('rfqs.index')) }}" 
               class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center space-x-1.5">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to RFQs List</span>
            </a>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('rfqs.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Product Name & Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Product Name / Component Required <span class="text-rose-500">*</span></label>
                    <input type="text" name="product_name" value="{{ old('product_name', request('product')) }}" required 
                           placeholder="e.g. 5-Axis CNC Milling Center or ASME B16.5 Forged Flanges" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Category <span class="text-rose-500">*</span></label>
                    <select name="category_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', request('category')) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Quantity & Target Price -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Quantity Required <span class="text-rose-500">*</span></label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity', 100) }}" required 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit of Measurement <span class="text-rose-500">*</span></label>
                    <select name="unit" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="Pieces">Pieces</option>
                        <option value="Units">Units</option>
                        <option value="Sets">Sets</option>
                        <option value="Metric Tons">Metric Tons</option>
                        <option value="Kilograms">Kilograms</option>
                        <option value="Meters">Meters</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target Unit Price</label>
                    <div class="flex items-center space-x-2">
                        <select name="currency" class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none">
                            <option value="GBP">GBP (£)</option>
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                        </select>
                        <input type="number" step="0.01" name="target_price" value="{{ old('target_price') }}" placeholder="Optional" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Preferred Countries Checkboxes -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Preferred Supplier Origin Countries</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs font-semibold text-slate-700">
                    <label class="flex items-center space-x-2.5 p-3 bg-slate-50 rounded-xl border border-slate-200 cursor-pointer hover:border-slate-300 transition">
                        <input type="checkbox" name="preferred_countries[]" value="India" checked class="w-4 h-4 text-brand-700 rounded border-slate-300">
                        <span>🇮🇳 India</span>
                    </label>
                    <label class="flex items-center space-x-2.5 p-3 bg-slate-50 rounded-xl border border-slate-200 cursor-pointer hover:border-slate-300 transition">
                        <input type="checkbox" name="preferred_countries[]" value="China" checked class="w-4 h-4 text-brand-700 rounded border-slate-300">
                        <span>🇨🇳 China</span>
                    </label>
                    <label class="flex items-center space-x-2.5 p-3 bg-slate-50 rounded-xl border border-slate-200 cursor-pointer hover:border-slate-300 transition">
                        <input type="checkbox" name="preferred_countries[]" value="Germany" checked class="w-4 h-4 text-brand-700 rounded border-slate-300">
                        <span>🇩🇪 Germany (EU)</span>
                    </label>
                    <label class="flex items-center space-x-2.5 p-3 bg-slate-50 rounded-xl border border-slate-200 cursor-pointer hover:border-slate-300 transition">
                        <input type="checkbox" name="preferred_countries[]" value="United Kingdom" checked class="w-4 h-4 text-brand-700 rounded border-slate-300">
                        <span>🇬🇧 United Kingdom</span>
                    </label>
                </div>
            </div>

            <!-- Technical Specifications -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Technical Specifications & Engineering Requirements <span class="text-rose-500">*</span></label>
                <textarea name="technical_specifications" rows="4" required 
                          placeholder="Detail specific engineering standards (e.g. ASME, DIN, ISO), material grades (SS316, ASTM A105), dimensional tolerances, voltage, pressure ratings, test certifications required (e.g. 3.1 MTR)..." 
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"></textarea>
            </div>

            <!-- Destination Port & Delivery Date -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Destination Country <span class="text-rose-500">*</span></label>
                    <input type="text" name="destination_country" value="United Kingdom" required 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Destination Port / City</label>
                    <input type="text" name="destination_port_city" value="Port of Felixstowe / London Gateway" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Required Delivery Date</label>
                    <input type="date" name="required_delivery_date" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Additional Commercial Terms / Inspection Requirements</label>
                <textarea name="additional_requirements" rows="2" 
                          placeholder="e.g. Third-party SGS inspection, letter of credit payment terms, seaworthy packaging..." 
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 bg-brand-700 hover:bg-brand-800 text-white font-black rounded-xl text-sm transition shadow-lg shadow-brand-700/20 flex items-center justify-center space-x-2">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Publish RFQ & Broadcast to Verified Manufacturers</span>
            </button>
        </form>
    </div>
</div>
@endsection
