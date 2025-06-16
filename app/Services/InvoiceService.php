<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceTemplate;
use App\Models\Order;
use Barryvdh\DomPdf\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    /**
     * Generate a new invoice for an order.
     */
    public function generateInvoice(Order $order): Invoice
    {
        // Generate unique invoice number
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        
        // Get default template or first available template
        $organization = $order->project->organization;
        $template = InvoiceTemplate::where('organization_id', $organization->id)
            ->where('is_default', true)
            ->first();
            
        if (!$template) {
            $template = InvoiceTemplate::where('organization_id', $organization->id)
                ->first();
        }
        
        // Determine invoice status based on order payment status
        $status = 'unpaid';
        
        switch ($order->payment_status) {
            case 'completed':
                $status = 'paid';
                break;
            case 'partial':
                $status = 'partially_paid';
                break;
            case 'cancelled':
                $status = 'cancelled';
                break;
        }
        
        // Create invoice
        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => now(),
            'due_date' => $order->payment_type === 'down_payment' ? now()->addDays(7) : null,
            'template_id' => $template ? $template->id : null,
            'status' => $status,
        ]);
        
        return $invoice;
    }

    /**
     * Update an existing invoice.
     */
    public function updateInvoice(Invoice $invoice): Invoice
    {
        $order = $invoice->order;
        
        // Determine invoice status based on order payment status
        $status = 'unpaid';
        
        switch ($order->payment_status) {
            case 'completed':
                $status = 'paid';
                break;
            case 'partial':
                $status = 'partially_paid';
                break;
            case 'cancelled':
                $status = 'cancelled';
                break;
        }
        
        // Update invoice
        $invoice->update([
            'status' => $status,
        ]);
        
        return $invoice;
    }

    /**
     * Generate PDF for an invoice.
     */
    public function generatePdf(Invoice $invoice)
    {
        $invoice->load([
            'order.customer',
            'order.project.organization',
            'order.orderItems.projectProduct.product',
            'order.paymentMethod',
            'order.courier',
            'template'
        ]);
        
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
        
        return $pdf;
    }

    /**
     * Generate image for an invoice.
     */
    public function generateImage(Invoice $invoice): string
    {
        $invoice->load([
            'order.customer',
            'order.project.organization',
            'order.orderItems.projectProduct.product',
            'order.paymentMethod',
            'order.courier',
            'template'
        ]);
        
        // Generate PDF first
        $pdf = $this->generatePdf($invoice);
        
        // Save PDF temporarily
        $pdfPath = storage_path('app/temp/Invoice-' . $invoice->invoice_number . '.pdf');
        
        // Create directory if it doesn't exist
        if (!file_exists(dirname($pdfPath))) {
            mkdir(dirname($pdfPath), 0755, true);
        }
        
        $pdf->save($pdfPath);
        
        // Convert PDF to image using Imagick
        $imagePath = storage_path('app/temp/Invoice-' . $invoice->invoice_number . '.png');
        
        // Using Imagick if available
        if (extension_loaded('imagick')) {
            $imagick = new \Imagick();
            $imagick->readImage($pdfPath . '[0]');
            $imagick->setResolution(300, 300);
            $imagick->setImageFormat('png');
            $imagick->writeImage($imagePath);
            $imagick->clear();
            $imagick->destroy();
        } else {
            // Fallback if Imagick is not available - just return the PDF path
            return $pdfPath;
        }
        
        // Delete the temporary PDF file
        @unlink($pdfPath);
        
        return $imagePath;
    }
}