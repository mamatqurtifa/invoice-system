<x-organization-layout>
    @section('title', 'Products')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Products</h2>
            <p class="mt-1 text-sm text-gray-600">Manage your product catalog</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <x-button 
                href="{{ route('organization.products.create') }}" 
                icon="fas fa-plus" 
                variant="primary"
            >
                New Product
            </x-button>
        </div>
    </div>
    
    <!-- Search & Filters -->
    <x-card class="mb-6">
        <form action="{{ route('organization.products.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <x-form.input
                    id="search"
                    name="search"
                    placeholder="Search products..."
                    :value="request()->get('search')"
                    leading-icon="fas fa-search"
                />
                
                <!-- Category -->
                <x-form.select
                    id="category_id"
                    name="category_id"
                    :options="$categories->pluck('name', 'id')->toArray()"
                    :value="request()->get('category_id')"
                    placeholder="All Categories"
                />
                
                <!-- Stock -->
                <x-form.select
                    id="stock"
                    name="stock"
                    :options="[
                        'in_stock' => 'In Stock',
                        'out_of_stock' => 'Out of Stock',
                        'low_stock' => 'Low Stock'
                    ]"
                    :value="request()->get('stock')"
                    placeholder="All Stock Status"
                />
                
                <!-- Sort -->
                <x-form.select
                    id="sort"
                    name="sort"
                    :options="[
                        'newest' => 'Newest First',
                        'oldest' => 'Oldest First',
                        'name_asc' => 'Name (A-Z)',
                        'name_desc' => 'Name (Z-A)',
                        'price_asc' => 'Price (Low to High)',
                        'price_desc' => 'Price (High to Low)'
                    ]"
                    :value="request()->get('sort', 'newest')"
                />
            </div>
            
            <div class="flex justify-end gap-2">
                <x-button type="submit" variant="primary" icon="fas fa-filter">
                    Filter
                </x-button>
                
                <x-button href="{{ route('organization.products.index') }}" variant="secondary" icon="fas fa-sync">
                    Reset
                </x-button>
            </div>
        </form>
    </x-card>
    
    <!-- Products Grid or Table -->
    <div x-data="{ view: 'grid' }" class="space-y-4">
        <!-- View Toggle -->
        <div class="flex justify-end space-x-2">
            <div class="inline-flex rounded-md shadow-sm">
                <button 
                    @click="view = 'grid'" 
                    :class="{'bg-sky-100 text-sky-700': view === 'grid', 'bg-white text-gray-700': view !== 'grid'}"
                    class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-l-lg hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-sky-500 focus:border-sky-500 transition-colors duration-200"
                >
                    <i class="fas fa-th-large mr-1"></i> Grid
                </button>
                <button 
                    @click="view = 'table'" 
                    :class="{'bg-sky-100 text-sky-700': view === 'table', 'bg-white text-gray-700': view !== 'table'}"
                    class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-r-lg hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-sky-500 focus:border-sky-500 transition-colors duration-200"
                >
                    <i class="fas fa-list mr-1"></i> Table
                </button>
            </div>
        </div>
        
        @if(count($products) > 0)
            <!-- Grid View -->
            <div x-show="view === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <x-card class="h-full flex flex-col hover:shadow transition-shadow duration-300">
                        <!-- Product Image -->
                        <div class="h-48 bg-gray-100 relative overflow-hidden">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <i class="fas fa-box-open text-4xl text-gray-300"></i>
                                </div>
                            @endif
                            
                            @if($product->stock_quantity !== null && $product->stock_quantity <= 0)
                                <div class="absolute top-2 right-2">
                                    <x-badge color="red" size="sm">Out of Stock</x-badge>
                                </div>
                            @elseif($product->stock_quantity !== null && $product->stock_quantity <= $product->stock_warning_level)
                                <div class="absolute top-2 right-2">
                                    <x-badge color="yellow" size="sm">Low Stock</x-badge>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Details -->
                        <div class="p-4 flex-grow flex flex-col">
                            <h3 class="text-sm font-medium text-gray-900 mb-1">
                                <a href="{{ route('organization.products.show', $product) }}" class="hover:text-sky-600">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-500 mb-2">SKU: {{ $product->sku }}</p>
                            
                            @if($product->category)
                                <p class="text-xs text-gray-500 mb-auto">
                                    <span class="bg-gray-100 text-gray-600 rounded-full px-2 py-0.5">
                                        {{ $product->category->name }}
                                    </span>
                                </p>
                            @endif
                            
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                @if($product->stock_quantity !== null)
                                    <span class="text-xs text-gray-500">
                                        Stock: {{ $product->stock_quantity }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="border-t border-gray-100 p-4 mt-auto flex justify-end space-x-2">
                            <a href="{{ route('organization.products.edit', $product) }}" class="text-yellow-600 hover:text-yellow-700" title="Edit product">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('organization.products.show', $product) }}" class="text-sky-600 hover:text-sky-700" title="View product">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </x-card>
                @endforeach
            </div>
            
            <!-- Table View -->
            <div x-show="view === 'table'" class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($product->image)
                                                    <img class="h-10 w-10 rounded object-cover" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded bg-gray-100 flex items-center justify-center">
                                                        <i class="fas fa-box text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('organization.products.show', $product) }}" class="hover:text-sky-600">
                                                        {{ $product->name }}
                                                    </a>
                                                </div>
                                                <div class="text-xs text-gray-500 max-w-xs truncate">{{ $product->description }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $product->sku }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $product->category ? $product->category->name : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($product->stock_quantity === null)
                                            <span class="text-sm text-gray-500">Unlimited</span>
                                        @elseif($product->stock_quantity <= 0)
                                            <x-badge color="red" size="sm">Out of Stock</x-badge>
                                        @elseif($product->stock_quantity <= $product->stock_warning_level)
                                            <span class="text-sm text-yellow-600">{{ $product->stock_quantity }} <span class="text-xs">(Low)</span></span>
                                        @else
                                            <span class="text-sm text-gray-500">{{ $product->stock_quantity }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('organization.products.edit', $product) }}" class="text-yellow-600 hover:text-yellow-900 mr-3" title="Edit product">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('organization.products.show', $product) }}" class="text-sky-600 hover:text-sky-900" title="View product">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                <x-pagination :paginator="$products" />
            </div>
        @else
            <x-card>
                <div class="text-center py-10">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                        <i class="fas fa-box-open text-sky-600"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
                    <div class="mt-6">
                        <x-button href="{{ route('organization.products.create') }}" variant="primary" icon="fas fa-plus">
                            New Product
                        </x-button>
                    </div>
                </div>
            </x-card>
        @endif
    </div>
    
    <!-- Bulk Import/Export -->
    <x-card title="Import & Export" class="mt-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-2">Import Products</h4>
                <p class="text-sm text-gray-500 mb-4">
                    You can import products from a CSV file. Download our template and follow the format.
                </p>
                <div class="flex flex-wrap gap-2">
                    <x-button href="{{ route('organization.products.download-template') }}" variant="secondary" size="sm" icon="fas fa-download">
                        Download Template
                    </x-button>
                    <x-button href="{{ route('organization.products.import-form') }}" variant="primary" size="sm" icon="fas fa-file-import">
                        Import Products
                    </x-button>
                </div>
            </div>
            
            <div class="border-t md:border-t-0 md:border-l border-gray-200 pt-6 md:pt-0 md:pl-6">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Export Products</h4>
                <p class="text-sm text-gray-500 mb-4">
                    Export your product catalog in various formats.
                </p>
                <div class="flex flex-wrap gap-2">
                    <x-button href="{{ route('organization.products.export', ['format' => 'csv']) }}" variant="secondary" size="sm" icon="fas fa-file-csv">
                        Export CSV
                    </x-button>
                    <x-button href="{{ route('organization.products.export', ['format' => 'xlsx']) }}" variant="secondary" size="sm" icon="fas fa-file-excel">
                        Export Excel
                    </x-button>
                    <x-button href="{{ route('organization.products.export', ['format' => 'pdf']) }}" variant="secondary" size="sm" icon="fas fa-file-pdf">
                        Export PDF
                    </x-button>
                </div>
            </div>
        </div>
    </x-card>
</x-organization-layout>