<x-organization-layout>
    @section('title', 'Import Customers')
    
    @php
        $breadcrumbs = [
            'Customers' => route('organization.customers.index'),
            'Import' => '#'
        ];
    @endphp
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Import Customers</h2>
            <p class="mt-1 text-sm text-gray-600">Bulk import customers from a CSV or Excel file</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Import Form -->
        <div class="lg:col-span-2">
            <x-card>
                <form action="{{ route('organization.customers.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    @if(session('success'))
                        <x-alert type="success" :dismissible="true">
                            {{ session('success') }}
                        </x-alert>
                    @endif
                    
                    @if(session('error'))
                        <x-alert type="error" :dismissible="true">
                            {{ session('error') }}
                        </x-alert>
                    @endif
                    
                    @if($errors->importCustomers->any())
                        <x-alert type="error" :dismissible="true">
                            <ul class="list-disc list-inside">
                                @foreach($errors->importCustomers->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-alert>
                    @endif
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upload File</h3>
                        
                        <x-form.input 
                            type="file"
                            id="file"
                            name="file"
                            label="CSV or Excel File"
                            accept=".csv, .xlsx"
                            help-text="Accepted formats: CSV (.csv), Excel (.xlsx)"
                            required
                        />
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Import Options</h3>
                        
                        <div class="space-y-4">
                            <x-form.checkbox
                                id="header_row"
                                name="header_row"
                                :checked="true"
                                value="1"
                                label="File contains header row"
                                help-text="First row contains column names and should be skipped"
                            />
                            
                            <x-form.checkbox
                                id="update_existing"
                                name="update_existing"
                                :checked="true"
                                value="1"
                                label="Update existing customers"
                                help-text="If a customer with the same email already exists, update their information"
                            />
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <x-button 
                            href="{{ route('organization.customers.index') }}" 
                            variant="secondary"
                        >
                            Cancel
                        </x-button>
                        
                        <x-button 
                            type="submit" 
                            variant="primary"
                            icon="fas fa-upload"
                        >
                            Import Customers
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>
        
        <!-- Right: Instructions -->
        <div>
            <x-card title="Import Instructions" class="mb-6">
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                    <li>Download our template or prepare a CSV/Excel file with customer data.</li>
                    <li>Make sure your file includes the required columns (name, email).</li>
                    <li>Other columns like phone_number, address, etc. are optional.</li>
                    <li>Upload your file using the form on the left.</li>
                    <li>Choose your import options.</li>
                    <li>Click Import to start the process.</li>
                </ol>
            </x-card>
            
            <x-card title="File Format" class="mb-6">
                <p class="text-sm text-gray-600 mb-4">Your file should contain these columns (header row):</p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-3 py-2 text-left font-medium text-gray-500">Column</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500">Required</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500">Example</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-3 py-2 text-gray-900">name</td>
                                <td class="px-3 py-2 text-green-600">Yes</td>
                                <td class="px-3 py-2 text-gray-600">John Doe</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-900">email</td>
                                <td class="px-3 py-2 text-green-600">Yes</td>
                                <td class="px-3 py-2 text-gray-600">john@example.com</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-900">phone_number</td>
                                <td class="px-3 py-2 text-gray-600">No</td>
                                <td class="px-3 py-2 text-gray-600">+1234567890</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-900">address</td>
                                <td class="px-3 py-2 text-gray-600">No</td>
                                <td class="px-3 py-2 text-gray-600">123 Main St</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-900">city</td>
                                <td class="px-3 py-2 text-gray-600">No</td>
                                <td class="px-3 py-2 text-gray-600">Jakarta</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-900">state</td>
                                <td class="px-3 py-2 text-gray-600">No</td>
                                <td class="px-3 py-2 text-gray-600">DKI Jakarta</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-900">postal_code</td>
                                <td class="px-3 py-2 text-gray-600">No</td>
                                <td class="px-3 py-2 text-gray-600">12345</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-900">country</td>
                                <td class="px-3 py-2 text-gray-600">No</td>
                                <td class="px-3 py-2 text-gray-600">Indonesia</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-card>
            
            <x-card>
                <div class="flex flex-col items-center text-center">
                    <i class="fas fa-file-download text-3xl text-sky-600 mb-2"></i>
                    <h4 class="font-medium text-gray-900">Download Template</h4>
                    <p class="text-sm text-gray-500 mb-4">Use our template file to ensure compatibility</p>
                    
                    <div class="flex flex-wrap justify-center gap-2">
                        <x-button href="{{ route('organization.customers.download-template', ['format' => 'csv']) }}" variant="secondary" size="sm" icon="fas fa-file-csv">
                            CSV Template
                        </x-button>
                        
                        <x-button href="{{ route('organization.customers.download-template', ['format' => 'xlsx']) }}" variant="secondary" size="sm" icon="fas fa-file-excel">
                            Excel Template
                        </x-button>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</x-organization-layout>