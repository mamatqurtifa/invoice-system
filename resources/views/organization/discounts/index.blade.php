<x-organization-layout>
    @section('title', 'Discount Codes')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Discount Codes</h2>
            <p class="mt-1 text-sm text-gray-600">Manage discount codes and promotions</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <x-button 
                href="{{ route('organization.discounts.create') }}" 
                icon="fas fa-plus" 
                variant="primary"
            >
                Create Discount
            </x-button>
        </div>
    </div>
    
    @if(session('success'))
        <x-alert type="success" :dismissible="true" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif
    
    <!-- Filters -->
    <x-card class="mb-6">
        <form action="{{ route('organization.discounts.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <x-form.input
                    id="search"
                    name="search"
                    placeholder="Search code or description..."
                    :value="request()->get('search')"
                    leading-icon="fas fa-search"
                />
                
                <!-- Type -->
                <x-form.select
                    id="type"
                    name="type"
                    placeholder="All Types"
                    :options="[
                        'percentage' => 'Percentage',
                        'fixed' => 'Fixed Amount'
                    ]"
                    :value="request()->get('type')"
                />
                
                <!-- Status -->
                <x-form.select
                    id="status"
                    name="status"
                    placeholder="All Status"
                    :options="[
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'expired' => 'Expired'
                    ]"
                    :value="request()->get('status')"
                />
                
                <!-- Sort -->
                <x-form.select
                    id="sort"
                    name="sort"
                    :options="[
                        'newest' => 'Newest First',
                        'oldest' => 'Oldest First',
                        'value_asc' => 'Value (Low to High)',
                        'value_desc' => 'Value (High to Low)'
                    ]"
                    :value="request()->get('sort', 'newest')"
                />
            </div>
            
            <div class="flex justify-end gap-2">
                <x-button type="submit" variant="primary" icon="fas fa-filter">
                    Filter
                </x-button>
                
                                <x-button href="{{ route('organization.discounts.index') }}" variant="secondary" icon="fas fa-sync">
                    Reset
                </x-button>
            </div>
        </form>
    </x-card>
    
    <!-- Discounts Table -->
    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Code
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Value
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Valid Period
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usage / Limit
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($discounts as $discount)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $discount->code }}</div>
                                <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($discount->description, 30) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ ucfirst($discount->type) }}</div>
                                <div class="text-xs text-gray-500">
                                    @if($discount->min_order_value > 0)
                                        Min. Order: Rp {{ number_format($discount->min_order_value, 0, ',', '.') }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    @if($discount->type === 'percentage')
                                        {{ $discount->value }}%
                                        @if($discount->max_discount_amount > 0)
                                            <span class="text-xs text-gray-500">
                                                (Max: Rp {{ number_format($discount->max_discount_amount, 0, ',', '.') }})
                                            </span>
                                        @endif
                                    @else
                                        Rp {{ number_format($discount->value, 0, ',', '.') }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $discount->valid_from ? $discount->valid_from->format('M d, Y') : 'Always' }}
                                </div>
                                @if($discount->valid_until)
                                    <div class="text-xs text-gray-500">
                                        Until: {{ $discount->valid_until->format('M d, Y') }}
                                        @if($discount->valid_until->isPast())
                                            <span class="text-red-600">(Expired)</span>
                                        @elseif($discount->valid_until->diffInDays(now()) <= 7)
                                            <span class="text-yellow-600">({{ $discount->valid_until->diffForHumans() }})</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-xs text-gray-500">No expiration</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $discount->usage_count }} 
                                    @if($discount->usage_limit)
                                        / {{ $discount->usage_limit }}
                                    @else
                                        <span class="text-xs text-gray-500">/ ∞</span>
                                    @endif
                                </div>
                                
                                @if($discount->usage_limit)
                                    <div class="mt-1 h-1.5 w-20 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-sky-600 rounded-full" style="width: {{ min(100, ($discount->usage_count / $discount->usage_limit) * 100) }}%"></div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-badge :color="$discount->isActive() ? 'green' : ($discount->isExpired() ? 'red' : 'gray')">
                                    {{ $discount->isActive() ? 'Active' : ($discount->isExpired() ? 'Expired' : 'Inactive') }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('organization.discounts.show', $discount) }}" class="text-sky-600 hover:text-sky-900" title="View discount">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('organization.discounts.edit', $discount) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit discount">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('organization.discounts.toggle', $discount) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($discount->is_active)
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Deactivate discount">
                                                <i class="fas fa-toggle-off"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Activate discount">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No discount codes found.
                                <a href="{{ route('organization.discounts.create') }}" class="text-sky-600 hover:text-sky-900">Create your first discount</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-3 border-t border-gray-200">
            {{ $discounts->withQueryString()->links() }}
        </div>
    </x-card>
    
    <!-- Summary Stats -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-card class="bg-gradient-to-br from-sky-50 to-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-sky-100 rounded-md p-3">
                    <i class="fas fa-ticket-alt text-sky-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Discounts</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $totalDiscounts }}</dd>
                    </dl>
                </div>
            </div>
        </x-card>
        
        <x-card class="bg-gradient-to-br from-green-50 to-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Active Discounts</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $activeDiscounts }}</dd>
                    </dl>
                </div>
            </div>
        </x-card>
        
        <x-card class="bg-gradient-to-br from-yellow-50 to-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                    <i class="fas fa-percentage text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Usage</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $totalUsage }}</dd>
                    </dl>
                </div>
            </div>
        </x-card>
        
        <x-card class="bg-gradient-to-br from-red-50 to-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                    <i class="fas fa-calendar-times text-red-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Expired Discounts</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $expiredDiscounts }}</dd>
                    </dl>
                </div>
            </div>
        </x-card>
    </div>
</x-organization-layout>