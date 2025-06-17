<x-organization-layout>
    @section('title', $paymentMethod->name)
    
    @php
        $breadcrumbs = [
            'Payment Methods' => route('organization.payment-methods.index'),
            $paymentMethod->name => '#'
        ];
    @endphp
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-14 w-14 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden mr-4">
                @if($paymentMethod->logo)
                    <img src="{{ Storage::url($paymentMethod->logo) }}" alt="{{ $paymentMethod->name }}" class="h-12 w-12 object-contain">
                @else
                    <i class="fas fa-money-bill-wave text-gray-400 text-2xl"></i>
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $paymentMethod->name }}</h1>
                <div class="flex items-center space-x-2 mt-1">
                    <x-badge :color="$paymentMethod->is_active ? 'green' : 'gray'">
                        {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                    </x-badge>
                    
                    <x-badge :color="
                        $paymentMethod->payment_type === 'bank_transfer' ? 'sky' : 
                        ($paymentMethod->payment_type === 'cash' ? 'green' : 
                        ($paymentMethod->payment_type === 'credit_card' ? 'blue' : 
                        ($paymentMethod->payment_type === 'qris' ? 'purple' : 'yellow')))
                    ">
                        {{ ucwords(str_replace('_', ' ', $paymentMethod->payment_type)) }}
                    </x-badge>
                </div>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
            <x-button 
                href="{{ route('organization.payment-methods.edit', $paymentMethod) }}" 
                variant="primary"
                icon="fas fa-edit"
            >
                Edit
            </x-button>
            
            <form action="{{ route('organization.payment-methods.destroy', $paymentMethod) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this payment method?')">
                @csrf
                @method('DELETE')
                <x-button 
                    type="submit"
                    variant="danger"
                    icon="fas fa-trash"
                    :disabled="$paymentMethod->orders_count > 0"
                >
                    Delete
                </x-button>
            </form>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Method Details -->
            <x-card title="Payment Details">
                <div class="divide-y divide-gray-200">
                    @if($paymentMethod->payment_type === 'bank_transfer')
                        <div class="py-4">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Bank Name</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $paymentMethod->bank_name }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $paymentMethod->account_number }}</dd>
                                </div>
                                
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Account Name</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $paymentMethod->account_name }}</dd>
                                </div>
                            </dl>
                        </div>
                    @elseif($paymentMethod->payment_type === 'qris' || $paymentMethod->payment_type === 'e_wallet')
                        <div class="py-4">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                @if($paymentMethod->account_id)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Account ID / Phone Number</dt>
                                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $paymentMethod->account_id }}</dd>
                                    </div>
                                @endif
                                
                                @if($paymentMethod->account_name)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Account Name</dt>
                                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $paymentMethod->account_name }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif
                    
                    @if($paymentMethod->instructions)
                        <div class="py-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Payment Instructions</h3>
                            <div class="bg-gray-50 p-3 rounded-md">
                                <p class="text-sm text-gray-800 whitespace-pre-line">{{ $paymentMethod->instructions }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($paymentMethod->notes)
                        <div class="py-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Internal Notes</h3>
                            <div class="bg-yellow-50 p-3 rounded-md">
                                <p class="text-sm text-gray-800 whitespace-pre-line">{{ $paymentMethod->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </x-card>
            
            <!-- Recent Orders -->
            <x-card title="Recent Orders">
                @if($recentOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Order #
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer
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
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('organization.orders.show', $order) }}" class="text-sm font-medium text-sky-600 hover:text-sky-800">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $order->customer->name }}
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($totalOrders > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('organization.orders.index', ['payment_method_id' => $paymentMethod->id]) }}" class="text-sm font-medium text-sky-600 hover:text-sky-800">
                                View all {{ $totalOrders }} orders
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-6">
                        <p class="text-sm text-gray-500">No orders have been placed using this payment method yet.</p>
                    </div>
                @endif
            </x-card>
        </div>
        
        <div class="space-y-6">
            <!-- Payment Stats -->
            <x-card title="Statistics">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <span class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</span>
                        <p class="text-xs text-gray-500 mt-1">Orders</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <span class="text-2xl font-bold text-gray-900">{{ $totalInvoices }}</span>
                        <p class="text-xs text-gray-500 mt-1">Invoices</p>
                    </div>
                </div>
                
                <div class="mt-4 bg-gray-50 p-4 rounded-lg text-center">
                    <span class="text-2xl font-bold text-gray-900">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-gray-500 mt-1">Total Revenue</p>
                </div>
                
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-500">Usage Percentage</span>
                        <span class="font-medium text-gray-900">{{ number_format($usagePercentage, 1) }}%</span>
                    </div>
                    <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-sky-600 h-1.5 rounded-full" style="width: {{ $usagePercentage }}%"></div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Percentage of orders using this payment method
                    </p>
                </div>
            </x-card>
            
            <!-- Action Card -->
            <x-card title="Actions">
                <div class="space-y-3">
                    <x-button 
                        href="{{ route('organization.payment-methods.edit', $paymentMethod) }}" 
                        variant="primary" 
                        icon="fas fa-edit"
                        full-width="true"
                    >
                        Edit Payment Method
                    </x-button>
                    
                    <form action="{{ route('organization.payment-methods.toggle', $paymentMethod) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_active" value="{{ $paymentMethod->is_active ? 0 : 1 }}">
                        <x-button 
                            type="submit"
                            variant="secondary"
                            icon="{{ $paymentMethod->is_active ? 'fas fa-toggle-off' : 'fas fa-toggle-on' }}"
                            full-width="true"
                        >
                            {{ $paymentMethod->is_active ? 'Disable' : 'Enable' }} Payment Method
                        </x-button>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
</x-organization-layout>