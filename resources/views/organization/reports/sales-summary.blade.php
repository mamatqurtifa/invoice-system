<x-organization-layout>
    @section('title', 'Sales Summary Report')
    
    @php
        $breadcrumbs = [
            'Reports' => route('organization.reports.index'),
            'Sales Summary' => '#'
        ];
    @endphp
    
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Sales Summary Report</h2>
                <p class="mt-1 text-sm text-gray-600">Overview of your organization's sales performance</p>
            </div>
            
            <div class="mt-4 sm:mt-0">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <x-button variant="secondary" icon="fas fa-download">
                            Export
                        </x-button>
                    </x-slot>
                    
                    <x-slot name="content">
                        <a href="{{ route('organization.reports.export-sales-summary', ['format' => 'pdf']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-pdf mr-2 text-red-500"></i> Export as PDF
                        </a>
                        
                        <a href="{{ route('organization.reports.export-sales-summary', ['format' => 'csv']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-csv mr-2 text-green-500"></i> Export as CSV
                        </a>
                        
                        <a href="{{ route('organization.reports.export-sales-summary', ['format' => 'xlsx']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-file-excel mr-2 text-blue-500"></i> Export as Excel
                        </a>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <x-card class="mb-6">
        <form action="{{ route('organization.reports.sales-summary') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Date Range Type -->
                <div x-data="{ dateRangeType: '{{ request()->get('date_range_type', 'predefined') }}' }">
                    <x-form.select
                        id="date_range_type"
                        name="date_range_type"
                        label="Date Range"
                        :options="[
                            'predefined' => 'Predefined Range',
                            'custom' => 'Custom Range'
                        ]"
                        :value="request()->get('date_range_type', 'predefined')"
                        x-model="dateRangeType"
                    />
                    
                    <div x-show="dateRangeType === 'predefined'" class="mt-4">
                        <x-form.select
                            id="predefined_range"
                            name="predefined_range"
                            label="Select Period"
                            :options="[
                                'this_month' => 'This Month',
                                'last_month' => 'Last Month',
                                'this_quarter' => 'This Quarter',
                                'last_quarter' => 'Last Quarter',
                                'this_year' => 'This Year',
                                'last_year' => 'Last Year',
                                'all_time' => 'All Time'
                            ]"
                            :value="request()->get('predefined_range', 'this_month')"
                        />
                    </div>
                    
                    <div x-show="dateRangeType === 'custom'" class="mt-4">
                        <div class="grid grid-cols-2 gap-2">
                            <x-form.input
                                type="date"
                                id="start_date"
                                name="start_date"
                                label="Start Date"
                                :value="request()->get('start_date')"
                                x-bind:required="dateRangeType === 'custom'"
                            />
                            
                            <x-form.input
                                type="date"
                                id="end_date"
                                name="end_date"
                                label="End Date"
                                :value="request()->get('end_date')"
                                x-bind:required="dateRangeType === 'custom'"
                            />
                        </div>
                    </div>
                </div>
                
                <!-- Project -->
                <div>
                    <x-form.select
                        id="project_id"
                        name="project_id"
                        label="Project"
                        :options="$projects->pluck('name', 'id')->toArray()"
                        :value="request()->get('project_id')"
                        placeholder="All Projects"
                    />
                </div>
                
                <!-- Group By -->
                <div>
                    <x-form.select
                        id="group_by"
                        name="group_by"
                        label="Group By"
                        :options="[
                            'day' => 'Day',
                            'week' => 'Week',
                            'month' => 'Month',
                            'quarter' => 'Quarter',
                            'year' => 'Year'
                        ]"
                        :value="request()->get('group_by', 'month')"
                    />
                </div>
                
                <!-- Compare To -->
                <div>
                    <x-form.select
                        id="compare_to"
                        name="compare_to"
                        label="Compare To (Optional)"
                        :options="[
                            'none' => 'No Comparison',
                            'previous_period' => 'Previous Period',
                            'previous_year' => 'Same Period Last Year'
                        ]"
                        :value="request()->get('compare_to', 'none')"
                    />
                </div>
            </div>
            
            <div class="flex justify-end gap-2">
                <x-button type="submit" variant="primary" icon="fas fa-filter">
                    Apply Filters
                </x-button>
                
                <x-button href="{{ route('organization.reports.sales-summary') }}" variant="secondary" icon="fas fa-sync">
                    Reset
                </x-button>
            </div>
        </form>
    </x-card>
    
    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Sales -->
        <x-card class="bg-gradient-to-br from-sky-50 to-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-sky-100 rounded-md p-3">
                    <i class="fas fa-chart-line text-sky-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Sales</dt>
                        <dd class="text-3xl font-semibold text-gray-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</dd>
                    </dl>
                    
                    @if($compareToActive && isset($comparisonData['total_sales_change_percentage']))
                        <div class="mt-1 flex items-center text-xs">
                            @if($comparisonData['total_sales_change_percentage'] > 0)
                                <span class="text-green-500 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    {{ number_format(abs($comparisonData['total_sales_change_percentage']), 1) }}%
                                </span>
                            @elseif($comparisonData['total_sales_change_percentage'] < 0)
                                <span class="text-red-500 flex items-center">
                                    <i class="fas fa-arrow-down mr-1"></i>
                                    {{ number_format(abs($comparisonData['total_sales_change_percentage']), 1) }}%
                                </span>
                            @else
                                <span class="text-gray-500">0% change</span>
                            @endif
                            
                            <span class="ml-1 text-gray-500">vs {{ $comparisonLabel }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </x-card>
        
        <!-- Total Orders -->
        <x-card class="bg-gradient-to-br from-green-50 to-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ $totalOrders }}</dd>
                    </dl>
                    
                    @if($compareToActive && isset($comparisonData['total_orders_change_percentage']))
                        <div class="mt-1 flex items-center text-xs">
                            @if($comparisonData['total_orders_change_percentage'] > 0)
                                <span class="text-green-500 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    {{ number_format(abs($comparisonData['total_orders_change_percentage']), 1) }}%
                                </span>
                            @elseif($comparisonData['total_orders_change_percentage'] < 0)
                                <span class="text-red-500 flex items-center">
                                    <i class="fas fa-arrow-down mr-1"></i>
                                    {{ number_format(abs($comparisonData['total_orders_change_percentage']), 1) }}%
                                </span>
                            @else
                                <span class="text-gray-500">0% change</span>
                            @endif
                            
                            <span class="ml-1 text-gray-500">vs {{ $comparisonLabel }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </x-card>
        
        <!-- Average Order Value -->
        <x-card class="bg-gradient-to-br from-purple-50 to-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                    <i class="fas fa-calculator text-purple-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Average Order Value</dt>
                        <dd class="text-3xl font-semibold text-gray-900">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</dd>
                    </dl>
                    
                    @if($compareToActive && isset($comparisonData['aov_change_percentage']))
                        <div class="mt-1 flex items-center text-xs">
                            @if($comparisonData['aov_change_percentage'] > 0)
                                <span class="text-green-500 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    {{ number_format(abs($comparisonData['aov_change_percentage']), 1) }}%
                                </span>
                            @elseif($comparisonData['aov_change_percentage'] < 0)
                                <span class="text-red-500 flex items-center">
                                    <i class="fas fa-arrow-down mr-1"></i>
                                    {{ number_format(abs($comparisonData['aov_change_percentage']), 1) }}%
                                </span>
                            @else
                                <span class="text-gray-500">0% change</span>
                            @endif
                            
                            <span class="ml-1 text-gray-500">vs {{ $comparisonLabel }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </x-card>
        
        <!-- Units Sold -->
        <x-card class="bg-gradient-to-br from-yellow-50 to-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                    <i class="fas fa-box text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Units Sold</dt>
                        <dd class="text-3xl font-semibold text-gray-900">{{ number_format($unitsSold) }}</dd>
                    </dl>
                    
                    @if($compareToActive && isset($comparisonData['units_sold_change_percentage']))
                        <div class="mt-1 flex items-center text-xs">
                            @if($comparisonData['units_sold_change_percentage'] > 0)
                                <span class="text-green-500 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    {{ number_format(abs($comparisonData['units_sold_change_percentage']), 1) }}%
                                </span>
                            @elseif($comparisonData['units_sold_change_percentage'] < 0)
                                <span class="text-red-500 flex items-center">
                                    <i class="fas fa-arrow-down mr-1"></i>
                                    {{ number_format(abs($comparisonData['units_sold_change_percentage']), 1) }}%
                                </span>
                            @else
                                <span class="text-gray-500">0% change</span>
                            @endif
                            
                            <span class="ml-1 text-gray-500">vs {{ $comparisonLabel }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </x-card>
    </div>
    
    <!-- Sales Trend Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-card title="Sales Trend">
                <div class="h-80">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </x-card>
        </div>
        
        <div>
            <x-card title="Top Products">
                @if(count($topProducts) > 0)
                    <div class="space-y-4">
                        @foreach($topProducts as $product)
                            <div class="flex items-center justify-between">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-8 w-8 bg-gray-100 rounded-md flex items-center justify-center overflow-hidden">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-8 w-8 object-cover">
                                        @else
                                            <i class="fas fa-box text-gray-400"></i>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $product->quantity }} units</p>
                                    </div>
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($product->revenue, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(count($topProducts) === 5 && $productsCount > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('organization.reports.sales-by-product') }}" class="text-sm font-medium text-sky-600 hover:text-sky-800">
                                View all products
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">No product data available for the selected period.</p>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
    
    <!-- Detailed Data Table -->
    <x-card title="Sales Data" class="mt-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Period
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Orders
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Units Sold
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Revenue
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Avg. Order Value
                        </th>
                        @if($compareToActive)
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                % Change
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($salesData as $period => $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $period }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                {{ number_format($data['orders']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                {{ number_format($data['units']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                Rp {{ number_format($data['revenue'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                Rp {{ number_format($data['aov'], 0, ',', '.') }}
                            </td>
                            @if($compareToActive)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                    @if(isset($data['change_percentage']))
                                        <span class="{{ $data['change_percentage'] > 0 ? 'text-green-500' : ($data['change_percentage'] < 0 ? 'text-red-500' : 'text-gray-500') }}">
                                            @if($data['change_percentage'] > 0)
                                                <i class="fas fa-arrow-up mr-1"></i>
                                            @elseif($data['change_percentage'] < 0)
                                                <i class="fas fa-arrow-down mr-1"></i>
                                            @else
                                                <i class="fas fa-minus mr-1"></i>
                                            @endif
                                            {{ number_format(abs($data['change_percentage']), 1) }}%
                                        </span>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $compareToActive ? 6 : 5 }}" class="px-6 py-4 text-center text-gray-500">
                                No sales data available for the selected period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
    
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const salesData = {!! json_encode($salesChartData) !!};
            const labels = salesData.map(item => item.period);
            const values = salesData.map(item => item.revenue);
            
            @if($compareToActive)
                const comparisonValues = salesData.map(item => item.comparison_revenue || null);
            @endif
            
            const ctx = document.getElementById('salesTrendChart').getContext('2d');
            
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Revenue',
                            data: values,
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            tension: 0.3
                        },
                        @if($compareToActive)
                        {
                            label: '{{ $comparisonLabel }}',
                            data: comparisonValues,
                            backgroundColor: 'rgba(107, 114, 128, 0.2)',
                            borderColor: 'rgba(107, 114, 128, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(107, 114, 128, 1)',
                            tension: 0.3,
                            borderDash: [5, 5]
                        }
                        @endif
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString();
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-organization-layout>