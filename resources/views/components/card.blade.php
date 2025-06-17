@props(['title' => null, 'footer' => null, 'glass' => false, 'padding' => 'p-6', 'headerClass' => '', 'bodyClass' => '', 'footerClass' => ''])

@php
    $baseCardClass = 'rounded-xl shadow-sm overflow-hidden';
    $glassEffect = $glass ? 'backdrop-blur-md bg-white/70 border border-white/30' : 'bg-white border border-gray-100';
@endphp

<div {{ $attributes->merge(['class' => "$baseCardClass $glassEffect"]) }}>
    @if ($title)
        <div class="border-b border-gray-100 px-6 py-4 {{ $headerClass }}">
            <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
        </div>
    @endif

    <div class="{{ $padding }} {{ $bodyClass }}">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="bg-gray-50 px-6 py-4 {{ $footerClass }}">
            {{ $footer }}
        </div>
    @endif
</div>