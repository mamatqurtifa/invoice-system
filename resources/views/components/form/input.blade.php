@props([
    'disabled' => false,
    'label' => null,
    'error' => null,
    'required' => false,
    'id' => null,
    'name' => null,
    'type' => 'text',
    'helpText' => null,
    'leadingIcon' => null,
    'trailingIcon' => null,
    'prefix' => null,
    'suffix' => null,
])

@php
    $id = $id ?? $name ?? Str::random(10);
    
    $inputClasses = 'block w-full rounded-md text-sm shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm';
    
    if ($error) {
        $inputClasses .= ' border-red-300 text-red-900 placeholder-red-300';
    } else {
        $inputClasses .= ' border-gray-300';
    }
    
    if ($leadingIcon || $prefix) {
        $inputClasses .= ' pl-10';
    }
    
    if ($trailingIcon || $suffix) {
        $inputClasses .= ' pr-10';
    }
    
    if ($disabled) {
        $inputClasses .= ' disabled:bg-gray-100 disabled:text-gray-500';
    }
@endphp

<div>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative rounded-md">
        @if($leadingIcon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="{{ $leadingIcon }} text-gray-400"></i>
            </div>
        @elseif($prefix)
            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-gray-500 sm:text-sm">{{ $prefix }}</span>
            </div>
        @endif
        
        <input
            type="{{ $type }}"
            id="{{ $id }}"
            @if($name) name="{{ $name }}" @endif
            {{ $disabled ? 'disabled' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => $inputClasses]) }}
        >
        
        @if($trailingIcon)
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <i class="{{ $trailingIcon }} text-gray-400"></i>
            </div>
        @elseif($suffix)
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <span class="text-gray-500 sm:text-sm">{{ $suffix }}</span>
            </div>
        @endif
    </div>
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
    
    @if($helpText)
        <p class="mt-1 text-xs text-gray-500">{{ $helpText }}</p>
    @endif
</div>