<x-organization-layout>
    @section('title', 'Create Payment Method')
    
    @php
        $breadcrumbs = [
            'Payment Methods' => route('organization.payment-methods.index'),
            'Create' => '#'
        ];
    @endphp
    
    <x-card class="max-w-3xl mx-auto">
        <form action="{{ route('organization.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-4">Payment Method Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Method Name -->
                    <div class="md:col-span-2">
                        <x-form.input 
                            id="name"
                            name="name"
                            label="Payment Method Name"
                            :value="old('name')"
                            required
                            :error="$errors->first('name')"
                        />
                    </div>
                    
                    <!-- Payment Type -->
                    <div>
                        <x-form.select
                            id="payment_type"
                            name="payment_type"
                            label="Payment Type"
                            :options="[
                                'bank_transfer' => 'Bank Transfer',
                                'cash' => 'Cash',
                                'credit_card' => 'Credit Card',
                                'qris' => 'QRIS',
                                'e_wallet' => 'E-Wallet',
                                'other' => 'Other'
                            ]"
                            :value="old('payment_type')"
                            required
                            x-model="paymentType"
                            :error="$errors->first('payment_type')"
                        />
                    </div>
                    
                    <!-- Active Status -->
                    <div>
                        <x-form.select
                            id="is_active"
                            name="is_active"
                            label="Status"
                            :options="[
                                '1' => 'Active',
                                '0' => 'Inactive'
                            ]"
                            :value="old('is_active', '1')"
                            required
                            :error="$errors->first('is_active')"
                        />
                    </div>
                    
                    <!-- Logo -->
                    <div class="md:col-span-2">
                        <x-form.input 
                            type="file"
                            id="logo"
                            name="logo"
                            label="Logo (Optional)"
                            accept="image/*"
                            help-text="Upload a small logo or icon (max 1MB, recommended size: 120×120px)"
                            :error="$errors->first('logo')"
                        />
                    </div>
                </div>
                
                <div x-data="{ paymentType: '{{ old('payment_type', 'bank_transfer') }}' }">
                    <!-- Bank Transfer Details -->
                    <div x-show="paymentType === 'bank_transfer'" class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Bank Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-form.input 
                                id="bank_name"
                                name="bank_name"
                                label="Bank Name"
                                :value="old('bank_name')"
                                :error="$errors->first('bank_name')"
                                x-bind:required="paymentType === 'bank_transfer'"
                            />
                            
                            <x-form.input 
                                id="account_number"
                                name="account_number"
                                label="Account Number"
                                :value="old('account_number')"
                                :error="$errors->first('account_number')"
                                x-bind:required="paymentType === 'bank_transfer'"
                            />
                            
                            <div class="md:col-span-2">
                                <x-form.input 
                                    id="account_name"
                                    name="account_name"
                                    label="Account Name"
                                    :value="old('account_name')"
                                    :error="$errors->first('account_name')"
                                    x-bind:required="paymentType === 'bank_transfer'"
                                />
                            </div>
                        </div>
                    </div>
                    
                    <!-- QRIS/E-Wallet Details -->
                    <div x-show="paymentType === 'qris' || paymentType === 'e_wallet'" class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Digital Payment Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-form.input 
                                id="account_id"
                                name="account_id"
                                label="Account ID / Phone Number"
                                :value="old('account_id')"
                                :error="$errors->first('account_id')"
                            />
                            
                            <x-form.input 
                                id="account_name"
                                name="account_name"
                                label="Account Name"
                                :value="old('account_name')"
                                :error="$errors->first('account_name')"
                            />
                        </div>
                    </div>
                </div>
                
                <!-- Instructions -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Instructions</h3>
                    
                    <x-form.textarea 
                        id="instructions"
                        name="instructions"
                        label="Instructions for Customers"
                        rows="4"
                        help-text="Provide clear payment instructions that will be displayed on invoices"
                        :error="$errors->first('instructions')"
                    >{{ old('instructions') }}</x-form.textarea>
                </div>
                
                <!-- Internal Notes -->
                <div>
                    <x-form.textarea 
                        id="notes"
                        name="notes"
                        label="Internal Notes (Optional)"
                        rows="3"
                        help-text="Notes for internal reference only (not visible to customers)"
                        :error="$errors->first('notes')"
                    >{{ old('notes') }}</x-form.textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <x-button 
                        href="{{ route('organization.payment-methods.index') }}" 
                        variant="secondary"
                    >
                        Cancel
                    </x-button>
                    
                    <x-button 
                        type="submit" 
                        variant="primary"
                        icon="fas fa-save"
                    >
                        Create Payment Method
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>
</x-organization-layout>