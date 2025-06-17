<x-organization-layout>
    @section('title', 'Edit Order')
    
    @php
        $breadcrumbs = [
            'Orders' => route('organization.orders.index'),
            $order->order_number => route('organization.orders.show', $order),
            'Edit' => '#'
        ];
    @endphp
    
    <x-card class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Order #{{ $order->order_number }}</h1>
                <p class="mt-1 text-sm text-gray-500">Update order details</p>
            </div>
            
            <div class="mt-4 sm:mt-0">
                @if($order->invoice)
                    <x-badge color="yellow">
                        <i class="fas fa-exclamation-circle mr-1"></i> This order has an invoice
                    </x-badge>
                @endif
            </div>
        </div>
    </x-card>
    
    <form id="order-form" action="{{ route('organization.orders.update', $order) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <x-card title="Order Information">
                    <div class="space-y-4">
                        <!-- Order Number / Project / Customer (Read-only) -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Order Number</label>
                                <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 px-3 py-2 text-gray-700 sm:text-sm">
                                    {{ $order->order_number }}
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                                <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 px-3 py-2 text-gray-700 sm:text-sm">
                                    {{ $order->project->name }}
                                </div>
                                <input type="hidden" name="project_id" value="{{ $order->project_id }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                                <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 px-3 py-2 text-gray-700 sm:text-sm">
                                    {{ $order->customer->name }}
                                </div>
                                <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                            </div>
                        </div>
                        
                        <!-- Order Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form.input 
                                type="date"
                                id="order_date"
                                name="order_date"
                                label="Order Date"
                                :value="old('order_date', $order->order_date->format('Y-m-d'))"
                                required
                                :error="$errors->first('order_date')"
                            />
                            
                            <!-- Payment Type -->
                            <div x-data="{ paymentType: '{{ old('payment_type', $order->payment_type) }}' }">
                                <x-form.select
                                    id="payment_type"
                                    name="payment_type"
                                    label="Payment Type"
                                    :options="[
                                        'full_payment' => 'Full Payment',
                                        'down_payment' => 'Down Payment'
                                    ]"
                                    :value="old('payment_type', $order->payment_type)"
                                    x-model="paymentType"
                                    required
                                    :error="$errors->first('payment_type')"
                                    :disabled="$order->invoice"
                                />
                                
                                <div x-show="paymentType === 'down_payment'" class="mt-4" id="down-payment-fields">
                                    <x-form.input 
                                        type="number"
                                        id="down_payment_percentage"
                                        name="down_payment_percentage"
                                        label="Down Payment Percentage"
                                        :value="old('down_payment_percentage', $order->down_payment_percentage)"
                                        min="1"
                                        max="99"
                                        suffix="%"
                                        :error="$errors->first('down_payment_percentage')"
                                        :disabled="$order->invoice"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>
                
                <x-card title="Order Items">
                    @if($order->invoice)
                        <div class="bg-yellow-50 p-4 mb-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-yellow-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        This order has an invoice. You can't modify order items, but you can update shipping, status, and other details.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($order->orderItems as $index => $item)
                                    <tr>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <input type="hidden" name="item_ids[]" value="{{ $item->id }}">
                                            <input type="hidden" name="product_ids[]" value="{{ $item->project_product_id }}">
                                            <span class="text-sm font-medium text-gray-900">{{ $item->projectProduct->product->name }}</span>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-right text-sm text-gray-500">
                                            Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            @if($order->invoice)
                                                <span class="text-sm text-gray-900 text-right block">{{ $item->quantity }}</span>
                                                <input type="hidden" name="quantities[]" value="{{ $item->quantity }}">
                                            @else
                                                <input 
                                                    type="number" 
                                                    name="quantities[]" 
                                                    value="{{ old("quantities.{$index}", $item->quantity) }}" 
                                                    min="1"
                                                    class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm text-right"
                                                >
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            @if($order->invoice)
                                                <span class="text-sm text-gray-900 text-right block">{{ number_format($item->discount, 0, ',', '.') }}</span>
                                                <input type="hidden" name="discounts[]" value="{{ $item->discount }}">
                                            @else
                                                <input 
                                                    type="number" 
                                                    name="discounts[]" 
                                                    value="{{ old("discounts.{$index}", $item->discount) }}" 
                                                    min="0"
                                                    class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm text-right"
                                                >
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                            Rp {{ number_format($item->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-3 py-2 text-right text-sm font-medium text-gray-700">Subtotal:</td>
                                    <td class="px-3 py-2 text-right text-sm font-medium text-gray-900">
                                        Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                                        <input type="hidden" name="subtotal" value="{{ $order->subtotal }}">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </x-card>
                
                <x-card title="Shipping & Additional Options">
                    <div class="space-y-6" x-data="{ 
                        shippingMethod: '{{ old('shipping_method', $order->shipping_method) }}',
                        selectedCourier: '{{ old('courier_id', $order->courier_id) }}',
                        taxPercentage: {{ old('tax_percentage', $order->tax_percentage) }},
                        orderDiscount: {{ old('order_discount', $order->discount) }},
                        shippingCost: {{ old('shipping_cost', $order->shipping_cost) }},
                        
                        get taxAmount() {
                            return (this.taxPercentage / 100) * {{ $order->subtotal }};
                        },
                        
                        get totalAmount() {
                            return {{ $order->subtotal }} + this.taxAmount + parseFloat(this.shippingCost) - parseFloat(this.orderDiscount);
                        }
                    }">
                        <!-- Shipping Method -->
                        <div>
                            <x-form.select
                                id="shipping_method"
                                name="shipping_method"
                                label="Shipping Method"
                                :options="[
                                    'pickup' => 'Pickup',
                                    'courier' => 'Courier',
                                    'digital' => 'Digital Delivery'
                                ]"
                                :value="old('shipping_method', $order->shipping_method)"
                                required
                                x-model="shippingMethod"
                                :error="$errors->first('shipping_method')"
                            />
                        </div>
                        
                        <!-- Courier Selection (only if shipping method is courier) -->
                        <div x-show="shippingMethod === 'courier'">
                            <x-form.select
                                id="courier_id"
                                name="courier_id"
                                label="Select Courier"
                                :options="$couriers->pluck('name', 'id')->toArray()"
                                :value="old('courier_id', $order->courier_id)"
                                x-model="selectedCourier"
                                x-bind:required="shippingMethod === 'courier'"
                                :error="$errors->first('courier_id')"
                            />
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <x-form.input 
                                    id="shipping_cost"
                                    name="shipping_cost"
                                    label="Shipping Cost"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    :value="old('shipping_cost', $order->shipping_cost)"
                                    x-model="shippingCost"
                                    prefix="Rp"
                                    :error="$errors->first('shipping_cost')"
                                    :disabled="$order->invoice && $order->invoice->status !== 'draft'"
                                />
                                
                                <x-form.input 
                                    id="tracking_number"
                                    name="tracking_number"
                                    label="Tracking Number (Optional)"
                                    :value="old('tracking_number', $order->tracking_number)"
                                    :error="$errors->first('tracking_number')"
                                />
                            </div>
                        </div>
                        
                        <!-- Tax & Discount -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form.input 
                                id="tax_percentage"
                                name="tax_percentage"
                                label="Tax Percentage"
                                type="number"
                                min="0"
                                max="100"
                                step="0.01"
                                :value="old('tax_percentage', $order->tax_percentage)"
                                x-model="taxPercentage"
                                suffix="%"
                                :error="$errors->first('tax_percentage')"
                                :disabled="$order->invoice && $order->invoice->status !== 'draft'"
                            />
                            
                            <x-form.input 
                                id="order_discount"
                                name="order_discount"
                                label="Order Discount"
                                type="number"
                                min="0"
                                step="0.01"
                                :value="old('order_discount', $order->discount)"
                                x-model="orderDiscount"
                                prefix="Rp"
                                :error="$errors->first('order_discount')"
                                :disabled="$order->invoice && $order->invoice->status !== 'draft'"
                            />
                        </div>
                        
                        <!-- Notes -->
                        <x-form.textarea 
                            id="notes"
                            name="notes"
                            label="Order Notes (Optional)"
                            :value="old('notes', $order->notes)"
                            rows="3"
                            :error="$errors->first('notes')"
                        />
                        
                        <!-- Calculate tax and total -->
                        <div class="border-t border-gray-200 pt-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dl class="space-y-1">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">Subtotal:</dt>
                                        <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">Tax (<span x-text="taxPercentage"></span>%):</dt>
                                        <dd class="text-sm font-medium text-gray-900" x-text="'Rp ' + taxAmount.toLocaleString()"></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">Shipping:</dt>
                                        <dd class="text-sm font-medium text-gray-900" x-text="'Rp ' + shippingCost.toLocaleString()"></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">Discount:</dt>
                                        <dd class="text-sm font-medium text-gray-900" x-text="'- Rp ' + orderDiscount.toLocaleString()"></dd>
                                    </div>
                                    <div class="border-t border-gray-200 pt-2 flex justify-between">
                                        <dt class="text-sm font-medium text-gray-900">Total:</dt>
                                        <dd class="text-sm font-bold text-gray-900" x-text="'Rp ' + totalAmount.toLocaleString()"></dd>
                                    </div>
                                </dl>
                                
                                <input type="hidden" name="tax_amount" x-bind:value="taxAmount">
                                <input type="hidden" name="total_amount" x-bind:value="totalAmount">
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
            
            <!-- Right Column - Payment Details -->
            <div class="space-y-6">
                <x-card title="Payment Details">
                    <div class="space-y-4">
                        <!-- Payment Method -->
                        <x-form.select
                            id="payment_method_id"
                            name="payment_method_id"
                            label="Payment Method"
                            :options="$paymentMethods->pluck('name', 'id')->toArray()"
                            :value="old('payment_method_id', $order->payment_method_id)"
                            required
                            :error="$errors->first('payment_method_id')"
                        />
                        
                        <!-- Payment Status -->
                        <x-form.select
                            id="payment_status"
                            name="payment_status"
                            label="Payment Status"
                            :options="[
                                'pending' => 'Pending',
                                'partial' => 'Partial Payment',
                                'completed' => 'Completed',
                                'refunded' => 'Refunded'
                            ]"
                            :value="old('payment_status', $order->payment_status)"
                            required
                            :error="$errors->first('payment_status')"
                        />
                    </div>
                </x-card>
                
                <x-card title="Order Status">
                    <div class="space-y-4">
                        <!-- Order Status -->
                        <x-form.select
                            id="status"
                            name="status"
                            label="Order Status"
                            :options="[
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled'
                            ]"
                            :value="old('status', $order->status)"
                            required
                            :error="$errors->first('status')"
                        />
                    </div>
                </x-card>
                
                <x-card title="Invoice Information">
                    <div class="space-y-4">
                        @if($order->invoice)
                            <div class="flex items-center justify-between">
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
                            
                            <div class="flex space-x-2">
                                <x-button 
                                    href="{{ route('organization.invoices.show', $order->invoice) }}" 
                                    variant="secondary"
                                    size="sm"
                                    icon="fas fa-eye"
                                >
                                    View Invoice
                                </x-button>
                                
                                <x-button 
                                    href="{{ route('organization.invoices.download-pdf', $order->invoice) }}" 
                                    variant="secondary"
                                    size="sm"
                                    icon="fas fa-download"
                                >
                                    Download PDF
                                </x-button>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-sm text-gray-500">No invoice created for this order yet.</p>
                                <div class="mt-3">
                                    <x-button 
                                        href="{{ route('organization.invoices.create', ['order_id' => $order->id]) }}" 
                                        variant="primary"
                                        size="sm"
                                        icon="fas fa-file-invoice"
                                    >
                                        Create Invoice
                                    </x-button>
                                </div>
                            </div>
                        @endif
                    </div>
                </x-card>
                
                <x-card title="Actions">
                    <div class="space-y-4">
                        <div class="flex flex-col gap-3">
                            <x-button 
                                type="submit" 
                                variant="primary"
                                icon="fas fa-save"
                                full-width="true"
                            >
                                Update Order
                            </x-button>
                            
                            <x-button 
                                href="{{ route('organization.orders.show', $order) }}" 
                                variant="secondary"
                                full-width="true"
                            >
                                Cancel
                            </x-button>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </form>
</x-organization-layout>