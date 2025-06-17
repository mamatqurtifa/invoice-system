@props(['striped' => false, 'hover' => true, 'responsive' => true])

@php
    $baseClasses = 'min-w-full divide-y divide-gray-200';
    $theadClasses = 'bg-gray-50';
    $thClasses = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider';
    $tbodyClasses = 'bg-white divide-y divide-gray-200';
    $trClasses = $hover ? 'transition-colors duration-150 ease-in-out' : '';
    $trHoverClasses = $hover ? 'hover:bg-gray-50' : '';
    $stripedEvenClasses = $striped ? 'bg-gray-50' : '';
    $tdClasses = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
@endphp

@if($responsive)
<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border border-gray-200 sm:rounded-lg">
@endif

<table class="{{ $baseClasses }}">
    @isset($header)
    <thead class="{{ $theadClasses }}">
        {{ $header }}
    </thead>
    @endisset

    <tbody class="{{ $tbodyClasses }}">
        {{ $slot }}
    </tbody>

    @isset($footer)
    <tfoot>
        {{ $footer }}
    </tfoot>
    @endisset
</table>

@if($responsive)
            </div>
        </div>
    </div>
</div>
@endif

@once
    @push('styles')
        <style>
            .table-row-even { @apply {{ $stripedEvenClasses }}; }
            .table-row { @apply {{ $trClasses }} {{ $trHoverClasses }}; }
            .table-heading { @apply {{ $thClasses }}; }
            .table-cell { @apply {{ $tdClasses }}; }
        </style>
    @endpush
    
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tableRows = document.querySelectorAll('tr');
                tableRows.forEach((row, index) => {
                    row.classList.add('table-row');
                    if (index % 2 === 1 && @json($striped)) {
                        row.classList.add('table-row-even');
                    }
                });
                
                const tableHeadings = document.querySelectorAll('th');
                tableHeadings.forEach(th => {
                    th.classList.add('table-heading');
                });
                
                const tableCells = document.querySelectorAll('td');
                tableCells.forEach(td => {
                    td.classList.add('table-cell');
                });
            });
        </script>
    @endpush
@endonce