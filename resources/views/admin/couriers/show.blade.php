<x-admin-layout>
    @section('title', 'Courier Details')
    
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $courier->name }}</h2>
                <p class="mt-1 text-sm text-gray-600">Courier Details</p>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
                <a href="{{ route('admin.couriers.edit', $courier) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i> Edit Courier
                </a>
                <form action="{{ route('admin.couriers.destroy', $courier) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this courier?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-colors duration-200">
                        <i class="fas fa-trash mr-2"></i> Delete Courier
                    </button>
                </form>
                <a href="{{ route('admin.couriers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Couriers
                </a>
            </div>
        </div>
    </div>
    
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-start">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Courier Information
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Details and shipping information.
                </p>
            </div>
            <div class="flex-shrink-0">
                @if($courier->logo)
                    <img class="h-16 w-16 object-contain rounded-md" src="{{ Storage::url($courier->logo) }}" alt="{{ $courier->name }} logo">
                @else
                    <div class="h-16 w-16 rounded-md bg-sky-100 flex items-center justify-center">
                        <span class="text-3xl text-sky-600 font-medium">{{ substr($courier->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Courier name
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $courier->name }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Code
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $courier->code }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Status
                    </dt>
                    <dd class="mt-1 sm:mt-0 sm:col-span-2">
                        @if($courier->status === 'active')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Inactive
                            </span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Tracking URL
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($courier->tracking_url)
                            <div class="flex items-center">
                                <span class="truncate max-w-md">{{ $courier->tracking_url }}</span>
                                <a href="{{ str_replace('{tracking_number}', '123456789', $courier->tracking_url) }}" target="_blank" class="ml-2 text-sky-600 hover:text-sky-800">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Example link with sample tracking number.</p>
                        @else
                            <span class="text-gray-500">No tracking URL provided</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Website
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($courier->website)
                            <a href="{{ $courier->website }}" target="_blank" class="text-sky-600 hover:text-sky-800">
                                {{ $courier->website }} <i class="fas fa-external-link-alt text-xs"></i>
                            </a>
                        @else
                            <span class="text-gray-500">No website provided</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Contact number
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $courier->contact_number ?? 'No contact number provided' }}
                    </dd>
                </div>
                @if($courier->description)
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Description
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $courier->description }}
                        </dd>
                    </div>
                @endif
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Created at
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $courier->created_at->format('F d, Y \a\t h:i A') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- Usage Statistics -->
    <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Usage Statistics
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                How this courier is being used in the system.
            </p>
        </div>
        <div class="border-t border-gray-200">
            <div class="bg-gray-50 px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center">
                            <h4 class="text-lg font-medium text-gray-900">Total Orders</h4>
                            <span class="text-3xl font-semibold text-gray-900">{{ $orderCount }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center">
                            <h4 class="text-lg font-medium text-gray-900">Organizations Using</h4>
                            <span class="text-3xl font-semibold text-gray-900">{{ $organizationCount }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center">
                            <h4 class="text-lg font-medium text-gray-900">Last Used</h4>
                            <span class="text-sm font-medium text-gray-500">
                                @if($lastUsed)
                                    {{ $lastUsed->format('M d, Y') }}
                                @else
                                    Never
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>