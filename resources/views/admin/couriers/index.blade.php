<x-admin-layout>
    @section('title', 'Couriers')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Couriers</h2>
            <p class="mt-1 text-sm text-gray-600">Manage shipping couriers for the system</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.couriers.create') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Create Courier
            </a>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <form action="{{ route('admin.couriers.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request()->get('search') }}" placeholder="Name or tracking URL..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                        <option value="">All Status</option>
                        <option value="active" {{ request()->get('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request()->get('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <div class="flex space-x-2 items-end">
                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    
                    <a href="{{ route('admin.couriers.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <i class="fas fa-sync mr-2"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Couriers Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Courier
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tracking URL
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($couriers as $courier)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($courier->logo)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($courier->logo) }}" alt="{{ $courier->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-sky-100 flex items-center justify-center">
                                                <span class="text-sky-600 font-medium">
                                                    {{ substr($courier->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $courier->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $courier->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs truncate">
                                    @if($courier->tracking_url)
                                        <a href="{{ str_replace('{tracking_number}', 'SAMPLE', $courier->tracking_url) }}" target="_blank" class="text-sky-600 hover:text-sky-900 text-sm">
                                            {{ $courier->tracking_url }}
                                        </a>
                                    @else
                                        <span class="text-gray-500 text-sm">No tracking URL</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($courier->status === 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $courier->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.couriers.show', $courier) }}" class="text-sky-600 hover:text-sky-900 p-1" title="View details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.couriers.edit', $courier) }}" class="text-yellow-600 hover:text-yellow-900 p-1" title="Edit courier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.couriers.destroy', $courier) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this courier?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-900 p-1" title="Delete courier">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No couriers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-200">
            {{ $couriers->links() }}
        </div>
    </div>
</x-admin-layout>