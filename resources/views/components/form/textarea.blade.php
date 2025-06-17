@props([
    'disabled' => false,
    'label' => null,
    'error' => null,
    'required' => false,
    'id' => null,
    'name' => null,
    'rows' => 3,
    'helpText' => null,
])

@php
    $id = $id ?? $name ?? Str::random(10);
    
    $textareaClasses = 'block w-full rounded-md border shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm';
    
    if ($error) {
        $textareaClasses .= ' border-red-300 text-red-900 placeholder-red-300';
    } else {
        $textareaClasses .= ' border-gray-300';
    }
    
    if ($disabled) {
        $textareaClasses .= ' disabled:bg-gray-100 disabled:text-gray-500';
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
    
    <textarea
        id="{{ $id }}"
        @if($name) name="{{ $name }}" @endif
        rows="{{ $rows }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => $textareaClasses]) }}
    >{{ $slot }}</textarea>
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
    
    @if($helpText)
        <p class="mt-1 text-xs text-gray-500">{{ $helpText }}</p>
    @endif
</div>