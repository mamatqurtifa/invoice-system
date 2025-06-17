@props([
    'headers' => [],
    'striped' => true,
    'hoverable' => true,
    'responsive' => true,
    'headerClass' => '',
    'bodyClass' => '',
    'footerSlot' => null,
    'emptyText' => 'No data available',
])

@php
    $responsiveClass = $responsive ? 'overflow-x-auto' : '';
    $theadClass = 'bg-gray-50';
    $tbodyClass = '';
    
    if ($striped) {
        $tbodyClass .= ' divide-y divide-gray-200';
    }
    
    $trClass = '';
    if ($hoverable) {
        $trClass .= ' hover:bg-gray-50 transition-colors duration-150';
    }
@endphp

<div {{ $attributes->merge(['class' => "bg-white shadow-sm rounded-lg overflow-hidden {$responsiveClass}"]) }}>
    <table class="min-w-full divide-y divide-gray-200">
        @if(count($headers) > 0)
            <thead class="{{ $theadClass }} {{ $headerClass }}">
                <tr>
                    @foreach($headers as $header)
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        
        <tbody class="{{ $tbodyClass }} {{ $bodyClass }}">
            {{ $slot }}
            
            @if(trim($slot) === '')
                <tr>
                    <td colspan="{{ count($headers) }}" class="px-6 py-4 text-center text-sm text-gray-500">
                        {{ $emptyText }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    
    @if($footerSlot)
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $footerSlot }}
        </div>
    @endif
</div>