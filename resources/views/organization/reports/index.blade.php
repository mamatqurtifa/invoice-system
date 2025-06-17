<x-organization-layout>
    @section('title', 'Reports')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Reports</h2>
            <p class="mt-1 text-sm text-gray-600">Generate and view reports for your business</p>
        </div>
    </div>
    
    <!-- Report Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Sales Reports -->
        <x-card>
            <div class="p-2">
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-sky-100 rounded-md p-3">
                        <i class="fas fa-chart-line text-sky-600 text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-medium text-gray-900">Sales Reports</h3>
                        <p class="text-sm text-gray-500 mt-1">Analyze your sales data by different periods and products</p>
                        
                        <ul class="mt-4 space-y-2">
                            <li>
                                <a href="{{ route('organization.reports.sales-summary') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Sales Summary
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('organization.reports.sales-by-product') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Sales by Product
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('organization.reports.sales-by-customer') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Sales by Customer
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('organization.reports.sales-by-project') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Sales by Project
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-card>
        
        <!-- Financial Reports -->
        <x-card>
            <div class="p-2">
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-medium text-gray-900">Financial Reports</h3>
                        <p class="text-sm text-gray-500 mt-1">Track revenue, taxes, and payment collection status</p>
                        
                        <ul class="mt-4 space-y-2">
                            <li>
                                <a href="{{ route('organization.reports.revenue') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Revenue Report
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('organization.reports.tax') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Tax Report
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('organization.reports.payment-collection') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Payment Collection
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('organization.reports.outstanding-invoices') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Outstanding Invoices
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-card>
        
        <!-- Customer Reports -->
        <x-card>
            <div class="p-2">
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-medium text-gray-900">Customer Reports</h3>
                        <p class="text-sm text-gray-500 mt-1">Analyze customer acquisition and retention</p>
                        
                        <ul class="mt-4 space-y-2">
                            <li>
                                <a href="{{ route('organization.reports.customer-acquisition') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Customer Acquisition
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('organization.reports.customer-retention') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Customer Retention
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('organization.reports.top-customers') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Top Customers
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-card>
        
        <!-- Inventory Reports -->
        <x-card>
            <div class="p-2">
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                        <i class="fas fa-boxes text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-medium text-gray-900">Inventory Reports</h3>
                        <p class="text-sm text-gray-500 mt-1">Track product inventory and performance</p>
                        
                        <ul class="mt-4 space-y-2">
                            <li>
                                <a href="{{ route('organization.reports.stock-levels') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Stock Levels
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('organization.reports.product-performance') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Product Performance
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('organization.reports.low-stock') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Low Stock Alert
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-card>
        
        <!-- Project Reports -->
        <x-card>
            <div class="p-2">
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                        <i class="fas fa-folder-open text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-medium text-gray-900">Project Reports</h3>
                        <p class="text-sm text-gray-500 mt-1">Analyze project performance and profitability</p>
                        
                        <ul class="mt-4 space-y-2">
                            <li>
                                <a href="{{ route('organization.reports.project-performance') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Project Performance
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('organization.reports.project-profitability') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Project Profitability
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-card>
        
        <!-- Custom Reports -->
        <x-card>
            <div class="p-2">
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                        <i class="fas fa-tools text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-medium text-gray-900">Custom Reports</h3>
                        <p class="text-sm text-gray-500 mt-1">Create and save custom reports with your own parameters</p>
                        
                        <ul class="mt-4 space-y-2">
                            <li>
                                <a href="{{ route('organization.reports.create') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Create Custom Report
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('organization.reports.saved') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 flex items-center">
                                    <i class="fas fa-arrow-right mr-1 text-xs"></i>
                                    Saved Reports
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
    
    <!-- Quick Stats -->
    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Business Performance at a Glance</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Sales This Month -->
            <x-card class="bg-gradient-to-br from-sky-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-sky-100 rounded-md p-3">
                        <i class="fas fa-chart-line text-sky-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Sales This Month</dt>
                            <dd class="text-3xl font-semibold text-gray-900">Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</dd>
                        </dl>
                        <div class="mt-1 flex items-center text-xs text-gray-500">
                            @if($salesGrowth > 0)
                                <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                                <span class="text-green-500">{{ number_format($salesGrowth, 1) }}%</span>
                            @elseif($salesGrowth < 0)
                                <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                                <span class="text-red-500">{{ number_format(abs($salesGrowth), 1) }}%</span>
                            @else
                                <i class="fas fa-minus text-gray-500 mr-1"></i>
                                <span>0%</span>
                            @endif
                            <span class="ml-1">vs last month</span>
                        </div>
                    </div>
                </div>
            </x-card>
            
            <!-- New Customers -->
            <x-card class="bg-gradient-to-br from-purple-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">New Customers</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ $newCustomers }}</dd>
                        </dl>
                        <div class="mt-1 flex items-center text-xs text-gray-500">
                            @if($customerGrowth > 0)
                                <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                                <span class="text-green-500">{{ number_format($customerGrowth, 1) }}%</span>
                            @elseif($customerGrowth < 0)
                                <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                                <span class="text-red-500">{{ number_format(abs($customerGrowth), 1) }}%</span>
                            @else
                                <i class="fas fa-minus text-gray-500 mr-1"></i>
                                <span>0%</span>
                            @endif
                            <span class="ml-1">vs last month</span>
                        </div>
                    </div>
                </div>
            </x-card>
            
            <!-- Outstanding Invoices -->
            <x-card class="bg-gradient-to-br from-yellow-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                        <i class="fas fa-file-invoice-dollar text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Outstanding Invoices</dt>
                            <dd class="text-3xl font-semibold text-gray-900">Rp {{ number_format($outstandingInvoices, 0, ',', '.') }}</dd>
                        </dl>
                        <div class="mt-1 text-xs text-gray-500">
                            {{ $overdueInvoices }} overdue invoice(s)
                        </div>
                    </div>
                </div>
            </x-card>
            
            <!-- Top Product -->
            <x-card class="bg-gradient-to-br from-green-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <i class="fas fa-box text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Top Product</dt>
                            <dd class="text-xl font-semibold text-gray-900 truncate">{{ $topProduct->name ?? 'N/A' }}</dd>
                        </dl>
                        <div class="mt-1 text-xs text-gray-500">
                            {{ $topProduct ? number_format($topProduct->quantity) . ' units sold' : 'No sales data available' }}
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</x-organization-layout>