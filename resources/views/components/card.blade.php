@props([
    'title' => null,
    'footer' => null,
    'headerActions' => null,
    'noPadding' => false
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm overflow-hidden']) }}>
    @if($title || $headerActions)
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            @if($title)
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $title }}
                </h3>
            @endif
            
            @if($headerActions)
                <div>
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    @endif
    
    <div @class(['p-6' => !$noPadding])>
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $footer }}
        </div>
    @endif
</div>