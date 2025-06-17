<x-organization-layout>
    @section('title', 'Create Custom Report')
    
    @php
        $breadcrumbs = [
            'Reports' => route('organization.reports.index'),
            'Create Custom Report' => '#'
        ];
    @endphp
    
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Create Custom Report</h2>
        <p class="mt-1 text-sm text-gray-600">Build a custom report by selecting parameters and fields</p>
    </div>
    
    <x-card>
        <form action="{{ route('organization.reports.generate') }}" method="GET" class="space-y-6">
            <!-- Report Type & Format -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-form.select
                        id="report_type"
                        name="report_type"
                        label="Report Type"
                        :options="[
                            'sales' => 'Sales Report',
                            'customer' => 'Customer Report', 
                            'product' => 'Product Report',
                            'invoice' => 'Invoice Report',
                            'tax' => 'Tax Report',
                            'payment' => 'Payment Report'
                        ]"
                        :value="request()->get('report_type', 'sales')"
                        required
                        x-model="reportType"
                        :error="$errors->first('report_type')"
                    />
                </div>
                
                <div>
                    <x-form.select
                        id="format"
                        name="format"
                        label="Output Format"
                        :options="[
                            'web' => 'Web View',
                            'pdf' => 'PDF Document', 
                            'csv' => 'CSV File',
                            'xlsx' => 'Excel File'
                        ]"
                        :value="request()->get('format', 'web')"
                        required
                        :error="$errors->first('format')"
                    />
                </div>
            </div>
            
            <!-- Date Range -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Date Range</h3>
                
                <div x-data="{ dateRangeType: '{{ request()->get('date_range_type', 'predefined') }}' }">
                    <div class="mb-4">
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="date_range_type" value="predefined" class="focus:ring-sky-500 h-4 w-4 text-sky-600 border-gray-300" x-model="dateRangeType">
                                <span class="ml-2 text-sm text-gray-700">Predefined Range</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="radio" name="date_range_type" value="custom" class="focus:ring-sky-500 h-4 w-4 text-sky-600 border-gray-300" x-model="dateRangeType">
                                <span class="ml-2 text-sm text-gray-700">Custom Range</span>
                            </label>
                        </div>
                    </div>
                    
                    <div x-show="dateRangeType === 'predefined'">
                        <x-form.select
                            id="predefined_range"
                            name="predefined_range"
                            label="Select Period"
                            :options="[
                                'today' => 'Today',
                                'yesterday' => 'Yesterday',
                                'this_week' => 'This Week',
                                'last_week' => 'Last Week',
                                'this_month' => 'This Month',
                                'last_month' => 'Last Month',
                                'this_quarter' => 'This Quarter',
                                'last_quarter' => 'Last Quarter',
                                'this_year' => 'This Year',
                                'last_year' => 'Last Year',
                                'all_time' => 'All Time'
                            ]"
                            :value="request()->get('predefined_range', 'this_month')"
                            :error="$errors->first('predefined_range')"
                        />
                    </div>
                    
                    <div x-show="dateRangeType === 'custom'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-form.input
                                type="date"
                                id="start_date"
                                name="start_date"
                                label="Start Date"
                                :value="request()->get('start_date')"
                                :error="$errors->first('start_date')"
                                x-bind:required="dateRangeType === 'custom'"
                            />
                        </div>
                        
                        <div>
                            <x-form.input
                                type="date"
                                id="end_date"
                                name="end_date"
                                label="End Date"
                                :value="request()->get('end_date')"
                                :error="$errors->first('end_date')"
                                x-bind:required="dateRangeType === 'custom'"
                            />
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Report Fields -->
            <div class="border-t border-gray-200 pt-6" x-data="{ reportType: '{{ request()->get('report_type', 'sales') }}' }">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Report Fields</h3>
                
                <!-- Sales Report Fields -->
                <div x-show="reportType === 'sales'" class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Group By</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="sales_group_by" value="day" class="focus:ring-sky-500 h-4 w-4 text-sky-600 border-gray-300" {{ request()->get('sales_group_by') === 'day' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Day</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="radio" name="sales_group_by" value="week" class="focus:ring-sky-500 h-4 w-4 text-sky-600 border-gray-300" {{ request()->get('sales_group_by') === 'week' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Week</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="radio" name="sales_group_by" value="month" class="focus:ring-sky-500 h-4 w-4 text-sky-600 border-gray-300" {{ request()->get('sales_group_by', 'month') === 'month' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Month</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Include Dimensions</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="sales_dimensions[]" value="product" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300" {{ in_array('product', request()->get('sales_dimensions', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Products</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="sales_dimensions[]" value="customer" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300" {{ in_array('customer', request()->get('sales_dimensions', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Customers</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="sales_dimensions[]" value="project" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300" {{ in_array('project', request()->get('sales_dimensions', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Projects</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="sales_dimensions[]" value="payment_method" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300" {{ in_array('payment_method', request()->get('sales_dimensions', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Payment Methods</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <x-form.select
                            id="project_id"
                            name="project_id"
                            label="Filter by Project (Optional)"
                            :options="$projects->pluck('name', 'id')->toArray()"
                            :value="request()->get('project_id')"
                            placeholder="All Projects"
                            :error="$errors->first('project_id')"
                        />
                    </div>
                </div>
                
                <!-- Customer Report Fields -->
                <div x-show="reportType === 'customer'" class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Customer Metrics</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="customer_metrics[]" value="orders" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300" {{ in_array('orders', request()->get('customer_metrics', ['orders'])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Order Count</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="customer_metrics[]" value="revenue" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300" {{ in_array('revenue', request()->get('customer_metrics', ['revenue'])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Revenue</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="customer_metrics[]" value="average_order" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300" {{ in_array('average_order', request()->get('customer_metrics', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Average Order Value</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="customer_metrics[]" value="last_order" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300" {{ in_array('last_order', request()->get('customer_metrics', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Last Order Date</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <x-form.select
                            id="customer_sort"
                            name="customer_sort"
                            label="Sort By"
                            :options="[
                                'revenue_desc' => 'Total Revenue (High to Low)',
                                'revenue_asc' => 'Total Revenue (Low to High)',
                                'orders_desc' => 'Order Count (High to Low)',
                                'orders_asc' => 'Order Count (Low to High)',
                                'recent_order' => 'Most Recent Order',
                                'name_asc' => 'Name (A-Z)',
                                'name_desc' => 'Name (Z-A)'
                            ]"
                            :value="request()->get('customer_sort', 'revenue_desc')"
                            :error="$errors->first('customer_sort')"
                        />
                    </div>
                </div>
                
                <!-- Product Report Fields -->
                <div x-show="reportType === 'product'" class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Product Metrics</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="product_metrics[]" value="units_sold" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300" {{ in_array('units_sold', request()->get('product_metrics', ['units_sold'])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Units Sold</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="product_metrics[]" value="revenue" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300" {{ in_array('revenue', request()->get('product_metrics', ['revenue'])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Revenue</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="product_metrics[]" value="avg_price" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300" {{ in_array('avg_price', request()->get('product_metrics', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Average Price</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="product_metrics[]" value="stock_levels" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300" {{ in_array('stock_levels', request()->get('product_metrics', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Stock Levels</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <x-form.select
                            id="category_id"
                            name="category_id"
                            label="Filter by Category (Optional)"
                            :options="$categories->pluck('name', 'id')->toArray()"
                            :value="request()->get('category_id')"
                            placeholder="All Categories"
                            :error="$errors->first('category_id')"
                        />
                    </div>
                </div>
                
                <!-- Invoice Report Fields -->
                <div x-show="reportType === 'invoice'" class="space-y-4">
                    <div>
                        <x-form.select
                            id="invoice_status"
                            name="invoice_status"
                            label="Filter by Status (Optional)"
                            :options="[
                                'all' => 'All Statuses',
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'paid' => 'Paid',
                                'partially_paid' => 'Partially Paid',
                                'overdue' => 'Overdue',
                                'cancelled' => 'Cancelled'
                            ]"
                            :value="request()->get('invoice_status', 'all')"
                            :error="$errors->first('invoice_status')"
                        />
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-form.select
                                id="invoice_age"
                                name="invoice_age"
                                label="Invoice Age (Optional)"
                                :options="[
                                    'all' => 'All Ages',
                                    'current' => 'Current',
                                    '1_to_30' => '1-30 days',
                                    '31_to_60' => '31-60 days',
                                    '61_to_90' => '61-90 days',
                                    'over_90' => 'Over 90 days'
                                ]"
                                :value="request()->get('invoice_age', 'all')"
                                :error="$errors->first('invoice_age')"
                            />
                        </div>
                        
                        <div>
                            <x-form.select
                                id="invoice_sort"
                                name="invoice_sort"
                                label="Sort By"
                                :options="[
                                    'date_desc' => 'Date (Newest First)',
                                    'date_asc' => 'Date (Oldest First)',
                                    'amount_desc' => 'Amount (Highest First)',
                                    'amount_asc' => 'Amount (Lowest First)',
                                    'due_date' => 'Due Date (Soonest First)'
                                ]"
                                :value="request()->get('invoice_sort', 'date_desc')"
                                :error="$errors->first('invoice_sort')"
                            />
                        </div>
                    </div>
                </div>
                
                <!-- Tax Report Fields -->
                <div x-show="reportType === 'tax'" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-form.select
                                id="tax_id"
                                name="tax_id"
                                label="Filter by Tax Rate (Optional)"
                                :options="$taxes->pluck('name', 'id')->toArray()"
                                :value="request()->get('tax_id')"
                                placeholder="All Tax Rates"
                                :error="$errors->first('tax_id')"
                            />
                        </div>
                        
                        <div>
                            <x-form.select
                                id="tax_group_by"
                                name="tax_group_by"
                                label="Group By"
                                :options="[
                                    'rate' => 'Tax Rate',
                                    'month' => 'Month',
                                    'quarter' => 'Quarter'
                                ]"
                                :value="request()->get('tax_group_by', 'month')"
                                :error="$errors->first('tax_group_by')"
                            />
                        </div>
                    </div>
                </div>
                
                <!-- Payment Report Fields -->
                <div x-show="reportType === 'payment'" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-form.select
                                id="payment_method_id"
                                name="payment_method_id"
                                label="Filter by Payment Method (Optional)"
                                :options="$paymentMethods->pluck('name', 'id')->toArray()"
                                :value="request()->get('payment_method_id')"
                                placeholder="All Payment Methods"
                                :error="$errors->first('payment_method_id')"
                            />
                        </div>
                        
                        <div>
                            <x-form.select
                                id="payment_group_by"
                                name="payment_group_by"
                                label="Group By"
                                :options="[
                                    'day' => 'Day',
                                    'week' => 'Week',
                                    'month' => 'Month',
                                    'payment_method' => 'Payment Method'
                                ]"
                                :value="request()->get('payment_group_by', 'month')"
                                :error="$errors->first('payment_group_by')"
                            />
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Chart Options -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Chart & Display Options</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-form.select
                            id="chart_type"
                            name="chart_type"
                            label="Chart Type"
                            :options="[
                                'bar' => 'Bar Chart',
                                'line' => 'Line Chart',
                                'pie' => 'Pie Chart',
                                'none' => 'No Chart (Table Only)'
                            ]"
                            :value="request()->get('chart_type', 'bar')"
                            :error="$errors->first('chart_type')"
                        />
                    </div>
                    
                    <div>
                        <x-form.input
                            type="number"
                            id="limit"
                            name="limit"
                            label="Results Limit"
                            :value="request()->get('limit', 20)"
                            min="1"
                            max="100"
                            help-text="Maximum number of rows to display"
                            :error="$errors->first('limit')"
                        />
                    </div>
                </div>
            </div>
            
            <!-- Report Name -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex items-center mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="save_report" value="1" class="focus:ring-sky-500 h-4 w-4 text-sky-600 rounded border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Save this report for future use</span>
                    </label>
                </div>
                
                <div>
                    <x-form.input
                        id="report_name"
                        name="report_name"
                        label="Report Name (if saving)"
                        :value="request()->get('report_name')"
                        :error="$errors->first('report_name')"
                    />
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <x-button 
                    href="{{ route('organization.reports.index') }}" 
                    variant="secondary"
                >
                    Cancel
                </x-button>
                
                <x-button 
                    type="submit" 
                    variant="primary"
                    icon="fas fa-chart-bar"
                >
                    Generate Report
                </x-button>
            </div>
        </form>
    </x-card>
</x-organization-layout>