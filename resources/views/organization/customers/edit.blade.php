<x-organization-layout>
    @section('title', 'Edit Customer')
    
    @php
        $breadcrumbs = [
            'Customers' => route('organization.customers.index'),
            $customer->name => route('organization.customers.show', $customer),
            'Edit' => '#'
        ];
    @endphp
    
    <x-card class="max-w-3xl mx-auto">
        <form action="{{ route('organization.customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-4">Customer Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <x-form.input 
                        id="name"
                        name="name"
                        label="Full Name"
                        :value="old('name', $customer->name)"
                        required
                        :error="$errors->first('name')"
                    />
                    
                    <!-- Email -->
                    <x-form.input 
                        type="email"
                        id="email"
                        name="email"
                        label="Email Address"
                        :value="old('email', $customer->email)"
                        :error="$errors->first('email')"
                    />
                    
                    <!-- Phone -->
                    <x-form.input 
                        id="phone_number"
                        name="phone_number"
                        label="Phone Number"
                        :value="old('phone_number', $customer->phone_number)"
                        :error="$errors->first('phone_number')"
                    />
                    
                    <!-- Gender -->
                    <x-form.select
                        id="gender"
                        name="gender"
                        label="Gender"
                        :options="[
                            'male' => 'Male',
                            'female' => 'Female',
                            'other' => 'Other'
                        ]"
                        :value="old('gender', $customer->gender)"
                        placeholder="Select Gender"
                        :error="$errors->first('gender')"
                    />
                    
                    <!-- Birth Date -->
                    <x-form.input 
                        type="date"
                        id="birthdate"
                        name="birthdate"
                        label="Birth Date"
                        :value="old('birthdate', $customer->birthdate ? $customer->birthdate->format('Y-m-d') : null)"
                        :error="$errors->first('birthdate')"
                    />
                    
                    <!-- ID Number -->
                    <x-form.input 
                        id="id_number"
                        name="id_number"
                        label="ID Number"
                        help-text="National identity card number, passport number, etc."
                        :value="old('id_number', $customer->id_number)"
                        :error="$errors->first('id_number')"
                    />
                </div>
                
                <!-- Address -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-form.textarea 
                                id="address"
                                name="address"
                                label="Full Address"
                                rows="3"
                                :error="$errors->first('address')"
                            >{{ old('address', $customer->address) }}</x-form.textarea>
                        </div>
                        
                        <x-form.input 
                            id="city"
                            name="city"
                            label="City"
                            :value="old('city', $customer->city)"
                            :error="$errors->first('city')"
                        />
                        
                        <x-form.input 
                            id="state"
                            name="state"
                            label="State/Province"
                            :value="old('state', $customer->state)"
                            :error="$errors->first('state')"
                        />
                        
                        <x-form.input 
                            id="postal_code"
                            name="postal_code"
                            label="Postal Code"
                            :value="old('postal_code', $customer->postal_code)"
                            :error="$errors->first('postal_code')"
                        />
                        
                        <x-form.input 
                            id="country"
                            name="country"
                            label="Country"
                            :value="old('country', $customer->country ?? 'Indonesia')"
                            :error="$errors->first('country')"
                        />
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.select
                            id="source"
                            name="source"
                            label="Customer Source"
                            :options="[
                                'online' => 'Online (website, social media)',
                                'referral' => 'Referral',
                                'direct' => 'Direct contact',
                                'exhibition' => 'Exhibition/Trade show',
                                'other' => 'Other'
                            ]"
                            :value="old('source', $customer->source)"
                            placeholder="Select Source"
                            :error="$errors->first('source')"
                        />
                        
                        <div class="md:col-span-2">
                            <x-form.textarea 
                                id="notes"
                                name="notes"
                                label="Notes"
                                rows="3"
                                help-text="Internal notes about this customer (not visible to customer)"
                                :error="$errors->first('notes')"
                            >{{ old('notes', $customer->notes) }}</x-form.textarea>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <x-button 
                        href="{{ route('organization.customers.show', $customer) }}" 
                        variant="secondary"
                    >
                        Cancel
                    </x-button>
                    
                    <x-button 
                        type="submit" 
                        variant="primary"
                        icon="fas fa-save"
                    >
                        Update Customer
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>
</x-organization-layout>