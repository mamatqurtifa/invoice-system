<x-organization-layout>
    @section('title', 'Invoice ' . $invoice->invoice_number)
    
    @php
        $breadcrumbs = [
            'Invoices' => route('organization.invoices.index'),
            $invoice->invoice_number => '#'
        ];
    @endphp
    
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Invoice #{{ $invoice->invoice_number }}</h2>
                <div class="flex items-center mt-1 space-x-2">
                    <x-badge :color="
                        $invoice->status === 'paid' ? 'green' : 
                        ($invoice->status === 'partially_paid' ? 'yellow' : 
                        ($invoice->status === 'sent' ? 'blue' : 
                        ($invoice->status === 'overdue' ? 'red' : 
                        ($invoice->status === 'cancelled' ? 'red' : 'gray'))))
                    ">
                        {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                    </x-badge>
                    
                    <span class="text-sm text-gray-500">{{ $invoice->invoice_date->format('M d, Y') }}</span>
                    
                    @if($invoice->due_date)
                        <span class="text-sm text-gray-500">
                            Due: {{ $invoice->due_date->format('M d, Y') }}
                            @if($invoice->isOverdue())
                                <span class="text-red-600">(Overdue)</span>
                            @endif
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
                <x-button 
                    href="{{ route('organization.invoices.download-pdf', $invoice) }}" 
                    variant="primary"
                    icon="fas fa-download"
                >
                    Download PDF
                </x-button>
                
                <x-button 
                    href="#" 
                    onClick="window.print();" 
                    variant="secondary"
                    icon="fas fa-print"
                >
                    Print
                </x-button>
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <x-button variant="secondary" icon="fas fa-ellipsis-h">
                            Actions
                        </x-button>
                    </x-slot>
                    
                    <x-slot name="content">
                        <a href="{{ route('organization.orders.show', $invoice->order) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-shopping-cart mr-2"></i> View Order
                        </a>
                        
                        <a href="{{ route('organization.invoices.send-email', $invoice) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-envelope mr-2"></i> Email Invoice
                        </a>
                        
                        @if($invoice->status === 'draft')
                            <a href="{{ route('organization.invoices.edit', $invoice) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-edit mr-2"></i> Edit Invoice
                            </a>
                        @endif
                        
                        @if($invoice->status === 'draft')
                            <form action="{{ route('organization.invoices.mark-sent', $invoice) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-gray-100">
                                    <i class="fas fa-paper-plane mr-2"></i> Mark as Sent
                                </button>
                            </form>
                        @endif
                        
                        @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
                            <form action="{{ route('organization.invoices.mark-paid', $invoice) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-gray-100">
                                    <i class="fas fa-check-circle mr-2"></i> Mark as Paid
                                </button>
                            </form>
                        @endif
                        
                        @if($invoice->status !== 'cancelled' && $invoice->status === 'draft')
                            <form action="{{ route('organization.invoices.mark-cancelled', $invoice) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" onclick="return confirm('Are you sure you want to cancel this invoice?')">
                                    <i class="fas fa-times-circle mr-2"></i> Cancel Invoice
                                </button>
                            </form>
                        @endif
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Invoice Preview -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Invoice Preview -->
            <x-card title="Invoice Preview" class="p-0 overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Preview</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('organization.invoices.download-pdf', $invoice) }}" class="text-sm text-sky-600 hover:text-sky-800">
                            <i class="fas fa-download mr-1"></i> Download
                        </a>
                        <a href="#" onClick="window.print();" class="text-sm text-sky-600 hover:text-sky-800">
                            <i class="fas fa-print mr-1"></i> Print
                        </a>
                    </div>
                </div>
                <div class="p-6 bg-white">
                    <iframe src="{{ route('organization.invoices.preview', $invoice) }}" class="w-full border-0 min-h-[800px]"></iframe>
                </div>
            </x-card>
            
            <!-- Order Details -->
            <x-card title="Order Details">
                <div class="mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Order #{{ $invoice->order->order_number }}</p>
                            <p class="text-xs text-gray-500">{{ $invoice->order->order_date->format('M d, Y') }}</p>
                        </div>
                        
                        <x-badge :color="
                            $invoice->order->status === 'completed' ? 'green' : 
                            ($invoice->order->status === 'processing' ? 'yellow' : 
                            ($invoice->order->status === 'cancelled' ? 'red' : 'gray'))
                        " size="sm">
                            {{ ucfirst($invoice->order->status) }}
                        </x-badge>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="overflow-x-auto mb-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoice->order->orderItems as $item)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->projectProduct->product->name }}</div>
                                        <div class="text-xs text-gray-500">SKU: {{ $item->projectProduct->product->sku }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-right text-gray-500">
                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-right text-gray-500">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-right text-gray-500">
                                        Rp {{ number_format($item->discount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-right font-medium text-gray-900">
                                        Rp {{ number_format($item->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <dl class="space-y-1">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Subtotal:</dt>
                                <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($invoice->order->subtotal, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Tax ({{ $invoice->order->tax_percentage }}%):</dt>
                                <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($invoice->order->tax_amount, 0, ',', '.') }}</dd>
                            </div>
                            @if($invoice->order->shipping_cost > 0)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Shipping:</dt>
                                    <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($invoice->order->shipping_cost, 0, ',', '.') }}</dd>
                                </div>
                            @endif
                            @if($invoice->order->discount > 0)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Discount:</dt>
                                    <dd class="text-sm font-medium text-gray-900">- Rp {{ number_format($invoice->order->discount, 0, ',', '.') }}</dd>
                                </div>
                            @endif
                            <div class="border-t border-gray-200 pt-2 flex justify-between">
                                <dt class="text-sm font-medium text-gray-900">Total:</dt>
                                <dd class="text-sm font-bold text-gray-900">Rp {{ number_format($invoice->order->total_amount, 0, ',', '.') }}</dd>
                            </div>
                            @if($invoice->order->payment_type === 'down_payment')
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Down Payment ({{ $invoice->order->down_payment_percentage }}%):</dt>
                                    <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($invoice->order->down_payment_amount, 0, ',', '.') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Remaining Payment:</dt>
                                    <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($invoice->order->remaining_payment, 0, ',', '.') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
                
                <div class="mt-4 border-t border-gray-200 pt-4 flex justify-end">
                    <x-button 
                        href="{{ route('organization.orders.show', $invoice->order) }}" 
                        variant="secondary"
                        size="sm"
                        icon="fas fa-eye"
                    >
                        View Order Details
                    </x-button>
                </div>
            </x-card>
            
            <!-- Payment History -->
            @if($invoice->payments->count() > 0)
                <x-card title="Payment History">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invoice->payments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $payment->payment_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $payment->payment_method }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $payment->reference_number ?: 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <!-- Total Row -->
                                <tr class="bg-gray-50">
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                        Total Paid:
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900">
                                        Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                
                                @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
                                    <!-- Remaining Balance -->
                                    <tr class="bg-gray-50">
                                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                            Remaining Balance:
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900">
                                            Rp {{ number_format($invoice->order->total_amount - $invoice->paid_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </x-card>
            @endif
        </div>
        
        <!-- Right Column - Invoice Details -->
        <div class="space-y-6">
            <!-- Invoice Summary -->
            <x-card title="Invoice Summary" class="bg-gray-50">
                <dl class="divide-y divide-gray-200">
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Invoice Number</dt>
                        <dd class="text-sm text-gray-900">{{ $invoice->invoice_number }}</dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Invoice Date</dt>
                        <dd class="text-sm text-gray-900">{{ $invoice->invoice_date->format('M d, Y') }}</dd>
                    </div>
                    
                    @if($invoice->due_date)
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                            <dd class="text-sm text-gray-900">
                                {{ $invoice->due_date->format('M d, Y') }}
                                @if($invoice->isOverdue())
                                    <span class="text-xs text-red-600 ml-1">(Overdue)</span>
                                @endif
                            </dd>
                        </div>
                    @endif
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd>
                            <x-badge :color="
                                $invoice->status === 'paid' ? 'green' : 
                                ($invoice->status === 'partially_paid' ? 'yellow' : 
                                ($invoice->status === 'sent' ? 'blue' : 
                                ($invoice->status === 'overdue' ? 'red' : 
                                ($invoice->status === 'cancelled' ? 'red' : 'gray'))))
                            " size="sm">
                                {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                            </x-badge>
                        </dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="text-sm font-bold text-gray-900">Rp {{ number_format($invoice->order->total_amount, 0, ',', '.') }}</dd>
                    </div>
                    
                    @if($invoice->status === 'partially_paid')
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Paid Amount</dt>
                            <dd class="text-sm text-gray-900">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</dd>
                        </div>
                        
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Balance Due</dt>
                            <dd class="text-sm font-medium text-red-600">Rp {{ number_format($invoice->order->total_amount - $invoice->paid_amount, 0, ',', '.') }}</dd>
                        </div>
                    @endif
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Template</dt>
                        <dd class="text-sm text-gray-900">{{ $invoice->template->name }}</dd>
                    </div>
                </dl>
            </x-card>
            
            <!-- Customer Information -->
            <x-card title="Customer Information">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Customer</h4>
                        <p class="text-sm font-medium text-gray-900">{{ $invoice->order->customer->name }}</p>
                        
                        <div class="mt-2 space-y-1">
                            @if($invoice->order->customer->email)
                                <p class="text-sm text-gray-600">
                                    <a href="mailto:{{ $invoice->order->customer->email }}" class="text-sky-600 hover:text-sky-800">
                                        {{ $invoice->order->customer->email }}
                                    </a>
                                </p>
                            @endif
                            
                            @if($invoice->order->customer->phone_number)
                                <p class="text-sm text-gray-600">
                                    <a href="tel:{{ $invoice->order->customer->phone_number }}" class="text-sky-600 hover:text-sky-800">
                                        {{ $invoice->order->customer->phone_number }}
                                    </a>
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    @if($invoice->order->customer->address)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Address</h4>
                            <p class="text-sm text-gray-600">{{ $invoice->order->customer->address }}</p>
                        </div>
                    @endif
                    
                    <div class="mt-2">
                        <x-button
                            href="{{ route('organization.customers.show', $invoice->order->customer) }}"
                            variant="secondary"
                            size="sm"
                            icon="fas fa-user"
                        >
                            View Customer Profile
                        </x-button>
                    </div>
                </div>
            </x-card>
            
            <!-- Payment Method -->
            <x-card title="Payment Method">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-md flex items-center justify-center overflow-hidden">
                        @if($invoice->order->paymentMethod->logo)
                            <img src="{{ Storage::url($invoice->order->paymentMethod->logo) }}" alt="{{ $invoice->order->paymentMethod->name }}" class="h-8 w-8 object-contain">
                        @else
                            <i class="fas fa-money-bill-wave text-gray-400 text-lg"></i>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">{{ $invoice->order->paymentMethod->name }}</h4>
                        <p class="text-xs text-gray-500 mt-1">{{ ucwords(str_replace('_', ' ', $invoice->order->paymentMethod->payment_type)) }}</p>
                    </div>
                </div>
                
                @if($invoice->order->paymentMethod->payment_type === 'bank_transfer')
                    <div class="mt-4 border-t border-gray-200 pt-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Bank Name</p>
                                <p class="text-sm font-medium">{{ $invoice->order->paymentMethod->bank_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Account Number</p>
                                <p class="text-sm font-medium">{{ $invoice->order->paymentMethod->account_number }}</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-xs text-gray-500">Account Name</p>
                            <p class="text-sm font-medium">{{ $invoice->order->paymentMethod->account_name }}</p>
                        </div>
                    </div>
                @endif
            </x-card>
            
            <!-- Actions -->
            <x-card title="Invoice Actions">
                <div class="space-y-3">
                    <x-button 
                        href="{{ route('organization.invoices.download-pdf', $invoice) }}" 
                        variant="primary" 
                        icon="fas fa-download"
                        full-width="true"
                    >
                        Download PDF
                    </x-button>
                    
                    <x-button 
                        href="{{ route('organization.invoices.send-email', $invoice) }}" 
                        variant="secondary" 
                        icon="fas fa-envelope"
                        full-width="true"
                    >
                        Email Invoice
                    </x-button>
                    
                    @if($invoice->status === 'draft')
                        <x-button 
                            href="{{ route('organization.invoices.edit', $invoice) }}" 
                            variant="secondary" 
                            icon="fas fa-edit"
                            full-width="true"
                        >
                            Edit Invoice
                        </x-button>
                        
                        <form action="{{ route('organization.invoices.mark-sent', $invoice) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-button 
                                type="submit"
                                variant="success" 
                                icon="fas fa-paper-plane"
                                full-width="true"
                            >
                                Mark as Sent
                            </x-button>
                        </form>
                    @endif
                    
                    @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
                        <form action="{{ route('organization.invoices.mark-paid', $invoice) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-button 
                                type="submit"
                                variant="success" 
                                icon="fas fa-check-circle"
                                full-width="true"
                            >
                                Mark as Paid
                            </x-button>
                        </form>
                    @endif
                    
                    @if(in_array($invoice->status, ['draft', 'sent']) && !$invoice->isOverdue())
                        <form action="{{ route('organization.invoices.mark-cancelled', $invoice) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-button 
                                type="submit"
                                variant="danger" 
                                icon="fas fa-times-circle"
                                full-width="true"
                                onclick="return confirm('Are you sure you want to cancel this invoice?')"
                            >
                                Cancel Invoice
                            </x-button>
                        </form>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</x-organization-layout>