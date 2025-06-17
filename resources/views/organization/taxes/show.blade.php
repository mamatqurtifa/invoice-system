<x-organization-layout>
    @section('title', 'Tax Rate: ' . $tax->name)
    
    @php
        $breadcrumbs = [
            'Tax Rates' => route('organization.taxes.index'),
            $tax->name => '#'
        ];
    @endphp
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $tax->name }}</h2>
            <div class="flex items-center mt-1 space-x-2">
                <x-badge :color="$tax->is_active ? 'green' : 'gray'">
                    {{ $tax->is_active ? 'Active' : 'Inactive' }}
                </x-badge>
                
                <span class="text-sm text-gray-500">{{ $tax->rate }}%</span>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
            <x-button 
                href="{{ route('organization.taxes.edit', $tax) }}" 
                variant="primary"
                icon="fas fa-edit"
            >
                Edit
            </x-button>
            
            <form action="{{ route('organization.taxes.toggle', $tax) }}" method="POST">
                @csrf
                @method('PATCH')
                <x-button 
                    type="submit"
                    variant="{{ $tax->is_active ? 'danger' : 'success' }}"
                    icon="{{ $tax->is_active ? 'fas fa-toggle-off' : 'fas fa-toggle-on' }}"
                >
                    {{ $tax->is_active ? 'Deactivate' : 'Activate' }}
                </x-button>
            </form>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Tax Details -->
            <x-card title="Tax Rate Details">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Tax Rate</h4>
                        <p class="text-sm font-medium text-gray-900">{{ $tax->rate }}%</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Tax Type</h4>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $tax->type === 'inclusive' ? 'Inclusive (Price includes tax)' : 'Exclusive (Tax added to price)' }}
                        </p>
                    </div>
                    
                    @if($tax->description)
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                            <p class="text-sm text-gray-900">{{ $tax->description }}</p>
                        </div>
                    @endif
                </div>
                
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Region Settings</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Country</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $tax->country ?: 'All Countries' }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Region</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $tax->region ?: 'All Regions' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Additional Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Tax Number</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $tax->tax_number ?: 'N/A' }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Compound Tax</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $tax->is_compound ? 'Yes' : 'No' }}
                                @if($tax->is_compound)
                                    <span class="text-xs text-gray-500 block mt-1">This tax is applied after other taxes</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </x-card>
            
            <!-- Usage Statistics -->
            <x-card title="Usage Statistics">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <span class="text-2xl font-bold text-gray-900">{{ $ordersCount }}</span>
                        <p class="text-xs text-gray-500 mt-1">Orders With This Tax</p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <span class="text-2xl font-bold text-gray-900">{{ $invoicesCount }}</span>
                        <p class="text-xs text-gray-500 mt-1">Invoices With This Tax</p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalTaxCollected, 0, ',', '.') }}</span>
                        <p class="text-xs text-gray-500 mt-1">Total Tax Collected</p>
                    </div>
                </div>
                
                @if($recentOrders->count() > 0)
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Recent Orders</h4>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tax Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentOrders as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-sky-600">
                                                <a href="{{ route('organization.orders.show', $order->id) }}" class="hover:underline">
                                                    {{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->order_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->customer->name }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-right text-gray-900">
                                                Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-right text-gray-900">
                                                Rp {{ number_format($order->tax_amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($ordersCount > 5)
                            <div class="mt-3 text-center">
                                <a href="{{ route('organization.orders.index', ['tax_id' => $tax->id]) }}" class="text-sm text-sky-600 hover:text-sky-800">
                                    View all orders
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </x-card>
        </div>
        
        <div class="space-y-6">
            <!-- Tax Information -->
            <x-card title="Tax Information">
                <dl class="divide-y divide-gray-200">
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="text-sm text-gray-900">{{ $tax->created_at->format('M d, Y') }}</dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $tax->updated_at->format('M d, Y') }}</dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="text-sm">
                            <x-badge :color="$tax->is_active ? 'green' : 'gray'">
                                {{ $tax->is_active ? 'Active' : 'Inactive' }}
                            </x-badge>
                        </dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Default Tax</dt>
                        <dd class="text-sm">
                            <x-badge :color="$isDefault ? 'green' : 'gray'">
                                {{ $isDefault ? 'Yes' : 'No' }}
                            </x-badge>
                        </dd>
                    </div>
                </dl>
            </x-card>
            
            <!-- Actions -->
            <x-card title="Actions">
                <div class="space-y-3">
                    <x-button 
                        href="{{ route('organization.taxes.edit', $tax) }}" 
                        variant="primary" 
                        icon="fas fa-edit"
                        full-width="true"
                    >
                        Edit Tax Rate
                    </x-button>
                    
                    <form action="{{ route('organization.taxes.toggle', $tax) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <x-button 
                            type="submit"
                            variant="{{ $tax->is_active ? 'danger' : 'success' }}" 
                            icon="{{ $tax->is_active ? 'fas fa-toggle-off' : 'fas fa-toggle-on' }}"
                            full-width="true"
                        >
                            {{ $tax->is_active ? 'Deactivate' : 'Activate' }} Tax Rate
                        </x-button>
                    </form>
                    
                    @if(!$isDefault)
                        <form action="{{ route('organization.taxes.set-default', $tax) }}" method="POST">
                            @csrf
                            <x-button 
                                type="submit"
                                variant="secondary" 
                                icon="fas fa-star"
                                full-width="true"
                            >
                                Set as Default Tax
                            </x-button>
                        </form>
                    @endif
                    
                    @if($ordersCount === 0 && $invoicesCount === 0)
                        <form action="{{ route('organization.taxes.destroy', $tax) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <x-button 
                                type="submit"
                                variant="danger" 
                                icon="fas fa-trash"
                                full-width="true"
                                onclick="return confirm('Are you sure you want to delete this tax rate? This action cannot be undone.')"
                            >
                                Delete Tax Rate
                            </x-button>
                        </form>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</x-organization-layout>