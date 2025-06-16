<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request): View
    {
        $organization = Auth::user()->organization;
        
        $query = Customer::where('organization_id', $organization->id);
        
        // Search by name, email or phone
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        
        $customers = $query->latest()->paginate(15);
        
        return view('organization.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        return view('organization.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $organization = Auth::user()->organization;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        
        $customer = new Customer($validated);
        $customer->organization_id = $organization->id;
        $customer->save();
        
        return redirect()->route('organization.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): View
    {
        $this->checkCustomerOwnership($customer);
        
        $customer->load('orders.project', 'orders.invoice');
        
        return view('organization.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): View
    {
        $this->checkCustomerOwnership($customer);
        
        return view('organization.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $this->checkCustomerOwnership($customer);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        
        $customer->update($validated);
        
        return redirect()->route('organization.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $this->checkCustomerOwnership($customer);
        
        // Check if customer has orders
        if ($customer->orders()->exists()) {
            return redirect()->route('organization.customers.index')
                ->with('error', 'Cannot delete customer as they have associated orders.');
        }
        
        $customer->delete();
        
        return redirect()->route('organization.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Import customers from CSV file.
     */
    public function importForm(): View
    {
        return view('organization.customers.import');
    }

    /**
     * Process the import of customers from CSV.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        
        $organization = Auth::user()->organization;
        $file = $request->file('csv_file');
        
        // Open the file
        $handle = fopen($file->getPathname(), 'r');
        
        // Read the CSV header
        $header = fgetcsv($handle);
        
        // Check required headers
        $requiredHeaders = ['name', 'email', 'phone_number', 'address'];
        $missingHeaders = array_diff($requiredHeaders, array_map('strtolower', $header));
        
        if (!empty($missingHeaders)) {
            return redirect()->route('organization.customers.import')
                ->with('error', 'CSV file is missing required headers: ' . implode(', ', $missingHeaders));
        }
        
        // Process each row
        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            
            Customer::create([
                'organization_id' => $organization->id,
                'name' => $data['name'],
                'email' => !empty($data['email']) ? $data['email'] : null,
                'phone_number' => !empty($data['phone_number']) ? $data['phone_number'] : null,
                'address' => !empty($data['address']) ? $data['address'] : null,
            ]);
            
            $imported++;
        }
        
        fclose($handle);
        
        return redirect()->route('organization.customers.index')
            ->with('success', $imported . ' customers imported successfully.');
    }

    /**
     * Check if the customer belongs to the current organization.
     */
    protected function checkCustomerOwnership(Customer $customer): void
    {
        $organization = Auth::user()->organization;
        
        if ($customer->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to customer.');
        }
    }
}