@props([
    'disabled' => false,
    'label' => null,
    'error' => null,
    'id' => null,
    'name' => null,
    'helpText' => null,
    'checked' => false,
    'value' => null,
])

@php
    $id = $id ?? $name ?? Str::random(10);
    
    $checkboxClasses = 'rounded border-gray-300 text-sky-600 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-offset-0 focus:ring-opacity-50';
    
    if ($error) {
        $checkboxClasses .= ' border-red-300';
    }
    
    if ($disabled) {
        $checkboxClasses .= ' opacity-50 cursor-not-allowed';
    }
@endphp

<div class="flex items-start">
    <div class="flex items-center h-5">
        <input
            type="checkbox"
            id="{{ $id }}"
            @if($name) name="{{ $name }}" @endif
            @if($value) value="{{ $value }}" @endif
            {{ $checked ? 'checked' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => $checkboxClasses]) }}
        >
    </div>
    
    @if($label || $slot->isNotEmpty())
        <div class="ml-3 text-sm">
            @if($label)
                <label for="{{ $id }}" class="font-medium text-gray-700 {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}">
                    {{ $label }}
                </label>
            @else
                <label for="{{ $id }}" class="font-medium text-gray-700 {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}">
                    {{ $slot }}
                </label>
            @endif
            
            @if($helpText)
                <p class="text-gray-500">{{ $helpText }}</p>
            @endif
        </div>
    @endif
</div>

@if($error && !$helpText)
    <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
@endif