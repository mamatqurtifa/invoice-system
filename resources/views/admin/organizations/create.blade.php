<x-admin-layout>
    @section('title', 'Create Organization')
    
    @php
        $users = \App\Models\User::all();
        $currentDateTime = '2025-06-17 09:47:25'; // Current UTC datetime
        $currentUser = 'mamat'; // Current user login
    @endphp
    
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Create Organization</h2>
        <p class="mt-1 text-sm text-gray-600">Add a new organization to the system</p>
        <p class="text-sm text-gray-500">Current Date: {{ $currentDateTime }} | User: {{ $currentUser }}</p>
    </div>
    
    <div class="bg-white shadow rounded-lg overflow-hidden max-w-3xl mx-auto">
        <form action="{{ route('admin.organizations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Organization Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Organization Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label for="customer_service_number" class="block text-sm font-medium text-gray-700">Customer Service Number</label>
                            <input type="text" name="customer_service_number" id="customer_service_number" value="{{ old('customer_service_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                            @error('customer_service_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                            <input type="url" name="website" id="website" value="{{ old('website') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Tax ID -->
                        <div>
                            <label for="tax_identification_number" class="block text-sm font-medium text-gray-700">Tax Identification Number</label>
                            <input type="text" name="tax_identification_number" id="tax_identification_number" value="{{ old('tax_identification_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                            @error('tax_identification_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Logo -->
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                            <div class="mt-1 flex items-center">
                                <input type="file" name="logo" id="logo" accept="image/*" class="block text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 focus:outline-none">
                            </div>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Owner Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="owner_type" class="block text-sm font-medium text-gray-700">Owner Type <span class="text-red-500">*</span></label>
                            <div class="mt-1 space-y-2">
                                <div class="flex items-center">
                                    <input type="radio" name="owner_type" id="existing_user" value="existing" class="focus:ring-sky-500 h-4 w-4 text-sky-600 border-gray-300" checked>
                                    <label for="existing_user" class="ml-3 block text-sm font-medium text-gray-700">
                                        Existing User
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="owner_type" id="new_user" value="new" class="focus:ring-sky-500 h-4 w-4 text-sky-600 border-gray-300">
                                    <label for="new_user" class="ml-3 block text-sm font-medium text-gray-700">
                                        Create New User
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div id="existing-user-container">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">Select User <span class="text-red-500">*</span></label>
                            <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                                <option value="">-- Select User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div id="new-user-container" class="md:col-span-2 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="owner_name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                                    @error('owner_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="owner_email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="owner_email" id="owner_email" value="{{ old('owner_email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                                    @error('owner_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="owner_password" class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                                    <input type="password" name="owner_password" id="owner_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                                    @error('owner_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="owner_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                                    <input type="password" name="owner_password_confirmation" id="owner_password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                                </div>
                                
                                <div>
                                    <label for="owner_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="text" name="owner_phone" id="owner_phone" value="{{ old('owner_phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                                    @error('owner_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 text-right">
                <a href="{{ route('admin.organizations.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200 mr-2">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i> Create Organization
                </button>
            </div>
        </form>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const existingUserInput = document.getElementById('existing_user');
            const newUserInput = document.getElementById('new_user');
            const existingUserContainer = document.getElementById('existing-user-container');
            const newUserContainer = document.getElementById('new-user-container');
            
            function toggleOwnerType() {
                if (existingUserInput.checked) {
                    existingUserContainer.classList.remove('hidden');
                    newUserContainer.classList.add('hidden');
                } else {
                    existingUserContainer.classList.add('hidden');
                    newUserContainer.classList.remove('hidden');
                }
            }
            
            // Initial toggle
            toggleOwnerType();
            
            // Listen for changes
            existingUserInput.addEventListener('change', toggleOwnerType);
            newUserInput.addEventListener('change', toggleOwnerType);
        });
    </script>
    @endpush
</x-admin-layout>