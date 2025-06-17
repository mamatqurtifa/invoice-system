<x-admin-layout>
    @section('title', 'User Details')
    
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">User Details</h2>
                <p class="mt-1 text-sm text-gray-600">Viewing information for {{ $user->name }}</p>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i> Edit User
                </a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-colors duration-200">
                        <i class="fas fa-trash mr-2"></i> Delete User
                    </button>
                </form>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Users
                </a>
            </div>
        </div>
    </div>
    
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12">
                    @if($user->profile_image)
                        <img class="h-12 w-12 rounded-full object-cover" src="{{ Storage::url($user->profile_image) }}" alt="{{ $user->name }}">
                    @else
                        <div class="h-12 w-12 rounded-full bg-sky-100 flex items-center justify-center">
                            <span class="text-xl text-sky-600 font-medium">
                                {{ substr($user->name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="ml-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $user->name }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        {{ ucfirst($user->role) }}
                        <span class="mx-2">•</span>
                        @if($user->status === 'active')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @elseif($user->status === 'inactive')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                Inactive
                            </span>
                        @elseif($user->status === 'suspended')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                Suspended
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Email address
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $user->email }}
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Phone number
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $user->phone_number ?? 'Not provided' }}
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Created at
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $user->created_at->format('F d, Y \a\t h:i A') }}
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Last updated at
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $user->updated_at->format('F d, Y \a\t h:i A') }}
                    </dd>
                </div>
            </dl>
        </div>
        
        @if($user->role === 'organization')
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Organization Information
                </h3>
            </div>
            
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                @if($user->organization)
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                Organization name
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $user->organization->name }}
                                <a href="{{ route('admin.organizations.show', $user->organization) }}" class="ml-1 text-sky-600 hover:text-sky-800">
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                Email address
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $user->organization->email ?? 'Not provided' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                Customer service number
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $user->organization->customer_service_number ?? 'Not provided' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                Website
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                                                @if($user->organization->website)
                                    <a href="{{ $user->organization->website }}" target="_blank" class="text-sky-600 hover:text-sky-800">
                                        {{ $user->organization->website }} <i class="fas fa-external-link-alt text-xs"></i>
                                    </a>
                                @else
                                    Not provided
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                Address
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $user->organization->address ?? 'Not provided' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                Organization Statistics
                            </dt>
                            <dd class="mt-2">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="bg-gray-50 p-3 rounded-lg text-center">
                                        <p class="text-sm font-medium text-gray-500">Projects</p>
                                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $user->organization->projects->count() }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg text-center">
                                        <p class="text-sm font-medium text-gray-500">Products</p>
                                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $user->organization->products->count() }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg text-center">
                                        <p class="text-sm font-medium text-gray-500">Customers</p>
                                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $user->organization->customers->count() }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg text-center">
                                        <p class="text-sm font-medium text-gray-500">Invoices</p>
                                        <p class="mt-1 text-2xl font-semibold text-gray-900">
                                            @php
                                                $invoiceCount = 0;
                                                foreach ($user->organization->projects as $project) {
                                                    $invoiceCount += $project->orders()->has('invoice')->count();
                                                }
                                            @endphp
                                            {{ $invoiceCount }}
                                        </p>
                                    </div>
                                </div>
                            </dd>
                        </div>
                    </dl>
                @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    This user has a role of 'organization' but doesn't have an associated organization profile yet.
                                    <a href="{{ route('admin.users.edit', $user) }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                                        Create one now
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
        
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Activity & Security
            </h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Last login
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $user->last_login_at ? $user->last_login_at->format('F d, Y \a\t h:i A') : 'Never logged in' }}
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Last login IP
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $user->last_login_ip ?? 'N/A' }}
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Email verified
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i> Verified on {{ $user->email_verified_at->format('F d, Y') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Not verified
                            </span>
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Two-factor authentication
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($user->two_factor_secret)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-lock mr-1"></i> Enabled
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-unlock mr-1"></i> Disabled
                            </span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</x-admin-layout>