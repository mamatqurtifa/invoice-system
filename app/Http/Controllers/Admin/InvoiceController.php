<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request): View
    {
        $query = Invoice::with(['order.customer', 'order.project.organization']);
        
        // Filter by organization
        if ($request->has('organization_id') && !empty($request->organization_id)) {
            $query->whereHas('order.project.organization', function ($q) use ($request) {
                $q->where('id', $request->organization_id);
            });
        }
        
        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->where('invoice_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->where('invoice_date', '<=', $request->end_date);
        }
        
        // Search by invoice number or customer name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('order.customer', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $invoices = $query->latest()->paginate(15);
        $organizations = Organization::orderBy('name')->get();
        
        return view('admin.invoices.index', compact('invoices', 'organizations'));
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load([
            'order.customer',
            'order.project.organization',
            'order.orderItems.projectProduct.product',
            'order.paymentMethod',
            'order.courier',
            'template'
        ]);
        
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Download the invoice as PDF.
     */
    public function downloadPdf(Invoice $invoice)
    {
        // This will be implemented with a service
        // For now, we'll just redirect back with a message
        
        return redirect()->back()->with('info', 'PDF download functionality will be implemented soon.');
    }

    /**
     * Download the invoice as Image.
     */
    public function downloadImage(Invoice $invoice)
    {
        // This will be implemented with a service
        // For now, we'll just redirect back with a message
        
        return redirect()->back()->with('info', 'Image download functionality will be implemented soon.');
    }
}