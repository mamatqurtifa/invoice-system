<x-organization-layout>
    @section('title', 'Projects')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Projects</h2>
            <p class="mt-1 text-sm text-gray-600">Manage your projects for selling products</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('organization.projects.create') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Create Project
            </a>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <form action="{{ route('organization.projects.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request()->get('search') }}" placeholder="Search by name..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
            </div>
            
            <div class="w-40">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" id="type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                    <option value="">All Types</option>
                    <option value="preorder" {{ request()->get('type') === 'preorder' ? 'selected' : '' }}>Pre-Order</option>
                    <option value="direct_order" {{ request()->get('type') === 'direct_order' ? 'selected' : '' }}>Direct Order</option>
                </select>
            </div>
            
            <div class="w-40">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request()->get('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request()->get('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request()->get('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                
                <a href="{{ route('organization.projects.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>
    
    <!-- Projects Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                <!-- Project Header -->
                <div class="relative h-32 bg-gray-100 flex items-center justify-center overflow-hidden">
                    @if($project->logo)
                        <img src="{{ Storage::url($project->logo) }}" alt="{{ $project->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-image text-4xl"></i>
                            <span class="text-sm mt-2">No Image</span>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-2 right-2">
                        @if($project->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @elseif($project->status === 'completed')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Completed
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Cancelled
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Project Content -->
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $project->name }}</h3>
                    
                    <div class="flex items-center mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->type === 'preorder' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800' }}">
                            {{ $project->type === 'preorder' ? 'Pre-Order' : 'Direct Order' }}
                        </span>
                        
                        <span class="ml-2 text-xs text-gray-500">
                            {{ $project->start_date->format('M d, Y') }}
                            @if($project->end_date)
                                - {{ $project->end_date->format('M d, Y') }}
                            @endif
                        </span>
                    </div>
                    
                    <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                        {{ $project->description ?? 'No description available.' }}
                    </p>
                    
                    <!-- Project Info -->
                    <div class="mt-4 border-t border-gray-100 pt-3 grid grid-cols-2 gap-3">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500">Products</span>
                            <span class="text-sm font-medium text-gray-900">{{ $project->projectProducts->count() }}</span>
                        </div>
                        
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500">Orders</span>
                            <span class="text-sm font-medium text-gray-900">{{ $project->orders->count() }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Project Actions -->
                <div class="bg-gray-50 px-5 py-3 flex justify-end space-x-2">
                    <a href="{{ route('organization.projects.show', $project) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 transition-colors duration-200">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    
                    <a href="{{ route('organization.projects.edit', $project) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="flex flex-col items-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                        <i class="fas fa-folder text-sky-600"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No projects</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new project.</p>
                    <div class="mt-6">
                        <a href="{{ route('organization.projects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            <i class="fas fa-plus mr-2"></i>
                            Create Project
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-6">
        {{ $projects->links() }}
    </div>
</x-organization-layout>