<x-organization-layout>
    @section('title', 'Discount: ' . $discount->code)
    
    @php
        $breadcrumbs = [
            'Discounts' => route('organization.discounts.index'),
            $discount->code => '#'
        ];
    @endphp
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $discount->code }}</h2>
            <div class="flex items-center mt-1 space-x-2">
                <x-badge :color="$discount->isActive() ? 'green' : ($discount->isExpired() ? 'red' : 'gray')">
                    {{ $discount->isActive() ? 'Active' : ($discount->isExpired() ? 'Expired' : 'Inactive') }}
                </x-badge>
                
                <span class="text-sm text-gray-500">{{ $discount->description }}</span>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
            <x-button 
                href="{{ route('organization.discounts.edit', $discount) }}" 
                variant="primary"
                icon="fas fa-edit"
            >
                Edit
            </x-button>
            
            <form action="{{ route('organization.discounts.toggle', $discount) }}" method="POST">
                @csrf
                @method('PATCH')
                <x-button 
                    type="submit"
                    variant="{{ $discount->is_active ? 'danger' : 'success' }}"
                    icon="{{ $discount->is_active ? 'fas fa-toggle-off' : 'fas fa-toggle-on' }}"
                >
                    {{ $discount->is_active ? 'Deactivate' : 'Activate' }}
                </x-button>
            </form>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Discount Details -->
            <x-card title="Discount Details">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Discount Type</h4>
                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($discount->type) }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Discount Value</h4>
                        <p class="text-sm font-medium text-gray-900">
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
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Minimum Order Value</h4>
                        <p class="text-sm font-medium text-gray-900">
                            @if($discount->min_order_value > 0)
                                Rp {{ number_format($discount->min_order_value, 0, ',', '.') }}
                            @else
                                <span class="text-gray-500">No minimum</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Status</h4>
                        <p class="text-sm">
                            <x-badge :color="$discount->isActive() ? 'green' : ($discount->isExpired() ? 'red' : 'gray')">
                                {{ $discount->isActive() ? 'Active' : ($discount->isExpired() ? 'Expired' : 'Inactive') }}
                            </x-badge>
                        </p>
                    </div>
                </div>
                
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Usage Limits</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Total Usage Limit</p>
                            <p class="text-sm font-medium text-gray-900">
                                @if($discount->usage_limit)
                                    {{ $discount->usage_count }} / {{ $discount->usage_limit }}
                                    
                                    @if($discount->usage_limit > 0)
                                        <div class="mt-1 h-2 w-full bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-sky-600 rounded-full" style="width: {{ min(100, ($discount->usage_count / $discount->usage_limit) * 100) }}%"></div>
                                        </div>
                                    @endif
                                @else
                                    {{ $discount->usage_count }} / ∞ <span class="text-xs text-gray-500">(Unlimited)</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Per Customer Limit</p>
                            <p class="text-sm font-medium text-gray-900">
                                @if($discount->per_customer_limit > 0)
                                    {{ $discount->per_customer_limit }}
                                @else
                                    <span class="text-gray-500">Unlimited</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Validity Period</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Valid From</p>
                            <p class="text-sm font-medium text-gray-900">
                                @if($discount->valid_from)
                                    {{ $discount->valid_from->format('M d, Y') }}
                                @else
                                    <span class="text-gray-500">Always</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Valid Until</p>
                            <p class="text-sm font-medium text-gray-900">
                                @if($discount->valid_until)
                                    {{ $discount->valid_until->format('M d, Y') }}
                                    @if($discount->valid_until->isPast())
                                        <span class="text-xs text-red-600 ml-1">(Expired)</span>
                                    @elseif($discount->valid_until->diffInDays(now()) <= 7)
                                        <span class="text-xs text-yellow-600 ml-1">({{ $discount->valid_until->diffForHumans() }})</span>
                                    @endif
                                @else
                                    <span class="text-gray-500">No expiration</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </x-card>
            
            <!-- Usage History -->
            <x-card title="Usage History">
                @if($usageHistory->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Original Amount</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Final Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($usageHistory as $usage)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $usage->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('organization.orders.show', $usage->order_id) }}" class="text-sm font-medium text-sky-600 hover:text-sky-900">
                                                {{ $usage->order->order_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $usage->order->customer->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            Rp {{ number_format($usage->original_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600">
                                            - Rp {{ number_format($usage->discount_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">
                                            Rp {{ number_format($usage->final_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-gray-900">
                                        Total Discount Amount:
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-red-600">
                                        - Rp {{ number_format($totalDiscountAmount, 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $usageHistory->links() }}
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-sm text-gray-500">This discount has not been used yet.</p>
                    </div>
                @endif
            </x-card>
        </div>
        
        <div class="space-y-6">
            <!-- Discount Statistics -->
            <x-card title="Discount Statistics">
                <dl class="divide-y divide-gray-200">
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="text-sm text-gray-900">{{ $discount->created_at->format('M d, Y') }}</dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Total Usage</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $discount->usage_count }} time(s)</dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Total Discount Amount</dt>
                        <dd class="text-sm font-medium text-red-600">Rp {{ number_format($totalDiscountAmount, 0, ',', '.') }}</dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Unique Customers</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $uniqueCustomers }}</dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Average Discount</dt>
                        <dd class="text-sm font-medium text-gray-900">
                            @if($discount->usage_count > 0)
                                Rp {{ number_format($totalDiscountAmount / $discount->usage_count, 0, ',', '.') }}
                            @else
                                Rp 0
                            @endif
                        </dd>
                    </div>
                </dl>
            </x-card>
            
            <!-- Discount Actions -->
            <x-card title="Actions">
                <div class="space-y-3">
                    <x-button 
                        href="{{ route('organization.discounts.edit', $discount) }}" 
                        variant="primary" 
                        icon="fas fa-edit"
                        full-width="true"
                    >
                        Edit Discount
                    </x-button>
                    
                    <form action="{{ route('organization.discounts.toggle', $discount) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <x-button 
                            type="submit"
                            variant="{{ $discount->is_active ? 'danger' : 'success' }}" 
                            icon="{{ $discount->is_active ? 'fas fa-toggle-off' : 'fas fa-toggle-on' }}"
                            full-width="true"
                        >
                            {{ $discount->is_active ? 'Deactivate' : 'Activate' }} Discount
                        </x-button>
                    </form>
                    
                    @if($discount->usage_count === 0)
                        <form action="{{ route('organization.discounts.destroy', $discount) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <x-button 
                                type="submit"
                                variant="danger" 
                                icon="fas fa-trash"
                                full-width="true"
                                onclick="return confirm('Are you sure you want to delete this discount? This action cannot be undone.')"
                            >
                                Delete Discount
                            </x-button>
                        </form>
                    @endif
                    
                    <x-button 
                        href="{{ route('organization.discounts.duplicate', $discount) }}" 
                        variant="secondary" 
                        icon="fas fa-copy"
                        full-width="true"
                    >
                        Duplicate Discount
                    </x-button>
                </div>
            </x-card>
        </div>
    </div>
</x-organization-layout>