<x-organization-layout>
    @section('title', 'Organization Profile')
    
    <div class="max-w-5xl mx-auto">
        <!-- Organization Profile Card -->
        <x-card class="mb-6">
            <div class="md:flex">
                <!-- Logo/Banner Section -->
                <div class="md:flex-shrink-0 bg-gradient-to-r from-sky-500 to-sky-600 text-white p-6 md:w-64 flex flex-col items-center justify-center relative">
                    @if($organization->logo)
                        <img src="{{ Storage::url($organization->logo) }}" alt="{{ $organization->name }}" class="h-24 w-24 object-contain rounded-lg bg-white p-2 shadow-sm">
                    @else
                        <div class="h-24 w-24 rounded-lg bg-white flex items-center justify-center shadow-sm">
                            <span class="text-3xl font-bold text-gray-700">{{ substr($organization->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <h2 class="mt-4 text-xl font-bold text-white text-center">{{ $organization->name }}</h2>
                </div>
                
                <!-- Profile Information -->
                <div class="p-6 md:p-8 flex-1">
                    <div class="flex justify-between flex-wrap gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Organization Profile</h1>
                            <p class="mt-1 text-sm text-gray-500">Manage your organization's information</p>
                        </div>
                        <div>
                            <x-button 
                                href="{{ route('organization.profile.edit') }}" 
                                icon="fas fa-edit"
                                variant="primary"
                            >
                                Edit Profile
                            </x-button>
                        </div>
                    </div>
                    
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Organization Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $organization->name }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $organization->email ?? 'Not set' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Customer Service Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $organization->customer_service_number ?? 'Not set' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Website</dt>
                                <dd class="mt-1 text-sm">
                                    @if($organization->website)
                                        <a href="{{ $organization->website }}" target="_blank" class="text-sky-600 hover:text-sky-900">
                                            {{ $organization->website }} <i class="fas fa-external-link-alt text-xs"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-500">Not set</span>
                                    @endif
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tax Identification Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $organization->tax_identification_number ?? 'Not set' }}</dd>
                            </div>
                            
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $organization->address ?? 'Not set' }}</dd>
                            </div>
                            
                            @if($organization->description)
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $organization->description }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </x-card>
        
        <!-- Account Information -->
        <x-card title="Account Information" class="mb-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Account Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->phone_number ?? 'Not set' }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                    <dd class="mt-1">
                        @if($user->status === 'active')
                            <x-badge color="green">Active</x-badge>
                        @elseif($user->status === 'inactive')
                            <x-badge color="gray">Inactive</x-badge>
                        @elseif($user->status === 'suspended')
                            <x-badge color="red">Suspended</x-badge>
                        @endif
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F d, Y') }}</dd>
                </div>
                
                <div class="flex items-center">
                    <dt class="text-sm font-medium text-gray-500">Password</dt>
                    <dd class="ml-2">
                        <span class="px-2 py-1 text-xs text-gray-500 bg-gray-100 rounded-md">
                            ••••••••
                        </span>
                    </dd>
                    <dd class="ml-2">
                        <a href="{{ route('organization.profile.edit') }}#password" class="text-sm text-sky-600 hover:text-sky-900">Change</a>
                    </dd>
                </div>
            </dl>
        </x-card>
        
        <!-- Organization Statistics -->
        <x-card title="Organization Statistics">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <dt class="text-sm font-medium text-gray-500">Projects</dt>
                    <dd class="mt-1 text-3xl font-semibold text-sky-600">{{ $organization->projects->count() }}</dd>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <dt class="text-sm font-medium text-gray-500">Products</dt>
                    <dd class="mt-1 text-3xl font-semibold text-sky-600">{{ $organization->products->count() }}</dd>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <dt class="text-sm font-medium text-gray-500">Customers</dt>
                    <dd class="mt-1 text-3xl font-semibold text-sky-600">{{ $organization->customers->count() }}</dd>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <dt class="text-sm font-medium text-gray-500">Total Orders</dt>
                    <dd class="mt-1 text-3xl font-semibold text-sky-600">
                        @php
                            $orderCount = 0;
                            foreach ($organization->projects as $project) {
                                $orderCount += $project->orders->count();
                            }
                        @endphp
                        {{ $orderCount }}
                    </dd>
                </div>
            </div>
        </x-card>
    </div>
</x-organization-layout>