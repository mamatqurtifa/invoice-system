<x-organization-layout>
    @section('title', $product->name)
    
    @php
        $breadcrumbs = [
            'Products' => route('organization.products.index'),
            $product->name => '#'
        ];
    @endphp
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">SKU: {{ $product->sku }}</p>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
            <x-button 
                href="{{ route('organization.products.edit', $product) }}" 
                variant="primary"
                icon="fas fa-edit"
            >
                Edit Product
            </x-button>
            
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <x-button variant="secondary" icon="fas fa-ellipsis-h">
                        Actions
                    </x-button>
                </x-slot>
                
                <x-slot name="content">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                        <i class="fas fa-copy mr-2 text-gray-500"></i> Duplicate
                    </a>
                    
                    <form action="{{ route('organization.products.destroy', $product) }}" method="POST" class="block w-full text-left" onsubmit="return confirm('Are you sure you want to delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors duration-150 text-left">
                            <i class="fas fa-trash-alt mr-2"></i> Delete
                        </button>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Details -->
        <x-card class="lg:col-span-2">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Product Image -->
                <div class="w-full md:w-1/3 flex-shrink-0">
                    <div class="bg-gray-100 rounded-lg overflow-hidden aspect-square">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center">
                                <i class="fas fa-box-open text-4xl text-gray-300"></i>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Product Information -->
                <div class="w-full md:w-2/3 flex-grow">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Details</h3>
                            
                            <dl class="mt-2 divide-y divide-gray-200">
                                <div class="flex justify-between py-3 text-sm">
                                    <dt class="text-gray-500">Price</dt>
                                    <dd class="text-gray-900 font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</dd>
                                </div>
                                
                                <div class="flex justify-between py-3 text-sm">
                                    <dt class="text-gray-500">Category</dt>
                                    <dd class="text-gray-900">{{ $product->category ? $product->category->name : 'N/A' }}</dd>
                                </div>
                                
                                <div class="flex justify-between py-3 text-sm">
                                    <dt class="text-gray-500">Stock</dt>
                                    <dd class="text-gray-900">
                                        @if($product->stock_quantity === null)
                                            <span>Unlimited</span>
                                        @elseif($product->stock_quantity <= 0)
                                            <x-badge color="red" size="sm">Out of Stock</x-badge>
                                        @elseif($product->stock_quantity <= $product->stock_warning_level)
                                            <span class="text-yellow-600">{{ $product->stock_quantity }} <span class="text-xs">(Low)</span></span>
                                        @else
                                            <span>{{ $product->stock_quantity }}</span>
                                        @endif
                                    </dd>
                                </div>
                                
                                @if($product->weight)
                                    <div class="flex justify-between py-3 text-sm">
                                        <dt class="text-gray-500">Weight</dt>
                                        <dd class="text-gray-900">{{ $product->weight }} gram</dd>
                                    </div>
                                @endif
                                
                                @if($product->width || $product->height || $product->depth)
                                    <div class="flex justify-between py-3 text-sm">
                                        <dt class="text-gray-500">Dimensions</dt>
                                        <dd class="text-gray-900">
                                            {{ $product->width ?? 0 }} × {{ $product->height ?? 0 }} × {{ $product->depth ?? 0 }} cm
                                        </dd>
                                    </div>
                                @endif
                                
                                <div class="flex justify-between py-3 text-sm">
                                    <dt class="text-gray-500">Created</dt>
                                    <dd class="text-gray-900">{{ $product->created_at->format('M d, Y') }}</dd>
                                </div>
                                
                                <div class="flex justify-between py-3 text-sm">
                                    <dt class="text-gray-500">Last Updated</dt>
                                    <dd class="text-gray-900">{{ $product->updated_at->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Description</h3>
                <div class="mt-3 text-gray-600 prose">
                    {{ $product->description ?: 'No description provided.' }}
                </div>
            </div>
            
            <!-- SEO Information -->
            @if($product->meta_title || $product->meta_description || $product->meta_keywords)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">SEO Information</h3>
                    <div class="mt-3 space-y-3">
                        @if($product->meta_title)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Meta Title</h4>
                                <p class="text-gray-600">{{ $product->meta_title }}</p>
                            </div>
                        @endif
                        
                        @if($product->meta_description)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Meta Description</h4>
                                <p class="text-gray-600">{{ $product->meta_description }}</p>
                            </div>
                        @endif
                        
                        @if($product->meta_keywords)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Meta Keywords</h4>
                                <p class="text-gray-600">{{ $product->meta_keywords }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </x-card>
        
        <!-- Stats & Actions -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <x-card title="Product Stats">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <span class="text-2xl font-bold text-gray-900">{{ $productProjects->count() }}</span>
                        <p class="text-xs text-gray-500 mt-1">Projects</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <span class="text-2xl font-bold text-gray-900">{{ $ordersCount }}</span>
                        <p class="text-xs text-gray-500 mt-1">Orders</p>
                    </div>
                </div>
                
                <div class="mt-4 grid grid-cols-1 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <span class="text-2xl font-bold text-gray-900">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">Total Revenue</p>
                    </div>
                </div>
            </x-card>
            
            <!-- Quick Actions -->
            <x-card title="Quick Actions">
                <div class="space-y-3">
                    <x-button 
                        variant="primary" 
                        icon="fas fa-plus"
                        full-width="true"
                        @click="openModal('add-to-project-modal')"
                    >
                        Add to Project
                    </x-button>
                    
                    <x-button 
                        href="{{ route('organization.products.edit', $product) }}" 
                        variant="secondary" 
                        icon="fas fa-edit"
                        full-width="true"
                    >
                        Edit Product
                    </x-button>
                    
                    @if($product->stock_quantity !== null)
                        <x-button 
                            variant="secondary" 
                            icon="fas fa-boxes"
                            full-width="true"
                            @click="openModal('update-stock-modal')"
                        >
                            Update Stock
                        </x-button>
                    @endif
                </div>
            </x-card>
            
            <!-- Projects using this product -->
            <x-card title="Projects Using This Product">
                @if($productProjects->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($productProjects as $projectProduct)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <a href="{{ route('organization.projects.show', $projectProduct->project) }}" class="text-sm font-medium text-sky-600 hover:text-sky-800">
                                        {{ $projectProduct->project->name }}
                                    </a>
                                    <div class="flex items-center mt-0.5 text-xs text-gray-500">
                                        <x-badge :color="$projectProduct->project->status === 'active' ? 'green' : ($projectProduct->project->status === 'completed' ? 'blue' : 'red')" size="xs">
                                            {{ ucfirst($projectProduct->project->status) }}
                                        </x-badge>
                                        <span class="mx-2">•</span>
                                        <span>Price: Rp {{ number_format($projectProduct->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500">{{ $projectProduct->orderItems->count() }} orders</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">This product is not used in any projects yet.</p>
                        <div class="mt-2">
                            <x-button 
                                variant="secondary" 
                                icon="fas fa-plus"
                                size="sm"
                                @click="openModal('add-to-project-modal')"
                            >
                                Add to Project
                            </x-button>
                        </div>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
    
    <!-- Add to Project Modal -->
    <x-modal id="add-to-project-modal" title="Add to Project">
        <form id="add-to-project-form" action="{{ route('organization.products.add-to-project', $product) }}" method="POST">
            @csrf
            
            <x-form.select
                id="project_id"
                name="project_id"
                label="Select Project"
                :options="$availableProjects->pluck('name', 'id')->toArray()"
                required
            />
            
            <x-form.input 
                type="number"
                id="project_price"
                name="price"
                label="Product Price in Project"
                prefix="Rp"
                :value="$product->price"
                help-text="You can set a different price for this product in the selected project"
                class="mt-4"
                required
            />
        </form>
        
        <x-slot name="footer">
            <div class="flex justify-end space-x-2">
                <x-button 
                    type="button"
                    variant="secondary"
                    @click="closeModal('add-to-project-modal')"
                >
                    Cancel
                </x-button>
                
                <x-button 
                    type="submit"
                    form="add-to-project-form"
                    variant="primary"
                >
                    Add to Project
                </x-button>
            </div>
        </x-slot>
    </x-modal>
    
    <!-- Update Stock Modal -->
    <x-modal id="update-stock-modal" title="Update Stock">
        <form id="update-stock-form" action="{{ route('organization.products.update-stock', $product) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-700">Current Stock: <strong>{{ $product->stock_quantity }}</strong></p>
                </div>
                
                <x-form.select
                    id="stock_adjustment_type"
                    name="adjustment_type"
                    label="Adjustment Type"
                    :options="[
                        'add' => 'Add to Stock',
                        'subtract' => 'Subtract from Stock',
                        'set' => 'Set Exact Value'
                    ]"
                    required
                />
                
                <x-form.input 
                    type="number"
                    id="stock_adjustment_quantity"
                    name="adjustment_quantity"
                    label="Quantity"
                    min="0"
                    required
                />
                
                <x-form.textarea 
                    id="stock_adjustment_reason"
                    name="adjustment_reason"
                    label="Reason for Adjustment"
                    rows="2"
                    placeholder="e.g., New shipment received, Inventory count correction"
                ></x-form.textarea>
            </div>
        </form>
        
        <x-slot name="footer">
            <div class="flex justify-end space-x-2">
                <x-button 
                    type="button"
                    variant="secondary"
                    @click="closeModal('update-stock-modal')"
                >
                    Cancel
                </x-button>
                
                <x-button 
                    type="submit"
                    form="update-stock-form"
                    variant="primary"
                >
                    Update Stock
                </x-button>
            </div>
        </x-slot>
    </x-modal>
</x-organization-layout>