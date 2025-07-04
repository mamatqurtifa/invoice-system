            <a href="#" onclick="window.open('{{ route('organization.invoices.download-pdf', $invoice) }}', '_blank')" class="inline-flex items-center px-4 py-2 bg-sky-600 border border-transparent rounded-md text-sm font-medium text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                <i class="fas fa-file-pdf mr-2"></i> Download PDF
            </a>
            
            <a href="#" onclick="window.open('{{ route('organization.invoices.download-image', $invoice) }}', '_blank')" class="inline-flex items-center px-4 py-2 bg-sky-600 border border-transparent rounded-md text-sm font-medium text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                <i class="fas fa-file-image mr-2"></i> Download Image
            </a>
            
            <div x-data="{ open: false }">
                <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                    <i class="fas fa-ellipsis-h mr-2"></i> More
                </button>
                
                <div x-show="open" @click.away="open = false" x-cloak
                    class="absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                    <div class="py-1">
                        <a href="#" onclick="window.print(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-print mr-2"></i> Print
                        </a>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('admin.invoices.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Back to Invoices
            </a>
        </div>
    </div>
    
    <!-- Organization & Status Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Organization Info -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden md:col-span-2">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Organization Information</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    @if($invoice->order->project->organization->logo)
                        <img src="{{ Storage::url($invoice->order->project->organization->logo) }}" alt="{{ $invoice->order->project->organization->name }}" class="h-12 w-12 rounded-full object-cover">
                    @else
                        <div class="h-12 w-12 rounded-full bg-sky-100 flex items-center justify-center">
                            <span class="text-lg font-medium text-sky-600">
                                {{ substr($invoice->order->project->organization->name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                    <div class="ml-4">
                        <h4 class="text-xl font-medium text-gray-900">
                            {{ $invoice->order->project->organization->name }}
                        </h4>
                        <div class="mt-1 text-sm text-gray-500">
                            <p>{{ $invoice->order->project->organization->email }}</p>
                            <p>{{ $invoice->order->project->organization->customer_service_number ?? 'No contact number' }}</p>
                        </div>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('admin.organizations.show', $invoice->order->project->organization) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                            View Organization
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Invoice Status -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Invoice Status</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if($invoice->status === 'paid')
                            <span class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                <i class="fas fa-check text-xl text-green-600"></i>
                            </span>
                        @elseif($invoice->status === 'partially_paid')
                            <span class="flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                                <i class="fas fa-hourglass-half text-xl text-yellow-600"></i>
                            </span>
                        @elseif($invoice->status === 'unpaid')
                            <span class="flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                                <i class="fas fa-clock text-xl text-gray-600"></i>
                            </span>
                        @else
                            <span class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                <i class="fas fa-times text-xl text-red-600"></i>
                            </span>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            @if($invoice->status === 'paid')
                                Payment Completed
                            @elseif($invoice->status === 'partially_paid')
                                Partially Paid
                            @elseif($invoice->status === 'unpaid')
                                Payment Pending
                            @else
                                Invoice Cancelled
                            @endif
                        </h3>
                        <p class="text-sm text-gray-500">
                            @if($invoice->status === 'paid')
                                Payment has been completed for this invoice.
                            @elseif($invoice->status === 'partially_paid')
                                This invoice has been partially paid.
                            @elseif($invoice->status === 'unpaid')
                                @if($invoice->due_date)
                                    Payment due by {{ $invoice->due_date->format('F d, Y') }}
                                @else
                                    Waiting for payment.
                                @endif
                            @else
                                This invoice has been cancelled.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Invoice Content -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">
                Invoice Preview
            </h3>
            
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                @if($invoice->status === 'paid') bg-green-100 text-green-800
                @elseif($invoice->status === 'partially_paid') bg-yellow-100 text-yellow-800
                @elseif($invoice->status === 'unpaid') bg-gray-100 text-gray-800
                @else bg-red-100 text-red-800 @endif">
                {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
            </span>
        </div>
        
        <div class="p-6 bg-white print:p-0">
            <!-- Invoice Header -->
            <div class="flex flex-col sm:flex-row justify-between mb-8 border-b pb-4">
                <div class="mb-4 sm:mb-0">
                    @if($invoice->template && $invoice->template->show_organization_logo && $invoice->order->project->organization->logo)
                        <img class="h-12 w-auto mb-2" src="{{ Storage::url($invoice->order->project->organization->logo) }}" alt="{{ $invoice->order->project->organization->name }}">
                    @endif
                    <h4 class="text-lg font-semibold text-gray-900">{{ $invoice->order->project->organization->name }}</h4>
                    <p class="text-sm text-gray-600">
                        {{ $invoice->order->project->organization->address }}<br>
                        {{ $invoice->order->project->organization->email }}<br>
                        {{ $invoice->order->project->organization->customer_service_number }}
                    </p>
                </div>
                
                <div class="text-right">
                    <h2 class="text-2xl font-bold text-gray-900">INVOICE</h2>
                    <p class="text-gray-600"># {{ $invoice->invoice_number }}</p>
                    <p class="text-gray-600 mt-2">Date: {{ $invoice->invoice_date->format('F d, Y') }}</p>
                    @if($invoice->due_date)
                        <p class="text-gray-600">Due Date: {{ $invoice->due_date->format('F d, Y') }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="flex flex-col sm:flex-row mb-8">
                <div class="sm:w-1/2 mb-4 sm:mb-0">
                    <h3 class="text-gray-600 text-sm uppercase font-semibold mb-2">Bill To:</h3>
                    <p class="text-gray-900 font-medium">{{ $invoice->order->customer->name }}</p>
                    <p class="text-gray-700">{{ $invoice->order->customer->email }}</p>
                    <p class="text-gray-700">{{ $invoice->order->customer->phone_number }}</p>
                    <p class="text-gray-700">{{ $invoice->order->customer->address }}</p>
                </div>
                
                <div class="sm:w-1/2">
                    <h3 class="text-gray-600 text-sm uppercase font-semibold mb-2">Project Details:</h3>
                    <p class="text-gray-900 font-medium">{{ $invoice->order->project->name }}</p>
                    <p class="text-gray-700">Order #: {{ $invoice->order->order_number }}</p>
                    <p class="text-gray-700">Order Date: {{ $invoice->order->order_date->format('F d, Y') }}</p>
                    <p class="text-gray-700">Payment Method: {{ $invoice->order->paymentMethod->name }}</p>
                </div>
            </div>
            
            <!-- Invoice Items -->
            <div class="mb-8 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Item
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Unit Price
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
                        @foreach($invoice->order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">{{ $item->projectProduct->product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($item->projectProduct->product->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                    Rp {{ number_format($item->discount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-right">
                                    Rp {{ number_format($item->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Invoice Summary -->
            <div class="flex justify-end mb-8">
                <div class="w-full sm:w-72">
                    <div class="border rounded-lg overflow-hidden">
                        <div class="py-3 px-4 bg-gray-50 text-gray-600 text-sm font-medium">
                            Summary
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="text-gray-900 font-medium">Rp {{ number_format($invoice->order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Tax:</span>
                                <span class="text-gray-900 font-medium">Rp {{ number_format($invoice->order->tax_amount, 0, ',', '.') }}</span>
                            </div>
                            @if($invoice->order->discount > 0)
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-600">Discount:</span>
                                    <span class="text-gray-900 font-medium">-Rp {{ number_format($invoice->order->discount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if($invoice->order->shipping_cost > 0)
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-600">Shipping:</span>
                                    <span class="text-gray-900 font-medium">Rp {{ number_format($invoice->order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between py-1 border-t border-gray-200 mt-2 pt-2">
                                <span class="text-gray-800 font-semibold">Total:</span>
                                <span class="text-gray-900 font-bold">Rp {{ number_format($invoice->order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            @if($invoice->order->payment_type === 'down_payment')
                                <div class="flex justify-between py-1 border-t border-gray-200 mt-2 pt-2">
                                    <span class="text-gray-600">Down Payment:</span>
                                    <span class="text-gray-900 font-medium">Rp {{ number_format($invoice->order->down_payment_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span class="text-gray-800 font-semibold">Remaining Payment:</span>
                                    <span class="text-gray-900 font-bold">Rp {{ number_format($invoice->order->remaining_payment, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Instructions -->
            <div class="mb-8">
                <h4 class="text-lg font-medium text-gray-900 mb-2">Payment Instructions</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center mb-4">
                        @if($invoice->order->paymentMethod->logo)
                            <img src="{{ Storage::url($invoice->order->paymentMethod->logo) }}" alt="{{ $invoice->order->paymentMethod->name }}" class="h-8 w-auto mr-3">
                        @endif
                        <div>
                            <p class="font-medium">{{ $invoice->order->paymentMethod->name }}</p>
                            <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $invoice->order->paymentMethod->payment_type)) }}</p>
                        </div>
                    </div>
                    
                    @if($invoice->order->paymentMethod->payment_type === 'bank_transfer')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Bank Name</p>
                                <p class="font-medium">{{ $invoice->order->paymentMethod->bank_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Account Number</p>
                                <p class="font-medium">{{ $invoice->order->paymentMethod->account_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Account Name</p>
                                <p class="font-medium">{{ $invoice->order->paymentMethod->account_name }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($invoice->order->paymentMethod->instructions)
                        <div class="mt-3 text-sm text-gray-600">
                            {!! nl2br(e($invoice->order->paymentMethod->instructions)) !!}
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Notes -->
            @if($invoice->notes)
                <div class="mb-8">
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Notes</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">
                            {!! nl2br(e($invoice->notes)) !!}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Order Information -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                Order Information
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Order Details</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-900"><strong>Order Number:</strong> {{ $invoice->order->order_number }}</p>
                        <p class="text-sm text-gray-900 mt-2"><strong>Order Date:</strong> {{ $invoice->order->order_date->format('F d, Y') }}</p>
                        <p class="text-sm text-gray-900 mt-2"><strong>Payment Type:</strong> {{ ucfirst(str_replace('_', ' ', $invoice->order->payment_type)) }}</p>
                        <p class="text-sm text-gray-900 mt-2">
                            <strong>Payment Status:</strong> 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($invoice->order->payment_status === 'completed') bg-green-100 text-green-800
                                @elseif($invoice->order->payment_status === 'partial') bg-yellow-100 text-yellow-800
                                @elseif($invoice->order->payment_status === 'pending') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($invoice->order->payment_status) }}
                            </span>
                        </p>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Shipping Information</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-900"><strong>Shipping Method:</strong> {{ ucfirst(str_replace('_', ' ', $invoice->order->shipping_method)) }}</p>
                        
                        @if($invoice->order->shipping_method === 'courier')
                            <p class="text-sm text-gray-900 mt-2"><strong>Courier:</strong> {{ $invoice->order->courier->name }}</p>
                            @if($invoice->order->tracking_number)
                                <p class="text-sm text-gray-900 mt-2">
                                    <strong>Tracking Number:</strong> {{ $invoice->order->tracking_number }}
                                    @if($invoice->order->courier->tracking_url)
                                        <a href="{{ str_replace('{tracking_number}', $invoice->order->tracking_number, $invoice->order->courier->tracking_url) }}" target="_blank" class="ml-2 text-sky-600 hover:text-sky-800">
                                            <i class="fas fa-external-link-alt text-xs"></i> Track
                                        </a>
                                    @endif
                                </p>
                            @endif
                            <p class="text-sm text-gray-900 mt-2"><strong>Shipping Cost:</strong> Rp {{ number_format($invoice->order->shipping_cost, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Project Information</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-900"><strong>Project Name:</strong> {{ $invoice->order->project->name }}</p>
                        <p class="text-sm text-gray-900 mt-2"><strong>Project Type:</strong> {{ $invoice->order->project->type === 'preorder' ? 'Pre-Order' : 'Direct Order' }}</p>
                        <p class="text-sm text-gray-900 mt-2">
                            <strong>Project Status:</strong> 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($invoice->order->project->status === 'active') bg-green-100 text-green-800 
                                @elseif($invoice->order->project->status === 'completed') bg-blue-100 text-blue-800 
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($invoice->order->project->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            
            @if($invoice->order->notes)
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Order Notes</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">
                            {!! nl2br(e($invoice->order->notes)) !!}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>