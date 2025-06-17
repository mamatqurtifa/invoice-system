<x-admin-layout>
    @section('title', 'Dashboard')

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-sky-500 rounded-md p-3">
                        <i class="fas fa-building text-white"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Organizations
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $totalOrganizations }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.organizations.index') }}"
                        class="font-medium text-sky-700 hover:text-sky-900 flex justify-between items-center">
                        <span>View all</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-400 rounded-md p-3">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Users
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $totalUsers }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.users.index') }}"
                        class="font-medium text-sky-700 hover:text-sky-900 flex justify-between items-center">
                        <span>View all</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-rose-700 rounded-md p-3">
                        <i class="fas fa-shopping-cart text-white"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Orders
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $totalOrders }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="font-medium text-gray-500">
                        All time
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-sky-600 rounded-md p-3">
                        <i class="fas fa-file-invoice text-white"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Invoices
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $totalInvoices }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.invoices.index') }}"
                        class="font-medium text-sky-700 hover:text-sky-900 flex justify-between items-center">
                        <span>View all</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Organizations -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Recent Organizations
                </h3>
            </div>
            <div class="bg-white p-6">
                <ul class="divide-y divide-gray-200">
                    @forelse($recentOrganizations as $organization)
                        <li
                            class="py-3 flex items-center justify-between hover:bg-gray-50 px-2 rounded-lg transition-colors duration-150">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if ($organization->logo)
                                        <img class="h-10 w-10 rounded-full object-cover"
                                            src="{{ Storage::url($organization->logo) }}"
                                            alt="{{ $organization->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-sky-100 flex items-center justify-center">
                                            <span class="text-sky-600 font-medium">
                                                {{ substr($organization->name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $organization->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 truncate max-w-xs">
                                        {{ $organization->email }}
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('admin.organizations.show', $organization) }}"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-sky-700 bg-sky-100 hover:bg-sky-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                                    View details
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="py-4 text-center text-gray-500">
                            No organizations found.
                        </li>
                    @endforelse
                </ul>

                @if (count($recentOrganizations) > 0)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.organizations.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                            View All Organizations
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Invoices -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Recent Invoices
                </h3>
            </div>
            <div class="bg-white p-6">
                <ul class="divide-y divide-gray-200">
                    @forelse($recentInvoices as $invoice)
                        <li class="py-3 hover:bg-gray-50 px-2 rounded-lg transition-colors duration-150">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-sky-600 truncate">
                                        {{ $invoice->invoice_number }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        @if ($invoice->status === 'paid')
                                            <x-badge color="green">Paid</x-badge>
                                        @elseif($invoice->status === 'partially_paid')
                                            <x-badge color="yellow">Partially Paid</x-badge>
                                        @elseif($invoice->status === 'unpaid')
                                            <x-badge color="gray">Unpaid</x-badge>
                                        @else
                                            <x-badge color="red">Cancelled</x-badge>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-1 flex items-center text-sm text-gray-500">
                                    <p class="truncate">
                                        <span class="font-medium">{{ $invoice->order->customer->name }}</span> -
                                        {{ $invoice->order->project->organization->name }}
                                    </p>
                                </div>
                                <div class="mt-1 flex items-center justify-between text-sm">
                                    <p class="text-gray-500">
                                        {{ $invoice->invoice_date->format('M d, Y') }}
                                    </p>
                                    <p class="font-medium text-gray-900">
                                        Rp {{ number_format($invoice->order->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="mt-2 flex justify-end">
                                    <a href="{{ route('admin.invoices.show', $invoice) }}"
                                        class="inline-flex items-center text-xs font-medium text-sky-600 hover:text-sky-900">
                                        View details <i class="fas fa-chevron-right ml-1 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="py-4 text-center text-gray-500">
                            No invoices found.
                        </li>
                    @endforelse
                </ul>

                @if (count($recentInvoices) > 0)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.invoices.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-200">
                            View All Invoices
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- System Stats -->
    <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                System Statistics
            </h3>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-gray-50 overflow-hidden rounded-lg px-4 py-5 sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">
                        Total Revenue
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                        @php
                            // Calculate total revenue if not provided
                            $calculatedRevenue = $totalRevenue ?? \App\Models\Order::sum('total_amount');
                        @endphp
                        Rp {{ number_format($calculatedRevenue, 0, ',', '.') }}
                    </dd>
                </div>

                <div class="bg-gray-50 overflow-hidden rounded-lg px-4 py-5 sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">
                        Active Projects
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ $activeProjects ?? \App\Models\Project::where('status', 'active')->count() }}
                    </dd>
                </div>

                <div class="bg-gray-50 overflow-hidden rounded-lg px-4 py-5 sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">
                        Unpaid Invoices
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ $unpaidInvoices ?? \App\Models\Invoice::where('status', 'unpaid')->count() }}
                    </dd>
                </div>

                <div class="bg-gray-50 overflow-hidden rounded-lg px-4 py-5 sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">
                        New Organizations (Month)
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ $newOrganizations ?? \App\Models\Organization::where('created_at', '>=', now()->subMonth())->count() }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</x-admin-layout>
