<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the organization profile.
     */
    public function index(): View
    {
        $user = Auth::user();
        $organization = $user->organization;
        
        return view('organization.profile.index', compact('user', 'organization'));
    }

    /**
     * Show the form for editing the organization profile.
     */
    public function edit(): View
    {
        $user = Auth::user();
        $organization = $user->organization;
        
        return view('organization.profile.edit', compact('user', 'organization'));
    }

    /**
     * Update the organization profile.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $organization = $user->organization;
        
        // Validate user data
        $validatedUser = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'current_password' => 'nullable|string|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Validate organization data
        $validatedOrg = $request->validate([
            'organization_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'customer_service_number' => 'nullable|string|max:20',
            'organization_email' => 'nullable|string|email|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'tax_identification_number' => 'nullable|string|max:50',
        ]);
        
        // Update user
        $user->name = $validatedUser['name'];
        $user->email = $validatedUser['email'];
        $user->phone_number = $validatedUser['phone_number'];
        
        // Check if password change is requested
        if (!empty($validatedUser['new_password'])) {
            // Verify current password
            if (!Hash::check($validatedUser['current_password'], $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
            
            $user->password = Hash::make($validatedUser['new_password']);
        }
        
        // Update profile image if provided
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $user->profile_image = $path;
        }
        
        $user->save();
        
        // Update organization
        $organization->name = $validatedOrg['organization_name'];
        $organization->address = $validatedOrg['address'];
        $organization->customer_service_number = $validatedOrg['customer_service_number'];
        $organization->email = $validatedOrg['organization_email'];
        $organization->description = $validatedOrg['description'];
        $organization->website = $validatedOrg['website'];
        $organization->tax_identification_number = $validatedOrg['tax_identification_number'];
        
        // Update logo if provided
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('organization-logos', 'public');
            $organization->logo = $path;
        }
        
        $organization->save();
        
        return redirect()->route('organization.profile.index')
            ->with('success', 'Profile updated successfully.');
    }
}