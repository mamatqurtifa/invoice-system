@props([
    'type' => 'button',
    'color' => 'primary',
    'size' => 'md',
    'href' => null,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left'
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-opacity-50 rounded-lg';
    
    $sizes = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2 text-base',
        'xl' => 'px-6 py-3 text-base'
    ];
    
    $colors = [
        'primary' => 'bg-sky-600 hover:bg-sky-700 text-white focus:ring-sky-500',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
        'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
        'danger' => 'bg-rose-700 hover:bg-rose-800 text-white focus:ring-rose-600',
        'warning' => 'bg-yellow-400 hover:bg-yellow-500 text-gray-900 focus:ring-yellow-300',
        'info' => 'bg-blue-500 hover:bg-blue-600 text-white focus:ring-blue-400',
        'light' => 'bg-gray-100 hover:bg-gray-200 text-gray-800 focus:ring-gray-300',
        'dark' => 'bg-gray-800 hover:bg-gray-900 text-white focus:ring-gray-700',
        'outline-primary' => 'border border-sky-600 text-sky-600 hover:bg-sky-50 focus:ring-sky-500',
        'outline-danger' => 'border border-rose-700 text-rose-700 hover:bg-rose-50 focus:ring-rose-600',
        'outline-warning' => 'border border-yellow-400 text-yellow-700 hover:bg-yellow-50 focus:ring-yellow-300',
        'glass' => 'backdrop-blur-sm bg-white/30 border border-gray-200 hover:bg-white/50 text-gray-800 focus:ring-gray-300',
    ];
    
    $disabledClass = $disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '';
    
    $classes = $baseClasses . ' ' . $sizes[$size] . ' ' . $colors[$color] . ' ' . $disabledClass . ' ' . ($attributes->get('class') ?? '');
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon && $iconPosition === 'left')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        
        {{ $slot }}
        
        @if ($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @if($disabled) disabled @endif>
        @if ($icon && $iconPosition === 'left')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        
        {{ $slot }}
        
        @if ($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </button>
@endif