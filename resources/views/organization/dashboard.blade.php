<x-organization-layout>
    @section('title', 'Dashboard')

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-card class="bg-gradient-to-br from-sky-50 to-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-sky-100 rounded-md p-3">
                    <i class="fas fa-folder-open text-sky-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Projects</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $totalProjects }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <a href="{{ route('organization.projects.index') }}" class="text-sky-600 hover:text-sky-900 font-medium flex items-center">
                    View all projects
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-yellow-50 to-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                    <i class="fas fa-users text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Customers</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $totalCustomers }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <a href="{{ route('organization.customers.index') }}" class="text-yellow-600 hover:text-yellow-900 font-medium flex items-center">
                    View all customers
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-green-50 to-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <i class="fas fa-file-invoice text-green-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Invoices</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $totalInvoices }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <a href="{{ route('organization.invoices.index') }}" class="text-green-600 hover:text-green-900 font-medium flex items-center">
                    View all invoices
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </x-card>

        <x-card class="bg-gradient-to-br from-rose-50 to-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-rose-100 rounded-md p-3">
                    <i class="fas fa-money-bill-wave text-rose-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                        <dd class="text-3xl font-semibold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <a href="{{ route('organization.reports.index') }}" class="text-rose-600 hover:text-rose-900 font-medium flex items-center">
                    View financial reports
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Projects -->
        <x-card 
            title="Recent Projects" 
            :header-actions="view('components.button', [
                'variant' => 'secondary',
                'size' => 'sm',
                'icon' => 'fas fa-plus',
                'href' => route('organization.projects.create'),
                'slot' => 'New Project'
            ])"
        >
            @if(count($recentProjects) > 0)
                <div class="space-y-4">
                    @foreach($recentProjects as $project)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if($project->logo)
                                        <img class="h-12 w-12 rounded-lg object-cover" src="{{ Storage::url($project->logo) }}" alt="{{ $project->name }}">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-sky-100 flex items-center justify-center">
                                            <span class="text-lg font-bold text-sky-600">{{ substr($project->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $project->name }}
                                    </p>
                                    <div class="flex items-center space-x-3 text-xs text-gray-500 mt-0.5">
                                        <span>
                                            <i class="fas fa-box-open mr-1"></i> {{ $project->projectProducts->count() }} products
                                        </span>
                                        <span>
                                            <i class="fas fa-file-invoice mr-1"></i> {{ $project->orders()->has('invoice')->count() }} invoices
                                        </span>
                                    </div>
                                    <div class="mt-1">
                                        <x-badge :color="$project->status === 'active' ? 'green' : ($project->status === 'completed' ? 'blue' : 'red')" size="sm">
                                            {{ ucfirst($project->status) }}
                                        </x-badge>
                                        <x-badge :color="$project->type === 'preorder' ? 'purple' : 'sky'" size="sm">
                                            {{ $project->type === 'preorder' ? 'Pre-Order' : 'Direct Order' }}
                                        </x-badge>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('organization.projects.show', $project) }}" class="text-sky-600 hover:text-sky-800">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('organization.projects.index') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800">
                        View all projects
                    </a>
                </div>
            @else
                <div class="text-center py-6">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                        <i class="fas fa-folder text-sky-600"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No projects</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new project.</p>
                    <div class="mt-6">
                        <a href="{{ route('organization.projects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            <i class="fas fa-plus mr-2"></i>
                            New Project
                        </a>
                    </div>
                </div>
            @endif
        </x-card>

        <!-- Recent Invoices -->
        <x-card title="Recent Invoices">
            @if(count($recentInvoices) > 0)
                <div class="space-y-4">
                    @foreach($recentInvoices as $invoice)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                            <div class="flex items-center flex-1 min-w-0">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-file-invoice text-gray-500"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $invoice->invoice_number }}
                                        </p>
                                        <div>
                                            <x-badge :color="$invoice->status === 'paid' ? 'green' : ($invoice->status === 'partially_paid' ? 'yellow' : ($invoice->status === 'unpaid' ? 'gray' : 'red'))" size="sm">
                                                {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                            </x-badge>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate mt-0.5">
                                        {{ $invoice->order->customer->name }} - {{ $invoice->invoice_date->format('d M Y') }}
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-gray-500">Order #{{ $invoice->order->order_number }}</span>
                                        <span class="text-sm font-medium text-gray-900">Rp {{ number_format($invoice->order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('organization.invoices.show', $invoice) }}" class="ml-4 text-sky-600 hover:text-sky-800">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('organization.invoices.index') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800">
                        View all invoices
                    </a>
                </div>
            @else
                <div class="text-center py-6">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-sky-100">
                        <i class="fas fa-file-invoice text-sky-600"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No invoices</h3>
                    <p class="mt-1 text-sm text-gray-500">Create an order to generate your first invoice.</p>
                    <div class="mt-6">
                        <a href="{{ route('organization.orders.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            <i class="fas fa-plus mr-2"></i>
                            Create Order
                        </a>
                    </div>
                </div>
            @endif
        </x-card>
    </div>

    <!-- Quick Actions and Latest Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Quick Actions -->
        <x-card title="Quick Actions">
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('organization.orders.create') }}" class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex flex-col items-center justify-center">
                    <div class="bg-sky-100 rounded-full h-12 w-12 flex items-center justify-center mb-2">
                        <i class="fas fa-plus text-sky-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">New Order</span>
                </a>

                <a href="{{ route('organization.projects.create') }}" class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex flex-col items-center justify-center">
                    <div class="bg-green-100 rounded-full h-12 w-12 flex items-center justify-center mb-2">
                        <i class="fas fa-folder-plus text-green-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">New Project</span>
                </a>

                <a href="{{ route('organization.customers.create') }}" class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex flex-col items-center justify-center">
                    <div class="bg-yellow-100 rounded-full h-12 w-12 flex items-center justify-center mb-2">
                        <i class="fas fa-user-plus text-yellow-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">New Customer</span>
                </a>

                <a href="{{ route('organization.products.create') }}" class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200 flex flex-col items-center justify-center">
                    <div class="bg-purple-100 rounded-full h-12 w-12 flex items-center justify-center mb-2">
                        <i class="fas fa-box-open text-purple-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">New Product</span>
                </a>
            </div>
        </x-card>

        <!-- Latest Activity -->
        <x-card title="Latest Activity" class="lg:col-span-2">
            @if(count($recentActivities) > 0)
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @foreach($recentActivities as $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center {{ $activityColors[$activity->type] ?? 'bg-gray-100' }}">
                                                <i class="{{ $activityIcons[$activity->type] ?? 'fas fa-bell' }} {{ $activityIconColors[$activity->type] ?? 'text-gray-500' }}"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    {!! $activity->description !!}
                                                </p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <time datetime="{{ $activity->created_at->format('Y-m-d H:i:s') }}">{{ $activity->created_at->diffForHumans() }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-sm text-gray-500">No recent activities.</p>
                </div>
            @endif
        </x-card>
    </div>
</x-organization-layout>