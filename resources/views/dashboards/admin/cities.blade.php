@extends('layouts.admin')

@section('title', 'Cities — Mart Admin')
@section('header_title', 'Cities Directory')
@section('header_subtitle', 'Manage industrial trade hubs, cities, and homepage showcase')

@section('content')
<div class="space-y-6" x-data="{ 
    editModal: false, 
    editId: '', 
    editName: '', 
    editCountry: '', 
    editStatus: 'active', 
    editSortOrder: 0, 
    editShowOnHome: true,
    editImagePreview: '',
    previewNewImage(event, target) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                if (target === 'new') this.newImagePreview = e.target.result;
                if (target === 'edit') this.editImagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    },
    newImagePreview: ''
}">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Column 1: Add New City Form -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm h-fit">
            <h2 class="text-sm font-black text-slate-900 mb-1 flex items-center space-x-2">
                <i class="fa-solid fa-city text-brand-600"></i>
                <span>Add New City</span>
            </h2>
            <p class="text-xs text-slate-500 mb-5">Create a city hub for product and supplier filtering</p>

            <form action="{{ route('admin.cities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">City Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Pune, Shenzhen, Manchester" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Country <span class="text-rose-500">*</span></label>
                    <select name="country" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="India">🇮🇳 India</option>
                        <option value="China">🇨🇳 China</option>
                        <option value="United Kingdom">🇬🇧 United Kingdom</option>
                        <option value="Germany">🇩🇪 Germany</option>
                        <option value="United States">🇺🇸 United States</option>
                        <option value="Turkey">🇹🇷 Turkey</option>
                        <option value="UAE">🇦🇪 UAE</option>
                        <option value="Vietnam">🇻🇳 Vietnam</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">City Image (Thumbnail / Skyline)</label>
                    <div class="flex items-center space-x-3">
                        <template x-if="newImagePreview">
                            <img :src="newImagePreview" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-xs">
                        </template>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                               @change="previewNewImage($event, 'new')"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Max 2MB (JPG, PNG, WebP). Auto-compressed to 400x400.</span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="0" min="0" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                </div>

                <div class="pt-1">
                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="show_on_home" value="1" checked
                               class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                        <span class="text-xs font-bold text-slate-700">Show on Home Page ("Top Cities")</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-plus"></i>
                        <span>Save City</span>
                    </button>
                </div>

            </form>
        </div>

        <!-- Column 2 & 3: Cities List -->
        <div class="lg:col-span-2 space-y-4">
            
            <div class="bg-white rounded-3xl border border-slate-200 p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
                <form action="{{ route('admin.cities') }}" method="GET" class="flex items-center space-x-2 w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search city or country..."
                           class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none w-full sm:w-64">
                    <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-slate-600">Total: {{ $cities->total() }}</span>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                            <tr>
                                <th class="px-5 py-3.5">City & Country</th>
                                <th class="px-4 py-3.5">Slug</th>
                                <th class="px-4 py-3.5 text-center">Status</th>
                                <th class="px-4 py-3.5 text-center">Home Page</th>
                                <th class="px-4 py-3.5 text-center">Order</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($cities as $city)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ $city->image_url }}" alt="{{ $city->name }}" 
                                                 class="w-10 h-10 rounded-xl object-cover border border-slate-200 flex-shrink-0"
                                                 loading="lazy">
                                            <div>
                                                <span class="font-black text-slate-900 block">{{ $city->name }}</span>
                                                <span class="text-[10px] text-slate-400">{{ $city->country }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 font-mono text-[11px] text-slate-500">
                                        {{ $city->slug }}
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        @if($city->status === 'active')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        @if($city->show_on_home)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                Featured
                                            </span>
                                        @else
                                            <span class="text-[11px] text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center font-bold text-slate-700">
                                        {{ $city->sort_order }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button type="button" 
                                                    @click="editModal = true; 
                                                            editId = '{{ $city->id }}'; 
                                                            editName = '{{ addslashes($city->name) }}'; 
                                                            editCountry = '{{ addslashes($city->country) }}'; 
                                                            editStatus = '{{ $city->status }}'; 
                                                            editSortOrder = {{ $city->sort_order }}; 
                                                            editShowOnHome = {{ $city->show_on_home ? 'true' : 'false' }};
                                                            editImagePreview = '{{ $city->image_url }}';"
                                                    class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-brand-50 text-slate-600 hover:text-brand-700 flex items-center justify-center transition"
                                                    title="Edit City">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </button>

                                            <form action="{{ route('admin.cities.destroy', $city->id) }}" method="POST" onsubmit="return confirm('Delete city {{ $city->name }}?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition"
                                                        title="Delete City">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                                        No cities found. Add your first industrial city using the form.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($cities->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $cities->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>

    <!-- Edit City Modal -->
    <div x-show="editModal" 
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         x-cloak>
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200" 
             @click.away="editModal = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-black text-slate-900">Edit City</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="'{{ url('admin/cities') }}/' + editId" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">City Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="editName" required 
                           class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Country <span class="text-rose-500">*</span></label>
                    <select name="country" x-model="editCountry" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="India">🇮🇳 India</option>
                        <option value="China">🇨🇳 China</option>
                        <option value="United Kingdom">🇬🇧 United Kingdom</option>
                        <option value="Germany">🇩🇪 Germany</option>
                        <option value="United States">🇺🇸 United States</option>
                        <option value="Turkey">🇹🇷 Turkey</option>
                        <option value="UAE">🇦🇪 UAE</option>
                        <option value="Vietnam">🇻🇳 Vietnam</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Replace City Image</label>
                    <div class="flex items-center space-x-3">
                        <template x-if="editImagePreview">
                            <img :src="editImagePreview" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-xs">
                        </template>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                               @change="previewNewImage($event, 'edit')"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                        <select name="status" x-model="editStatus" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" x-model="editSortOrder" min="0" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                </div>

                <div class="pt-1">
                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="show_on_home" value="1" x-model="editShowOnHome"
                               class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                        <span class="text-xs font-bold text-slate-700">Show on Home Page ("Top Cities")</span>
                    </label>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                        Update City
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
