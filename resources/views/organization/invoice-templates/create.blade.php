<x-organization-layout>
    @section('title', 'Create Invoice Template')
    
    @php
        $breadcrumbs = [
            'Invoice Templates' => route('organization.invoice-templates.index'),
            'Create' => '#'
        ];
    @endphp
    
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('organization.invoice-templates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-4">Template Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Template Name -->
                    <div class="md:col-span-2">
                        <x-form.input 
                            id="name"
                            name="name"
                            label="Template Name"
                            :value="old('name')"
                            required
                            :error="$errors->first('name')"
                        />
                    </div>
                    
                    <!-- Preview Image -->
                    <div class="md:col-span-2">
                        <x-form.input 
                            type="file"
                            id="preview_image"
                            name="preview_image"
                            label="Preview Image (Optional)"
                            accept="image/*"
                            help-text="Upload a preview image for this template (recommended size: 800x1200px)"
                            :error="$errors->first('preview_image')"
                        />
                    </div>
                </div>
                
                <!-- Header Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Header Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-form.checkbox
                                id="show_organization_logo"
                                name="show_organization_logo"
                                :checked="old('show_organization_logo', true)"
                                value="1"
                                label="Show organization logo"
                            />
                        </div>
                        
                        <div>
                            <x-form.input 
                                id="header_text"
                                name="header_text"
                                label="Header Text (Optional)"
                                :value="old('header_text', 'INVOICE')"
                                :error="$errors->first('header_text')"
                            />
                        </div>
                        
                        <div>
                            <x-form.select
                                id="text_alignment"
                                name="text_alignment"
                                label="Text Alignment"
                                :options="[
                                    'left' => 'Left',
                                    'center' => 'Center',
                                    'right' => 'Right'
                                ]"
                                :value="old('text_alignment', 'right')"
                                :error="$errors->first('text_alignment')"
                            />
                        </div>
                    </div>
                </div>
                
                <!-- Content Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Content Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-form.select
                                id="primary_color"
                                name="primary_color"
                                label="Primary Color"
                                :options="[
                                    '#0284c7' => 'Blue',
                                    '#059669' => 'Green',
                                    '#d97706' => 'Orange',
                                    '#dc2626' => 'Red',
                                    '#7c3aed' => 'Purple',
                                    '#1e293b' => 'Dark Blue',
                                    '#374151' => 'Gray',
                                    '#000000' => 'Black'
                                ]"
                                :value="old('primary_color', '#0284c7')"
                                :error="$errors->first('primary_color')"
                            />
                        </div>
                        
                        <div>
                            <x-form.select
                                id="font_family"
                                name="font_family"
                                label="Font Family"
                                :options="[
                                    'Inter, sans-serif' => 'Inter (Sans-serif)',
                                    'Arial, sans-serif' => 'Arial (Sans-serif)',
                                    'Georgia, serif' => 'Georgia (Serif)',
                                    'Courier New, monospace' => 'Courier (Monospace)'
                                ]"
                                :value="old('font_family', 'Inter, sans-serif')"
                                :error="$errors->first('font_family')"
                            />
                        </div>
                        
                        <div>
                            <x-form.checkbox
                                id="show_payment_instructions"
                                name="show_payment_instructions"
                                :checked="old('show_payment_instructions', true)"
                                value="1"
                                label="Show payment instructions"
                            />
                        </div>
                        
                        <div>
                            <x-form.checkbox
                                id="show_payment_method_logo"
                                name="show_payment_method_logo"
                                :checked="old('show_payment_method_logo', true)"
                                value="1"
                                label="Show payment method logo"
                            />
                        </div>
                        
                        <div class="md:col-span-2">
                            <x-form.textarea 
                                id="footer_text"
                                name="footer_text"
                                label="Footer Text (Optional)"
                                rows="2"
                                :value="old('footer_text', 'Thank you for your business!')"
                                :error="$errors->first('footer_text')"
                            />
                        </div>
                    </div>
                </div>
                
                <!-- Advanced Settings -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-form.checkbox
                                id="include_signature"
                                name="include_signature"
                                :checked="old('include_signature', false)"
                                value="1"
                                label="Include signature block"
                            />
                        </div>
                        
                        <div>
                            <x-form.checkbox
                                id="include_stamp"
                                name="include_stamp"
                                :checked="old('include_stamp', false)"
                                value="1"
                                label="Include stamp space"
                            />
                        </div>
                        
                        <div>
                            <x-form.checkbox
                                id="include_terms"
                                name="include_terms"
                                :checked="old('include_terms', true)"
                                value="1"
                                label="Include terms & conditions"
                            />
                        </div>
                        
                        <div>
                            <x-form.checkbox
                                id="is_default"
                                name="is_default"
                                :checked="old('is_default', false)"
                                value="1"
                                label="Set as default template"
                                help-text="This will override the current default template"
                            />
                        </div>
                        
                        <div class="md:col-span-2">
                            <x-form.textarea 
                                id="terms_text"
                                name="terms_text"
                                label="Terms & Conditions Text"
                                rows="3"
                                help-text="Will only be shown if 'Include terms & conditions' is checked"
                                :error="$errors->first('terms_text')"
                            >{{ old('terms_text', 'All invoices must be paid within 30 days. Please contact us if you have any questions.') }}</x-form.textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Save Buttons -->
                <div class="flex justify-end space-x-3">
                    <x-button 
                        href="{{ route('organization.invoice-templates.index') }}" 
                        variant="secondary"
                    >
                        Cancel
                    </x-button>
                    
                    <x-button 
                        type="submit" 
                        name="action"
                        value="save_preview"
                        variant="secondary"
                    >
                        Save & Preview
                    </x-button>
                    
                    <x-button 
                        type="submit"
                        name="action"
                        value="save"
                        variant="primary"
                        icon="fas fa-save"
                    >
                        Create Template
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>
</x-organization-layout>