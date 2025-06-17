@props([
    'color' => 'gray',
    'size' => 'md',
    'pill' => false,
    'dot' => false,
])

@php
    // Badge color variants
    $colorClasses = [
        'gray' => 'bg-gray-100 text-gray-800',
        'red' => 'bg-red-100 text-red-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'green' => 'bg-green-100 text-green-800',
        'blue' => 'bg-blue-100 text-blue-800',
        'indigo' => 'bg-indigo-100 text-indigo-800',
        'purple' => 'bg-purple-100 text-purple-800',
        'pink' => 'bg-pink-100 text-pink-800',
        'sky' => 'bg-sky-100 text-sky-800',
    ];

    // Badge size variants
    $sizeClasses = [
        'sm' => 'px-1.5 py-0.5 text-xs',
        'md' => 'px-2.5 py-0.5 text-xs',
        'lg' => 'px-3 py-1 text-sm',
    ];

    $colorClass = $colorClasses[$color] ?? $colorClasses['gray'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $radiusClass = $pill ? 'rounded-full' : 'rounded';
    
    $classes = "inline-flex items-center font-medium {$colorClass} {$sizeClass} {$radiusClass}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="flex-shrink-0 inline-block h-1.5 w-1.5 rounded-full bg-current mr-1.5"></span>
    @endif
    {{ $slot }}
</span>