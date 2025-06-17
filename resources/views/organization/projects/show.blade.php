<x-organization-layout>
    @section('title', $project->name)
    
    @php
        $breadcrumbs = [
            'Projects' => route('organization.projects.index'),
            $project->name => '#'
        ];
    @endphp
    
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center">
                @if($project->logo)
                    <img src="{{ Storage::url($project->logo) }}" alt="{{ $project->name }}" class="h-14 w-14 rounded-lg object-cover mr-4">
                @else
                    <div class="h-14 w-14 rounded-lg bg-sky-100 flex items-center justify-center mr-4">
                        <span class="text-2xl font-bold text-sky-600">{{ substr($project->name, 0, 1) }}</span>
                    </div>
                @endif
                
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
                    <div class="mt-1 flex flex-wrap gap-2">
                        <x-badge :color="$project->status === 'active' ? 'green' : ($project->status === 'completed' ? 'blue' : 'red')">
                            {{ ucfirst($project->status) }}
                        </x-badge>
                        
                        <x-badge :color="$project->type === 'preorder' ? 'purple' : 'sky'">
                            {{ $project->type === 'preorder' ? 'Pre-Order' : 'Direct Order' }}
                        </x-badge>
                        
                        <span class="text-sm text-gray-500">
                            Created {{ $project->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
                <x-button 
                    href="{{ route('organization.projects.add-products', $project) }}" 
                    variant="primary" 
                    icon="fas fa-box-open"
                >
                    Add Products
                </x-button>
                
                <x-button 
                    href="{{ route('organization.orders.create', ['project_id' => $project->id]) }}" 
                    variant="primary" 
                    icon="fas fa-shopping-cart"
                >
                    New Order
                </x-button>
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <x-button variant="secondary" icon="fas fa-ellipsis-h">
                            Actions
                        </x-button>
                    </x-slot>
                    
                    <x-slot name="content">
                        <a href="{{ route('organization.projects.edit', $project) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                            <i class="fas fa-edit mr-2 text-gray-500"></i> Edit Project
                        </a>
                        
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                            <i class="fas fa-copy mr-2 text-gray-500"></i> Duplicate Project
                        </a>
                        
                        <form action="{{ route('organization.projects.destroy', $project) }}" method="POST" class="block w-full text-left" onsubmit="return confirm('Are you sure? This will permanently delete the project and all associated data.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors duration-150 text-left">
                                <i class="fas fa-trash-alt mr-2"></i> Delete Project
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Project Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description Card -->
            <x-card>
                <div class="prose max-w-none">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Description</h3>
                    <p>{{ $project->description ?: 'No description provided.' }}</p>
                </div>
                
                @if($project->notes)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Internal Notes</h3>
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                            <p class="text-sm text-gray-700">{{ $project->notes }}</p>
                        </div>
                    </div>
                @endif
                
                <div class="mt-6 pt-6 border-t border-gray-200 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Project Timeline</h4>
                                                <p class="mt-1">
                            <span class="text-gray-900 font-medium">Start:</span> {{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}
                        </p>
                        @if($project->type === 'preorder')
                            <p class="mt-1">
                                <span class="text-gray-900 font-medium">End:</span> {{ $project->end_date ? $project->end_date->format('M d, Y') : 'Not set' }}
                                @if($project->end_date && $project->end_date->isPast())
                                    <span class="text-xs text-red-600">(Ended)</span>
                                @elseif($project->end_date)
                                    <span class="text-xs text-gray-500">({{ now()->diffForHumans($project->end_date, ['parts' => 1]) }})</span>
                                @endif
                            </p>
                        @endif
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Created By</h4>
                        <div class="mt-1 flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                @if($project->user->profile_image)
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Storage::url($project->user->profile_image) }}" alt="{{ $project->user->name }}">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-sky-100 flex items-center justify-center">
                                        <span class="text-xs text-sky-600 font-medium">{{ substr($project->user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $project->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $project->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
            
            <!-- Products -->
            <x-card 
                title="Products" 
                :header-actions="view('components.button', [
                    'variant' => 'secondary',
                    'size' => 'sm',
                    'icon' => 'fas fa-plus',
                    'href' => route('organization.projects.add-products', $project),
                    'slot' => 'Add Products'
                ])"
            >
                @if($project->projectProducts->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($project->projectProducts as $projectProduct)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-md bg-gray-100 flex items-center justify-center">
                                                    @if($projectProduct->product->image)
                                                        <img class="h-10 w-10 object-cover rounded" src="{{ Storage::url($projectProduct->product->image) }}" alt="{{ $projectProduct->product->name }}">
                                                    @else
                                                        <i class="fas fa-box text-gray-400"></i>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $projectProduct->product->name }}</div>
                                                    <div class="text-xs text-gray-500">SKU: {{ $projectProduct->product->sku }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Rp {{ number_format($projectProduct->price, 0, ',', '.') }}</div>
                                            @if($projectProduct->price !== $projectProduct->product->price)
                                                <div class="text-xs text-gray-500 line-through">Rp {{ number_format($projectProduct->product->price, 0, ',', '.') }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($project->type === 'preorder')
                                                <span class="text-sm text-gray-900">Pre-order</span>
                                            @elseif($projectProduct->stock_quantity !== null)
                                                <div class="text-sm text-gray-900">{{ $projectProduct->stock_quantity }}</div>
                                                @if($projectProduct->stock_quantity <= $projectProduct->stock_warning_level)
                                                    <div class="text-xs text-red-600">Low stock</div>
                                                @endif
                                            @else
                                                <span class="text-sm text-gray-900">{{ $projectProduct->product->stock_quantity ?? 'N/A' }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $projectProduct->orderItems->count() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger">
                                                    <button class="text-gray-500 hover:text-gray-700">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                </x-slot>
                                                
                                                <x-slot name="content">
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                                        <i class="fas fa-edit mr-2 text-gray-500"></i> Edit
                                                    </a>
                                                    
                                                    <form action="{{ route('organization.projects.remove-product', ['project' => $project->id, 'projectProduct' => $projectProduct->id]) }}" method="POST" class="block w-full text-left" onsubmit="return confirm('Are you sure you want to remove this product?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors duration-150 text-left">
                                                            <i class="fas fa-trash-alt mr-2"></i> Remove
                                                        </button>
                                                    </form>
                                                </x-slot>
                                            </x-dropdown>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                            <i class="fas fa-box-open text-sky-600"></i>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No products</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by adding products to this project.</p>
                        <div class="mt-6">
                            <x-button 
                                href="{{ route('organization.projects.add-products', $project) }}" 
                                variant="primary" 
                                icon="fas fa-plus"
                            >
                                Add Products
                            </x-button>
                        </div>
                    </div>
                @endif
            </x-card>
            
            <!-- Orders -->
            <x-card 
                title="Recent Orders" 
                :header-actions="view('components.button', [
                    'variant' => 'secondary',
                    'size' => 'sm',
                    'icon' => 'fas fa-plus',
                    'href' => route('organization.orders.create', ['project_id' => $project->id]),
                    'slot' => 'New Order'
                ])"
            >
                @if($project->orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($project->orders->take(5) as $order)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-sky-600 font-medium">
                                                <a href="{{ route('organization.orders.show', $order) }}" class="hover:underline">
                                                    {{ $order->order_number }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $order->customer->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->customer->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $order->order_date->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-badge 
                                                :color="$order->status === 'completed' ? 'green' : ($order->status === 'processing' ? 'yellow' : ($order->status === 'cancelled' ? 'red' : 'gray'))" 
                                                size="sm"
                                            >
                                                {{ ucfirst($order->status) }}
                                            </x-badge>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('organization.orders.show', $order) }}" class="text-sky-600 hover:text-sky-900">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($project->orders->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('organization.orders.index', ['project_id' => $project->id]) }}" class="text-sm font-medium text-sky-600 hover:text-sky-800">
                                View all {{ $project->orders->count() }} orders
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-6">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                            <i class="fas fa-shopping-cart text-sky-600"></i>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new order for this project.</p>
                        <div class="mt-6">
                            <x-button 
                                href="{{ route('organization.orders.create', ['project_id' => $project->id]) }}" 
                                variant="primary" 
                                icon="fas fa-plus"
                            >
                                Create Order
                            </x-button>
                        </div>
                    </div>
                @endif
            </x-card>
        </div>
        
        <!-- Stats Column -->
        <div class="space-y-6">
            <!-- Project Stats -->
            <x-card title="Project Statistics">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <span class="text-2xl font-bold text-gray-900">{{ $project->projectProducts->count() }}</span>
                            <p class="text-xs text-gray-500 mt-1">Products</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <span class="text-2xl font-bold text-gray-900">{{ $project->orders->count() }}</span>
                            <p class="text-xs text-gray-500 mt-1">Orders</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <span class="text-2xl font-bold text-gray-900">{{ $project->orders()->has('invoice')->count() }}</span>
                            <p class="text-xs text-gray-500 mt-1">Invoices</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <span class="text-2xl font-bold text-gray-900">
                                @php
                                    $totalRevenue = $project->orders->sum('total_amount');
                                @endphp
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Total Revenue</p>
                        </div>
                    </div>
                    
                    @if($project->type === 'preorder' && $project->end_date)
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Pre-order Timeline</h4>
                            @php
                                $startDate = $project->start_date;
                                $endDate = $project->end_date;
                                $totalDays = $startDate && $endDate ? $startDate->diffInDays($endDate) : 0;
                                $daysElapsed = $startDate ? min($startDate->diffInDays(now()), $totalDays) : 0;
                                $progressPercentage = $totalDays > 0 ? min(($daysElapsed / $totalDays) * 100, 100) : 0;
                            @endphp
                            
                            <div class="relative pt-1">
                                <div class="overflow-hidden h-2 mb-1 text-xs flex rounded bg-gray-200">
                                    <div style="width:{{ $progressPercentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-sky-500"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>{{ $project->start_date ? $project->start_date->format('M d') : 'Not set' }}</span>
                                    <span>{{ $project->end_date ? $project->end_date->format('M d') : 'Not set' }}</span>
                                </div>
                                @if($project->end_date && $project->end_date->isFuture())
                                    <p class="text-xs text-center text-gray-500 mt-1">
                                        {{ $project->end_date->diffForHumans(['parts' => 1]) }} remaining
                                    </p>
                                @elseif($project->end_date)
                                    <p class="text-xs text-center text-red-500 mt-1">
                                        Pre-order period has ended
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </x-card>
            
            <!-- Quick Actions -->
            <x-card title="Quick Actions">
                <div class="space-y-3">
                    <x-button 
                        href="{{ route('organization.orders.create', ['project_id' => $project->id]) }}" 
                        variant="primary" 
                        icon="fas fa-shopping-cart"
                        full-width="true"
                    >
                        Create New Order
                    </x-button>
                    
                    <x-button 
                        href="{{ route('organization.projects.add-products', $project) }}" 
                        variant="secondary" 
                        icon="fas fa-box-open"
                        full-width="true"
                    >
                        Add Products
                    </x-button>
                    
                    <x-button 
                        href="{{ route('organization.projects.edit', $project) }}" 
                        variant="secondary" 
                        icon="fas fa-edit"
                        full-width="true"
                    >
                        Edit Project
                    </x-button>
                    
                    @if($project->status === 'active')
                        <form action="{{ route('organization.projects.update-status', $project) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <x-button 
                                type="submit"
                                variant="secondary" 
                                icon="fas fa-check-circle"
                                full-width="true"
                            >
                                Mark as Completed
                            </x-button>
                        </form>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</x-organization-layout>
                