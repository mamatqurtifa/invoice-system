<x-organization-layout>
    @section('title', 'Create Order')
    
    @php
        $breadcrumbs = [
            'Orders' => route('organization.orders.index'),
            'Create' => '#'
        ];
    @endphp
    
    <x-card class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Order</h1>
                <p class="mt-1 text-sm text-gray-500">Create a new order for your project</p>
            </div>
        </div>
    </x-card>
    
    <form id="order-form" action="{{ route('organization.orders.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <x-card title="Order Information">
                    <div class="space-y-4">
                        <!-- Project Selection -->
                        <div id="project-selection">
                            <x-form.select
                                id="project_id"
                                name="project_id"
                                label="Select Project"
                                :options="$projects->pluck('name', 'id')->toArray()"
                                :value="old('project_id', request('project_id'))"
                                required
                                x-model="projectId"
                                @change="loadProjectProducts"
                                :error="$errors->first('project_id')"
                            />
                        </div>
                        
                        <!-- Customer Selection -->
                        <div id="customer-section" x-data="{ showNewCustomerForm: {{ old('customer_id') ? 'false' : (old('new_customer') ? 'true' : 'false') }} }">
                            <div class="flex justify-between mb-2">
                                <label for="customer_id" class="block text-sm font-medium text-gray-700">Select Customer</label>
                                <button type="button" @click="showNewCustomerForm = !showNewCustomerForm" class="text-sm text-sky-600 hover:text-sky-800">
                                    <span x-show="!showNewCustomerForm">+ Add New Customer</span>
                                    <span x-show="showNewCustomerForm">Select Existing Customer</span>
                                </button>
                            </div>
                            
                            <div x-show="!showNewCustomerForm">
                                <x-form.select
                                    id="customer_id"
                                    name="customer_id"
                                    :options="$customers->pluck('name', 'id')->toArray()"
                                    :value="old('customer_id', request('customer_id'))"
                                    required
                                    x-bind:required="!showNewCustomerForm"
                                    :error="$errors->first('customer_id')"
                                />
                            </div>
                            
                            <div x-show="showNewCustomerForm">
                                <input type="hidden" name="new_customer" value="1" x-bind:disabled="!showNewCustomerForm">
                                
                                <div class="space-y-4">
                                    <!-- Customer Name -->
                                    <x-form.input 
                                        id="customer_name"
                                        name="customer_name"
                                        label="Customer Name"
                                        :value="old('customer_name')"
                                        x-bind:required="showNewCustomerForm"
                                        :error="$errors->first('customer_name')"
                                    />
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Customer Email -->
                                        <x-form.input 
                                            type="email"
                                            id="customer_email"
                                            name="customer_email"
                                            label="Email Address"
                                            :value="old('customer_email')"
                                            :error="$errors->first('customer_email')"
                                        />
                                        
                                        <!-- Customer Phone -->
                                        <x-form.input 
                                            id="customer_phone"
                                            name="customer_phone"
                                            label="Phone Number"
                                            :value="old('customer_phone')"
                                            :error="$errors->first('customer_phone')"
                                        />
                                    </div>
                                    
                                    <!-- Customer Address -->
                                    <x-form.textarea 
                                        id="customer_address"
                                        name="customer_address"
                                        label="Address"
                                        :value="old('customer_address')"
                                        rows="2"
                                        :error="$errors->first('customer_address')"
                                    />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form.input 
                                type="date"
                                id="order_date"
                                name="order_date"
                                label="Order Date"
                                :value="old('order_date', date('Y-m-d'))"
                                required
                                :error="$errors->first('order_date')"
                            />
                            
                            <!-- Payment Type -->
                            <div x-data="{ paymentType: '{{ old('payment_type', 'full_payment') }}' }">
                                <x-form.select
                                    id="payment_type"
                                    name="payment_type"
                                    label="Payment Type"
                                    :options="[
                                        'full_payment' => 'Full Payment',
                                        'down_payment' => 'Down Payment'
                                    ]"
                                    :value="old('payment_type', 'full_payment')"
                                    x-model="paymentType"
                                    required
                                    :error="$errors->first('payment_type')"
                                />
                                
                                <div x-show="paymentType === 'down_payment'" class="mt-4" id="down-payment-fields">
                                    <x-form.input 
                                        type="number"
                                        id="down_payment_percentage"
                                        name="down_payment_percentage"
                                        label="Down Payment Percentage"
                                        :value="old('down_payment_percentage', 50)"
                                        min="1"
                                        max="99"
                                        suffix="%"
                                        :error="$errors->first('down_payment_percentage')"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>
                
                <x-card title="Product Selection" 
                    id="product-selection"
                    x-data="{
                        selectedProducts: [],
                        products: [],
                        projectId: '{{ old('project_id', request('project_id')) }}',
                        
                        async loadProjectProducts() {
                            if (!this.projectId) return;
                            
                            try {
                                const response = await fetch(`{{ route('organization.orders.project-products', '') }}/${this.projectId}`);
                                const data = await response.json();
                                
                                if (data.success) {
                                    this.products = data.products;
                                    
                                    // If there's a previous selection from validation error, restore it
                                    const oldProductIds = document.querySelectorAll('[name^=\"product_ids\"]');
                                    if (oldProductIds.length > 0) {
                                        oldProductIds.forEach(input => {
                                            const index = parseInt(input.dataset.index || 0);
                                            const productId = input.value;
                                            const oldQuantity = document.querySelector(`[name=\"quantities[${index}]\"]`).value;
                                            
                                            const product = this.products.find(p => p.id == productId);
                                            if (product) {
                                                this.selectedProducts.push({
                                                    id: product.id,
                                                    name: product.name,
                                                    price: product.price,
                                                    quantity: oldQuantity,
                                                    discount: 0,
                                                    subtotal: product.price * oldQuantity
                                                });
                                            }
                                        });
                                    }
                                    
                                    this.updateOrderTotals();
                                }
                            } catch (error) {
                                console.error('Error loading products:', error);
                            }
                        },
                        
                        addProduct(productId) {
                            const product = this.products.find(p => p.id == productId);
                            if (!product) return;
                            
                            // Check if already in selected products
                            const existing = this.selectedProducts.find(p => p.id == product.id);
                            if (existing) {
                                existing.quantity++;
                                existing.subtotal = existing.price * existing.quantity;
                            } else {
                                this.selectedProducts.push({
                                    id: product.id,
                                    name: product.name,
                                    price: product.price,
                                    quantity: 1,
                                    discount: 0,
                                    subtotal: product.price
                                });
                            }
                            
                            this.updateOrderTotals();
                        },
                        
                        removeProduct(index) {
                            this.selectedProducts.splice(index, 1);
                            this.updateOrderTotals();
                        },
                        
                        updateQuantity(index, event) {
                            const quantity = parseInt(event.target.value) || 0;
                            if (quantity < 1) event.target.value = 1;
                            
                            this.selectedProducts[index].quantity = Math.max(1, quantity);
                            this.updateProductSubtotal(index);
                        },
                        
                        updateProductSubtotal(index) {
                            const product = this.selectedProducts[index];
                            product.subtotal = (product.price * product.quantity) - product.discount;
                            this.updateOrderTotals();
                        },
                        
                        updateDiscount(index, event) {
                            const discount = parseFloat(event.target.value) || 0;
                            if (discount < 0) event.target.value = 0;
                            
                            this.selectedProducts[index].discount = Math.max(0, discount);
                            this.updateProductSubtotal(index);
                        },
                        
                        updateOrderTotals() {
                            // This will be handled by the computed properties
                            // and by the shipping/tax sections
                        },
                        
                        // Computed properties
                        get subtotal() {
                            return this.selectedProducts.reduce((sum, product) => sum + product.subtotal, 0);
                        },
                        
                        get hasProducts() {
                            return this.selectedProducts.length > 0;
                        }
                    }"
                    x-init="loadProjectProducts()"
                >
                    <div class="space-y-6">
                        <!-- Product Selector -->
                        <div class="border border-gray-200 rounded-md p-4">
                            <x-form.select
                                id="product_selector"
                                label="Add Product"
                                :options="[]"
                                x-show="products.length > 0"
                                placeholder="Select a product to add"
                                x-on:change="addProduct($event.target.value); $event.target.value = '';"
                            >
                                <template x-for="product in products" :key="product.id">
                                    <option :value="product.id" x-text="product.name + ' (Rp ' + product.price.toLocaleString() + ')'"></option>
                                </template>
                            </x-form.select>
                            
                            <div x-show="!products.length && projectId" class="text-center py-3">
                                <p class="text-sm text-gray-500">No products available for this project.</p>
                                <a :href="`{{ route('organization.projects.add-products', '') }}/${projectId}`" class="inline-block mt-2 text-sm text-sky-600 hover:text-sky-800">
                                    Add products to this project
                                </a>
                            </div>
                            
                            <div x-show="!projectId" class="text-center py-3">
                                <p class="text-sm text-gray-500">Please select a project first.</p>
                            </div>
                        </div>
                        
                        <!-- Selected Products Table -->
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Selected Products</h4>
                            
                            <div class="border border-gray-200 rounded-md overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                            <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                            <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                            <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                            <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                            <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200" x-show="hasProducts">
                                        <template x-for="(product, index) in selectedProducts" :key="index">
                                            <tr>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    <input type="hidden" :name="`product_ids[${index}]`" :value="product.id" :data-index="index">
                                                    <span x-text="product.name" class="text-sm font-medium text-gray-900"></span>
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap text-right text-sm text-gray-500">
                                                    <span x-text="'Rp ' + product.price.toLocaleString()"></span>
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    <input 
                                                        type="number" 
                                                        :name="`quantities[${index}]`" 
                                                        :value="product.quantity" 
                                                        min="1"
                                                        class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm text-right"
                                                        @input="updateQuantity(index, $event)"
                                                    >
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    <input 
                                                        type="number" 
                                                        :name="`discounts[${index}]`" 
                                                        :value="product.discount" 
                                                        min="0"
                                                        class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm text-right"
                                                        @input="updateDiscount(index, $event)"
                                                    >
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                                    <span x-text="'Rp ' + product.subtotal.toLocaleString()"></span>
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap text-right text-sm font-medium">
                                                    <button type="button" @click="removeProduct(index)" class="text-rose-600 hover:text-rose-900">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot class="bg-gray-50" x-show="hasProducts">
                                        <tr>
                                            <td colspan="4" class="px-3 py-2 text-right text-sm font-medium text-gray-700">Subtotal:</td>
                                            <td class="px-3 py-2 text-right text-sm font-medium text-gray-900">
                                                <span x-text="'Rp ' + subtotal.toLocaleString()"></span>
                                                <input type="hidden" name="subtotal" x-bind:value="subtotal">
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                
                                <div x-show="!hasProducts" class="text-center py-4 text-sm text-gray-500">
                                    <p>No products selected yet.</p>
                                    <p class="text-xs mt-1">Select a product from the dropdown above.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>
                
                <x-card title="Shipping & Additional Options">
                    <div class="space-y-6" x-data="{ 
                        shippingMethod: '{{ old('shipping_method', 'pickup') }}',
                        selectedCourier: '{{ old('courier_id') }}',
                        taxPercentage: {{ old('tax_percentage', 0) }},
                        orderDiscount: {{ old('order_discount', 0) }},
                        shippingCost: {{ old('shipping_cost', 0) }},
                        
                        get taxAmount() {
                            return (this.taxPercentage / 100) * this.subtotal;
                        },
                        
                        get totalAmount() {
                            return this.subtotal + this.taxAmount + this.shippingCost - this.orderDiscount;
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
                                :value="old('shipping_method', 'pickup')"
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
                                :value="old('courier_id')"
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
                                    :value="old('shipping_cost', 0)"
                                    x-model="shippingCost"
                                    prefix="Rp"
                                    :error="$errors->first('shipping_cost')"
                                />
                                
                                <x-form.input 
                                    id="tracking_number"
                                    name="tracking_number"
                                    label="Tracking Number (Optional)"
                                    :value="old('tracking_number')"
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
                                :value="old('tax_percentage', 0)"
                                x-model="taxPercentage"
                                suffix="%"
                                :error="$errors->first('tax_percentage')"
                            />
                            
                            <x-form.input 
                                id="order_discount"
                                name="order_discount"
                                label="Order Discount"
                                type="number"
                                min="0"
                                step="0.01"
                                :value="old('order_discount', 0)"
                                x-model="orderDiscount"
                                prefix="Rp"
                                :error="$errors->first('order_discount')"
                            />
                        </div>
                        
                        <!-- Notes -->
                        <x-form.textarea 
                            id="notes"
                            name="notes"
                            label="Order Notes (Optional)"
                            :value="old('notes')"
                            rows="3"
                            :error="$errors->first('notes')"
                        />
                        
                        <!-- Calculate tax and total -->
                        <div class="border-t border-gray-200 pt-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dl class="space-y-1">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-500">Subtotal:</dt>
                                        <dd class="text-sm font-medium text-gray-900" x-text="'Rp ' + subtotal.toLocaleString()"></dd>
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
                            :value="old('payment_method_id')"
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
                                'completed' => 'Completed'
                            ]"
                            :value="old('payment_status', 'pending')"
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
                            :value="old('status', 'pending')"
                            required
                            :error="$errors->first('status')"
                        />
                        
                        <!-- Create Invoice -->
                        <div class="mt-4">
                            <x-form.checkbox
                                id="create_invoice"
                                name="create_invoice"
                                :checked="old('create_invoice', true)"
                                value="1"
                                label="Create invoice automatically"
                                help-text="An invoice will be generated for this order"
                            />
                        </div>
                    </div>
                </x-card>
                
                <x-card title="Summary" 
                    x-data="{
                        get formValid() {
                            return selectedProducts.length > 0;
                        }
                    }"
                >
                    <div class="space-y-4">
                        <div class="bg-yellow-50 p-4 rounded-md" x-show="!hasProducts">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Attention</h3>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        You need to add at least one product to create an order.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Products:</span>
                                <span class="text-sm text-gray-900" x-text="selectedProducts.length + ' items'"></span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Total Amount:</span>
                                <span class="text-sm font-medium text-gray-900" x-text="'Rp ' + totalAmount.toLocaleString()"></span>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex flex-col gap-3">
                            <x-button 
                                type="submit" 
                                variant="primary"
                                icon="fas fa-check-circle"
                                full-width="true"
                                x-bind:disabled="!formValid"
                            >
                                Create Order
                            </x-button>
                            
                            <x-button 
                                href="{{ route('organization.orders.index') }}" 
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