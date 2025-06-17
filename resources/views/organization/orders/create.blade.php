<x-organization-layout>
    @section('title', 'Create Order')
    
    @php
        $breadcrumbs = [
            'Orders' => route('organization.orders.index'),
            'Create' => '#'
        ];
    @endphp
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6">
            <form id="order-form" action="{{ route('organization.orders.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Project Selection -->
                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">Project <span class="text-red-500">*</span></label>
                        <select name="project_id" id="project_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" required>
                            <option value="">-- Select Project --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }} ({{ $project->type === 'preorder' ? 'Pre-Order' : 'Direct Order' }})
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Customer Selection -->
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Customer <span class="text-red-500">*</span></label>
                        <div class="flex">
                            <select name="customer_id" id="customer_id" class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" required>
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->phone_number ?? 'No Phone' }})
                                    </option>
                                @endforeach
                            </select>
                            <a href="{{ route('organization.customers.create') }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm rounded-r-md hover:bg-gray-100">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Order Date -->
                    <div>
                        <label for="order_date" class="block text-sm font-medium text-gray-700 mb-1">Order Date <span class="text-red-500">*</span></label>
                        <input type="date" name="order_date" id="order_date" value="{{ old('order_date', date('Y-m-d')) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" required>
                        @error('order_date')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method_id" class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                        <div class="flex">
                            <select name="payment_method_id" id="payment_method_id" class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" required>
                                <option value="">-- Select Payment Method --</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}" {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }} ({{ ucfirst(str_replace('_', ' ', $method->payment_type)) }})
                                    </option>
                                @endforeach
                            </select>
                            <a href="{{ route('organization.payment-methods.create') }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm rounded-r-md hover:bg-gray-100">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                        @error('payment_method_id')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Payment Type -->
                    <div>
                        <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-1">Payment Type <span class="text-red-500">*</span></label>
                        <select name="payment_type" id="payment_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" required>
                            <option value="full_payment" {{ old('payment_type') == 'full_payment' ? 'selected' : '' }}>Full Payment</option>
                            <option value="down_payment" {{ old('payment_type') == 'down_payment' ? 'selected' : '' }}>Down Payment</option>
                        </select>
                        @error('payment_type')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Down Payment Amount (conditionally shown) -->
                    <div id="down-payment-container" class="{{ old('payment_type') == 'down_payment' ? '' : 'hidden' }}">
                        <label for="down_payment_amount" class="block text-sm font-medium text-gray-700 mb-1">Down Payment Amount <span class="text-red-500">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="down_payment_amount" id="down_payment_amount" value="{{ old('down_payment_amount') }}" class="block w-full rounded-md border-gray-300 pl-10 pr-12 focus:border-sky-500 focus:ring-sky-500 sm:text-sm" placeholder="0">
                        </div>
                        @error('down_payment_amount')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Shipping Method -->
                    <div>
                        <label for="shipping_method" class="block text-sm font-medium text-gray-700 mb-1">Shipping Method <span class="text-red-500">*</span></label>
                        <select name="shipping_method" id="shipping_method" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" required>
                            <option value="self_pickup" {{ old('shipping_method') == 'self_pickup' ? 'selected' : '' }}>Self Pickup</option>
                            <option value="courier" {{ old('shipping_method') == 'courier' ? 'selected' : '' }}>Courier</option>
                        </select>
                        @error('shipping_method')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Courier Selection (conditionally shown) -->
                    <div id="courier-container" class="{{ old('shipping_method') == 'courier' ? '' : 'hidden' }}">
                        <label for="courier_id" class="block text-sm font-medium text-gray-700 mb-1">Courier <span class="text-red-500">*</span></label>
                        <select name="courier_id" id="courier_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" {{ old('shipping_method') == 'courier' ? 'required' : '' }}>
                            <option value="">-- Select Courier --</option>
                            @foreach($couriers as $courier)
                                <option value="{{ $courier->id }}" {{ old('courier_id') == $courier->id ? 'selected' : '' }}>
                                    {{ $courier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('courier_id')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tracking Number (conditionally shown) -->
                    <div id="tracking-container" class="{{ old('shipping_method') == 'courier' ? '' : 'hidden' }}">
                        <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-1">Tracking Number</label>
                        <input type="text" name="tracking_number" id="tracking_number" value="{{ old('tracking_number') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" placeholder="Optional">
                        @error('tracking_number')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Shipping Cost (conditionally shown) -->
                    <div id="shipping-cost-container" class="{{ old('shipping_method') == 'courier' ? '' : 'hidden' }}">
                        <label for="shipping_cost" class="block text-sm font-medium text-gray-700 mb-1">Shipping Cost</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="shipping_cost" id="shipping_cost" value="{{ old('shipping_cost') }}" class="block w-full rounded-md border-gray-300 pl-10 pr-12 focus:border-sky-500 focus:ring-sky-500 sm:text-sm" placeholder="0">
                        </div>
                        @error('shipping_cost')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" placeholder="Optional notes about the order">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Order Items Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Order Items</h3>
                    
                    <div id="project-products-container" class="{{ old('project_id') ? '' : 'hidden' }}">
                        <table class="min-w-full divide-y divide-gray-200 mb-4">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="order-items-container" class="bg-white divide-y divide-gray-200">
                                <tr id="no-products-row">
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Select a project to view available products.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <button type="button" id="add-item-btn" class="hidden mt-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            <i class="fas fa-plus mr-2"></i>
                            Add Item
                        </button>
                    </div>
                    
                    <div id="no-project-selected" class="{{ old('project_id') ? 'hidden' : '' }} bg-yellow-50 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">No project selected</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Please select a project to view available products.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Summary -->
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <dl class="grid grid-cols-2 gap-x-4 gap-y-2">
                            <dt class="text-sm font-medium text-gray-500">Subtotal:</dt>
                            <dd class="text-sm font-medium text-gray-900 text-right" id="subtotal">Rp 0</dd>
                            
                            <dt class="text-sm font-medium text-gray-500">Tax:</dt>
                            <dd class="text-sm font-medium text-gray-900 text-right" id="tax">Rp 0</dd>
                            
                            <dt class="text-sm font-medium text-gray-500">Shipping:</dt>
                            <dd class="text-sm font-medium text-gray-900 text-right" id="shipping">Rp 0</dd>
                            
                            <dt class="text-sm font-medium text-gray-900 border-t border-gray-200 pt-2 mt-2">Total:</dt>
                            <dd class="text-lg font-semibold text-gray-900 text-right border-t border-gray-200 pt-2 mt-2" id="total">Rp 0</dd>
                        </dl>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('organization.orders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </a>
                    
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                        <i class="fas fa-save mr-2"></i>
                        Create Order
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Item Template (Hidden) -->
    <template id="order-item-template">
        <tr class="order-item">
            <td class="px-6 py-4 whitespace-nowrap">
                <select name="items[INDEX][project_product_id]" class="project-product-select block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" required>
                    <option value="">-- Select Product --</option>
                </select>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="item-price text-sm text-gray-900">Rp 0</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="number" name="items[INDEX][quantity]" class="item-quantity block w-20 rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" min="1" value="1" required>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="number" name="items[INDEX][discount]" class="item-discount block w-28 rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" min="0" value="0">
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                <span class="item-total">Rp 0</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button type="button" class="remove-item text-rose-600 hover:text-rose-900">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    </template>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Element references
            const projectSelect = document.getElementById('project_id');
            const paymentTypeSelect = document.getElementById('payment_type');
            const downPaymentContainer = document.getElementById('down-payment-container');
            const downPaymentInput = document.getElementById('down_payment_amount');
            const shippingMethodSelect = document.getElementById('shipping_method');
            const courierContainer = document.getElementById('courier-container');
            const courierSelect = document.getElementById('courier_id');
            const trackingContainer = document.getElementById('tracking-container');
            const shippingCostContainer = document.getElementById('shipping-cost-container');
            const shippingCostInput = document.getElementById('shipping_cost');
            const projectProductsContainer = document.getElementById('project-products-container');
            const noProjectSelected = document.getElementById('no-project-selected');
            const orderItemsContainer = document.getElementById('order-items-container');
            const noProductsRow = document.getElementById('no-products-row');
            const addItemBtn = document.getElementById('add-item-btn');
            const orderForm = document.getElementById('order-form');
            
            // Variables
            let itemIndex = 0;
            let projectProducts = [];
            let taxRate = 0;
            
            // Initialize
            init();
            
            function init() {
                // Event listeners
                projectSelect.addEventListener('change', handleProjectChange);
                paymentTypeSelect.addEventListener('change', handlePaymentTypeChange);
                shippingMethodSelect.addEventListener('change', handleShippingMethodChange);
                shippingCostInput.addEventListener('input', updateTotals);
                addItemBtn.addEventListener('click', addOrderItem);
                
                // Initialize conditional fields
                handlePaymentTypeChange();
                handleShippingMethodChange();
                
                // Load project products if project is already selected
                if (projectSelect.value) {
                    loadProjectProducts(projectSelect.value);
                }
                
                // Form submission validation
                orderForm.addEventListener('submit', function(event) {
                    const orderItems = document.querySelectorAll('.order-item');
                    if (orderItems.length === 0) {
                        event.preventDefault();
                        alert('Please add at least one product to the order.');
                    }
                });
            }
            
            function handleProjectChange() {
                const projectId = projectSelect.value;
                
                if (projectId) {
                    loadProjectProducts(projectId);
                    projectProductsContainer.classList.remove('hidden');
                    noProjectSelected.classList.add('hidden');
                } else {
                    projectProductsContainer.classList.add('hidden');
                    noProjectSelected.classList.remove('hidden');
                    clearOrderItems();
                    addItemBtn.classList.add('hidden');
                }
            }
            
            function handlePaymentTypeChange() {
                const paymentType = paymentTypeSelect.value;
                
                if (paymentType === 'down_payment') {
                    downPaymentContainer.classList.remove('hidden');
                    downPaymentInput.setAttribute('required', 'required');
                } else {
                    downPaymentContainer.classList.add('hidden');
                    downPaymentInput.removeAttribute('required');
                }
            }
            
            function handleShippingMethodChange() {
                const shippingMethod = shippingMethodSelect.value;
                
                if (shippingMethod === 'courier') {
                    courierContainer.classList.remove('hidden');
                    trackingContainer.classList.remove('hidden');
                    shippingCostContainer.classList.remove('hidden');
                    courierSelect.setAttribute('required', 'required');
                } else {
                    courierContainer.classList.add('hidden');
                    trackingContainer.classList.add('hidden');
                    shippingCostContainer.classList.add('hidden');
                    courierSelect.removeAttribute('required');
                    shippingCostInput.value = '';
                }
                
                updateTotals();
            }
            
            function loadProjectProducts(projectId) {
                fetch(`/organization/orders/projects/${projectId}/products`)
                    .then(response => response.json())
                    .then(data => {
                        projectProducts = data;
                        
                        // Get tax information for the project
                        // This would typically come from the backend
                        taxRate = 10; // Default to 10% for demo
                        
                        clearOrderItems();
                        
                        if (projectProducts.length > 0) {
                            addItemBtn.classList.remove('hidden');
                            noProductsRow.classList.add('hidden');
                            addOrderItem();
                        } else {
                            addItemBtn.classList.add('hidden');
                            noProductsRow.classList.remove('hidden');
                            noProductsRow.querySelector('td').textContent = 'No products available for this project.';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading project products:', error);
                    });
            }
            
            function addOrderItem() {
                // Clone the template
                const template = document.getElementById('order-item-template');
                const clone = document.importNode(template.content, true);
                
                // Update index
                const currentIndex = itemIndex++;
                const elements = clone.querySelectorAll('[name*="INDEX"]');
                elements.forEach(element => {
                    element.name = element.name.replace('INDEX', currentIndex);
                });
                
                // Populate product select
                const productSelect = clone.querySelector('.project-product-select');
                projectProducts.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = `${product.product.name} - Rp ${formatNumber(product.price)}`;
                    option.dataset.price = product.price;
                    productSelect.appendChild(option);
                });
                
                // Add event listeners
                productSelect.addEventListener('change', () => updateItemPrice(productSelect.closest('tr')));
                clone.querySelector('.item-quantity').addEventListener('input', () => updateItemPrice(productSelect.closest('tr')));
                clone.querySelector('.item-discount').addEventListener('input', () => updateItemPrice(productSelect.closest('tr')));
                clone.querySelector('.remove-item').addEventListener('click', function() {
                    this.closest('tr').remove();
                    updateTotals();
                    
                    if (document.querySelectorAll('.order-item').length === 0) {
                        noProductsRow.classList.remove('hidden');
                        noProductsRow.querySelector('td').textContent = 'No items added. Click "Add Item" to add products.';
                    }
                });
                
                // Add to container
                noProductsRow.classList.add('hidden');
                orderItemsContainer.appendChild(clone);
                
                // Initialize price
                updateItemPrice(document.querySelectorAll('.order-item')[document.querySelectorAll('.order-item').length - 1]);
            }
            
            function updateItemPrice(row) {
                const select = row.querySelector('.project-product-select');
                const quantityInput = row.querySelector('.item-quantity');
                const discountInput = row.querySelector('.item-discount');
                const priceSpan = row.querySelector('.item-price');
                const totalSpan = row.querySelector('.item-total');
                
                if (select.value) {
                    const selectedOption = select.options[select.selectedIndex];
                    const price = parseFloat(selectedOption.dataset.price);
                    const quantity = parseInt(quantityInput.value) || 1;
                    const discount = parseFloat(discountInput.value) || 0;
                    
                    priceSpan.textContent = `Rp ${formatNumber(price)}`;
                    
                    const total = (price * quantity) - discount;
                    totalSpan.textContent = `Rp ${formatNumber(total)}`;
                    
                    updateTotals();
                }
            }
            
            function updateTotals() {
                let subtotal = 0;
                
                // Calculate subtotal
                document.querySelectorAll('.order-item').forEach(row => {
                    const totalText = row.querySelector('.item-total').textContent;
                    const total = parseFloat(totalText.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
                    subtotal += total;
                });
                
                // Calculate tax
                const taxAmount = subtotal * (taxRate / 100);
                
                // Get shipping cost
                const shippingCost = parseFloat(shippingCostInput.value) || 0;
                
                // Calculate total
                const total = subtotal + taxAmount + shippingCost;
                
                // Update display
                document.getElementById('subtotal').textContent = `Rp ${formatNumber(subtotal)}`;
                document.getElementById('tax').textContent = `Rp ${formatNumber(taxAmount)}`;
                document.getElementById('shipping').textContent = `Rp ${formatNumber(shippingCost)}`;
                document.getElementById('total').textContent = `Rp ${formatNumber(total)}`;
            }
            
            function clearOrderItems() {
                const orderItems = document.querySelectorAll('.order-item');
                orderItems.forEach(item => item.remove());
                
                noProductsRow.classList.remove('hidden');
                updateTotals();
            }
            
            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        });
    </script>
    @endpush
</x-organization-layout>