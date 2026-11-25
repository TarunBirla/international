@extends('layouts.admin')

@section('title', 'Category Management — Mart Admin')
@section('header_title', 'Categories & Subcategories')
@section('header_subtitle', 'Manage hierarchical taxonomy, product classification, and public storefront navigation')

@section('content')
<div class="space-y-6" x-data="{ editModal: false, editId: '', editName: '', editParentId: '', editIcon: '', editDesc: '', editFeatured: false }">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Column 1: Add New Category Form -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm h-fit">
            <h2 class="text-sm font-black text-slate-900 mb-1 flex items-center space-x-2">
                <i class="fa-solid fa-folder-plus text-brand-600"></i>
                <span>Add Category or Subcategory</span>
            </h2>
            <p class="text-xs text-slate-500 mb-5">Create a top-level parent or link as a subcategory</p>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
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
                    <label class="block text-xs font-bold text-slate-700 mb-1">FontAwesome Icon Class</label>
                    <input type="text" name="icon" value="fa-cube" placeholder="fa-cogs, fa-bolt, fa-wrench" 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Description (Optional)</label>
                    <textarea name="description" rows="2" placeholder="Brief scope of products in this category..."
                              class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" 
                               class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                        <span class="text-xs font-bold text-slate-700">Feature on Homepage Grid</span>
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
                    Existing Hierarchy ({{ $categories->count() }} Primary Categories)
                </h2>
                <span class="text-xs text-slate-500 font-medium">Click Edit to modify or Delete to remove</span>
            </div>

            <div class="space-y-4">
                @foreach($categories as $category)
                    <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-sm space-y-3">
                        
                        <!-- Parent Row -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-sm border border-brand-100 flex-shrink-0">
                                    <i class="fa-solid {{ $category->icon ?? 'fa-folder' }}"></i>
                                </div>
                                <div>
                                    <h3 class="font-black text-slate-900 text-sm flex items-center space-x-2">
                                        <span>{{ $category->name }}</span>
                                        @if($category->is_featured)
                                            <span class="text-[9px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 px-1.5 py-0.2 rounded uppercase">Featured</span>
                                        @endif
                                    </h3>
                                    <p class="text-[10px] text-slate-400">
                                        {{ $category->subcategories->count() }} Subcategories • {{ $category->products_count }} Direct Products
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
                                                editFeatured = {{ $category->is_featured ? 'true' : 'false' }};"
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
                                            <span class="font-medium mr-2">{{ $sub->name }}</span>
                                            <span class="text-[10px] text-slate-400 mr-2">({{ $sub->products_count }})</span>
                                            
                                            <button type="button" 
                                                    @click="editModal = true; 
                                                            editId = '{{ $sub->id }}'; 
                                                            editName = '{{ addslashes($sub->name) }}'; 
                                                            editParentId = '{{ $category->id }}'; 
                                                            editIcon = '{{ $sub->icon }}'; 
                                                            editDesc = '{{ addslashes($sub->description ?? '') }}';
                                                            editFeatured = {{ $sub->is_featured ? 'true' : 'false' }};"
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
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200" 
             @click.away="editModal = false">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-black text-slate-900">Edit Category</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="'{{ url('admin/categories') }}/' + editId" method="POST" class="space-y-4">
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
                    <label class="block text-xs font-bold text-slate-700 mb-1">FontAwesome Icon Class</label>
                    <input type="text" name="icon" x-model="editIcon" 
                           class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                    <textarea name="description" x-model="editDesc" rows="2" 
                              class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" x-model="editFeatured"
                               class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                        <span class="text-xs font-bold text-slate-700">Feature on Homepage Grid</span>
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
