<x-organization-layout>
    @section('title', 'Edit Organization Profile')
    
    <div class="max-w-5xl mx-auto">
        <!-- Update Organization Information -->
        <x-card 
            title="Organization Information"
            class="mb-6"
            id="organization-info"
        >
            <form action="{{ route('organization.profile.update-organization') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <!-- Current Logo -->
                        <div class="mr-6 flex-shrink-0">
                            @if($organization->logo)
                                <img src="{{ Storage::url($organization->logo) }}" alt="{{ $organization->name }}" class="h-20 w-20 object-cover rounded-lg">
                            @else
                                <div class="h-20 w-20 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-400">{{ substr($organization->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Logo Upload -->
                        <div class="flex-grow">
                            <x-form.input 
                                type="file" 
                                id="logo"
                                name="logo"
                                label="Organization Logo"
                                accept="image/*"
                                help-text="Upload a square image, minimum 200x200 pixels (PNG, JPG, or SVG)"
                                :error="$errors->first('logo')"
                            />
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Organization Name -->
                        <x-form.input 
                            id="name"
                            name="name"
                            label="Organization Name"
                            :value="old('name', $organization->name)"
                            required
                            :error="$errors->first('name')"
                        />
                        
                        <!-- Email -->
                        <x-form.input 
                            type="email"
                            id="email"
                            name="email"
                            label="Email Address"
                            :value="old('email', $organization->email)"
                            :error="$errors->first('email')"
                        />
                        
                        <!-- Customer Service Number -->
                        <x-form.input 
                            id="customer_service_number"
                            name="customer_service_number"
                            label="Customer Service Number"
                            :value="old('customer_service_number', $organization->customer_service_number)"
                            :error="$errors->first('customer_service_number')"
                        />
                        
                        <!-- Website -->
                        <x-form.input 
                            type="url"
                            id="website"
                            name="website"
                            label="Website"
                            :value="old('website', $organization->website)"
                            :error="$errors->first('website')"
                            help-text="Include http:// or https://"
                        />
                        
                        <!-- Tax ID -->
                        <x-form.input 
                            id="tax_identification_number"
                            name="tax_identification_number"
                            label="Tax Identification Number"
                            :value="old('tax_identification_number', $organization->tax_identification_number)"
                            :error="$errors->first('tax_identification_number')"
                        />
                    </div>
                    
                    <!-- Address -->
                    <x-form.textarea 
                        id="address"
                        name="address"
                        label="Address"
                        rows="3"
                        :error="$errors->first('address')"
                    >{{ old('address', $organization->address) }}</x-form.textarea>
                    
                    <!-- Description -->
                    <x-form.textarea 
                        id="description"
                        name="description"
                        label="Organization Description"
                        rows="4"
                        help-text="Brief description about your organization"
                        :error="$errors->first('description')"
                    >{{ old('description', $organization->description) }}</x-form.textarea>
                    
                    <div class="flex justify-end">
                        <x-button type="submit" variant="primary">
                            Save Organization Information
                        </x-button>
                    </div>
                </div>
            </form>
        </x-card>
        
        <!-- Update Personal Information -->
        <x-card 
            title="Personal Account Information"
            class="mb-6"
            id="account-info"
        >
            <form action="{{ route('organization.profile.update-account') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <!-- Current Profile Image -->
                        <div class="mr-6 flex-shrink-0">
                            @if($user->profile_image)
                                <img src="{{ Storage::url($user->profile_image) }}" alt="{{ $user->name }}" class="h-16 w-16 object-cover rounded-full">
                            @else
                                <div class="h-16 w-16 rounded-full bg-sky-100 flex items-center justify-center">
                                    <span class="text-xl font-bold text-sky-600">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Profile Image Upload -->
                        <div class="flex-grow">
                            <x-form.input 
                                type="file" 
                                id="profile_image"
                                name="profile_image"
                                label="Profile Image"
                                accept="image/*"
                                help-text="Upload a square image (PNG or JPG)"
                                :error="$errors->first('profile_image')"
                            />
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <x-form.input 
                            id="name"
                            name="name"
                            label="Name"
                            :value="old('name', $user->name)"
                            required
                            :error="$errors->first('name')"
                        />
                        
                        <!-- Email -->
                        <x-form.input 
                            type="email"
                            id="email"
                            name="email"
                            label="Email Address"
                            :value="old('email', $user->email)"
                            required
                            :error="$errors->first('email')"
                        />
                        
                        <!-- Phone Number -->
                        <x-form.input 
                            id="phone_number"
                            name="phone_number"
                            label="Phone Number"
                            :value="old('phone_number', $user->phone_number)"
                            :error="$errors->first('phone_number')"
                        />
                    </div>
                    
                    <div class="flex justify-end">
                        <x-button type="submit" variant="primary">
                            Save Account Information
                        </x-button>
                    </div>
                </div>
            </form>
        </x-card>
        
        <!-- Update Password -->
        <x-card 
            title="Update Password"
            id="password"
        >
            <form action="{{ route('organization.profile.update-password') }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="space-y-6">
                    <x-form.input 
                        type="password"
                        id="current_password"
                        name="current_password"
                        label="Current Password"
                        required
                        :error="$errors->updatePassword->first('current_password')"
                    />
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            type="password"
                            id="password"
                            name="password"
                            label="New Password"
                            required
                            :error="$errors->updatePassword->first('password')"
                        />
                        
                        <x-form.input 
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            label="Confirm New Password"
                            required
                        />
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Password Requirements:</h4>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            <li>Minimum 8 characters</li>
                            <li>At least one uppercase letter</li>
                            <li>At least one lowercase letter</li>
                            <li>At least one number</li>
                            <li>At least one special character</li>
                        </ul>
                    </div>
                    
                    <div class="flex justify-end">
                        <x-button type="submit" variant="primary">
                            Update Password
                        </x-button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</x-organization-layout>