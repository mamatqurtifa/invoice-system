<x-organization-layout>
    @section('title', 'Import Customers')
    
    @php
        $breadcrumbs = [
            'Customers' => route('organization.customers.index'),
            'Import' => '#'
        ];
    @endphp
    
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    Import Customers
                </h3>
            </div>
            
            <div class="p-6">
                <form action="{{ route('organization.customers.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="csv_file" class="block text-sm font-medium text-gray-700">CSV File <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex items-center">
                            <input type="file" name="csv_file" id="csv_file" accept=".csv,.txt" required
                                class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                        </div>
                        @error('csv_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">CSV Format Requirements:</h4>
                        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                            <p class="mb-2">Your CSV file should have the following columns:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li><span class="font-medium">name</span> - Required</li>
                                <li><span class="font-medium">email</span> - Optional</li>
                                <li><span class="font-medium">phone_number</span> - Optional</li>
                                <li><span class="font-medium">address</span> - Optional</li>
                            </ul>
                            <p class="mt-2">The first row should be the column headers exactly as shown above.</p>
                        </div>
                    </div>
                    
                    <!-- Example Template -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Example CSV:</h4>
                        <div class="bg-gray-50 rounded-lg p-4 overflow-auto">
                            <pre class="text-xs text-gray-600">name,email,phone_number,address
John Doe,john@example.com,081234567890,"123 Main St, City"
Jane Smith,jane@example.com,082345678901,"456 Oak St, Town"</pre>
                        </div>
                        <div class="mt-2 text-right">
                            <a href="#" class="text-sm text-sky-600 hover:text-sky-800">Download template</a>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('organization.customers.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                            Cancel
                        </a>
                        
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                            <i class="fas fa-file-import mr-2"></i> Import Customers
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    Import Instructions
                </h3>
            </div>
            
            <div class="p-6">
                <div class="prose max-w-none">
                    <h4>Follow these steps to import customers:</h4>
                    
                    <ol>
                        <li>Prepare your CSV file with the required columns.</li>
                        <li>Make sure your CSV is properly formatted (UTF-8 encoding recommended).</li>
                        <li>Upload the file using the form above.</li>
                        <li>Review any import errors that might appear.</li>
                    </ol>
                    
                    <h4 class="mt-6">Notes:</h4>
                    
                    <ul>
                        <li>Maximum file size: 2MB</li>
                        <li>Duplicate emails will be identified during import.</li>
                        <li>All customers will be associated with your organization.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-organization-layout>