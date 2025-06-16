<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the payment methods.
     */
    public function index(Request $request): View
    {
        $organization = Auth::user()->organization;
        
        $query = PaymentMethod::where('organization_id', $organization->id);
        
        // Filter by payment type
        if ($request->has('payment_type') && !empty($request->payment_type)) {
            $query->where('payment_type', $request->payment_type);
        }
        
        // Filter by status
        if ($request->has('is_active') && $request->is_active !== null) {
            $query->where('is_active', $request->is_active);
        }
        
        // Search by name or account
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%");
            });
        }
        
        $paymentMethods = $query->latest()->paginate(10);
        
        return view('organization.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create(): View
    {
        return view('organization.payment-methods.create');
    }

    /**
     * Store a newly created payment method in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $organization = Auth::user()->organization;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'payment_type' => 'required|in:bank_transfer,e_wallet,cash,other',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048',
        ]);
        
        $paymentMethod = new PaymentMethod($validated);
        $paymentMethod->organization_id = $organization->id;
        
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('payment-method-logos', 'public');
            $paymentMethod->logo = $path;
        }
        
        $paymentMethod->save();
        
        return redirect()->route('organization.payment-methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    /**
     * Display the specified payment method.
     */
    public function show(PaymentMethod $paymentMethod): View
    {
        $this->checkPaymentMethodOwnership($paymentMethod);
        
        return view('organization.payment-methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod): View
    {
        $this->checkPaymentMethodOwnership($paymentMethod);
        
        return view('organization.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $this->checkPaymentMethodOwnership($paymentMethod);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'payment_type' => 'required|in:bank_transfer,e_wallet,cash,other',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048',
        ]);
        
        $paymentMethod->update($validated);
        
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('payment-method-logos', 'public');
            $paymentMethod->update(['logo' => $path]);
        }
        
        return redirect()->route('organization.payment-methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    /**
     * Remove the specified payment method from storage.
     */
    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        $this->checkPaymentMethodOwnership($paymentMethod);
        
        // Check if payment method is used in orders
        if ($paymentMethod->orders()->exists()) {
            return redirect()->route('organization.payment-methods.index')
                ->with('error', 'Cannot delete payment method as it is used in orders.');
        }
        
        $paymentMethod->delete();
        
        return redirect()->route('organization.payment-methods.index')
            ->with('success', 'Payment method deleted successfully.');
    }

    /**
     * Toggle the status of the payment method.
     */
    public function toggleStatus(PaymentMethod $paymentMethod): RedirectResponse
    {
        $this->checkPaymentMethodOwnership($paymentMethod);
        
        $paymentMethod->is_active = !$paymentMethod->is_active;
        $paymentMethod->save();
        
        $status = $paymentMethod->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Payment method {$status} successfully.");
    }

    /**
     * Check if the payment method belongs to the current organization.
     */
    protected function checkPaymentMethodOwnership(PaymentMethod $paymentMethod): void
    {
        $organization = Auth::user()->organization;
        
        if ($paymentMethod->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to payment method.');
        }
    }
}