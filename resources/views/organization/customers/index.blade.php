<x-organization-layout>
    @section('title', 'Customers')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Customers</h2>
            <p class="mt-1 text-sm text-gray-600">Manage your customer information</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
            <x-button 
                href="{{ route('organization.customers.create') }}" 
                icon="fas fa-user-plus" 
                variant="primary"
            >
                New Customer
            </x-button>
            
            <x-button 
                href="{{ route('organization.customers.import-form') }}" 
                icon="fas fa-file-import" 
                variant="secondary"
            >
                Import
            </x-button>
        </div>
    </div>
    
    <!-- Filters -->
    <x-card class="mb-6">
        <form action="{{ route('organization.customers.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <x-form.input
                        id="search"
                        name="search"
                        placeholder="Search by name, email or phone..."
                        :value="request()->get('search')"
                        leading-icon="fas fa-search"
                    />
                </div>
                
                <div>
                    <x-form.select
                        id="sort"
                        name="sort"
                        :options="[
                            'newest' => 'Newest First',
                            'oldest' => 'Oldest First',
                            'name_asc' => 'Name (A-Z)',
                            'name_desc' => 'Name (Z-A)'
                        ]"
                        :value="request()->get('sort', 'newest')"
                    />
                </div>
                
                <div class="flex items-end space-x-2">
                    <x-button type="submit" variant="primary" icon="fas fa-filter">
                        Search
                    </x-button>
                    
                    <x-button href="{{ route('organization.customers.index') }}" variant="secondary" icon="fas fa-sync">
                        Reset
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>
    
    <!-- Customer List -->
    <x-card>
        @if($customers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact Info
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Address
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Orders
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customers as $customer)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center">
                                            <span class="text-gray-500 font-medium">
                                                {{ substr($customer->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $customer->email ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $customer->phone_number ?? 'No phone' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">{{ $customer->address ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-badge color="sky">
                                        {{ $customer->orders->count() }} orders
                                    </x-badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center space-x-2">
                                        <a href="{{ route('organization.customers.show', $customer) }}" class="text-sky-600 hover:text-sky-900 p-1" title="View details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('organization.customers.edit', $customer) }}" class="text-yellow-600 hover:text-yellow-900 p-1" title="Edit customer">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('organization.customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-900 p-1" {{ $customer->orders->count() > 0 ? 'disabled' : '' }} title="{{ $customer->orders->count() > 0 ? 'Cannot delete customer with orders' : 'Delete customer' }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 border-t border-gray-200 pt-4">
                <x-pagination :paginator="$customers" />
            </div>
        @else
            <div class="text-center py-10">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                    <i class="fas fa-users text-sky-600"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No customers</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new customer.</p>
                <div class="mt-6">
                    <x-button href="{{ route('organization.customers.create') }}" variant="primary" icon="fas fa-user-plus">
                        New Customer
                    </x-button>
                </div>
            </div>
        @endif
    </x-card>
    
        <!-- Export Options -->
    <x-card title="Export Options" class="mt-6">
        <div class="flex flex-wrap gap-3">
            <x-button href="{{ route('organization.customers.export', ['format' => 'csv']) }}" variant="secondary" icon="fas fa-file-csv">
                Export as CSV
            </x-button>
            
            <x-button href="{{ route('organization.customers.export', ['format' => 'xlsx']) }}" variant="secondary" icon="fas fa-file-excel">
                Export as Excel
            </x-button>
            
            <x-button href="{{ route('organization.customers.export', ['format' => 'pdf']) }}" variant="secondary" icon="fas fa-file-pdf">
                Export as PDF
            </x-button>
        </div>
    </x-card>
</x-organization-layout>