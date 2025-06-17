<x-organization-layout>
    @section('title', 'Edit Tax Rate')
    
    @php
        $breadcrumbs = [
            'Tax Rates' => route('organization.taxes.index'),
            $tax->name => route('organization.taxes.show', $tax),
            'Edit' => '#'
        ];
    @endphp
    
    <x-card class="max-w-3xl mx-auto">
        <form action="{{ route('organization.taxes.update', $tax) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-4">Tax Rate Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tax Name -->
                    <div class="md:col-span-2">
                        <x-form.input 
                            id="name"
                            name="name"
                            label="Tax Name"
                            :value="old('name', $tax->name)"
                            required
                            help-text="Name that appears on invoices (e.g., 'VAT', 'GST', 'Sales Tax')"
                            :error="$errors->first('name')"
                        />
                    </div>
                    
                    <!-- Tax Rate -->
                    <div>
                        <x-form.input 
                            type="number"
                            id="rate"
                            name="rate"
                            label="Tax Rate"
                            :value="old('rate', $tax->rate)"
                            required
                            min="0"
                            max="100"
                            step="0.01"
                            suffix="%"
                            :error="$errors->first('rate')"
                        />
                    </div>
                    
                    <!-- Tax Type -->
                    <div>
                        <x-form.select
                                                        id="type"
                            name="type"
                            label="Tax Type"
                            :options="[
                                'inclusive' => 'Inclusive (Price includes tax)',
                                'exclusive' => 'Exclusive (Tax added to price)'
                            ]"
                            :value="old('type', $tax->type)"
                            required
                            :error="$errors->first('type')"
                        />
                    </div>
                    
                    <!-- Description -->
                    <div class="md:col-span-2">
                        <x-form.input 
                            id="description"
                            name="description"
                            label="Description (Optional)"
                            :value="old('description', $tax->description)"
                            :error="$errors->first('description')"
                        />
                    </div>
                </div>
                
                <!-- Region Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Region Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Country -->
                        <div>
                            <x-form.input 
                                id="country"
                                name="country"
                                label="Country (Optional)"
                                :value="old('country', $tax->country)"
                                help-text="Leave empty to apply to all countries"
                                :error="$errors->first('country')"
                            />
                        </div>
                        
                        <!-- Region -->
                        <div>
                            <x-form.input 
                                id="region"
                                name="region"
                                label="Region/State/Province (Optional)"
                                :value="old('region', $tax->region)"
                                help-text="Leave empty to apply to all regions"
                                :error="$errors->first('region')"
                            />
                        </div>
                    </div>
                </div>
                
                <!-- Additional Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Settings</h3>
                    
                    <div class="space-y-4">
                        <!-- Tax Number -->
                        <x-form.input 
                            id="tax_number"
                            name="tax_number"
                            label="Tax Number (Optional)"
                            :value="old('tax_number', $tax->tax_number)"
                            help-text="Your company's tax ID number to display on invoices"
                            :error="$errors->first('tax_number')"
                        />
                        
                        <!-- Active Status -->
                        <x-form.checkbox
                            id="is_active"
                            name="is_active"
                            :checked="old('is_active', $tax->is_active)"
                            value="1"
                            label="Make this tax rate active"
                        />
                        
                        <!-- Compound Tax -->
                        <x-form.checkbox
                            id="is_compound"
                            name="is_compound"
                            :checked="old('is_compound', $tax->is_compound)"
                            value="1"
                            label="Compound tax (apply this tax on top of other taxes)"
                            help-text="When enabled, this tax will be calculated after other taxes are applied"
                        />
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 border-t border-gray-200 pt-6">
                    <x-button 
                        href="{{ route('organization.taxes.show', $tax) }}" 
                        variant="secondary"
                    >
                        Cancel
                    </x-button>
                    
                    <x-button 
                        type="submit" 
                        variant="primary"
                        icon="fas fa-save"
                    >
                        Update Tax Rate
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>
</x-organization-layout>