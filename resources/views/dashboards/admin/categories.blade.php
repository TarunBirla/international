@extends('layouts.admin')

@section('title', 'Categories — Mart Admin')
@section('header_title', 'Categories & Subcategories')
@section('header_subtitle', 'Manage product categories, subcategories, images, and homepage display')

@section('content')
<div class="space-y-6" x-data="{ 
    editModal: false, 
    editId: '', 
    editName: '', 
    editParentId: '', 
    editIcon: '', 
    editDesc: '', 
    editFeatured: false,
    editShowOnHome: true,
    editSortOrder: 0,
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
        
        <!-- Column 1: Add New Category Form -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm h-fit">
            <h2 class="text-sm font-black text-slate-900 mb-1 flex items-center space-x-2">
                <i class="fa-solid fa-folder-plus text-brand-600"></i>
                <span>Add Category</span>
            </h2>
            <p class="text-xs text-slate-500 mb-5">Create a main category or subcategory</p>

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Category Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Industrial Automation" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Parent Category</label>
                    <select name="parent_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="">None (Create as Top-Level Category)</option>
                        @foreach($allParents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-[10px] text-slate-400 mt-1 block">Leave empty to create a primary root category</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Category / Subcategory Image</label>
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
                        <label class="block text-xs font-bold text-slate-700 mb-1">FontAwesome Icon</label>
                        <input type="text" name="icon" value="fa-cube" placeholder="fa-cogs, fa-bolt" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="0" min="0" 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Description (Optional)</label>
                    <textarea name="description" rows="2" placeholder="Brief scope of products in this category..."
                              class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"></textarea>
                </div>

                <div class="space-y-2 pt-1">
                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="show_on_home" value="1" checked
                               class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                        <span class="text-xs font-bold text-slate-700">Show on Home Page</span>
                    </label>

                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" 
                               class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                        <span class="text-xs font-bold text-slate-700">Feature on Navigation Grid</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-plus"></i>
                        <span>Save Category</span>
                    </button>
                </div>

            </form>
        </div>

        <!-- Column 2 & 3: Category Hierarchical List & CRUD -->
        <div class="lg:col-span-2 space-y-4">
            
            <div class="flex items-center justify-between px-2">
                <h2 class="text-sm font-black text-slate-900">
                    All Categories ({{ $categories->count() }})
                </h2>
                <span class="text-xs text-slate-500 font-medium">Click Edit to modify or Delete to remove</span>
            </div>

            <div class="space-y-4">
                @foreach($categories as $category)
                    <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-sm space-y-3">
                        
                        <!-- Parent Row -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3.5">
                                <div class="relative w-12 h-12 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0">
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" 
                                         class="w-full h-full object-cover" loading="lazy">
                                </div>
                                <div>
                                    <h3 class="font-black text-slate-900 text-sm flex items-center space-x-2">
                                        <span>{{ $category->name }}</span>
                                        @if($category->show_on_home)
                                            <span class="text-[9px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200 px-1.5 py-0.2 rounded uppercase">Home</span>
                                        @endif
                                        @if($category->is_featured)
                                            <span class="text-[9px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 px-1.5 py-0.2 rounded uppercase">Featured</span>
                                        @endif
                                    </h3>
                                    <p class="text-[10px] text-slate-400">
                                        {{ $category->subcategories->count() }} Subcategories • {{ $category->products_count }} Direct Products • Order: {{ $category->sort_order }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <button type="button" 
                                        @click="editModal = true; 
                                                editId = '{{ $category->id }}'; 
                                                editName = '{{ addslashes($category->name) }}'; 
                                                editParentId = ''; 
                                                editIcon = '{{ $category->icon }}'; 
                                                editDesc = '{{ addslashes($category->description ?? '') }}';
                                                editFeatured = {{ $category->is_featured ? 'true' : 'false' }};
                                                editShowOnHome = {{ $category->show_on_home ? 'true' : 'false' }};
                                                editSortOrder = {{ $category->sort_order }};
                                                editImagePreview = '{{ $category->image_url }}';"
                                        class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-brand-50 text-slate-600 hover:text-brand-700 flex items-center justify-center transition"
                                        title="Edit Category">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>

                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete category {{ $category->name }}? Subcategories will be moved to root.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition"
                                            title="Delete Category">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Subcategories Pills -->
                        @if($category->subcategories->count() > 0)
                            <div class="pt-3 border-t border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Subcategories:</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($category->subcategories as $sub)
                                        <div class="inline-flex items-center bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1 text-xs text-slate-700 group hover:border-slate-300 transition">
                                            <img src="{{ $sub->image_url }}" alt="{{ $sub->name }}" class="w-5 h-5 rounded-md object-cover mr-2 flex-shrink-0 border border-slate-200">
                                            <span class="font-medium mr-2">{{ $sub->name }}</span>
                                            @if($sub->show_on_home)
                                                <span class="text-[9px] bg-blue-100 text-blue-700 font-bold px-1 rounded mr-1.5">Home</span>
                                            @endif
                                            <span class="text-[10px] text-slate-400 mr-2">({{ $sub->products_count }})</span>
                                            
                                            <button type="button" 
                                                    @click="editModal = true; 
                                                            editId = '{{ $sub->id }}'; 
                                                            editName = '{{ addslashes($sub->name) }}'; 
                                                            editParentId = '{{ $category->id }}'; 
                                                            editIcon = '{{ $sub->icon }}'; 
                                                            editDesc = '{{ addslashes($sub->description ?? '') }}';
                                                            editFeatured = {{ $sub->is_featured ? 'true' : 'false' }};
                                                            editShowOnHome = {{ $sub->show_on_home ? 'true' : 'false' }};
                                                            editSortOrder = {{ $sub->sort_order }};
                                                            editImagePreview = '{{ $sub->image_url }}';"
                                                    class="text-slate-400 hover:text-brand-700 mr-1.5" title="Edit">
                                                <i class="fa-solid fa-pen text-[10px]"></i>
                                            </button>

                                            <form action="{{ route('admin.categories.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Delete subcategory {{ $sub->name }}?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-rose-600" title="Delete">
                                                    <i class="fa-solid fa-xmark text-[11px]"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>

        </div>

    </div>

    <!-- Edit Category Modal -->
    <div x-show="editModal" 
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" 
         x-cloak>
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto" 
             @click.away="editModal = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-black text-slate-900">Edit Category / Subcategory</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="'{{ url('admin/categories') }}/' + editId" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Category Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="editName" required 
                           class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Parent Category</label>
                    <select name="parent_id" x-model="editParentId" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                        <option value="">None (Top-Level Category)</option>
                        @foreach($allParents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Replace Image</label>
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
                        <label class="block text-xs font-bold text-slate-700 mb-1">FontAwesome Icon</label>
                        <input type="text" name="icon" x-model="editIcon" 
                               class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" x-model="editSortOrder" min="0" 
                               class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                    <textarea name="description" x-model="editDesc" rows="2" 
                              class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"></textarea>
                </div>

                <div class="space-y-2 pt-1">
                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="show_on_home" value="1" x-model="editShowOnHome"
                               class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                        <span class="text-xs font-bold text-slate-700">Show on Home Page</span>
                    </label>

                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" x-model="editFeatured"
                               class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                        <span class="text-xs font-bold text-slate-700">Feature on Navigation Grid</span>
                    </label>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                        Update Category
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
