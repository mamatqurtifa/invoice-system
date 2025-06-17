<x-organization-layout>
    @section('title', 'Order ' . $order->order_number)
    
    @php
        $breadcrumbs = [
            'Orders' => route('organization.orders.index'),
            $order->order_number => '#'
        ];
    @endphp
    
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h2>
                <div class="flex items-center mt-1 space-x-2">
                    <x-badge :color="
                        $order->status === 'completed' ? 'green' : 
                        ($order->status === 'processing' ? 'yellow' : 
                        ($order->status === 'cancelled' ? 'red' : 'gray'))
                    ">
                        {{ ucfirst($order->status) }}
                    </x-badge>
                    
                    <x-badge :color="
                        $order->payment_status === 'completed' ? 'green' : 
                        ($order->payment_status === 'partial' ? 'yellow' : 
                        ($order->payment_status === 'refunded' ? 'red' : 'gray'))
                    ">
                        Payment: {{ ucfirst($order->payment_status) }}
                    </x-badge>
                    
                    <span class="text-sm text-gray-500">{{ $order->order_date->format('M d, Y') }}</span>
                </div>
            </div>
            
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
                @if(!$order->invoice)
                    <x-button 
                        href="{{ route('organization.invoices.create', ['order_id' => $order->id]) }}" 
                        variant="primary"
                        icon="fas fa-file-invoice"
                    >
                        Create Invoice
                    </x-button>
                @endif
                
                @if($order->status != 'completed' && $order->status != 'cancelled')
                    <x-button 
                        href="{{ route('organization.orders.edit', $order) }}" 
                        variant="secondary"
                        icon="fas fa-edit"
                    >
                        Edit Order
                    </x-button>
                @endif
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <x-button variant="secondary" icon="fas fa-ellipsis-h">
                            Actions
                        </x-button>
                    </x-slot>
                    
                    <x-slot name="content">
                        @if($order->invoice)
                            <a href="{{ route('organization.invoices.show', $order->invoice) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-file-invoice mr-2"></i> View Invoice
                            </a>
                        @endif
                        
                        <a href="{{ route('organization.orders.duplicate', $order) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-copy mr-2"></i> Duplicate Order
                        </a>
                        
                        @if($order->status !== 'completed')
                            <form action="{{ route('organization.orders.mark-completed', $order) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-gray-100">
                                    <i class="fas fa-check-circle mr-2"></i> Mark as Completed
                                </button>
                            </form>
                        @endif
                        
                        @if($order->status !== 'cancelled')
                            <form action="{{ route('organization.orders.mark-cancelled', $order) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" onclick="return confirm('Are you sure you want to cancel this order?')">
                                    <i class="fas fa-times-circle mr-2"></i> Cancel Order
                                </button>
                            </form>
                        @endif
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer & Project Info -->
            <x-card>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Customer Information</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-grow">
                                    <p class="text-sm font-medium text-gray-900">{{ $order->customer->name }}</p>
                                    
                                    @if($order->customer->email)
                                        <p class="text-sm text-gray-600 mt-1">
                                            <a href="mailto:{{ $order->customer->email }}" class="text-sky-600 hover:text-sky-800">
                                                {{ $order->customer->email }}
                                            </a>
                                        </p>
                                    @endif
                                    
                                    @if($order->customer->phone_number)
                                        <p class="text-sm text-gray-600 mt-1">
                                            <a href="tel:{{ $order->customer->phone_number }}" class="text-sky-600 hover:text-sky-800">
                                                {{ $order->customer->phone_number }}
                                            </a>
                                        </p>
                                    @endif
                                    
                                    @if($order->customer->address)
                                        <p class="text-sm text-gray-600 mt-2">{{ $order->customer->address }}</p>
                                    @endif
                                </div>
                                
                                <a href="{{ route('organization.customers.show', $order->customer) }}" class="text-sm text-sky-600 hover:text-sky-800 flex-shrink-0">
                                    View Profile <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Project Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Project Information</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-900">{{ $order->project->name }}</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <x-badge :color="$order->project->status === 'active' ? 'green' : ($order->project->status === 'completed' ? 'blue' : 'red')" size="sm">
                                    {{ ucfirst($order->project->status) }}
                                </x-badge>
                                
                                <x-badge :color="$order->project->type === 'preorder' ? 'purple' : 'sky'" size="sm">
                                    {{ $order->project->type === 'preorder' ? 'Pre-Order' : 'Direct Order' }}
                                </x-badge>
                            </div>
                            
                            <a href="{{ route('organization.projects.show', $order->project) }}" class="text-sm text-sky-600 hover:text-sky-800 mt-2 inline-block">
                                View Project <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </x-card>
            
            <!-- Order Items -->
            <x-card title="Order Items">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Discount
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->projectProduct->product->name }}</div>
                                        <div class="text-xs text-gray-500">SKU: {{ $item->projectProduct->product->sku }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                        Rp {{ number_format($item->discount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">
                                        Rp {{ number_format($item->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">
                                    Subtotal:
                                </td>
                                <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                                    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">
                                    Tax ({{ $order->tax_percentage }}%):
                                </td>
                                <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                                    Rp {{ number_format($order->tax_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @if($order->shipping_cost > 0)
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">
                                        Shipping Cost:
                                    </td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                                        Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            @if($order->discount > 0)
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">
                                        Discount:
                                    </td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                                        - Rp {{ number_format($order->discount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            <tr class="bg-gray-100">
                                <td colspan="4" class="px-6 py-3 text-right text-sm font-bold text-gray-700">
                                    Total Amount:
                                </td>
                                <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @if($order->payment_type === 'down_payment')
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">
                                        Down Payment ({{ $order->down_payment_percentage }}%):
                                    </td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                                        Rp {{ number_format($order->down_payment_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">
                                        Remaining Payment:
                                    </td>
                                    <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                                        Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </x-card>
            
            <!-- Shipping & Notes -->
            <x-card title="Shipping & Additional Information">
                <div class="space-y-6">
                    <!-- Shipping Info -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Shipping Information</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Shipping Method</p>
                                    <p class="text-sm font-medium text-gray-900">{{ ucfirst($order->shipping_method) }}</p>
                                </div>
                                
                                @if($order->shipping_method === 'courier')
                                    <div>
                                        <p class="text-sm text-gray-500">Courier</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $order->courier->name ?? 'N/A' }}</p>
                                    </div>
                                    
                                    @if($order->shipping_cost > 0)
                                        <div>
                                            <p class="text-sm text-gray-500">Shipping Cost</p>
                                            <p class="text-sm font-medium text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($order->tracking_number)
                                        <div>
                                            <p class="text-sm text-gray-500">Tracking Number</p>
                                            <p class="text-sm font-medium">
                                                @if($order->courier && $order->courier->tracking_url)
                                                    <a href="{{ str_replace('{tracking_number}', $order->tracking_number, $order->courier->tracking_url) }}" target="_blank" class="text-sky-600 hover:text-sky-800 flex items-center">
                                                        {{ $order->tracking_number }}
                                                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                                    </a>
                                                @else
                                                    {{ $order->tracking_number }}
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Info -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Payment Information</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Payment Method</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $order->paymentMethod->name }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Payment Type</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $order->payment_type === 'full_payment' ? 'Full Payment' : 'Down Payment' }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Payment Status</p>
                                    <p class="text-sm font-medium">
                                        <x-badge :color="
                                            $order->payment_status === 'completed' ? 'green' : 
                                            ($order->payment_status === 'partial' ? 'yellow' : 
                                            ($order->payment_status === 'refunded' ? 'red' : 'gray'))
                                        " size="sm">
                                            {{ ucfirst($order->payment_status) }}
                                        </x-badge>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Notes -->
                    @if($order->notes)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Order Notes</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>
        
        <!-- Right Column - Order Summary & Actions -->
        <div class="space-y-6">
            <!-- Order Summary -->
            <x-card title="Order Summary" class="bg-gray-50">
                <dl class="divide-y divide-gray-200">
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                        <dd class="text-sm text-gray-900">{{ $order->order_date->format('M d, Y') }}</dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Order Status</dt>
                        <dd class="text-sm">
                            <x-badge :color="
                                $order->status === 'completed' ? 'green' : 
                                ($order->status === 'processing' ? 'yellow' : 
                                ($order->status === 'cancelled' ? 'red' : 'gray'))
                            " size="sm">
                                {{ ucfirst($order->status) }}
                            </x-badge>
                        </dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                        <dd class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $order->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </x-card>
            
            <!-- Invoice Status -->
            <x-card title="Invoice Status">
                @if($order->invoice)
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Invoice #{{ $order->invoice->invoice_number }}</p>
                                <p class="text-xs text-gray-500">{{ $order->invoice->invoice_date->format('M d, Y') }}</p>
                            </div>
                            
                            <x-badge :color="
                                $order->invoice->status === 'paid' ? 'green' : 
                                ($order->invoice->status === 'partially_paid' ? 'yellow' : 
                                ($order->invoice->status === 'cancelled' ? 'red' : 'gray'))
                            ">
                                {{ ucfirst(str_replace('_', ' ', $order->invoice->status)) }}
                            </x-badge>
                        </div>
                        
                        @if($order->invoice->due_date)
                            <div class="bg-gray-50 p-3 rounded-md text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Due Date:</span>
                                    <span class="font-medium text-gray-900">{{ $order->invoice->due_date->format('M d, Y') }}</span>
                                </div>
                                
                                @if($order->invoice->status !== 'paid' && $order->invoice->status !== 'cancelled')
                                    <div class="mt-1 flex justify-between items-center">
                                        <span class="text-gray-700">Time Remaining:</span>
                                        <span class="{{ $order->invoice->due_date->isPast() ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                            {{ $order->invoice->due_date->isPast() 
                                                ? 'Overdue by ' . $order->invoice->due_date->diffForHumans(null, true)
                                                : $order->invoice->due_date->diffForHumans() . ' left'
                                            }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <div class="flex flex-col space-y-2">
                            <x-button 
                                href="{{ route('organization.invoices.show', $order->invoice) }}" 
                                variant="primary" 
                                icon="fas fa-eye"
                                full-width="true"
                            >
                                View Invoice
                            </x-button>
                            
                            <x-button 
                                href="{{ route('organization.invoices.download-pdf', $order->invoice) }}" 
                                variant="secondary" 
                                icon="fas fa-download"
                                full-width="true"
                            >
                                Download PDF
                            </x-button>
                        </div>
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="text-sm text-gray-500">No invoice has been created for this order yet.</p>
                        <div class="mt-3">
                            <x-button 
                                href="{{ route('organization.invoices.create', ['order_id' => $order->id]) }}" 
                                variant="primary"
                                icon="fas fa-file-invoice"
                                size="sm"
                            >
                                Create Invoice
                            </x-button>
                        </div>
                    </div>
                @endif
            </x-card>
            
            <!-- Actions -->
            <x-card title="Actions">
                <div class="space-y-3">
                    @if($order->status != 'completed' && $order->status != 'cancelled')
                        <x-button 
                            href="{{ route('organization.orders.edit', $order) }}" 
                            variant="primary" 
                            icon="fas fa-edit"
                            full-width="true"
                        >
                            Edit Order
                        </x-button>
                    @endif
                    
                    @if($order->status !== 'completed')
                        <form action="{{ route('organization.orders.mark-completed', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-button 
                                type="submit"
                                variant="success"
                                icon="fas fa-check-circle"
                                full-width="true"
                            >
                                Mark as Completed
                            </x-button>
                        </form>
                    @endif
                    
                    <x-button 
                        href="{{ route('organization.orders.duplicate', $order) }}" 
                        variant="secondary" 
                        icon="fas fa-copy"
                        full-width="true"
                    >
                        Duplicate Order
                    </x-button>
                    
                    @if($order->status !== 'cancelled')
                        <form action="{{ route('organization.orders.mark-cancelled', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-button 
                                type="submit"
                                variant="danger"
                                icon="fas fa-times-circle"
                                full-width="true"
                                onclick="return confirm('Are you sure you want to cancel this order?')"
                            >
                                Cancel Order
                            </x-button>
                        </form>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</x-organization-layout>