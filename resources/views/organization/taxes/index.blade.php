<x-organization-layout>
    @section('title', 'Tax Rates')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Tax Rates</h2>
            <p class="mt-1 text-sm text-gray-600">Manage tax rates for your invoices</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <x-button 
                href="{{ route('organization.taxes.create') }}" 
                icon="fas fa-plus" 
                variant="primary"
            >
                Add Tax Rate
            </x-button>
        </div>
    </div>
    
    @if(session('success'))
        <x-alert type="success" :dismissible="true" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif
    
    <!-- Tax Rates List -->
    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rate
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Country/Region
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($taxes as $tax)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $tax->name }}</div>
                                <div class="text-xs text-gray-500">{{ $tax->description }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $tax->rate }}%</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ ucfirst($tax->type) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $tax->country ?? 'All Countries' }}
                                    @if($tax->region)
                                        <span class="text-xs text-gray-500">({{ $tax->region }})</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <x-badge :color="$tax->is_active ? 'green' : 'gray'">
                                    {{ $tax->is_active ? 'Active' : 'Inactive' }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('organization.taxes.show', $tax) }}" class="text-sky-600 hover:text-sky-900" title="View tax rate">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('organization.taxes.edit', $tax) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit tax rate">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('organization.taxes.toggle', $tax) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($tax->is_active)
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Deactivate tax rate">
                                                <i class="fas fa-toggle-off"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Activate tax rate">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No tax rates found.
                                <a href="{{ route('organization.taxes.create') }}" class="text-sky-600 hover:text-sky-900">Add your first tax rate</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($taxes->hasPages())
            <div class="p-3 border-t border-gray-200">
                {{ $taxes->links() }}
            </div>
        @endif
    </x-card>
    
    <!-- Default Tax Settings -->
    <x-card title="Default Tax Settings" class="mt-6">
        <form action="{{ route('organization.taxes.update-defaults') }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-form.select
                        id="default_tax_id"
                        name="default_tax_id"
                        label="Default Tax"
                        :options="$taxes->where('is_active', true)->pluck('name', 'id')->toArray()"
                        :value="$defaultSettings->default_tax_id ?? null"
                        placeholder="None (No default tax)"
                        help-text="This tax will be automatically applied to new orders"
                        :error="$errors->first('default_tax_id')"
                    />
                </div>
                
                <div>
                    <x-form.checkbox
                        id="auto_apply_tax"
                        name="auto_apply_tax"
                        :checked="$defaultSettings->auto_apply_tax ?? false"
                        value="1"
                        label="Automatically apply default tax to all orders"
                    />
                </div>
            </div>
            
            <div class="flex justify-end">
                <x-button 
                    type="submit" 
                    variant="primary"
                    icon="fas fa-save"
                >
                    Save Default Settings
                </x-button>
            </div>
        </form>
    </x-card>
</x-organization-layout>