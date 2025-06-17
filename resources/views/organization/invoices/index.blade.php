<x-organization-layout>
    @section('title', 'Invoices')
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Invoices</h2>
            <p class="mt-1 text-sm text-gray-600">Manage all your invoices</p>
        </div>
    </div>
    
    <!-- Filters -->
    <x-card class="mb-6">
        <form action="{{ route('organization.invoices.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <x-form.input
                    id="search"
                    name="search"
                    placeholder="Search invoices..."
                    :value="request()->get('search')"
                    leading-icon="fas fa-search"
                />
                
                <!-- Status -->
                <x-form.select
                    id="status"
                    name="status"
                    placeholder="All Status"
                    :options="[
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'paid' => 'Paid',
                        'partially_paid' => 'Partially Paid',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled'
                    ]"
                    :value="request()->get('status')"
                />
                
                <!-- Project -->
                <x-form.select
                    id="project_id"
                    name="project_id"
                    placeholder="All Projects"
                    :options="$projects->pluck('name', 'id')->toArray()"
                    :value="request()->get('project_id')"
                />
                
                <!-- Customer -->
                <x-form.select
                    id="customer_id"
                    name="customer_id"
                    placeholder="All Customers"
                    :options="$customers->pluck('name', 'id')->toArray()"
                    :value="request()->get('customer_id')"
                />
            </div>
            
            <!-- Date Range and Template -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Invoice Date Range -->
                <div>
                    <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Invoice Date Range</label>
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
                
                <!-- Due Date Range -->
                <div>
                    <label for="due_date_range" class="block text-sm font-medium text-gray-700 mb-1">Due Date Range</label>
                    <div class="flex space-x-2">
                        <x-form.input
                            type="date"
                            id="due_start_date"
                            name="due_start_date"
                            :value="request()->get('due_start_date')"
                            class="w-full"
                        />
                        <span class="flex items-center text-gray-500">to</span>
                        <x-form.input
                            type="date"
                            id="due_end_date"
                            name="due_end_date"
                            :value="request()->get('due_end_date')"
                            class="w-full"
                        />
                    </div>
                </div>
                
                <!-- Template -->
                <x-form.select
                    id="template_id"
                    name="template_id"
                    placeholder="All Templates"
                    label="Invoice Template"
                    :options="$templates->pluck('name', 'id')->toArray()"
                    :value="request()->get('template_id')"
                />
            </div>
            
            <div class="flex justify-end gap-2">
                <x-button type="submit" variant="primary" icon="fas fa-filter">
                    Filter
                </x-button>
                
                <x-button href="{{ route('organization.invoices.index') }}" variant="secondary" icon="fas fa-sync">
                    Reset
                </x-button>
            </div>
        </form>
    </x-card>
    
    <!-- Invoice Table -->
    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Invoice
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date / Due Date
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
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('organization.invoices.show', $invoice) }}" class="text-sky-600 hover:text-sky-900">
                                            {{ $invoice->invoice_number }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <a href="{{ route('organization.orders.show', $invoice->order) }}" class="hover:text-sky-600">
                                            Order #{{ $invoice->order->order_number }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('organization.customers.show', $invoice->order->customer) }}" class="hover:text-sky-600">
                                        {{ $invoice->order->customer->name }}
                                    </a>
                                </div>
                                <div class="text-xs text-gray-500 truncate max-w-[150px]">{{ $invoice->order->customer->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $invoice->invoice_date->format('M d, Y') }}</div>
                                @if($invoice->due_date)
                                    <div class="text-xs text-gray-500">
                                        Due: {{ $invoice->due_date->format('M d, Y') }}
                                        @if($invoice->isOverdue())
                                            <span class="text-red-600 ml-1">(Overdue)</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($invoice->order->total_amount, 0, ',', '.') }}
                                </div>
                                @if($invoice->status === 'partially_paid')
                                    <div class="text-xs text-gray-500">
                                        Paid: Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-badge :color="
                                    $invoice->status === 'paid' ? 'green' : 
                                    ($invoice->status === 'partially_paid' ? 'yellow' : 
                                    ($invoice->status === 'sent' ? 'blue' : 
                                    ($invoice->status === 'overdue' ? 'red' : 
                                    ($invoice->status === 'cancelled' ? 'red' : 'gray'))))
                                " size="sm">
                                    {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('organization.invoices.show', $invoice) }}" class="text-sky-600 hover:text-sky-900" title="View invoice">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('organization.invoices.download-pdf', $invoice) }}" class="text-green-600 hover:text-green-900" title="Download PDF">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    
                                    @if($invoice->status === 'draft')
                                        <a href="{{ route('organization.invoices.edit', $invoice) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit invoice">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    
                                    @if($invoice->status === 'draft' || $invoice->status === 'sent')
                                        <form action="{{ route('organization.invoices.mark-cancelled', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this invoice?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Cancel invoice">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No invoices found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-3 border-t border-gray-200">
            {{ $invoices->withQueryString()->links() }}
        </div>
    </x-card>
    
    <!-- Summary Stats -->
    @if($invoices->count() > 0)
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card class="bg-gradient-to-br from-sky-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-sky-100 rounded-md p-3">
                        <i class="fas fa-file-invoice text-sky-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Invoices</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ $totalInvoices }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>
            
            <x-card class="bg-gradient-to-br from-green-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Paid Invoices</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ $paidInvoices }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>
            
            <x-card class="bg-gradient-to-br from-yellow-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Invoices</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ $pendingInvoices }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>
            
            <x-card class="bg-gradient-to-br from-red-50 to-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Overdue Invoices</dt>
                            <dd class="text-3xl font-semibold text-gray-900">{{ $overdueInvoices }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>
        </div>
    @endif
    
    <!-- Export Options -->
    <x-card title="Export Options" class="mt-6">
        <div class="flex flex-wrap gap-3">
            <x-button href="{{ route('organization.invoices.export', ['format' => 'csv']) }}" variant="secondary" icon="fas fa-file-csv">
                Export as CSV
            </x-button>
            
            <x-button href="{{ route('organization.invoices.export', ['format' => 'xlsx']) }}" variant="secondary" icon="fas fa-file-excel">
                Export as Excel
            </x-button>
            
            <x-button href="{{ route('organization.invoices.export', ['format' => 'pdf']) }}" variant="secondary" icon="fas fa-file-pdf">
                Export as PDF
            </x-button>
        </div>
    </x-card>
</x-organization-layout>