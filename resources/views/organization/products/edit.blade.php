<x-organization-layout>
    @section('title', 'Edit Product')
    
    @php
        $breadcrumbs = [
            'Products' => route('organization.products.index'),
            $product->name => route('organization.products.show', $product),
            'Edit' => '#'
        ];
    @endphp
    
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('organization.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-4">Product Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <x-form.input 
                        id="name"
                        name="name"
                        label="Product Name"
                        :value="old('name', $product->name)"
                        required
                        :error="$errors->first('name')"
                    />
                    
                    <!-- SKU -->
                    <x-form.input 
                        id="sku"
                        name="sku"
                        label="SKU"
                        help-text="Stock Keeping Unit - Unique identifier for your product"
                        :value="old('sku', $product->sku)"
                        required
                        :error="$errors->first('sku')"
                    />
                    
                    <!-- Price -->
                    <div>
                        <x-form.input 
                            type="number"
                            id="price"
                            name="price"
                            label="Price"
                            step="0.01"
                            prefix="Rp"
                            :value="old('price', $product->price)"
                            required
                            :error="$errors->first('price')"
                        />
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <x-form.select
                            id="category_id"
                            name="category_id"
                            label="Category"
                            :options="$categories->pluck('name', 'id')->toArray()"
                            :value="old('category_id', $product->category_id)"
                            :error="$errors->first('category_id')"
                        />
                        <div class="mt-1 text-xs text-gray-500">
                            <a href="#" @click.prevent="openModal('new-category-modal')" class="text-sky-600 hover:text-sky-800">
                                + Add new category
                            </a>
                        </div>
                    </div>
                    
                    <!-- Stock Quantity -->
                    <div x-data="{ manageStock: {{ old('manage_stock', $product->stock_quantity !== null) ? 'true' : 'false' }} }">
                        <div class="flex items-center mb-2">
                            <x-form.checkbox
                                id="manage_stock"
                                name="manage_stock"
                                :checked="old('manage_stock', $product->stock_quantity !== null)"
                                value="1"
                                x-model="manageStock"
                                label="Track inventory"
                            />
                        </div>
                        
                        <div x-show="manageStock == true">
                            <x-form.input 
                                type="number"
                                id="stock_quantity"
                                name="stock_quantity"
                                label="Stock Quantity"
                                :value="old('stock_quantity', $product->stock_quantity)"
                                min="0"
                                :error="$errors->first('stock_quantity')"
                            />
                        </div>
                    </div>
                    
                    <!-- Stock Warning Level -->
                    <x-form.input 
                        type="number"
                        id="stock_warning_level"
                        name="stock_warning_level"
                        label="Low Stock Warning Level"
                        help-text="Get notified when stock falls below this number"
                        :value="old('stock_warning_level', $product->stock_warning_level)"
                        min="0"
                        :error="$errors->first('stock_warning_level')"
                    />
                    
                    <!-- Weight -->
                    <div>
                        <x-form.input 
                            type="number"
                            id="weight"
                            name="weight"
                            label="Weight"
                            suffix="gram"
                            step="0.01"
                            :value="old('weight', $product->weight)"
                            :error="$errors->first('weight')"
                        />
                    </div>
                    
                    <!-- Dimensions -->
                    <div class="grid grid-cols-3 gap-2">
                        <x-form.input 
                            type="number"
                            id="width"
                            name="width"
                            label="Width"
                            suffix="cm"
                            step="0.01"
                            :value="old('width', $product->width)"
                            :error="$errors->first('width')"
                        />
                        
                        <x-form.input 
                            type="number"
                            id="height"
                            name="height"
                            label="Height"
                            suffix="cm"
                            step="0.01"
                            :value="old('height', $product->height)"
                            :error="$errors->first('height')"
                        />
                        
                        <x-form.input 
                            type="number"
                            id="depth"
                            name="depth"
                            label="Depth"
                            suffix="cm"
                            step="0.01"
                            :value="old('depth', $product->depth)"
                            :error="$errors->first('depth')"
                        />
                    </div>
                    
                    <!-- Product Image -->
                    <div class="md:col-span-2">
                        <x-form.input 
                            type="file"
                            id="image"
                            name="image"
                            label="Product Image"
                            accept="image/*"
                            help-text="Upload a square image (recommended size: 800x800 pixels)"
                            :error="$errors->first('image')"
                        />
                        
                        @if($product->image)
                            <div class="mt-2 flex items-center">
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-16 w-16 object-cover rounded">
                                <span class="ml-2 text-sm text-gray-500">Current image</span>
                                <label for="remove_image" class="ml-4 flex items-center">
                                    <input type="checkbox" id="remove_image" name="remove_image" value="1" class="rounded border-gray-300 text-sky-600 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Remove image</span>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Description -->
                <x-form.textarea 
                    id="description"
                    name="description"
                    label="Description"
                    rows="4"
                    :error="$errors->first('description')"
                >{{ old('description', $product->description) }}</x-form.textarea>
                
                <!-- Meta Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Information (Optional)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            id="meta_title"
                            name="meta_title"
                            label="Meta Title"
                            :value="old('meta_title', $product->meta_title)"
                            :error="$errors->first('meta_title')"
                        />
                        
                        <x-form.input 
                            id="meta_keywords"
                            name="meta_keywords"
                            label="Meta Keywords"
                            help-text="Separate keywords with commas"
                            :value="old('meta_keywords', $product->meta_keywords)"
                            :error="$errors->first('meta_keywords')"
                        />
                    </div>
                    
                    <div class="mt-4">
                        <x-form.textarea 
                            id="meta_description"
                            name="meta_description"
                            label="Meta Description"
                            rows="2"
                            :error="$errors->first('meta_description')"
                        >{{ old('meta_description', $product->meta_description) }}</x-form.textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <x-button 
                        href="{{ route('organization.products.show', $product) }}" 
                        variant="secondary"
                    >
                        Cancel
                    </x-button>
                    
                    <x-button 
                        type="submit" 
                        variant="primary"
                        icon="fas fa-save"
                    >
                        Update Product
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>
    
    <!-- New Category Modal -->
    <x-modal id="new-category-modal" title="Create New Category">
        <form id="create-category-form" action="{{ route('organization.categories.store') }}" method="POST">
            @csrf
            
            <x-form.input 
                id="category_name"
                name="name"
                label="Category Name"
                required
            />
            
            <x-form.textarea 
                id="category_description"
                name="description"
                label="Description"
                rows="3"
                class="mt-4"
            ></x-form.textarea>
        </form>
        
        <x-slot name="footer">
            <div class="flex justify-end space-x-2">
                <x-button 
                    type="button"
                    variant="secondary"
                    @click="closeModal('new-category-modal')"
                >
                    Cancel
                </x-button>
                
                <x-button 
                    type="submit"
                    form="create-category-form"
                    variant="primary"
                >
                    Create Category
                </x-button>
            </div>
        </x-slot>
    </x-modal>
</x-organization-layout>