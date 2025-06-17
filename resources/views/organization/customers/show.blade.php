<x-organization-layout>
    @section('title', $customer->name)
    
    @php
        $breadcrumbs = [
            'Customers' => route('organization.customers.index'),
            $customer->name => '#'
        ];
    @endphp
    
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h2>
                <div class="flex items-center mt-1 text-sm text-gray-500">
                    <span>Added {{ $customer->created_at->format('M d, Y') }}</span>
                    @if($customer->orders->count() > 0)
                        <span class="mx-2">•</span>
                        <span>{{ $customer->orders->count() }} orders</span>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
                <x-button 
                    href="{{ route('organization.orders.create', ['customer_id' => $customer->id]) }}" 
                    variant="primary"
                    icon="fas fa-shopping-cart"
                >
                    New Order
                </x-button>
                
                <x-button 
                    href="{{ route('organization.customers.edit', $customer) }}" 
                    variant="secondary"
                    icon="fas fa-edit"
                >
                    Edit
                </x-button>
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <x-button variant="secondary" icon="fas fa-ellipsis-h">
                            More
                        </x-button>
                    </x-slot>
                    
                    <x-slot name="content">
                        <form action="{{ route('organization.customers.destroy', $customer) }}" method="POST" class="block w-full text-left" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors duration-150 text-left {{ $customer->orders->count() > 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $customer->orders->count() > 0 ? 'disabled' : '' }}>
                                <i class="fas fa-trash-alt mr-2"></i> Delete Customer
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <!-- Customer Information Cards -->
            <div class="space-y-6">
                <!-- Basic Information -->
                <x-card title="Contact Information">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($customer->email)
                                    <a href="mailto:{{ $customer->email }}" class="text-sky-600 hover:text-sky-800">
                                        {{ $customer->email }}
                                    </a>
                                @else
                                    <span class="text-gray-400">Not provided</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($customer->phone_number)
                                    <a href="tel:{{ $customer->phone_number }}" class="text-sky-600 hover:text-sky-800">
                                        {{ $customer->phone_number }}
                                    </a>
                                @else
                                    <span class="text-gray-400">Not provided</span>
                                @endif
                            </dd>
                        </div>
                        
                        @if($customer->gender)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ ucfirst($customer->gender) }}
                                </dd>
                            </div>
                        @endif
                        
                        @if($customer->birthdate)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $customer->birthdate->format('M d, Y') }} 
                                    <span class="text-gray-500">({{ $customer->birthdate->age }} years old)</span>
                                </dd>
                            </div>
                        @endif
                        
                        @if($customer->id_number)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $customer->id_number }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </x-card>
                
                <!-- Address -->
                <x-card title="Address Information">
                    @if($customer->address || $customer->city || $customer->state || $customer->postal_code || $customer->country)
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                            @if($customer->address)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Full Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $customer->address }}
                                    </dd>
                                </div>
                            @endif
                            
                            @if($customer->city)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">City</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $customer->city }}
                                    </dd>
                                </div>
                            @endif
                            
                            @if($customer->state)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">State/Province</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $customer->state }}
                                    </dd>
                                </div>
                            @endif
                            
                            @if($customer->postal_code)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Postal Code</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $customer->postal_code }}
                                    </dd>
                                </div>
                            @endif
                            
                            @if($customer->country)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Country</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $customer->country }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    @else
                        <p class="text-sm text-gray-500">No address information provided.</p>
                    @endif
                </x-card>
                
                <!-- Additional Information -->
                <x-card title="Additional Information">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        @if($customer->source)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Customer Source</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $customer->source)) }}
                                </dd>
                            </div>
                        @endif
                        
                        @if($customer->notes)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                    {{ $customer->notes }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </x-card>
                
                <!-- Recent Orders -->
                <x-card
                    title="Orders" 
                    :header-actions="view('components.button', [
                        'href' => route('organization.orders.create', ['customer_id' => $customer->id]),
                        'variant' => 'secondary',
                        'size' => 'sm',
                        'icon' => 'fas fa-plus',
                        'slot' => 'New Order'
                    ])"
                >
                    @if($customer->orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Order #
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($customer->orders()->latest('order_date')->take(5)->get() as $order)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('organization.orders.show', $order) }}" class="text-sm font-medium text-sky-600 hover:text-sky-800">
                                                    {{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->order_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <x-badge :color="$order->status === 'completed' ? 'green' : ($order->status === 'processing' ? 'yellow' : ($order->status === 'cancelled' ? 'red' : 'gray'))" size="sm">
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
                        
                        @if($customer->orders->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('organization.orders.index', ['customer_id' => $customer->id]) }}" class="text-sm font-medium text-sky-600 hover:text-sky-800">
                                    View all {{ $customer->orders->count() }} orders
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                                <i class="fas fa-shopping-cart text-sky-600"></i>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No orders yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new order for this customer.</p>
                            <div class="mt-6">
                                <x-button 
                                    href="{{ route('organization.orders.create', ['customer_id' => $customer->id]) }}" 
                                    variant="primary"
                                    icon="fas fa-plus"
                                    size="sm"
                                >
                                    Create Order
                                </x-button>
                            </div>
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
        
        <!-- Right Sidebar -->
        <div class="space-y-6">
            <!-- Customer Stats -->
            <x-card title="Customer Statistics">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <span class="text-2xl font-bold text-gray-900">{{ $customer->orders->count() }}</span>
                            <p class="text-xs text-gray-500 mt-1">Orders</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <span class="text-2xl font-bold text-gray-900">{{ $customer->invoices_count }}</span>
                            <p class="text-xs text-gray-500 mt-1">Invoices</p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-500">Total Spent</span>
                            <span class="text-lg font-bold text-gray-900">
                                Rp {{ number_format($totalSpent, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Average Order</span>
                            <span class="text-sm font-medium text-gray-900">
                                Rp {{ $customer->orders->count() > 0 ? number_format($totalSpent / $customer->orders->count(), 0, ',', '.') : 0 }}
                            </span>
                        </div>
                    </div>
                    
                    @if($customer->orders->count() > 0)
                        <div class="pt-2">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-500">First Order</span>
                                <span class="text-sm text-gray-900">{{ $firstOrder->order_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Last Order</span>
                                <span class="text-sm text-gray-900">{{ $lastOrder->order_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </x-card>
            
            <!-- Quick Actions -->
            <x-card title="Quick Actions">
                <div class="space-y-3">
                    <x-button 
                        href="{{ route('organization.orders.create', ['customer_id' => $customer->id]) }}" 
                        variant="primary" 
                        icon="fas fa-shopping-cart"
                        full-width="true"
                    >
                        Create New Order
                    </x-button>
                    
                    <x-button 
                        href="{{ route('organization.customers.edit', $customer) }}" 
                        variant="secondary" 
                        icon="fas fa-edit"
                        full-width="true"
                    >
                        Edit Customer
                    </x-button>
                    
                    @if($customer->email)
                        <x-button 
                            href="mailto:{{ $customer->email }}" 
                            variant="secondary" 
                            icon="fas fa-envelope"
                            full-width="true"
                        >
                            Send Email
                        </x-button>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</x-organization-layout>