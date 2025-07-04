@props([
    'align' => 'right',
    'width' => '48',
    'contentClasses' => 'py-1 bg-white',
    'trigger' => null,
])

@php
    $alignmentClasses = match($align) {
        'left' => 'origin-top-left left-0',
        'top' => 'origin-bottom-left bottom-full mb-2',
        'bottom' => 'origin-top-left top-full mt-2',
        'right', default => 'origin-top-right right-0',
    };

    $width = match($width) {
        '48' => 'w-48',
        'auto' => 'w-auto',
        default => 'w-' . $width,
    };
@endphp

<div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
        style="display: none;"
        @click="open = false"
        x-cloak
    >
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>