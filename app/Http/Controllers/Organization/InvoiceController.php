<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceTemplate;
use App\Models\Project;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use ZipArchive;

class InvoiceController extends Controller
{
    protected $invoiceService;

    /**
     * Create a new controller instance.
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request): View
    {
        $organization = Auth::user()->organization;
        
        // Get projects for this organization
        $projects = Project::where('organization_id', $organization->id)
            ->orderBy('name')
            ->get();
            
        $query = Invoice::whereHas('order.project', function ($q) use ($organization) {
            $q->where('organization_id', $organization->id);
        })->with(['order.customer', 'order.project']);
        
        // Filter by project
        if ($request->has('project_id') && !empty($request->project_id)) {
            $query->whereHas('order', function ($q) use ($request) {
                $q->where('project_id', $request->project_id);
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
        
        return view('organization.invoices.index', compact('invoices', 'projects'));
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice): View
    {
        $this->checkInvoiceOwnership($invoice);
        
        $invoice->load([
            'order.customer',
            'order.project.organization',
            'order.orderItems.projectProduct.product',
            'order.paymentMethod',
            'order.courier',
            'template'
        ]);
        
        return view('organization.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Invoice $invoice): View
    {
        $this->checkInvoiceOwnership($invoice);
        
        $invoice->load([
            'order.customer',
            'order.project.organization',
            'template'
        ]);
        
        $organization = Auth::user()->organization;
        $templates = InvoiceTemplate::where('organization_id', $organization->id)->get();
        
        return view('organization.invoices.edit', compact('invoice', 'templates'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $this->checkInvoiceOwnership($invoice);
        
        $validated = $request->validate([
            'template_id' => 'required|exists:invoice_templates,id',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        $organization = Auth::user()->organization;
        
        // Validate that the template belongs to this organization
        $template = InvoiceTemplate::where('id', $validated['template_id'])
            ->where('organization_id', $organization->id)
            ->firstOrFail();
            
        $invoice->update([
            'template_id' => $validated['template_id'],
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'],
        ]);
        
        return redirect()->route('organization.invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Download the invoice as PDF.
     */
    public function downloadPdf(Invoice $invoice)
    {
        $this->checkInvoiceOwnership($invoice);
        
        $pdf = $this->invoiceService->generatePdf($invoice);
        
        return $pdf->download('Invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Download the invoice as Image.
     */
    public function downloadImage(Invoice $invoice)
    {
        $this->checkInvoiceOwnership($invoice);
        
        $imagePath = $this->invoiceService->generateImage($invoice);
        
        return response()->download($imagePath, 'Invoice-' . $invoice->invoice_number . '.png')->deleteFileAfterSend(true);
    }

    /**
     * Download multiple invoices.
     */
    public function downloadMultiple(Request $request)
    {
        $validated = $request->validate([
            'invoice_ids' => 'required|array',
            'invoice_ids.*' => 'exists:invoices,id',
            'format' => 'required|in:pdf,image',
        ]);
        
        $organization = Auth::user()->organization;
        $invoices = Invoice::whereIn('id', $validated['invoice_ids'])
            ->whereHas('order.project', function ($query) use ($organization) {
                $query->where('organization_id', $organization->id);
            })
            ->with([
                'order.customer',
                'order.project.organization',
                'order.orderItems.projectProduct.product',
                'order.paymentMethod',
                'template'
            ])
            ->get();
            
        // If only one invoice, download directly
        if ($invoices->count() === 1) {
            $invoice = $invoices->first();
            
            if ($validated['format'] === 'pdf') {
                return $this->downloadPdf($invoice);
            } else {
                return $this->downloadImage($invoice);
            }
        }
        
        // For multiple invoices, create zip file
        $zipFileName = 'Invoices-' . date('YmdHis') . '.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);
        
        // Create directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
            return redirect()->back()->with('error', 'Could not create zip file.');
        }
        
        foreach ($invoices as $invoice) {
            if ($validated['format'] === 'pdf') {
                $pdf = $this->invoiceService->generatePdf($invoice);
                $pdfPath = storage_path('app/temp/Invoice-' . $invoice->invoice_number . '.pdf');
                $pdf->save($pdfPath);
                $zip->addFile($pdfPath, 'Invoice-' . $invoice->invoice_number . '.pdf');
            } else {
                $imagePath = $this->invoiceService->generateImage($invoice);
                $zip->addFile($imagePath, 'Invoice-' . $invoice->invoice_number . '.png');
            }
        }
        
        $zip->close();
        
        // Download zip file
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

        /**
     * Download all invoices for a project.
     */
    public function downloadProjectInvoices(Request $request, Project $project)
    {
        $this->checkProjectOwnership($project);
        
        $validated = $request->validate([
            'format' => 'required|in:pdf,image',
        ]);
        
        $invoices = Invoice::whereHas('order', function ($query) use ($project) {
            $query->where('project_id', $project->id);
        })
        ->with([
            'order.customer',
            'order.project.organization',
            'order.orderItems.projectProduct.product',
            'order.paymentMethod',
            'template'
        ])
        ->get();
        
        // If no invoices, return with error
        if ($invoices->count() === 0) {
            return redirect()->back()->with('error', 'No invoices found for this project.');
        }
        
        // If only one invoice, download directly
        if ($invoices->count() === 1) {
            $invoice = $invoices->first();
            
            if ($validated['format'] === 'pdf') {
                return $this->downloadPdf($invoice);
            } else {
                return $this->downloadImage($invoice);
            }
        }
        
        // For multiple invoices, create zip file
        $zipFileName = 'Project-' . $project->id . '-Invoices-' . date('YmdHis') . '.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);
        
        // Create directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
            return redirect()->back()->with('error', 'Could not create zip file.');
        }
        
        foreach ($invoices as $invoice) {
            if ($validated['format'] === 'pdf') {
                $pdf = $this->invoiceService->generatePdf($invoice);
                $pdfPath = storage_path('app/temp/Invoice-' . $invoice->invoice_number . '.pdf');
                $pdf->save($pdfPath);
                $zip->addFile($pdfPath, 'Invoice-' . $invoice->invoice_number . '.pdf');
            } else {
                $imagePath = $this->invoiceService->generateImage($invoice);
                $zip->addFile($imagePath, 'Invoice-' . $invoice->invoice_number . '.png');
            }
        }
        
        $zip->close();
        
        // Clean up temporary files
        if ($validated['format'] === 'pdf') {
            foreach ($invoices as $invoice) {
                @unlink(storage_path('app/temp/Invoice-' . $invoice->invoice_number . '.pdf'));
            }
        }
        
        // Download zip file
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    /**
     * Check if the invoice belongs to the current organization.
     */
    protected function checkInvoiceOwnership(Invoice $invoice): void
    {
        $organization = Auth::user()->organization;
        
        // Check if the invoice's order's project belongs to this organization
        $belongsToOrg = $invoice->order->project->organization_id === $organization->id;
        
        if (!$belongsToOrg) {
            abort(403, 'Unauthorized access to invoice.');
        }
    }

    /**
     * Check if the project belongs to the current organization.
     */
    protected function checkProjectOwnership(Project $project): void
    {
        $organization = Auth::user()->organization;
        
        if ($project->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to project.');
        }
    }
}