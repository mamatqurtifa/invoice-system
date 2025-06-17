<x-organization-layout>
    @section('title', 'Dashboard')
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-xl hover:shadow transition-shadow duration-300">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-sky-600 rounded-md p-3">
                        <i class="fas fa-project-diagram text-white"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Projects
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $totalProjects }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                <div class="text-sm">
                    <a href="{{ route('organization.projects.index') }}" class="font-medium text-sky-700 hover:text-sky-900 flex items-center justify-between">
                        <span>View all</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl hover:shadow transition-shadow duration-300">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-400 rounded-md p-3">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Customers
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $totalCustomers }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                <div class="text-sm">
                    <a href="{{ route('organization.customers.index') }}" class="font-medium text-sky-700 hover:text-sky-900 flex items-center justify-between">
                        <span>View all</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl hover:shadow transition-shadow duration-300">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-rose-700 rounded-md p-3">
                        <i class="fas fa-shopping-cart text-white"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Orders
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
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                <div class="text-sm">
                    <a href="{{ route('organization.orders.index') }}" class="font-medium text-sky-700 hover:text-sky-900 flex items-center justify-between">
                        <span>View all</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl hover:shadow transition-shadow duration-300">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-sky-600 rounded-md p-3">
                        <i class="fas fa-file-invoice text-white"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Invoices
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
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                <div class="text-sm">
                    <a href="{{ route('organization.invoices.index') }}" class="font-medium text-sky-700 hover:text-sky-900 flex items-center justify-between">
                        <span>View all</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Total Revenue Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-xl hover:shadow transition-shadow duration-300">
            <div class="p-5">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Total Revenue</h3>
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                        <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <div class="text-2xl font-semibold text-gray-900">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Total revenue from all orders
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Payments Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-xl hover:shadow transition-shadow duration-300">
            <div class="p-5">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Pending Payments</h3>
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <div class="text-2xl font-semibold text-gray-900">
                            Rp {{ number_format($pendingPayments, 0, ',', '.') }}
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Total pending payments from orders
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white overflow-hidden shadow-sm rounded-xl hover:shadow transition-shadow duration-300">
            <div class="p-5">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('organization.projects.create') }}" class="flex flex-col items-center p-3 bg-sky-50 rounded-lg hover:bg-sky-100 transition-colors duration-200">
                        <i class="fas fa-folder-plus text-sky-600 text-xl mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">New Project</span>
                    </a>
                    <a href="{{ route('organization.products.create') }}" class="flex flex-col items-center p-3 bg-sky-50 rounded-lg hover:bg-sky-100 transition-colors duration-200">
                        <i class="fas fa-box-open text-sky-600 text-xl mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">New Product</span>
                    </a>
                    <a href="{{ route('organization.customers.create') }}" class="flex flex-col items-center p-3 bg-sky-50 rounded-lg hover:bg-sky-100 transition-colors duration-200">
                        <i class="fas fa-user-plus text-sky-600 text-xl mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">New Customer</span>
                    </a>
                    <a href="{{ route('organization.orders.create') }}" class="flex flex-col items-center p-3 bg-sky-50 rounded-lg hover:bg-sky-100 transition-colors duration-200">
                        <i class="fas fa-cart-plus text-sky-600 text-xl mb-2"></i>
                        <span class="text-sm font-medium text-gray-900">New Order</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Projects -->
        <div class="bg-white shadow-sm rounded-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Recent Projects
                </h3>
                <a href="{{ route('organization.projects.index') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 transition-colors duration-200">
                    View All
                </a>
            </div>
            <div class="bg-white p-6">
                <ul class="divide-y divide-gray-200">
                    @forelse($recentProjects as $project)
                        <li class="py-4 hover:bg-gray-50 transition-colors duration-150 rounded-lg px-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($project->logo)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($project->logo) }}" alt="{{ $project->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-sky-100 flex items-center justify-center">
                                                <span class="text-sky-600 font-medium">
                                                    {{ substr($project->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $project->name }}
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500">
                                                {{ $project->start_date->format('M d, Y') }}
                                                @if($project->end_date)
                                                    - {{ $project->end_date->format('M d, Y') }}
                                                @endif
                                            </span>
                                            <span class="text-xs px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($project->status === 'active') bg-green-100 text-green-800 
                                                @elseif($project->status === 'completed') bg-blue-100 text-blue-800 
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                            <span class="text-xs px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($project->type === 'preorder') bg-purple-100 text-purple-800 
                                                @else bg-indigo-100 text-indigo-800 @endif">
                                                {{ $project->type === 'preorder' ? 'Pre-Order' : 'Direct Order' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('organization.projects.show', $project) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    View
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="py-4 text-center text-gray-500">
                            No projects found. <a href="{{ route('organization.projects.create') }}" class="text-sky-600 hover:text-sky-800">Create your first project</a>.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white shadow-sm rounded-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Recent Orders
                </h3>
                <a href="{{ route('organization.orders.index') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800 transition-colors duration-200">
                    View All
                </a>
            </div>
            <div class="bg-white p-6">
                <ul class="divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                        <li class="py-4 hover:bg-gray-50 transition-colors duration-150 rounded-lg px-2">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $order->order_number }}
                                                </p>
                                                <div class="mt-1 flex items-center text-sm text-gray-500">
                                                    <p class="truncate">
                                                        {{ $order->customer->name }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            @if($order->payment_status === 'completed')
                                                <x-badge color="green">Completed</x-badge>
                                            @elseif($order->payment_status === 'partial')
                                                <x-badge color="yellow">Partial</x-badge>
                                            @elseif($order->payment_status === 'pending')
                                                <x-badge color="gray">Pending</x-badge>
                                            @else
                                                <x-badge color="red">Cancelled</x-badge>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-1 flex items-center justify-between text-sm">
                                        <p class="text-gray-500">
                                            {{ $order->order_date->format('M d, Y') }}
                                        </p>
                                        <p class="font-medium text-gray-900">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('organization.orders.show', $order) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                        View
                                    </a>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="py-4 text-center text-gray-500">
                            No orders found. <a href="{{ route('organization.orders.create') }}" class="text-sky-600 hover:text-sky-800">Create your first order</a>.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-organization-layout>