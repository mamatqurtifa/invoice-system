<x-admin-layout>
    @section('title', 'Organizations')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Organizations</h2>
            <p class="mt-1 text-sm text-gray-600">Manage all organizations in the system</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.organizations.create') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Create Organization
            </a>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <form action="{{ route('admin.organizations.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request()->get('search') }}" placeholder="Name, email, or address..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select name="sort" id="sort" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                        <option value="name_asc" {{ request()->get('sort') === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="name_desc" {{ request()->get('sort') === 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                        <option value="newest" {{ request()->get('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ request()->get('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>
                
                <div class="flex space-x-2 items-end">
                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    
                    <a href="{{ route('admin.organizations.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <i class="fas fa-sync mr-2"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Organizations Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($organizations as $organization)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow transition-shadow duration-300">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            @if($organization->logo)
                                <img class="h-12 w-12 rounded-full object-cover" src="{{ Storage::url($organization->logo) }}" alt="{{ $organization->name }}">
                            @else
                                <div class="h-12 w-12 rounded-full bg-sky-100 flex items-center justify-center">
                                    <span class="text-xl text-sky-600 font-medium">
                                        {{ substr($organization->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4 flex-1 truncate">
                            <h3 class="text-lg font-medium text-gray-900 truncate">{{ $organization->name }}</h3>
                            <p class="text-sm text-gray-500 truncate">{{ $organization->email }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 border-t border-gray-100 pt-4">
                        <dl class="grid grid-cols-2 gap-x-4 gap-y-3">
                            <div>
                                <dt class="text-xs text-gray-500">Projects</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $organization->projects->count() }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500">Products</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $organization->products->count() }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500">Customers</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $organization->customers->count() }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500">Users</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $organization->users->count() }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    @if($organization->user)
                        <div class="mt-4 border-t border-gray-100 pt-3 flex items-center">
                            <span class="text-xs text-gray-500 mr-2">Owner:</span>
                            <div class="flex items-center">
                                @if($organization->user->profile_image)
                                    <img class="h-5 w-5 rounded-full object-cover" src="{{ Storage::url($organization->user->profile_image) }}" alt="{{ $organization->user->name }}">
                                @else
                                    <div class="h-5 w-5 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-xs text-gray-600">
                                            {{ substr($organization->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                <span class="ml-2 text-sm font-medium text-gray-900 truncate max-w-[150px]">
                                    {{ $organization->user->name }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-end space-x-2">
                    <a href="{{ route('admin.organizations.show', $organization) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-sky-700 bg-sky-100 hover:bg-sky-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                        View
                    </a>
                    
                    <a href="{{ route('admin.organizations.edit', $organization) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                        Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow p-6 text-center">
                <div class="flex flex-col items-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                        <i class="fas fa-building text-sky-600"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No organizations</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new organization.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.organizations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Create Organization
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-6">
        {{ $organizations->links() }}
    </div>
</x-admin-layout>