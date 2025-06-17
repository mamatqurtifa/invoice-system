<x-organization-layout>
    @section('title', 'Create Project')
    
    @php
        $breadcrumbs = [
            'Projects' => route('organization.projects.index'),
            'Create' => '#'
        ];
    @endphp
    
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('organization.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-4">Project Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Project Name -->
                    <x-form.input 
                        id="name"
                        name="name"
                        label="Project Name"
                        :value="old('name')"
                        required
                        :error="$errors->first('name')"
                    />
                    
                    <!-- Project Type -->
                    <x-form.select
                        id="type"
                        name="type"
                        label="Project Type"
                        :options="['direct' => 'Direct Order', 'preorder' => 'Pre-Order']"
                        :value="old('type', 'direct')"
                        required
                        :error="$errors->first('type')"
                    />
                    
                    <!-- Start Date -->
                    <x-form.input 
                        type="date"
                        id="start_date"
                        name="start_date"
                        label="Start Date"
                        :value="old('start_date', date('Y-m-d'))"
                        required
                        :error="$errors->first('start_date')"
                    />
                    
                    <!-- End Date (For Pre-order) -->
                    <div x-data="{ projectType: '{{ old('type', 'direct') }}' }" 
                         x-init="$watch('projectType', type => { if(type === 'preorder') { $refs.endDateField.classList.remove('hidden'); } else { $refs.endDateField.classList.add('hidden'); } })">
                        <div 
                            x-ref="endDateField" 
                            class="{{ old('type', 'direct') === 'direct' ? 'hidden' : '' }}"
                        >
                            <x-form.input 
                                type="date"
                                id="end_date"
                                name="end_date"
                                label="Pre-order End Date"
                                :value="old('end_date')"
                                :error="$errors->first('end_date')"
                            />
                        </div>
                        
                        <input type="hidden" x-model="projectType" id="projectTypeListener">
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const typeSelect = document.getElementById('type');
                                const typeListener = document.getElementById('projectTypeListener');
                                
                                typeSelect.addEventListener('change', function() {
                                    typeListener.value = this.value;
                                    const event = new Event('input', { bubbles: true });
                                    typeListener.dispatchEvent(event);
                                });
                            });
                        </script>
                    </div>
                    
                    <!-- Status -->
                    <x-form.select
                        id="status"
                        name="status"
                        label="Status"
                        :options="['active' => 'Active', 'completed' => 'Completed', 'cancelled' => 'Cancelled']"
                        :value="old('status', 'active')"
                        required
                        :error="$errors->first('status')"
                    />
                    
                    <!-- Project Logo -->
                    <x-form.input 
                        type="file"
                        id="logo"
                        name="logo"
                        label="Project Logo"
                        accept="image/*"
                        help-text="Optional. Upload a square image, maximum 2MB (PNG, JPG)"
                        :error="$errors->first('logo')"
                    />
                </div>
                
                <!-- Description -->
                <x-form.textarea 
                    id="description"
                    name="description"
                    label="Project Description"
                    rows="4"
                    :error="$errors->first('description')"
                >{{ old('description') }}</x-form.textarea>
                
                <!-- Notes -->
                <x-form.textarea 
                    id="notes"
                    name="notes"
                    label="Internal Notes"
                    rows="3"
                    help-text="These notes are for internal reference only and won't be shown to customers"
                    :error="$errors->first('notes')"
                >{{ old('notes') }}</x-form.textarea>
                
                <div class="flex justify-end space-x-3">
                    <x-button 
                        href="{{ route('organization.projects.index') }}" 
                        variant="secondary"
                    >
                        Cancel
                    </x-button>
                    
                    <x-button 
                        type="submit" 
                        variant="primary"
                        icon="fas fa-save"
                    >
                        Create Project
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>
</x-organization-layout>