@props([
    'disabled' => false,
    'label' => null,
    'error' => null,
    'required' => false,
    'id' => null,
    'name' => null,
    'options' => [],
    'value' => null,
    'helpText' => null,
    'placeholder' => null,
])

@php
    $id = $id ?? $name ?? Str::random(10);
    
    $selectClasses = 'block w-full rounded-md border text-sm shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm';
    
    if ($error) {
        $selectClasses .= ' border-red-300 text-red-900 placeholder-red-300';
    } else {
        $selectClasses .= ' border-gray-300';
    }
    
    if ($disabled) {
        $selectClasses .= ' disabled:bg-gray-100 disabled:text-gray-500';
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
    
    <select
        id="{{ $id }}"
        @if($name) name="{{ $name }}" @endif
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => $selectClasses]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ ($value !== null && $value == $optionValue) ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
    
    @if($helpText)
        <p class="mt-1 text-xs text-gray-500">{{ $helpText }}</p>
    @endif
</div>