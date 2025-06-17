<x-organization-layout>
    @section('title', 'Invoice Templates')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Invoice Templates</h2>
            <p class="mt-1 text-sm text-gray-600">Manage your organization's invoice templates</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <x-button 
                href="{{ route('organization.invoice-templates.create') }}" 
                icon="fas fa-plus" 
                variant="primary"
            >
                Create Template
            </x-button>
        </div>
    </div>
    
    @if(session('success'))
        <x-alert type="success" :dismissible="true" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($invoiceTemplates as $template)
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="h-32 bg-gray-50 border-b border-gray-200 flex items-center justify-center overflow-hidden">
                    @if($template->preview_image)
                        <img src="{{ Storage::url($template->preview_image) }}" alt="{{ $template->name }}" class="object-contain h-full">
                    @else
                        <div class="text-gray-400 flex flex-col items-center justify-center p-4 text-center">
                            <i class="fas fa-file-invoice text-4xl mb-2"></i>
                            <span class="text-sm">No preview available</span>
                        </div>
                    @endif
                </div>
                
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $template->name }}</h3>
                            
                            <div class="mt-1 space-y-1">
                                @if($template->is_default)
                                    <x-badge color="green" size="sm">Default Template</x-badge>
                                @endif
                                
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-clock text-xs mr-1.5"></i>
                                    <span>Last updated {{ $template->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            {{ $invoiceCountByTemplate[$template->id] ?? 0 }} invoices
                        </div>
                        
                        <div class="flex space-x-1">
                            <a href="{{ route('organization.invoice-templates.show', $template) }}" class="p-1.5 text-gray-500 hover:text-gray-700 transition-colors duration-200" title="View details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('organization.invoice-templates.preview', $template) }}" class="p-1.5 text-gray-500 hover:text-gray-700 transition-colors duration-200" title="Preview template">
                                <i class="fas fa-desktop"></i>
                            </a>
                            <a href="{{ route('organization.invoice-templates.edit', $template) }}" class="p-1.5 text-gray-500 hover:text-gray-700 transition-colors duration-200" title="Edit template">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            @if(!$template->is_default)
                                <form action="{{ route('organization.invoice-templates.destroy', $template) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this template?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-500 hover:text-rose-600 transition-colors duration-200" title="Delete template" {{ $invoiceCountByTemplate[$template->id] > 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            @if(!$template->is_default)
                                <form action="{{ route('organization.invoice-templates.set-default', $template) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm text-sky-600 hover:text-sky-800">
                                        Set as default
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <a href="{{ route('organization.invoice-templates.preview', $template) }}" class="inline-flex items-center text-sm font-medium text-sky-600 hover:text-sky-800">
                            Preview <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                    <i class="fas fa-file-invoice text-sky-600"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No invoice templates</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new invoice template.</p>
                <div class="mt-6">
                    <x-button href="{{ route('organization.invoice-templates.create') }}" variant="primary" icon="fas fa-plus">
                        Create Template
                    </x-button>
                </div>
            </div>
        @endforelse
    </div>
    
    @if($invoiceTemplates->hasPages())
        <div class="mt-6">
            {{ $invoiceTemplates->links() }}
        </div>
    @endif
</x-organization-layout>