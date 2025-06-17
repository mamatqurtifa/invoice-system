<x-organization-layout>
    @section('title', 'Orders')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Orders</h2>
            <p class="mt-1 text-sm text-gray-600">Manage all your orders and invoices</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <x-button 
                href="{{ route('organization.orders.create') }}" 
                icon="fas fa-plus" 
                variant="primary"
            >
                Create Order
            </x-button>
        </div>
    </div>
    
    @if(session('success'))
        <x-alert type="success" :dismissible="true" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif
    
    <!-- Filters -->
    <x-card class="mb-6">
        <form action="{{ route('organization.orders.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <x-form.input
                    id="search"
                    name="search"
                    placeholder="Order #, customer name..."
                    :value="request()->get('search')"
                    leading-icon="fas fa-search"
                />
                
                <!-- Project -->
                <x-form.select
                    id="project_id"
                    name="project_id"
                    placeholder="All Projects"
                    :options="$projects->pluck('name', 'id')->toArray()"
                    :value="request()->get('project_id')"
                />
                
                <!-- Status -->
                <x-form.select
                    id="status"
                    name="status"
                    placeholder="All Status"
                    :options="[
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled'
                    ]"
                    :value="request()->get('status')"
                />
                
                <!-- Date Range -->
                <div>
                    <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <div class="flex space-x-2">
                        <x-form.input
                            type="date"
                            id="start_date"
                            name="start_date"
                            :value="request()->get('start_date')"
                            class="w-full"
                        />
                        <span class="flex items-center text-gray-500">to</span>
                        <x-form.input
                            type="date"
                            id="end_date"
                            name="end_date"
                            :value="request()->get('end_date')"
                            class="w-full"
                        />
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Payment Status -->
                <x-form.select
                    id="payment_status"
                    name="payment_status"
                    placeholder="All Payment Status"
                    :options="[
                        'pending' => 'Pending',
                        'partial' => 'Partially Paid',
                        'completed' => 'Fully Paid',
                        'refunded' => 'Refunded'
                    ]"
                    :value="request()->get('payment_status')"
                />
                
                <!-- Has Invoice -->
                <x-form.select
                    id="has_invoice"
                    name="has_invoice"
                    placeholder="With/Without Invoice"
                    :options="[
                        '1' => 'With Invoice',
                        '0' => 'Without Invoice'
                    ]"
                    :value="request()->get('has_invoice')"
                />
                
                <!-- Sort -->
                <x-form.select
                    id="sort"
                    name="sort"
                    :options="[
                        'newest' => 'Newest First',
                        'oldest' => 'Oldest First',
                        'total_asc' => 'Amount (Low to High)',
                        'total_desc' => 'Amount (High to Low)'
                    ]"
                    :value="request()->get('sort', 'newest')"
                />
            </div>
            
            <div class="flex justify-end gap-2">
                <x-button type="submit" variant="primary" icon="fas fa-filter">
                    Filter
                </x-button>
                
                <x-button href="{{ route('organization.orders.index') }}" variant="secondary" icon="fas fa-sync">
                    Reset
                </x-button>
            </div>
        </form>
    </x-card>
    
    <!-- Orders Table -->
    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Order/Invoice
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Project
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('organization.orders.show', $order) }}" class="text-sky-600 hover:text-sky-900">
                                            {{ $order->order_number }}
                                        </a>
                                    </div>
                                    
                                    @if($order->invoice)
                                        <div class="text-xs text-gray-500">
                                            <a href="{{ route('organization.invoices.show', $order->invoice) }}" class="text-sky-600 hover:text-sky-900">
                                                Invoice #{{ $order->invoice->invoice_number }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-500">
                                            No invoice
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('organization.customers.show', $order->customer) }}" class="hover:text-sky-600">
                                        {{ $order->customer->name }}
                                    </a>
                                </div>
                                <div class="text-xs text-gray-500 truncate max-w-[150px]">{{ $order->customer->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $order->project->name }}</div>
                                <div class="text-xs text-gray-500">
                                    <x-badge :color="$order->project->type === 'preorder' ? 'purple' : 'sky'" size="xs">
                                        {{ $order->project->type === 'preorder' ? 'Pre-Order' : 'Direct Order' }}
                                    </x-badge>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order->order_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $order->order_date->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </div>
                                @if($order->payment_type === 'down_payment')
                                    <div class="text-xs text-gray-500">
                                        DP: Rp {{ number_format($order->down_payment_amount, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <x-badge :color="
                                        $order->status === 'completed' ? 'green' : 
                                        ($order->status === 'processing' ? 'yellow' : 
                                        ($order->status === 'cancelled' ? 'red' : 'gray'))
                                    " size="sm">
                                        {{ ucfirst($order->status) }}
                                    </x-badge>
                                    
                                    <x-badge :color="
                                        $order->payment_status === 'completed' ? 'green' : 
                                        ($order->payment_status === 'partial' ? 'yellow' : 
                                        ($order->payment_status === 'refunded' ? 'red' : 'gray'))
                                    " size="sm">
                                        {{ ucfirst($order->payment_status) }}
                                    </x-badge>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('organization.orders.show', $order) }}" class="text-sky-600 hover:text-sky-900" title="View order details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if(!$order->invoice)
                                        <a href="{{ route('organization.invoices.create', ['order_id' => $order->id]) }}" class="text-green-600 hover:text-green-900" title="Create invoice">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                    @endif
                                    
                                    @if($order->status !== 'completed' && $order->status !== 'cancelled')
                                        <a href="{{ route('organization.orders.edit', $order) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit order">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No orders found. 
                                <a href="{{ route('organization.orders.create') }}" class="text-sky-600 hover:text-sky-900">Create your first order</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-3 border-t border-gray-200">
            {{ $orders->withQueryString()->links() }}
        </div>
    </x-card>
    
    <!-- Summary Stats -->
    @if($orders->count() > 0)
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card class="bg-gradient-to-br from-sky-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-sky-100 rounded-md p-3">
                        <i class="fas fa-shopping-cart text-sky-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ $totalOrders }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>
            
            <x-card class="bg-gradient-to-br from-green-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <i class="fas fa-file-invoice-dollar text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                            <dd class="text-3xl font-semibold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>
            
            <x-card class="bg-gradient-to-br from-yellow-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                        <i class="fas fa-file-invoice text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Invoices</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ $totalInvoices }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>
            
            <x-card class="bg-gradient-to-br from-red-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                        <i class="fas fa-clock text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Orders</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ $pendingOrders }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>
        </div>
    @endif
    
    <!-- Export Options -->
    <x-card title="Export Options" class="mt-6">
        <div class="flex flex-wrap gap-3">
            <x-button href="{{ route('organization.orders.export', ['format' => 'csv']) }}" variant="secondary" icon="fas fa-file-csv">
                Export as CSV
            </x-button>
            
            <x-button href="{{ route('organization.orders.export', ['format' => 'xlsx']) }}" variant="secondary" icon="fas fa-file-excel">
                Export as Excel
            </x-button>
            
            <x-button href="{{ route('organization.orders.export', ['format' => 'pdf']) }}" variant="secondary" icon="fas fa-file-pdf">
                Export as PDF
            </x-button>
        </div>
    </x-card>
</x-organization-layout>