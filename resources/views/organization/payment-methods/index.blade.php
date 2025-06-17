<x-organization-layout>
    @section('title', 'Payment Methods')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Payment Methods</h2>
            <p class="mt-1 text-sm text-gray-600">Manage your organization's payment methods</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <x-button 
                href="{{ route('organization.payment-methods.create') }}" 
                icon="fas fa-plus" 
                variant="primary"
            >
                Add Payment Method
            </x-button>
        </div>
    </div>
    
    @if(session('success'))
        <x-alert type="success" :dismissible="true" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($paymentMethods as $paymentMethod)
            <x-card class="h-full flex flex-col hover:shadow transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                        @if($paymentMethod->logo)
                            <img src="{{ Storage::url($paymentMethod->logo) }}" alt="{{ $paymentMethod->name }}" class="h-10 w-10 object-contain">
                        @else
                            <i class="fas fa-money-bill-wave text-gray-400 text-xl"></i>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $paymentMethod->name }}</h3>
                        <div class="mt-1">
                            <x-badge :color="$paymentMethod->is_active ? 'green' : 'gray'">
                                {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                            </x-badge>
                            
                            <x-badge :color="
                                $paymentMethod->payment_type === 'bank_transfer' ? 'sky' : 
                                ($paymentMethod->payment_type === 'cash' ? 'green' : 
                                ($paymentMethod->payment_type === 'credit_card' ? 'blue' : 
                                ($paymentMethod->payment_type === 'qris' ? 'purple' : 'yellow')))
                            ">
                                {{ ucwords(str_replace('_', ' ', $paymentMethod->payment_type)) }}
                            </x-badge>
                        </div>
                    </div>
                </div>
                
                @if($paymentMethod->payment_type === 'bank_transfer')
                    <div class="mt-2 py-3 border-y border-gray-100">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Bank Name</p>
                                <p class="text-sm font-medium">{{ $paymentMethod->bank_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Account Number</p>
                                <p class="text-sm font-medium">{{ $paymentMethod->account_number }}</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-xs text-gray-500">Account Name</p>
                            <p class="text-sm font-medium">{{ $paymentMethod->account_name }}</p>
                        </div>
                    </div>
                @endif
                
                @if($paymentMethod->instructions)
                    <div class="mt-2 text-sm text-gray-500 flex-grow">
                        <p class="font-medium text-xs text-gray-700 mb-1">Instructions:</p>
                        <p class="line-clamp-3">{{ $paymentMethod->instructions }}</p>
                    </div>
                @endif
                
                <div class="flex justify-end space-x-2 mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('organization.payment-methods.edit', $paymentMethod) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit payment method">
                        <i class="fas fa-edit"></i>
                    </a>
                    
                    <a href="{{ route('organization.payment-methods.show', $paymentMethod) }}" class="text-sky-600 hover:text-sky-900" title="View payment method">
                        <i class="fas fa-eye"></i>
                    </a>
                    
                    <form action="{{ route('organization.payment-methods.destroy', $paymentMethod) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this payment method?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-600 hover:text-rose-900" title="Delete payment method" {{ $paymentMethod->orders_count > 0 ? 'disabled' : '' }}>
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </x-card>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                    <i class="fas fa-credit-card text-sky-600"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No payment methods</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new payment method.</p>
                <div class="mt-6">
                    <x-button href="{{ route('organization.payment-methods.create') }}" variant="primary" icon="fas fa-plus">
                        Add Payment Method
                    </x-button>
                </div>
            </div>
        @endforelse
    </div>
    
    @if($paymentMethods->hasPages())
        <div class="mt-6">
            {{ $paymentMethods->links() }}
        </div>
    @endif
</x-organization-layout>