<x-organization-layout>
    @section('title', 'Preview Template')
    
    @php
        $breadcrumbs = [
            'Invoice Templates' => route('organization.invoice-templates.index'),
            $template->name => route('organization.invoice-templates.show', $template),
            'Preview' => '#'
        ];
    @endphp
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Preview: {{ $template->name }}</h2>
            <p class="mt-1 text-sm text-gray-500">This is a preview of how your invoice will look with this template</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
            <x-button 
                href="{{ route('organization.invoice-templates.edit', $template) }}" 
                variant="primary"
                icon="fas fa-edit"
            >
                Edit Template
            </x-button>
            
            <x-button 
                href="{{ route('organization.invoice-templates.show', $template) }}" 
                variant="secondary"
            >
                Back to Details
            </x-button>
        </div>
    </div>
    
    <x-card class="max-w-5xl mx-auto p-0">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex-grow">
                    <h3 class="text-lg font-medium text-gray-900">Preview Invoice</h3>
                </div>
                <div class="flex space-x-2">
                    <x-button 
                        href="#" 
                        onClick="window.print();"
                        variant="secondary"
                        size="sm"
                        icon="fas fa-print"
                    >
                        Print
                    </x-button>
                    
                    @if($demoInvoice)
                        <x-button 
                            href="{{ route('organization.invoice-templates.download-preview', $template) }}" 
                            variant="secondary"
                            size="sm"
                            icon="fas fa-download"
                        >
                            Download PDF
                        </x-button>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-b-lg">
            <div id="invoice-preview" class="border border-gray-200 rounded-lg overflow-hidden shadow-sm min-h-[1000px]">
                <!-- This will be populated with the iframe -->
                <iframe src="{{ route('organization.invoice-templates.render-preview', $template) }}" class="w-full h-[1000px] border-0"></iframe>
            </div>
        </div>
    </x-card>
    
    <div class="mt-6 text-center">
        @if(!$template->is_default)
            <form action="{{ route('organization.invoice-templates.set-default', $template) }}" method="POST" class="inline">
                @csrf
                <x-button 
                    type="submit"
                    variant="primary"
                    icon="fas fa-star"
                >
                    Use This Template as Default
                </x-button>
            </form>
        @endif
    </div>
</x-organization-layout>