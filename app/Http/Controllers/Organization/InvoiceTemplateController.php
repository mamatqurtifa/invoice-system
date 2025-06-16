<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\InvoiceTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvoiceTemplateController extends Controller
{
    /**
     * Display a listing of the invoice templates.
     */
    public function index(): View
    {
        $organization = Auth::user()->organization;
        
        $templates = InvoiceTemplate::where('organization_id', $organization->id)
            ->latest()
            ->paginate(10);
            
        return view('organization.invoice-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new invoice template.
     */
    public function create(): View
    {
        return view('organization.invoice-templates.create');
    }

    /**
     * Store a newly created invoice template in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $organization = Auth::user()->organization;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'font' => 'required|string|max:50',
            'logo_position' => 'required|in:left,center,right',
            'show_organization_logo' => 'boolean',
            'show_project_logo' => 'boolean',
            'footer_text' => 'nullable|string',
            'additional_information' => 'nullable|string',
            'has_watermark' => 'boolean',
            'watermark_text' => 'nullable|string|max:255',
            'has_signature' => 'boolean',
            'signature_position' => 'required|in:left,center,right',
            'signature_image' => 'nullable|image|max:2048',
        ]);
        
        $template = new InvoiceTemplate($validated);
        $template->organization_id = $organization->id;
        
        if ($request->hasFile('signature_image')) {
            $path = $request->file('signature_image')->store('signature-images', 'public');
            $template->signature_image = $path;
        }
        
        $template->save();
        
        return redirect()->route('organization.invoice-templates.index')
            ->with('success', 'Invoice template created successfully.');
    }

    /**
     * Display the specified invoice template.
     */
    public function show(InvoiceTemplate $invoiceTemplate): View
    {
        $this->checkTemplateOwnership($invoiceTemplate);
        
        return view('organization.invoice-templates.show', compact('invoiceTemplate'));
    }

    /**
     * Show the form for editing the specified invoice template.
     */
    public function edit(InvoiceTemplate $invoiceTemplate): View
    {
        $this->checkTemplateOwnership($invoiceTemplate);
        
        return view('organization.invoice-templates.edit', compact('invoiceTemplate'));
    }

    /**
     * Update the specified invoice template in storage.
     */
    public function update(Request $request, InvoiceTemplate $invoiceTemplate): RedirectResponse
    {
        $this->checkTemplateOwnership($invoiceTemplate);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'font' => 'required|string|max:50',
            'logo_position' => 'required|in:left,center,right',
            'show_organization_logo' => 'boolean',
            'show_project_logo' => 'boolean',
            'footer_text' => 'nullable|string',
            'additional_information' => 'nullable|string',
            'has_watermark' => 'boolean',
            'watermark_text' => 'nullable|string|max:255',
            'has_signature' => 'boolean',
            'signature_position' => 'required|in:left,center,right',
            'signature_image' => 'nullable|image|max:2048',
        ]);
        
        $invoiceTemplate->update($validated);
        
        if ($request->hasFile('signature_image')) {
            $path = $request->file('signature_image')->store('signature-images', 'public');
            $invoiceTemplate->update(['signature_image' => $path]);
        }
        
        return redirect()->route('organization.invoice-templates.index')
            ->with('success', 'Invoice template updated successfully.');
    }

    /**
     * Remove the specified invoice template from storage.
     */
    public function destroy(InvoiceTemplate $invoiceTemplate): RedirectResponse
    {
        $this->checkTemplateOwnership($invoiceTemplate);
        
        // Check if template is used in invoices
        if ($invoiceTemplate->invoices()->exists()) {
            return redirect()->route('organization.invoice-templates.index')
                ->with('error', 'Cannot delete template as it is used in invoices.');
        }
        
        $invoiceTemplate->delete();
        
        return redirect()->route('organization.invoice-templates.index')
            ->with('success', 'Invoice template deleted successfully.');
    }

    /**
     * Show preview of the invoice template.
     */
    public function preview(InvoiceTemplate $invoiceTemplate): View
    {
        $this->checkTemplateOwnership($invoiceTemplate);
        
        $organization = Auth::user()->organization;
        
        // Get a sample order with items for preview
        $sampleOrder = null;
        
        // Try to find an order from this organization
        $sampleOrder = \App\Models\Order::whereHas('project', function ($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })->with(['customer', 'orderItems.projectProduct.product', 'project'])
            ->latest()
            ->first();
        
        // If no real order exists, create a dummy one for preview
        if (!$sampleOrder) {
            $sampleOrder = $this->createDummyOrder($organization);
        }
        
        return view('organization.invoice-templates.preview', compact('invoiceTemplate', 'organization', 'sampleOrder'));
    }

    /**
     * Create a dummy order for template preview.
     */
    protected function createDummyOrder($organization)
    {
        // This is just a mock object for preview, not saved to database
        $order = new \stdClass();
        $order->order_number = 'SAMPLE-001';
        $order->order_date = now();
        $order->payment_type = 'full_payment';
        $order->subtotal = 1500000;
        $order->discount = 0;
        $order->tax_amount = 150000;
        $order->shipping_cost = 50000;
        $order->total_amount = 1700000;
        
        // Customer
        $customer = new \stdClass();
        $customer->name = 'Sample Customer';
        $customer->email = 'sample@example.com';
        $customer->phone_number = '08123456789';
        $customer->address = 'Jl. Sample No. 123, Jakarta';
        $order->customer = $customer;
        
        // Project
        $project = new \stdClass();
        $project->name = 'Sample Project';
        $project->organization = $organization;
        $order->project = $project;
        
        // Order Items
        $order->orderItems = [];
        
        $item1 = new \stdClass();
        $item1->quantity = 2;
        $item1->unit_price = 500000;
        $item1->total = 1000000;
        
        $product1 = new \stdClass();
        $product1->name = 'Sample Product 1';
        $product1->description = 'This is a sample product description';
        
        $projectProduct1 = new \stdClass();
        $projectProduct1->product = $product1;
        $item1->projectProduct = $projectProduct1;
        
        $item2 = new \stdClass();
        $item2->quantity = 1;
        $item2->unit_price = 500000;
        $item2->total = 500000;
        
        $product2 = new \stdClass();
        $product2->name = 'Sample Product 2';
        $product2->description = 'This is another sample product description';
        
        $projectProduct2 = new \stdClass();
        $projectProduct2->product = $product2;
        $item2->projectProduct = $projectProduct2;
        
        $order->orderItems[] = $item1;
        $order->orderItems[] = $item2;
        
        return $order;
    }

    /**
     * Set template as default.
     */
    public function setDefault(InvoiceTemplate $invoiceTemplate): RedirectResponse
    {
        $this->checkTemplateOwnership($invoiceTemplate);
        
        $organization = Auth::user()->organization;
        
        // Update all templates of this organization to be not default
        InvoiceTemplate::where('organization_id', $organization->id)
            ->update(['is_default' => false]);
            
        // Set this one as default
        $invoiceTemplate->is_default = true;
        $invoiceTemplate->save();
        
        return redirect()->route('organization.invoice-templates.index')
            ->with('success', 'Default invoice template set successfully.');
    }

    /**
     * Check if the invoice template belongs to the current organization.
     */
    protected function checkTemplateOwnership(InvoiceTemplate $invoiceTemplate): void
    {
        $organization = Auth::user()->organization;
        
        if ($invoiceTemplate->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to invoice template.');
        }
    }
}