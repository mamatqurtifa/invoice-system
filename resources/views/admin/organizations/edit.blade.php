<x-admin-layout>
    @section('title', 'Edit Organization')
    
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Edit Organization</h2>
        <p class="mt-1 text-sm text-gray-600">Update information for {{ $organization->name }}</p>
    </div>
    
    <div class="bg-white shadow rounded-lg overflow-hidden max-w-3xl mx-auto">
        <form action="{{ route('admin.organizations.update', $organization) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Organization Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Organization Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $organization->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $organization->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label for="customer_service_number" class="block text-sm font-medium text-gray-700">Customer Service Number</label>
                            <input type="text" name="customer_service_number" id="customer_service_number" value="{{ old('customer_service_number', $organization->customer_service_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                            @error('customer_service_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                            <input type="url" name="website" id="website" value="{{ old('website', $organization->website) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Tax ID -->
                        <div>
                            <label for="tax_identification_number" class="block text-sm font-medium text-gray-700">Tax Identification Number</label>
                            <input type="text" name="tax_identification_number" id="tax_identification_number" value="{{ old('tax_identification_number', $organization->tax_identification_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                            @error('tax_identification_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Logo -->
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                            <div class="mt-1 flex items-center">
                                @if($organization->logo)
                                    <div class="mr-3">
                                        <img src="{{ Storage::url($organization->logo) }}" alt="{{ $organization->name }}" class="h-12 w-12 object-cover rounded">
                                    </div>
                                @endif
                                <input type="file" name="logo" id="logo" accept="image/*" class="block text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 focus:outline-none">
                            </div>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">{{ old('address', $organization->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">{{ old('description', $organization->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Owner Information</h3>
                    
                    <div class="space-y-4">
                        @if($organization->user)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($organization->user->profile_image)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($organization->user->profile_image) }}" alt="{{ $organization->user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-sky-100 flex items-center justify-center">
                                                <span class="text-sm text-sky-600 font-medium">
                                                    {{ substr($organization->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $organization->user->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $organization->user->email }}</p>
                                    </div>
                                    <a href="{{ route('admin.users.edit', $organization->user) }}" class="ml-auto text-sm text-sky-600 hover:text-sky-800">
                                        Edit User
                                    </a>
                                </div>
                            </div>
                            
                            <div>
                                <label for="change_owner" class="block text-sm font-medium text-gray-700">Change Owner?</label>
                                <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500 sm:text-sm">
                                    <option value="{{ $organization->user->id }}">Current: {{ $organization->user->name }} ({{ $organization->user->email }})</option>
                                    @foreach($users as $user)
                                        @if($user->id != $organization->user->id)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            This organization doesn't have an owner user assigned.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">Assign Owner <span class="text-red-500">*</span></label>
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
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 text-right">
                <a href="{{ route('admin.organizations.show', $organization) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200 mr-2">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i> Update Organization
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>