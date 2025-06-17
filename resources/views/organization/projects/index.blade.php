<x-organization-layout>
    @section('title', 'Projects')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Projects</h2>
            <p class="mt-1 text-sm text-gray-600">Manage all your projects</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <x-button 
                href="{{ route('organization.projects.create') }}" 
                icon="fas fa-plus" 
                variant="primary"
            >
                New Project
            </x-button>
        </div>
    </div>
    
    <!-- Search & Filters -->
    <x-card class="mb-6">
        <form action="{{ route('organization.projects.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <x-form.input
                    id="search"
                    name="search"
                    placeholder="Search projects..."
                    :value="request()->get('search')"
                    leading-icon="fas fa-search"
                />
                
                <!-- Status -->
                <x-form.select
                    id="status"
                    name="status"
                    :options="['active' => 'Active', 'completed' => 'Completed', 'cancelled' => 'Cancelled']"
                    :value="request()->get('status')"
                    placeholder="All Status"
                />
                
                <!-- Type -->
                <x-form.select
                    id="type"
                    name="type"
                    :options="['direct' => 'Direct Order', 'preorder' => 'Pre-Order']"
                    :value="request()->get('type')"
                    placeholder="All Types"
                />
                
                <!-- Sort -->
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
            
            <div class="flex justify-end gap-2">
                <x-button type="submit" variant="primary" icon="fas fa-filter">
                    Filter
                </x-button>
                
                <x-button href="{{ route('organization.projects.index') }}" variant="secondary" icon="fas fa-sync">
                    Reset
                </x-button>
            </div>
        </form>
    </x-card>
    
    <!-- Projects Grid -->
    @if(count($projects) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
                <x-card class="h-full flex flex-col hover:shadow transition-shadow duration-300">
                    <!-- Project Header -->
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            @if($project->logo)
                                <img class="h-12 w-12 rounded-lg object-cover" src="{{ Storage::url($project->logo) }}" alt="{{ $project->name }}">
                            @else
                                <div class="h-12 w-12 rounded-lg bg-sky-100 flex items-center justify-center">
                                    <span class="text-xl font-bold text-sky-600">{{ substr($project->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4 flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 truncate">
                                <a href="{{ route('organization.projects.show', $project) }}" class="hover:text-sky-600">
                                    {{ $project->name }}
                                </a>
                            </h3>
                            <div class="flex flex-wrap gap-2 mt-1">
                                <x-badge :color="$project->status === 'active' ? 'green' : ($project->status === 'completed' ? 'blue' : 'red')" size="sm">
                                    {{ ucfirst($project->status) }}
                                </x-badge>
                                
                                <x-badge :color="$project->type === 'preorder' ? 'purple' : 'sky'" size="sm">
                                    {{ $project->type === 'preorder' ? 'Pre-Order' : 'Direct Order' }}
                                </x-badge>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Project Description -->
                    <div class="mb-4 flex-grow">
                        <p class="text-sm text-gray-500 line-clamp-3">
                            {{ $project->description ?: 'No description provided.' }}
                        </p>
                    </div>
                    
                    <!-- Project Stats -->
                    <div class="border-t border-gray-100 pt-4">
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $project->projectProducts->count() }}</p>
                                <p class="text-xs text-gray-500">Products</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $project->orders->count() }}</p>
                                <p class="text-xs text-gray-500">Orders</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $project->orders()->has('invoice')->count() }}</p>
                                <p class="text-xs text-gray-500">Invoices</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Project Actions -->
                    <div class="mt-4 flex justify-end space-x-2 border-t border-gray-100 pt-4">
                        <x-button href="{{ route('organization.projects.show', $project) }}" variant="secondary" size="sm">
                            View
                        </x-button>
                        
                        <x-button href="{{ route('organization.projects.edit', $project) }}" variant="secondary" size="sm" icon="fas fa-edit">
                            Edit
                        </x-button>
                    </div>
                </x-card>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            <x-pagination :paginator="$projects" />
        </div>
    @else
        <x-card>
            <div class="text-center py-10">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                    <i class="fas fa-folder text-sky-600"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No projects</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new project.</p>
                <div class="mt-6">
                    <x-button href="{{ route('organization.projects.create') }}" variant="primary" icon="fas fa-plus">
                        New Project
                    </x-button>
                </div>
            </div>
        </x-card>
    @endif
</x-organization-layout>