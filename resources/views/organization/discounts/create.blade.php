<x-organization-layout>
    @section('title', 'Create Discount')
    
    @php
        $breadcrumbs = [
            'Discounts' => route('organization.discounts.index'),
            'Create' => '#'
        ];
    @endphp
    
    <x-card class="max-w-3xl mx-auto">
        <form action="{{ route('organization.discounts.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-4">Discount Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Discount Code -->
                    <div class="md:col-span-2">
                        <div class="flex items-end gap-3">
                            <div class="flex-grow">
                                <x-form.input 
                                    id="code"
                                    name="code"
                                    label="Discount Code"
                                    :value="old('code')"
                                    required
                                    help-text="Unique code customers will use to apply the discount"
                                    :error="$errors->first('code')"
                                />
                            </div>
                            <x-button 
                                type="button"
                                variant="secondary"
                                id="generate-code"
                                onclick="generateDiscountCode()"
                            >
                                Generate
                            </x-button>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="md:col-span-2">
                        <x-form.input 
                            id="description"
                            name="description"
                            label="Description"
                            :value="old('description')"
                            required
                            help-text="Brief description of this discount"
                            :error="$errors->first('description')"
                        />
                    </div>
                    
                    <!-- Discount Type -->
                    <div x-data="{ discountType: '{{ old('type', 'percentage') }}' }">
                        <x-form.select
                            id="type"
                            name="type"
                            label="Discount Type"
                            :options="[
                                'percentage' => 'Percentage',
                                'fixed' => 'Fixed Amount'
                            ]"
                            :value="old('type', 'percentage')"
                            required
                            x-model="discountType"
                            :error="$errors->first('type')"
                        />
                        
                        <!-- Discount Value -->
                        <div class="mt-4">
                            <x-form.input 
                                type="number"
                                id="value"
                                name="value"
                                label="Discount Value"
                                :value="old('value')"
                                required
                                min="0"
                                step="0.01"
                                :suffix="$discountType === 'percentage' ? '%' : ''"
                                :prefix="$discountType === 'fixed' ? 'Rp' : ''"
                                x-bind:suffix="discountType === 'percentage' ? '%' : ''"
                                x-bind:prefix="discountType === 'fixed' ? 'Rp' : ''"
                                :error="$errors->first('value')"
                            />
                        </div>
                        
                        <!-- Max Discount Amount (only for percentage) -->
                        <div class="mt-4" x-show="discountType === 'percentage'">
                            <x-form.input 
                                type="number"
                                id="max_discount_amount"
                                name="max_discount_amount"
                                label="Maximum Discount Amount"
                                :value="old('max_discount_amount')"
                                min="0"
                                step="0.01"
                                prefix="Rp"
                                help-text="Leave empty for no maximum"
                                :error="$errors->first('max_discount_amount')"
                            />
                        </div>
                    </div>
                    
                    <!-- Minimum Order Value -->
                    <div>
                        <x-form.input 
                            type="number"
                            id="min_order_value"
                            name="min_order_value"
                            label="Minimum Order Value"
                            :value="old('min_order_value')"
                            min="0"
                            step="0.01"
                            prefix="Rp"
                            help-text="Leave empty if no minimum required"
                            :error="$errors->first('min_order_value')"
                        />
                    </div>
                </div>
                
                <!-- Usage Limits -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Usage Limits</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Usage Limit -->
                        <div>
                            <x-form.input 
                                type="number"
                                id="usage_limit"
                                name="usage_limit"
                                label="Usage Limit"
                                :value="old('usage_limit')"
                                min="0"
                                help-text="Maximum number of times this discount can be used. Leave empty for unlimited."
                                :error="$errors->first('usage_limit')"
                            />
                        </div>
                        
                        <!-- Per Customer Limit -->
                        <div>
                            <x-form.input 
                                type="number"
                                id="per_customer_limit"
                                name="per_customer_limit"
                                label="Per Customer Limit"
                                :value="old('per_customer_limit', 1)"
                                min="0"
                                help-text="How many times a customer can use this discount. 0 for unlimited."
                                :error="$errors->first('per_customer_limit')"
                            />
                        </div>
                    </div>
                </div>
                
                <!-- Validity Period -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Validity Period</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Valid From -->
                        <div>
                            <x-form.input 
                                type="date"
                                id="valid_from"
                                name="valid_from"
                                label="Valid From"
                                :value="old('valid_from')"
                                help-text="Leave empty to make active immediately"
                                :error="$errors->first('valid_from')"
                            />
                        </div>
                        
                        <!-- Valid Until -->
                        <div>
                            <x-form.input 
                                type="date"
                                id="valid_until"
                                name="valid_until"
                                label="Valid Until"
                                :value="old('valid_until')"
                                help-text="Leave empty for no expiration"
                                :error="$errors->first('valid_until')"
                            />
                        </div>
                    </div>
                </div>
                
                <!-- Additional Options -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Options</h3>
                    
                    <div class="space-y-4">
                        <!-- Active Status -->
                        <x-form.checkbox
                            id="is_active"
                            name="is_active"
                            :checked="old('is_active', true)"
                            value="1"
                            label="Make this discount active immediately"
                        />
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 border-t border-gray-200 pt-6">
                    <x-button 
                        href="{{ route('organization.discounts.index') }}" 
                        variant="secondary"
                    >
                        Cancel
                    </x-button>
                    
                    <x-button 
                        type="submit" 
                        variant="primary"
                        icon="fas fa-save"
                    >
                        Create Discount
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>
    
    @push('scripts')
    <script>
        function generateDiscountCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < 8; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('code').value = result;
        }
    </script>
    @endpush
</x-organization-layout>