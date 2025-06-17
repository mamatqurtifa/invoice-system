<x-organization-layout>
    @section('title', 'Invoice Templates')
    
    @php
        $breadcrumbs = [
            'Settings' => '#',
            'Invoice Templates' => '#'
        ];
    @endphp
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Invoice Templates</h2>
            <p class="mt-1 text-sm text-gray-600">Manage your invoice templates for professional looking invoices</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('organization.invoice-templates.create') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                New Template
            </a>
        </div>
    </div>
    
    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $template)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300 relative">
                <!-- Template Preview -->
                <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden relative">
                    <div class="absolute inset-0 p-4 bg-gradient-to-b from-{{ $template->primary_color }}/10 to-{{ $template->secondary_color }}/20">
                        <div class="h-full border-t-4 border-{{ $template->primary_color }}-500 flex flex-col">
                            <div class="flex justify-between items-center mb-4">
                                <div class="bg-white/80 backdrop-blur-sm p-2 rounded">
                                    <div class="w-20 h-5 bg-gray-700 rounded"></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-bold">INVOICE</div>
                                    <div class="text-xs">#INV-12345</div>
                                </div>
                            </div>
                            
                            <div class="flex-grow mt-2">
                                <div class="h-1 w-20 bg-gray-300"></div>
                                <div class="h-1 w-32 bg-gray-300 mt-1"></div>
                                
                                <div class="mt-4 flex justify-between">
                                    <div>
                                        <div class="h-1 w-16 bg-gray-300"></div>
                                        <div class="h-1 w-24 bg-gray-300 mt-1"></div>
                                    </div>
                                    <div>
                                        <div class="h-1 w-16 bg-gray-300"></div>
                                        <div class="h-1 w-24 bg-gray-300 mt-1"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-end">
                                <div class="bg-white/80 backdrop-blur-sm p-1 rounded">
                                    <div class="h-1 w-20 bg-gray-700"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($template->is_default)
                        <div class="absolute top-2 left-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Default
                            </span>
                        </div>
                    @endif
                </div>
                
                <!-- Template Content -->
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $template->name }}</h3>
                    
                    <div class="mt-2 flex items-center">
                        <span class="inline-block h-4 w-4 rounded-full bg-{{ $template->primary_color }}-500 mr-1"></span>
                        <span class="inline-block h-4 w-4 rounded-full bg-{{ $template->secondary_color }}-500"></span>
                        <span class="ml-2 text-sm text-gray-500">{{ ucfirst($template->font) }}</span>
                    </div>
                    
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <span class="mr-2">Logo position:</span>
                        <span class="font-medium text-gray-900">{{ ucfirst($template->logo_position) }}</span>
                    </div>
                    
                    @if($template->has_signature)
                        <div class="mt-1 flex items-center text-sm text-gray-500">
                            <span class="mr-2">Signature:</span>
                            <span class="font-medium text-gray-900">{{ ucfirst($template->signature_position) }}</span>
                        </div>
                    @endif
                </div>
                
                <!-- Template Actions -->
                <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex flex-wrap justify-between items-center gap-2">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('organization.invoice-templates.preview', $template) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 transition-colors duration-200">
                            <i class="fas fa-eye mr-1"></i> Preview
                        </a>
                        
                        <a href="{{ route('organization.invoice-templates.edit', $template) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                    </div>
                    
                    <div>
                        @if(!$template->is_default)
                            <form action="{{ route('organization.invoice-templates.set-default', $template) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                    <i class="fas fa-check-circle mr-1"></i> Set Default
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm p-6 text-center">
                <div class="flex flex-col items-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                        <i class="fas fa-file-alt text-sky-600"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No invoice templates</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new template.</p>
                    <div class="mt-6">
                        <a href="{{ route('organization.invoice-templates.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            <i class="fas fa-plus mr-2"></i>
                            Create Template
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Template Tips -->
    <div class="mt-8 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Template Tips</h3>
        </div>
        
        <div class="p-6">
            <div class="prose max-w-none text-gray-500">
                <p>A well-designed invoice template can enhance your brand image and improve customer experience. Here are some tips:</p>
                
                <ul class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <li class="flex items-start">
                        <div class="flex-shrink-0 h-5 w-5 text-green-500">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="ml-2">
                            <span class="font-medium text-gray-900">Use consistent branding</span> - Match colors with your logo and website
                        </p>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 h-5 w-5 text-green-500">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="ml-2">
                            <span class="font-medium text-gray-900">Keep it professional</span> - Choose readable fonts and clean layouts
                        </p>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 h-5 w-5 text-green-500">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="ml-2">
                            <span class="font-medium text-gray-900">Include all details</span> - Ensure payment information is clearly visible
                        </p>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 h-5 w-5 text-green-500">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="ml-2">
                            <span class="font-medium text-gray-900">Set a default template</span> - This will be used for all new invoices
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-organization-layout>