<x-organization-layout>
    @section('title', 'Add Products to Project')
    
    @php
        $breadcrumbs = [
            'Projects' => route('organization.projects.index'),
            $project->name => route('organization.projects.show', $project),
            'Add Products' => '#'
        ];
    @endphp
    
    <div x-data="productSelector()" class="space-y-6">
        <x-card>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Add Products to {{ $project->name }}</h2>
                    <p class="text-sm text-gray-600 mt-1">Select products to add to this project</p>
                </div>
                
                <div class="mt-4 md:mt-0 flex items-center">
                    <span class="mr-2 text-sm text-gray-700">Selected <span x-text="selectedProducts.length" class="font-medium"></span> products</span>
                    
                    <x-button
                        @click="submitForm"
                        variant="primary"
                        :disabled="true"
                        x-bind:disabled="selectedProducts.length === 0"
                    >
                        Add to Project
                    </x-button>
                </div>
            </div>
        </x-card>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Left Sidebar (Filters) -->
            <div class="lg:col-span-1">
                <x-card title="Filters">
                    <div class="space-y-4">
                        <x-form.input
                            id="searchInput"
                            x-model="search"
                            placeholder="Search products..."
                            leading-icon="fas fa-search"
                        />
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categories</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                                @foreach($categories as $category)
                                    <x-form.checkbox
                                        id="category-{{ $category->id }}"
                                        x-model="selectedCategories"
                                        :value="$category->id"
                                        :label="$category->name . ' (' . $category->products_count . ')'"
                                    />
                                @endforeach
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                            <div class="grid grid-cols-2 gap-2">
                                <x-form.input
                                    id="minPrice"
                                    x-model="minPrice"
                                    placeholder="Min"
                                    prefix="Rp"
                                />
                                
                                <x-form.input
                                    id="maxPrice"
                                    x-model="maxPrice"
                                    placeholder="Max"
                                    prefix="Rp"
                                />
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <x-button
                                variant="secondary"
                                icon="fas fa-sync"
                                full-width="true"
                                @click="resetFilters"
                            >
                                Reset Filters
                            </x-button>
                        </div>
                    </div>
                </x-card>
            </div>
            
            <!-- Right Column (Product Grid) -->
            <div class="lg:col-span-3">
                <!-- Product Grid -->
                <x-card>
                    <div class="mb-4 flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            Showing <span x-text="filteredProducts.length"></span> products
                        </div>
                        <div>
                            <x-form.select
                                id="sort"
                                x-model="sortOrder"
                                :options="[
                                    'name_asc' => 'Name (A-Z)',
                                    'name_desc' => 'Name (Z-A)',
                                    'price_asc' => 'Price (Low to High)',
                                    'price_desc' => 'Price (High to Low)',
                                ]"
                            />
                        </div>
                    </div>
                    
                    <!-- Product Grid -->
                    <div>
                        <template x-if="isLoading">
                            <div class="py-12 text-center">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-sky-600"></div>
                                <p class="mt-2 text-sm text-gray-500">Loading products...</p>
                            </div>
                        </template>
                        
                        <template x-if="!isLoading && filteredProducts.length === 0">
                            <div class="py-12 text-center">
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                                <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filters</p>
                            </div>
                        </template>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" x-show="filteredProducts.length > 0">
                            <template x-for="product in filteredProducts" :key="product.id">
                                <div class="relative border border-gray-200 rounded-lg overflow-hidden hover:shadow-sm transition-shadow duration-200">
                                    <div class="absolute top-2 right-2 z-10">
                                        <div class="relative">
                                            <input 
                                                type="checkbox" 
                                                :id="'product_' + product.id" 
                                                :value="product.id"
                                                x-model="selectedProducts"
                                                class="h-5 w-5 rounded border-gray-300 text-sky-600 focus:ring-sky-500"
                                            >
                                        </div>
                                    </div>
                                    
                                    <div class="h-40 bg-gray-100 relative overflow-hidden">
                                        <template x-if="product.image">
                                            <img 
                                                :src="product.image_url" 
                                                :alt="product.name"
                                                class="h-full w-full object-cover"
                                            >
                                        </template>
                                        <template x-if="!product.image">
                                            <div class="h-full w-full flex items-center justify-center">
                                                <i class="fas fa-box-open text-3xl text-gray-300"></i>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <div class="p-4">
                                        <h3 class="text-sm font-medium text-gray-900" x-text="product.name"></h3>
                                        <p class="mt-1 text-xs text-gray-500" x-text="'SKU: ' + product.sku"></p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900" x-text="formatCurrency(product.price)"></p>
                                            <p class="text-xs text-gray-500" x-text="product.stock_quantity !== null ? 'Stock: ' + product.stock_quantity : 'In stock'"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
        
        <!-- Product Price Form -->
        <form id="add-products-form" action="{{ route('organization.projects.store-products', $project) }}" method="POST">
            @csrf
            
            <x-modal id="price-modal" max-width="xl" title="Set Product Prices">
                <div class="space-y-4">
                    <p class="text-sm text-gray-500">Set the price for each product in this project. Leave the same as default to use the original product price.</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Original Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Price</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="price-table-body">
                                <!-- Will be populated dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <x-slot name="footer">
                    <div class="flex justify-end space-x-2">
                        <x-button 
                            type="button"
                            variant="secondary"
                            @click="closeModal('price-modal')"
                        >
                            Cancel
                        </x-button>
                        
                        <x-button 
                            type="submit"
                            form="add-products-form"
                            variant="primary"
                        >
                            Add Products
                        </x-button>
                    </div>
                </x-slot>
            </x-modal>
        </form>
    </div>
    
    @push('scripts')
    <script>
        function productSelector() {
            return {
                products: @json($products),
                selectedProducts: [],
                selectedCategories: [],
                search: '',
                minPrice: '',
                maxPrice: '',
                sortOrder: 'name_asc',
                isLoading: false,
                
                get filteredProducts() {
                    let filtered = this.products;
                    
                    // Filter by search term
                    if (this.search.trim() !== '') {
                        const searchLower = this.search.toLowerCase();
                        filtered = filtered.filter(product => 
                            product.name.toLowerCase().includes(searchLower) || 
                            product.sku.toLowerCase().includes(searchLower)
                        );
                    }
                    
                    // Filter by categories
                    if (this.selectedCategories.length > 0) {
                        filtered = filtered.filter(product => 
                            product.category_ids.some(id => this.selectedCategories.includes(id))
                        );
                    }
                    
                    // Filter by price range
                    if (this.minPrice) {
                        filtered = filtered.filter(product => product.price >= parseFloat(this.minPrice));
                    }
                    
                    if (this.maxPrice) {
                        filtered = filtered.filter(product => product.price <= parseFloat(this.maxPrice));
                    }
                    
                    // Sort products
                    filtered = [...filtered].sort((a, b) => {
                        switch (this.sortOrder) {
                            case 'name_asc':
                                return a.name.localeCompare(b.name);
                            case 'name_desc':
                                return b.name.localeCompare(a.name);
                            case 'price_asc':
                                return a.price - b.price;
                            case 'price_desc':
                                return b.price - a.price;
                            default:
                                return 0;
                        }
                    });
                    
                    return filtered;
                },
                
                resetFilters() {
                    this.search = '';
                    this.selectedCategories = [];
                    this.minPrice = '';
                    this.maxPrice = '';
                    this.sortOrder = 'name_asc';
                },
                
                formatCurrency(amount) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
                },
                
                submitForm() {
                    if (this.selectedProducts.length === 0) return;
                    
                    const tableBody = document.getElementById('price-table-body');
                    tableBody.innerHTML = '';
                    
                    this.selectedProducts.forEach(productId => {
                        const product = this.products.find(p => p.id === productId);
                        if (!product) return;
                        
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50';
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">${product.name}</div>
                                    <input type="hidden" name="product_ids[]" value="${product.id}">
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${this.formatCurrency(product.price)}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" name="prices[]" value="${product.price}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                    
                    openModal('price-modal');
                }
            }
        }
    </script>
    @endpush
</x-organization-layout>