@props([
    'type' => 'button',
    'variant' => 'primary', 
    'size' => 'md', 
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'fullWidth' => false,
])

@php
    // Button variant styles
    $variantClasses = [
        'primary' => 'bg-sky-600 hover:bg-sky-700 focus:ring-sky-500 text-white border-transparent',
        'secondary' => 'bg-white hover:bg-gray-50 focus:ring-sky-500 text-gray-700 border-gray-300 shadow-sm',
        'danger' => 'bg-rose-600 hover:bg-rose-700 focus:ring-rose-500 text-white border-transparent',
        'success' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white border-transparent',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500 text-white border-transparent',
        'plain' => 'hover:bg-gray-100 text-gray-700 border-transparent',
    ];

    // Button size styles
    $sizeClasses = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
        'xl' => 'px-6 py-3 text-lg',
    ];

    $classes = 'inline-flex items-center justify-center border rounded-md font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200';
    $variantClass = $variantClasses[$variant] ?? $variantClasses['primary'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $widthClass = $fullWidth ? 'w-full' : '';
    
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => $classes . ' ' . $variantClass . ' ' . $sizeClass . ' ' . $disabledClasses . ' ' . $widthClass
    ]) }}
    @if($disabled) disabled @endif
>
    @if($icon && $iconPosition === 'left')
        <i class="{{ $icon }} mr-2"></i>
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        <i class="{{ $icon }} ml-2"></i>
    @endif
</button>