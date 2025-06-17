<x-organization-layout>
    @section('title', $template->name)
    
    @php
        $breadcrumbs = [
            'Invoice Templates' => route('organization.invoice-templates.index'),
            $template->name => '#'
        ];
    @endphp
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $template->name }}</h2>
            <div class="mt-1 flex items-center">
                @if($template->is_default)
                    <x-badge color="green">Default Template</x-badge>
                @endif
                
                <span class="text-sm text-gray-500 ml-2">{{ $invoiceCount }} invoices using this template</span>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
            <x-button 
                href="{{ route('organization.invoice-templates.preview', $template) }}" 
                variant="primary"
                icon="fas fa-desktop"
            >
                Preview
            </x-button>
            
            <x-button 
                href="{{ route('organization.invoice-templates.edit', $template) }}" 
                variant="secondary"
                icon="fas fa-edit"
            >
                Edit
            </x-button>
            
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <x-button variant="secondary" icon="fas fa-ellipsis-h">
                        Actions
                    </x-button>
                </x-slot>
                
                <x-slot name="content">
                    @if(!$template->is_default)
                        <form action="{{ route('organization.invoice-templates.set-default', $template) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-star mr-2 text-yellow-500"></i> Set as Default
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('organization.invoice-templates.duplicate', $template) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-copy mr-2"></i> Duplicate Template
                    </a>
                    
                    @if(!$template->is_default && $invoiceCount === 0)
                        <form action="{{ route('organization.invoice-templates.destroy', $template) }}" method="POST" class="block w-full text-left" onsubmit="return confirm('Are you sure you want to delete this template?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <i class="fas fa-trash-alt mr-2"></i> Delete Template
                            </button>
                        </form>
                    @endif
                </x-slot>
            </x-dropdown>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Template Preview -->
            <x-card>
                <div class="flex items-center justify-center border border-gray-200 rounded-md overflow-hidden bg-gray-50 h-96">
                    @if($template->preview_image)
                        <img src="{{ Storage::url($template->preview_image) }}" alt="{{ $template->name }}" class="object-contain h-full max-w-full">
                    @else
                        <div class="text-center text-gray-400">
                            <i class="fas fa-file-invoice text-5xl mb-3"></i>
                            <p>No preview image available</p>
                            <p class="text-sm mt-1">Click the 'Preview' button to see how this template looks</p>
                        </div>
                    @endif
                </div>
                
                <div class="flex justify-center mt-4">
                    <x-button 
                        href="{{ route('organization.invoice-templates.preview', $template) }}" 
                        variant="primary"
                        icon="fas fa-desktop"
                    >
                        Preview Template
                    </x-button>
                </div>
            </x-card>
            
            <!-- Template Details -->
            <x-card title="Template Settings">
                <div class="divide-y divide-gray-200">
                    <div class="py-3">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Header Settings</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <div>
                                <dt class="text-gray-500">Show Organization Logo</dt>
                                <dd class="font-medium text-gray-900">{{ $template->show_organization_logo ? 'Yes' : 'No' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-gray-500">Header Text</dt>
                                <dd class="font-medium text-gray-900">{{ $template->header_text ?: 'None' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-gray-500">Text Alignment</dt>
                                <dd class="font-medium text-gray-900">{{ ucfirst($template->text_alignment) }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <div class="py-3">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Content Settings</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <div>
                                <dt class="text-gray-500">Primary Color</dt>
                                <dd class="font-medium text-gray-900 flex items-center">
                                    <span class="inline-block h-4 w-4 rounded mr-1.5" style="background-color: {{ $template->primary_color }}"></span>
                                    {{ $template->primary_color }}
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-gray-500">Font Family</dt>
                                <dd class="font-medium text-gray-900">{{ explode(',', $template->font_family)[0] }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-gray-500">Show Payment Instructions</dt>
                                <dd class="font-medium text-gray-900">{{ $template->show_payment_instructions ? 'Yes' : 'No' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-gray-500">Show Payment Method Logo</dt>
                                <dd class="font-medium text-gray-900">{{ $template->show_payment_method_logo ? 'Yes' : 'No' }}</dd>
                            </div>
                            
                            @if($template->footer_text)
                                <div class="sm:col-span-2">
                                    <dt class="text-gray-500">Footer Text</dt>
                                    <dd class="font-medium text-gray-900">{{ $template->footer_text }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                    
                    <div class="py-3">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Additional Settings</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <div>
                                <dt class="text-gray-500">Include Signature Block</dt>
                                <dd class="font-medium text-gray-900">{{ $template->include_signature ? 'Yes' : 'No' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-gray-500">Include Stamp Space</dt>
                                <dd class="font-medium text-gray-900">{{ $template->include_stamp ? 'Yes' : 'No' }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-gray-500">Include Terms & Conditions</dt>
                                <dd class="font-medium text-gray-900">{{ $template->include_terms ? 'Yes' : 'No' }}</dd>
                            </div>
                        </dl>
                        
                        @if($template->include_terms && $template->terms_text)
                            <div class="mt-3">
                                <h4 class="text-xs font-medium text-gray-500 mb-1">Terms & Conditions Text:</h4>
                                <div class="bg-gray-50 p-3 rounded text-sm text-gray-700">
                                    {{ $template->terms_text }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </x-card>
            
            <!-- Recent Invoices -->
            <x-card title="Recent Invoices Using This Template">
                @if($recentInvoices->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Invoice #
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentInvoices as $invoice)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('organization.invoices.show', $invoice) }}" class="text-sm font-medium text-sky-600 hover:text-sky-800">
                                                {{ $invoice->invoice_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $invoice->order->customer->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $invoice->invoice_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($invoice->order->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('organization.invoices.show', $invoice) }}" class="text-sky-600 hover:text-sky-900 mr-3">View</a>
                                            <a href="{{ route('organization.invoices.download-pdf', $invoice) }}" class="text-sky-600 hover:text-sky-900">PDF</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($invoiceCount > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('organization.invoices.index', ['template_id' => $template->id]) }}" class="text-sm font-medium text-sky-600 hover:text-sky-800">
                                View all {{ $invoiceCount }} invoices
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">No invoices have been created with this template yet.</p>
                    </div>
                @endif
            </x-card>
        </div>
        
        <div class="space-y-6">
            <!-- Template Information -->
            <x-card title="Template Information">
                <dl class="divide-y divide-gray-200">
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Creation Date</dt>
                        <dd class="text-sm text-gray-900">{{ $template->created_at->format('M d, Y') }}</dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $template->updated_at->format('M d, Y') }}</dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Default Template</dt>
                        <dd class="text-sm">
                            @if($template->is_default)
                                <x-badge color="green" size="sm">Yes</x-badge>
                            @else
                                <x-badge color="gray" size="sm">No</x-badge>
                            @endif
                        </dd>
                    </div>
                    
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Invoices Using Template</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $invoiceCount }}</dd>
                    </div>
                </dl>
            </x-card>
            
            <!-- Actions -->
            <x-card title="Actions">
                <div class="space-y-3">
                    <x-button 
                        href="{{ route('organization.invoice-templates.preview', $template) }}" 
                        variant="primary" 
                        icon="fas fa-desktop"
                        full-width="true"
                    >
                        Preview Template
                    </x-button>
                    
                    <x-button 
                        href="{{ route('organization.invoice-templates.edit', $template) }}" 
                        variant="secondary" 
                        icon="fas fa-edit"
                        full-width="true"
                    >
                        Edit Template
                    </x-button>
                    
                    @if(!$template->is_default)
                        <form action="{{ route('organization.invoice-templates.set-default', $template) }}" method="POST">
                            @csrf
                            <x-button 
                                type="submit"
                                variant="secondary"
                                icon="fas fa-star"
                                full-width="true"
                            >
                                Set as Default Template
                            </x-button>
                        </form>
                    @endif
                    
                    <x-button 
                        href="{{ route('organization.invoice-templates.duplicate', $template) }}" 
                        variant="secondary" 
                        icon="fas fa-copy"
                        full-width="true"
                    >
                        Duplicate Template
                    </x-button>
                    
                    @if(!$template->is_default && $invoiceCount === 0)
                        <form action="{{ route('organization.invoice-templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this template?')">
                            @csrf
                            @method('DELETE')
                            <x-button 
                                type="submit"
                                variant="danger"
                                icon="fas fa-trash"
                                full-width="true"
                            >
                                Delete Template
                            </x-button>
                        </form>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</x-organization-layout>