            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center">
                    <input 
                        id="select-all-checkbox" 
                        type="checkbox" 
                        x-model="selectAll"
                        @click="
                            selectAll = $event.target.checked;
                            if (selectAll) {
                                selectedInvoices = Array.from(document.querySelectorAll('.invoice-checkbox')).map(cb => cb.value);
                            } else {
                                selectedInvoices = [];
                            }
                            showBulkActions = selectedInvoices.length > 0;
                        "
                        class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-gray-300 rounded"
                    >
                    <label for="select-all-checkbox" class="ml-2 block text-sm text-gray-900">
                        Select All (<span x-text="selectedInvoices.length"></span>)
                    </label>
                </div>
                
                <div x-show="showBulkActions" class="flex flex-wrap gap-2" x-cloak>
                    <input type="hidden" name="format" value="pdf">
                    <template x-for="invoiceId in selectedInvoices" :key="invoiceId">
                        <input type="hidden" name="invoice_ids[]" :value="invoiceId">
                    </template>
                    
                    <button type="submit" name="format" value="pdf" class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                        <i class="fas fa-file-pdf mr-2"></i> Download as PDF
                    </button>
                    
                    <button type="submit" name="format" value="image" class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                        <i class="fas fa-file-image mr-2"></i> Download as Image
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Invoices Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 overflow-x-auto">
            @if($invoices->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                                <span class="sr-only">Select</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Invoice
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
                        @foreach($invoices as $invoice)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input 
                                        type="checkbox" 
                                        value="{{ $invoice->id }}" 
                                        class="invoice-checkbox h-4 w-4 text-sky-600 focus:ring-sky-500 border-gray-300 rounded"
                                        x-model="selectedInvoices"
                                        @change="showBulkActions = selectedInvoices.length > 0"
                                    >
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-gray-100 rounded-md">
                                            <i class="fas fa-file-invoice text-gray-500"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-sky-600">
                                                <a href="{{ route('organization.invoices.show', $invoice) }}" class="hover:underline">
                                                    {{ $invoice->invoice_number }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Order #: {{ $invoice->order->order_number }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $invoice->order->customer->name }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-[150px]" title="{{ $invoice->order->customer->email }}">{{ $invoice->order->customer->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 truncate max-w-[150px]" title="{{ $invoice->order->project->name }}">{{ $invoice->order->project->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $invoice->invoice_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if($invoice->due_date)
                                            Due: {{ $invoice->due_date->format('d M Y') }}
                                        @else
                                            No due date
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        Rp {{ number_format($invoice->order->total_amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($invoice->status === 'paid')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Paid
                                        </span>
                                    @elseif($invoice->status === 'partially_paid')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Partial
                                        </span>
                                    @elseif($invoice->status === 'unpaid')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Unpaid
                                        </span>
                                    @elseif($invoice->status === 'cancelled')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                            Cancelled
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('organization.invoices.show', $invoice) }}" class="text-sky-600 hover:text-sky-900 p-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('organization.invoices.download-pdf', $invoice) }}" class="text-green-600 hover:text-green-900 p-1" title="Download PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <a href="{{ route('organization.invoices.edit', $invoice) }}" class="text-yellow-600 hover:text-yellow-900 p-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <div x-data="{ open: false }" class="relative inline-block text-left">
                                            <button @click="open = !open" class="text-gray-600 hover:text-gray-900 p-1">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div
                                                x-show="open"
                                                @click.away="open = false"
                                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                                x-cloak
                                            >
                                                <div class="py-1">
                                                    <a href="{{ route('organization.invoices.download-image', $invoice) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                                        <i class="fas fa-file-image mr-2"></i> Download as Image
                                                    </a>
                                                    <a href="#" onclick="window.print(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                                        <i class="fas fa-print mr-2"></i> Print
                                                    </a>
                                                    <a href="mailto:?subject=Invoice {{ $invoice->invoice_number }}&body=Please find attached invoice {{ $invoice->invoice_number }}." class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                                        <i class="fas fa-envelope mr-2"></i> Send by Email
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>
            @else
                <div class="text-center py-10">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                        <i class="fas fa-file-invoice text-sky-600"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No invoices</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new order.</p>
                    <div class="mt-6">
                        <a href="{{ route('organization.orders.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            <i class="fas fa-plus mr-2"></i>
                            Create Order
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Summary Stats -->
    @if($invoices->count() > 0)
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                        <i class="fas fa-file-invoice text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Invoices</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $invoices->total() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Paid</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $invoices->where('status', 'paid')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $invoices->where('status', 'unpaid')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Overdue</dt>
                            <dd class="text-lg font-semibold text-gray-900">
                                @php
                                    $overdue = $invoices->filter(function ($invoice) {
                                        return $invoice->status === 'unpaid' && $invoice->due_date && $invoice->due_date->isPast();
                                    })->count();
                                @endphp
                                {{ $overdue }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('invoicesData', () => ({
                selectedInvoices: [],
                selectAll: false,
                showBulkActions: false
            }));
        });
    </script>
    @endpush
</x-organization-layout>