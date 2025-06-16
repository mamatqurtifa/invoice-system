<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the organizations.
     */
    public function index(Request $request): View
    {
        $query = Organization::with('user');
        
        // Search by name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('tax_identification_number', 'like', "%{$search}%");
            });
        }
        
        $organizations = $query->latest()->paginate(10);
        
        return view('admin.organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new organization.
     */
    public function create(): View
    {
        return view('admin.organizations.create');
    }

    /**
     * Store a newly created organization in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate user data
        $validatedUser = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
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

        // Create user
        $user = User::create([
            'name' => $validatedUser['name'],
            'email' => $validatedUser['email'],
            'password' => Hash::make($validatedUser['password']),
            'phone_number' => $validatedUser['phone_number'],
            'role' => 'organization',
            'status' => 'active',
        ]);

        // Create organization
        $organization = Organization::create([
            'user_id' => $user->id,
            'name' => $validatedOrg['organization_name'],
            'address' => $validatedOrg['address'],
            'customer_service_number' => $validatedOrg['customer_service_number'],
            'email' => $validatedOrg['organization_email'],
            'description' => $validatedOrg['description'],
            'website' => $validatedOrg['website'],
            'tax_identification_number' => $validatedOrg['tax_identification_number'],
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('organization-logos', 'public');
            $organization->update(['logo' => $path]);
        }

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    /**
     * Display the specified organization.
     */
    public function show(Organization $organization): View
    {
        $organization->load('user', 'projects', 'customers');
        
        return view('admin.organizations.show', compact('organization'));
    }

    /**
     * Show the form for editing the specified organization.
     */
    public function edit(Organization $organization): View
    {
        $organization->load('user');
        
        return view('admin.organizations.edit', compact('organization'));
    }

    /**
     * Update the specified organization in storage.
     */
    public function update(Request $request, Organization $organization): RedirectResponse
    {
        // Validate organization data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'customer_service_number' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'tax_identification_number' => 'nullable|string|max:50',
        ]);

        $organization->update($validated);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('organization-logos', 'public');
            $organization->update(['logo' => $path]);
        }

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified organization from storage.
     */
    public function destroy(Organization $organization): RedirectResponse
    {
        // Delete the associated user as well
        $organization->user->delete();
        // The organization will be deleted automatically due to cascade
        
        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }
}