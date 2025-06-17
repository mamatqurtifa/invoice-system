<x-organization-layout>
    @section('title', 'Edit Project')
    
    @php
        $breadcrumbs = [
            'Projects' => route('organization.projects.index'),
            $project->name => route('organization.projects.show', $project),
            'Edit' => '#'
        ];
    @endphp
    
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('organization.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-4">Project Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Project Name -->
                    <x-form.input 
                        id="name"
                        name="name"
                        label="Project Name"
                        :value="old('name', $project->name)"
                        required
                        :error="$errors->first('name')"
                    />
                    
                    <!-- Project Type -->
                    <x-form.select
                        id="type"
                        name="type"
                        label="Project Type"
                        :options="['direct' => 'Direct Order', 'preorder' => 'Pre-Order']"
                        :value="old('type', $project->type)"
                        required
                        :error="$errors->first('type')"
                    />
                    
                    <!-- Start Date -->
                    <x-form.input 
                        type="date"
                        id="start_date"
                        name="start_date"
                        label="Start Date"
                        :value="old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '')"
                        required
                        :error="$errors->first('start_date')"
                    />
                    
                    <!-- End Date (For Pre-order) -->
                    <div x-data="{ projectType: '{{ old('type', $project->type) }}' }" 
                         x-init="$watch('projectType', type => { if(type === 'preorder') { $refs.endDateField.classList.remove('hidden'); } else { $refs.endDateField.classList.add('hidden'); } })">
                        <div 
                            x-ref="endDateField" 
                            class="{{ old('type', $project->type) === 'direct' ? 'hidden' : '' }}"
                        >
                            <x-form.input 
                                type="date"
                                id="end_date"
                                name="end_date"
                                label="Pre-order End Date"
                                :value="old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '')"
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
                        :value="old('status', $project->status)"
                        required
                        :error="$errors->first('status')"
                    />
                    
                    <!-- Project Logo -->
                    <div>
                        <x-form.input 
                            type="file"
                            id="logo"
                            name="logo"
                            label="Project Logo"
                            accept="image/*"
                            help-text="Optional. Upload a square image, maximum 2MB (PNG, JPG)"
                            :error="$errors->first('logo')"
                        />
                        
                        @if($project->logo)
                            <div class="mt-2 flex items-center">
                                <img src="{{ Storage::url($project->logo) }}" alt="{{ $project->name }}" class="h-10 w-10 object-cover rounded">
                                <span class="ml-2 text-sm text-gray-500">Current logo</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Description -->
                <x-form.textarea 
                    id="description"
                    name="description"
                    label="Project Description"
                    rows="4"
                    :error="$errors->first('description')"
                >{{ old('description', $project->description) }}</x-form.textarea>
                
                <!-- Notes -->
                <x-form.textarea 
                    id="notes"
                    name="notes"
                    label="Internal Notes"
                    rows="3"
                    help-text="These notes are for internal reference only and won't be shown to customers"
                    :error="$errors->first('notes')"
                >{{ old('notes', $project->notes) }}</x-form.textarea>
                
                <div class="flex justify-end space-x-3">
                    <x-button 
                        href="{{ route('organization.projects.show', $project) }}" 
                        variant="secondary"
                    >
                        Cancel
                    </x-button>
                    
                    <x-button 
                        type="submit" 
                        variant="primary"
                        icon="fas fa-save"
                    >
                        Update Project
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>
</x-organization-layout>