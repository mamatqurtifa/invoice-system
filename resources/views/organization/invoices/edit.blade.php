<x-organization-layout>
    @section('title', 'Edit Invoice')
    
    @php
        $breadcrumbs = [
            'Invoices' => route('organization.invoices.index'),
            $invoice->invoice_number => route('organization.invoices.show', $invoice),
            'Edit' => '#'
        ];
    @endphp
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Invoice #{{ $invoice->invoice_number }}</h2>
            <p class="mt-1 text-sm text-gray-600">Update invoice details</p>
        </div>
    </div>
    
    @if($invoice->status !== 'draft')
        <x-alert type="warning" title="Warning" class="mb-6">
            This invoice has already been {{ strtolower(str_replace('_', ' ', $invoice->status)) }}. Only drafts can be edited.
        </x-alert>
    @endif
    
    <form action="{{ route('organization.invoices.update', $invoice) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Invoice Details -->
            <div class="lg:col-span-2 space-y-6">
                <x-card title="Invoice Information">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Invoice Number -->
                            <div>
                                <x-form.input 
                                    id="invoice_number"
                                    name="invoice_number"
                                    label="Invoice Number"
                                    :value="old('invoice_number', $invoice->invoice_number)"
                                    :disabled="true"
                                    :error="$errors->first('invoice_number')"
                                />
                                <input type="hidden" name="invoice_number" value="{{ $invoice->invoice_number }}">
                            </div>
                            
                            <!-- Invoice Date -->
                            <div>
                                <x-form.input 
                                    type="date"
                                    id="invoice_date"
                                    name="invoice_date"
                                    label="Invoice Date"
                                    :value="old('invoice_date', $invoice->invoice_date->format('Y-m-d'))"
                                    :disabled="$invoice->status !== 'draft'"
                                    :error="$errors->first('invoice_date')"
                                />
                            </div>
                            
                            <!-- Due Date -->
                            <div>
                                <x-form.input 
                                    type="date"
                                    id="due_date"
                                    name="due_date"
                                    label="Due Date"
                                    :value="old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '')"
                                    :disabled="$invoice->status !== 'draft'"
                                    :error="$errors->first('due_date')"
                                />
                            </div>
                            
                            <!-- Invoice Template -->
                            <div>
                                <x-form.select
                                    id="template_id"
                                    name="template_id"
                                    label="Invoice Template"
                                    :options="$templates->pluck('name', 'id')->toArray()"
                                    :value="old('template_id', $invoice->template_id)"
                                    required
                                    :disabled="$invoice->status !== 'draft'"
                                    :error="$errors->first('template_id')"
                                />
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div>
                            <x-form.textarea 
                                id="notes"
                                name="notes"
                                label="Invoice Notes"
                                :value="old('notes', $invoice->notes)"
                                rows="3"
                                :disabled="$invoice->status !== 'draft'"
                                help-text="These notes will appear on the invoice"
                                :error="$errors->first('notes')"
                            />
                        </div>
                    </div>
                </x-card>
                
                <!-- Order Items (Read-only) -->
                <x-card title="Order Details">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invoice->order->orderItems as $item)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->projectProduct->product->name }}</div>
                                            <div class="text-xs text-gray-500">SKU: {{ $item->projectProduct->product->sku }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-500">
                                            Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-500">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-500">
                                            Rp {{ number_format($item->discount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-gray-900">
                                            Rp {{ number_format($item->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-700">
                                        Subtotal:
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                        Rp {{ number_format($invoice->order->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-700">
                                        Tax ({{ $invoice->order->tax_percentage }}%):
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                        Rp {{ number_format($invoice->order->tax_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @if($invoice->order->shipping_cost > 0)
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-700">
                                            Shipping:
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                            Rp {{ number_format($invoice->order->shipping_cost, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                                @if($invoice->order->discount > 0)
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-700">
                                            Discount:
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                            - Rp {{ number_format($invoice->order->discount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                                <tr class="bg-gray-100">
                                    <td colspan="4" class="px-4 py-3 text-right text-sm font-bold text-gray-700">
                                        Total Amount:
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-bold text-gray-900">
                                        Rp {{ number_format($invoice->order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @if($invoice->order->payment_type === 'down_payment')
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-700">
                                            Down Payment ({{ $invoice->order->down_payment_percentage }}%):
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                            Rp {{ number_format($invoice->order->down_payment_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-700">
                                            Remaining Payment:
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                            Rp {{ number_format($invoice->order->remaining_payment, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </x-card>
            </div>
            
            <!-- Right Column - Additional Details -->
            <div class="space-y-6">
                <x-card title="Payment Details">
                    <div class="space-y-4">
                        <!-- Payment Method (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 px-3 py-2 text-gray-700 sm:text-sm">
                                {{ $invoice->order->paymentMethod->name }}
                            </div>
                        </div>
                        
                        <!-- Payment Instructions -->
                        <div>
                            <x-form.checkbox
                                id="include_payment_instructions"
                                name="include_payment_instructions"
                                :checked="old('include_payment_instructions', $invoice->include_payment_instructions)"
                                value="1"
                                label="Include payment instructions on invoice"
                                :disabled="$invoice->status !== 'draft'"
                            />
                        </div>
                    </div>
                </x-card>
                
                <x-card title="Recipient Information">
                    <div class="space-y-4">
                        <!-- Customer Details (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                            <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 px-3 py-2 text-gray-700 sm:text-sm">
                                {{ $invoice->order->customer->name }}
                            </div>
                        </div>
                        
                        <!-- Email to Send -->
                        <div>
                            <x-form.input 
                                type="email"
                                id="recipient_email"
                                name="recipient_email"
                                label="Send Invoice To (Email)"
                                :value="old('recipient_email', $invoice->recipient_email ?: $invoice->order->customer->email)"
                                :error="$errors->first('recipient_email')"
                                :disabled="$invoice->status !== 'draft'"
                            />
                        </div>
                    </div>
                </x-card>
                
                <x-card title="Actions">
                    <div class="space-y-3">
                        @if($invoice->status === 'draft')
                            <x-button 
                                type="submit" 
                                variant="primary"
                                icon="fas fa-save"
                                name="action"
                                value="save"
                                full-width="true"
                            >
                                Update Invoice
                            </x-button>
                            
                            <x-button 
                                type="submit"
                                variant="success"
                                icon="fas fa-paper-plane"
                                name="action"
                                value="save_send"
                                full-width="true"
                            >
                                Save & Mark as Sent
                            </x-button>
                        @endif
                        
                        <x-button 
                            href="{{ route('organization.invoices.show', $invoice) }}" 
                            variant="secondary"
                            full-width="true"
                        >
                            Cancel
                        </x-button>
                    </div>
                </x-card>
            </div>
        </div>
    </form>
</x-organization-layout>