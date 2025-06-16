<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Project;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     */
    public function index(Request $request): View
    {
        $organization = Auth::user()->organization;
        
        $query = Report::where('organization_id', $organization->id)->with('project');
        
        // Filter by project
        if ($request->has('project_id') && !empty($request->project_id)) {
            $query->where('project_id', $request->project_id);
        }
        
        // Filter by report type
        if ($request->has('report_type') && !empty($request->report_type)) {
            $query->where('report_type', $request->report_type);
        }
        
        // Filter by date range
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->where('start_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->where('end_date', '<=', $request->end_date);
        }
        
        $reports = $query->latest()->paginate(10);
        
        $projects = Project::where('organization_id', $organization->id)
            ->orderBy('name')
            ->get();
            
        return view('organization.reports.index', compact('reports', 'projects'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create(): View
    {
        $organization = Auth::user()->organization;
        
        $projects = Project::where('organization_id', $organization->id)
            ->orderBy('name')
            ->get();
            
        return view('organization.reports.create', compact('projects'));
    }

    /**
     * Generate and store a new report.
     */
    public function store(Request $request): RedirectResponse
    {
        $organization = Auth::user()->organization;
        
        $validated = $request->validate([
            'report_type' => 'required|in:daily,weekly,monthly,project_based',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'project_id' => 'nullable|required_if:report_type,project_based|exists:projects,id',
        ]);
        
        // Validate that the project belongs to this organization if provided
        if (!empty($validated['project_id'])) {
            $project = Project::where('id', $validated['project_id'])
                ->where('organization_id', $organization->id)
                ->firstOrFail();
        }
        
        // Build query to get orders
        $query = Order::whereHas('project', function ($q) use ($organization) {
            $q->where('organization_id', $organization->id);
        })
        ->whereBetween('order_date', [$validated['start_date'], $validated['end_date']]);
        
        // Filter by project if report is project-based
        if ($validated['report_type'] === 'project_based') {
            $query->where('project_id', $validated['project_id']);
        }
        
        // Get data for report
        $orders = $query->get();
        
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_amount');
        $totalTax = $orders->sum('tax_amount');
        $totalDiscount = $orders->sum('discount');
        
        // Create report
        $report = new Report([
            'organization_id' => $organization->id,
            'project_id' => $validated['project_id'] ?? null,
            'report_type' => $validated['report_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'total_tax' => $totalTax,
            'total_discount' => $totalDiscount,
            'status' => 'generated',
        ]);
        
        $report->save();
        
        return redirect()->route('organization.reports.show', $report)
            ->with('success', 'Report generated successfully.');
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report): View
    {
        $this->checkReportOwnership($report);
        
        $report->load('project', 'organization');
        
        // Get orders for this report
        $query = Order::whereHas('project', function ($q) use ($report) {
            $q->where('organization_id', $report->organization_id);
        })
        ->whereBetween('order_date', [$report->start_date, $report->end_date]);
        
        // Filter by project if report is project-based
        if ($report->project_id) {
            $query->where('project_id', $report->project_id);
        }
        
        $orders = $query->with(['customer', 'project', 'invoice'])->get();
        
        // Prepare chart data
        $chartData = $this->prepareChartData($report, $orders);
        
        return view('organization.reports.show', compact('report', 'orders', 'chartData'));
    }

    /**
     * Download the report as PDF.
     */
    public function downloadPdf(Report $report)
    {
        $this->checkReportOwnership($report);
        
        // This will be implemented with a service
        // For now, we'll just redirect back with a message
        
        return redirect()->back()->with('info', 'PDF download functionality will be implemented soon.');
    }

    /**
     * Generate sales summary report.
     */
    public function salesSummary(Request $request): View
    {
        $organization = Auth::user()->organization;
        
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'project_id' => 'nullable|exists:projects,id',
        ]);
        
        // Default to current month if no dates provided
        $startDate = $validated['start_date'] ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        
        // Build query to get orders
        $query = Order::whereHas('project', function ($q) use ($organization) {
            $q->where('organization_id', $organization->id);
        })
        ->whereBetween('order_date', [$startDate, $endDate]);
        
        // Filter by project if provided
        if (!empty($validated['project_id'])) {
            $query->where('project_id', $validated['project_id']);
        }
        
        $orders = $query->with(['customer', 'project', 'invoice'])->get();
        
        // Calculate summary data
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_amount');
        $totalTax = $orders->sum('tax_amount');
        $totalDiscount = $orders->sum('discount');
        
        // Get projects for the filter
        $projects = Project::where('organization_id', $organization->id)
            ->orderBy('name')
            ->get();
            
        // Prepare daily revenue data for chart
        $dailyData = [];
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $dailyRevenue = $orders->where('order_date', $dateStr)->sum('total_amount');
            $dailyData[$dateStr] = $dailyRevenue;
        }
        
        // Group orders by payment status
        $paymentStatusData = [
            'pending' => $orders->where('payment_status', 'pending')->count(),
            'partial' => $orders->where('payment_status', 'partial')->count(),
            'completed' => $orders->where('payment_status', 'completed')->count(),
            'cancelled' => $orders->where('payment_status', 'cancelled')->count(),
        ];
        
        // Group orders by project if no specific project is selected
        $projectData = [];
        if (empty($validated['project_id'])) {
            foreach ($projects as $project) {
                $projectOrders = $orders->where('project_id', $project->id);
                if ($projectOrders->count() > 0) {
                    $projectData[$project->name] = $projectOrders->sum('total_amount');
                }
            }
        }
        
        return view('organization.reports.sales-summary', compact(
            'startDate',
            'endDate',
            'projects',
            'validated',
            'totalOrders',
            'totalRevenue',
            'totalTax',
            'totalDiscount',
            'dailyData',
            'paymentStatusData',
            'projectData'
        ));
    }

    /**
     * Prepare chart data for the report.
     */
    protected function prepareChartData(Report $report, $orders)
    {
        $chartData = [];
        
        // Daily revenue data
        $dailyData = [];
        $start = Carbon::parse($report->start_date);
        $end = Carbon::parse($report->end_date);
        
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $dailyRevenue = $orders->where('order_date', $dateStr)->sum('total_amount');
            $dailyData[$dateStr] = $dailyRevenue;
        }
        
        $chartData['dailyData'] = $dailyData;
        
        // Payment status data
        $chartData['paymentStatusData'] = [
            'pending' => $orders->where('payment_status', 'pending')->count(),
            'partial' => $orders->where('payment_status', 'partial')->count(),
            'completed' => $orders->where('payment_status', 'completed')->count(),
            'cancelled' => $orders->where('payment_status', 'cancelled')->count(),
        ];
        
        return $chartData;
    }

    /**
     * Check if the report belongs to the current organization.
     */
    protected function checkReportOwnership(Report $report): void
    {
        $organization = Auth::user()->organization;
        
        if ($report->organization_id !== $organization->id) {
            abort(403, 'Unauthorized access to report.');
        }
    }
}