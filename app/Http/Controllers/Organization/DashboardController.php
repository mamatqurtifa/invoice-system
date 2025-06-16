<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the organization dashboard.
     */
    public function index(): View
    {
        $organization = Auth::user()->organization;
        
        // Count data for dashboard
        $totalProjects = Project::where('organization_id', $organization->id)->count();
        $totalCustomers = Customer::where('organization_id', $organization->id)->count();
        $totalOrders = Order::whereHas('project', function ($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })->count();
        $totalInvoices = Invoice::whereHas('order.project', function ($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })->count();

        // Recent projects
        $recentProjects = Project::where('organization_id', $organization->id)
            ->latest()
            ->take(5)
            ->get();

        // Recent orders
        $recentOrders = Order::whereHas('project', function ($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })
            ->with(['customer', 'project'])
            ->latest()
            ->take(5)
            ->get();

        // Recent invoices
        $recentInvoices = Invoice::whereHas('order.project', function ($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })
            ->with(['order.customer', 'order.project'])
            ->latest()
            ->take(5)
            ->get();

        // Calculate revenue (total from all orders)
        $totalRevenue = Order::whereHas('project', function ($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })
            ->where('payment_status', 'completed')
            ->sum('total_amount');
            
        // Pending payments
        $pendingPayments = Order::whereHas('project', function ($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })
            ->where('payment_status', 'pending')
            ->sum('total_amount');

        return view('organization.dashboard', compact(
            'organization',
            'totalProjects',
            'totalCustomers',
            'totalOrders',
            'totalInvoices',
            'recentProjects',
            'recentOrders',
            'recentInvoices',
            'totalRevenue',
            'pendingPayments'
        ));
    }
}