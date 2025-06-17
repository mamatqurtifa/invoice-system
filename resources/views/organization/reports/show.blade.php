<x-organization-layout>
    @section('title', $report->name)
    
    @php
        $breadcrumbs = [
            'Reports' => route('organization.reports.index'),
            'Saved Reports' => route('organization.reports.saved'),
            $report->name => '#'
        ];
    @endphp
    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $report->name }}</h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ ucfirst($report->report_type) }} Report • 
                Created {{ $report->created_at->format('M d, Y') }} • 
                Last run {{ $report->last_run_at ? $report->last_run_at->format('M d, Y H:i') : 'Never' }}
            </p>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
                        <x-button 
                href="{{ route('organization.reports.run', $report) }}" 
                variant="primary"
                icon="fas fa-play"
            >
                Run Report
            </x-button>
            
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <x-button variant="secondary" icon="fas fa-ellipsis-h">
                        Actions
                    </x-button>
                </x-slot>
                
                <x-slot name="content">
                    <a href="{{ route('organization.reports.edit', $report) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-edit mr-2"></i> Edit Report
                    </a>
                    
                    <a href="{{ route('organization.reports.duplicate', $report) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-copy mr-2"></i> Duplicate Report
                    </a>
                    
                    <a href="{{ route('organization.reports.schedule', $report) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-calendar-alt mr-2"></i> Schedule Report
                    </a>
                    
                    <form action="{{ route('organization.reports.destroy', $report) }}" method="POST" class="block w-full text-left">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100" onclick="return confirm('Are you sure you want to delete this report?')">
                            <i class="fas fa-trash-alt mr-2"></i> Delete Report
                        </button>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
    
    <div class="space-y-6">
        <!-- Report Parameters -->
        <x-card title="Report Parameters">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Report Type</h4>
                    <p class="text-sm text-gray-900 mt-1">{{ ucfirst($report->report_type) }} Report</p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Date Range</h4>
                    <p class="text-sm text-gray-900 mt-1">
                        @if($report->date_range_type === 'predefined')
                            {{ ucfirst(str_replace('_', ' ', $report->predefined_range)) }}
                        @else
                            {{ $report->start_date->format('M d, Y') }} to {{ $report->end_date->format('M d, Y') }}
                        @endif
                    </p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Chart Type</h4>
                    <p class="text-sm text-gray-900 mt-1">
                        {{ $report->chart_type === 'none' ? 'No Chart (Table Only)' : ucfirst($report->chart_type) . ' Chart' }}
                    </p>
                </div>
                
                @if($report->report_type === 'sales')
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Group By</h4>
                        <p class="text-sm text-gray-900 mt-1">{{ ucfirst($report->sales_group_by) }}</p>
                    </div>
                    
                    @if(!empty($report->sales_dimensions))
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Dimensions</h4>
                            <p class="text-sm text-gray-900 mt-1">
                                {{ implode(', ', array_map('ucfirst', $report->sales_dimensions)) }}
                            </p>
                        </div>
                    @endif
                    
                    @if($report->project_id)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Project</h4>
                            <p class="text-sm text-gray-900 mt-1">{{ $projectName }}</p>
                        </div>
                    @endif
                @endif
                
                @if($report->report_type === 'customer' && !empty($report->customer_metrics))
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Customer Metrics</h4>
                        <p class="text-sm text-gray-900 mt-1">
                            {{ implode(', ', array_map(function($metric) {
                                return ucwords(str_replace('_', ' ', $metric));
                            }, $report->customer_metrics)) }}
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Sort By</h4>
                        <p class="text-sm text-gray-900 mt-1">
                            @php
                                $sortMap = [
                                    'revenue_desc' => 'Revenue (High to Low)',
                                    'revenue_asc' => 'Revenue (Low to High)',
                                    'orders_desc' => 'Order Count (High to Low)',
                                    'orders_asc' => 'Order Count (Low to High)',
                                    'recent_order' => 'Most Recent Order',
                                    'name_asc' => 'Name (A-Z)',
                                    'name_desc' => 'Name (Z-A)'
                                ];
                            @endphp
                            {{ $sortMap[$report->customer_sort] ?? ucwords(str_replace('_', ' ', $report->customer_sort)) }}
                        </p>
                    </div>
                @endif
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end">
                <x-button 
                    href="{{ route('organization.reports.edit', $report) }}" 
                    variant="secondary"
                    size="sm"
                    icon="fas fa-edit"
                >
                    Edit Parameters
                </x-button>
            </div>
        </x-card>
        
        <!-- Report Results -->
        <x-card title="Report Results">
            @if($reportData && count($reportData) > 0)
                <!-- Chart -->
                @if($report->chart_type !== 'none')
                    <div class="mb-6 h-80">
                        <canvas id="reportChart"></canvas>
                    </div>
                @endif
                
                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @foreach($columns as $column)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ ucwords(str_replace('_', ' ', $column)) }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reportData as $row)
                                <tr class="hover:bg-gray-50">
                                    @foreach($columns as $column)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ in_array($column, ['revenue', 'amount', 'total', 'price']) ? 'text-right' : 'text-left' }} {{ in_array($column, ['revenue', 'amount', 'total', 'price']) ? 'font-medium text-gray-900' : 'text-gray-500' }}">
                                            @if(in_array($column, ['revenue', 'amount', 'total', 'price', 'average_order', 'avg_price']) && isset($row[$column]) && is_numeric($row[$column]))
                                                Rp {{ number_format($row[$column], 0, ',', '.') }}
                                            @elseif($column === 'date' && isset($row[$column]))
                                                {{ \Carbon\Carbon::parse($row[$column])->format('M d, Y') }}
                                            @elseif($column === 'last_order' && isset($row[$column]))
                                                {{ \Carbon\Carbon::parse($row[$column])->format('M d, Y') }}
                                            @elseif(isset($row[$column]))
                                                {{ $row[$column] }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Summary Stats -->
                @if(isset($summaryStats) && count($summaryStats) > 0)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Summary</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach($summaryStats as $key => $value)
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-xs text-gray-500">{{ ucwords(str_replace('_', ' ', $key)) }}</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        @if(in_array($key, ['total_revenue', 'average_order_value', 'total_amount', 'average_price']))
                                            Rp {{ number_format($value, 0, ',', '.') }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                        <i class="fas fa-chart-bar text-gray-400"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Data Available</h3>
                    <p class="mt-1 text-sm text-gray-500">This report contains no data for the selected parameters.</p>
                    <div class="mt-6">
                        <x-button 
                            href="{{ route('organization.reports.edit', $report) }}" 
                            variant="primary"
                            icon="fas fa-edit"
                        >
                            Edit Parameters
                        </x-button>
                    </div>
                </div>
            @endif
        </x-card>
        
        <!-- Export Options -->
        <x-card title="Export Options">
            <div class="flex flex-wrap gap-3">
                <x-button href="{{ route('organization.reports.export', ['id' => $report->id, 'format' => 'pdf']) }}" variant="secondary" icon="fas fa-file-pdf">
                    Export as PDF
                </x-button>
                
                <x-button href="{{ route('organization.reports.export', ['id' => $report->id, 'format' => 'csv']) }}" variant="secondary" icon="fas fa-file-csv">
                    Export as CSV
                </x-button>
                
                <x-button href="{{ route('organization.reports.export', ['id' => $report->id, 'format' => 'xlsx']) }}" variant="secondary" icon="fas fa-file-excel">
                    Export as Excel
                </x-button>
            </div>
        </x-card>
    </div>
    
    @push('scripts')
    @if($report->chart_type !== 'none' && $reportData && count($reportData) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('reportChart').getContext('2d');
            
            // Extract data for chart
            const labels = {!! json_encode(array_column($reportData, $columns[0])) !!};
            
            // For bar and line charts
            const datasets = [];
            @foreach($columns as $index => $column)
                @if($index > 0 && in_array($column, ['revenue', 'amount', 'total', 'price', 'units_sold', 'count', 'orders']))
                    datasets.push({
                        label: '{{ ucwords(str_replace('_', ' ', $column)) }}',
                        data: {!! json_encode(array_column($reportData, $column)) !!},
                        backgroundColor: getChartColor({{ $index - 1 }}, 0.2),
                        borderColor: getChartColor({{ $index - 1 }}, 1),
                        borderWidth: 1
                    });
                @endif
            @endforeach
            
            const chartConfig = {
                type: '{{ $report->chart_type }}',
                data: {
                    labels: labels.map(label => {
                        // Format date labels
                        if (label && label.match(/^\d{4}-\d{2}-\d{2}/)) {
                            const date = new Date(label);
                            return date.toLocaleDateString();
                        }
                        return label;
                    }),
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Format y axis labels as currency for financial data
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
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
            };
            
            // Special config for pie chart
            if ('{{ $report->chart_type }}' === 'pie') {
                chartConfig.data = {
                    labels: labels,
                    datasets: [{
                        data: {!! json_encode(array_column($reportData, $columns[1])) !!},
                        backgroundColor: labels.map((_, i) => getChartColor(i, 0.7)),
                        borderColor: labels.map((_, i) => getChartColor(i, 1)),
                        borderWidth: 1
                    }]
                };
                
                // Remove unnecessary scales for pie chart
                delete chartConfig.options.scales;
            }
            
            new Chart(ctx, chartConfig);
            
            function getChartColor(index, alpha) {
                const colors = [
                    `rgba(59, 130, 246, ${alpha})`,   // blue
                    `rgba(16, 185, 129, ${alpha})`,   // green
                    `rgba(245, 158, 11, ${alpha})`,   // yellow
                    `rgba(239, 68, 68, ${alpha})`,    // red
                    `rgba(139, 92, 246, ${alpha})`,   // purple
                    `rgba(14, 165, 233, ${alpha})`,   // sky
                    `rgba(249, 115, 22, ${alpha})`,   // orange
                    `rgba(236, 72, 153, ${alpha})`,   // pink
                ];
                return colors[index % colors.length];
            }
        });
    </script>
    @endif
    @endpush
</x-organization-layout>