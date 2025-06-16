<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Organization;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Count data for dashboard
        $totalOrganizations = Organization::count();
        $totalUsers = User::where('role', 'organization')->count();
        $totalOrders = Order::count();
        $totalInvoices = Invoice::count();

        // Recent organizations
        $recentOrganizations = Organization::latest()->take(5)->get();

        // Recent invoices
        $recentInvoices = Invoice::with(['order.customer', 'order.project.organization'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrganizations',
            'totalUsers',
            'totalOrders',
            'totalInvoices',
            'recentOrganizations',
            'recentInvoices'
        ));
    }
}