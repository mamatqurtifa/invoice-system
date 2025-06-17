@props([
    'id' => Str::random(10),
    'maxWidth' => '2xl',
    'title' => null,
    'footer' => null,
    'closeButton' => true,
])

@php
    $maxWidthClass = match($maxWidth) {
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
        '5xl' => 'sm:max-w-5xl',
        '6xl' => 'sm:max-w-6xl',
        '7xl' => 'sm:max-w-7xl',
        default => 'sm:max-w-2xl',
    };
@endphp

<div
    x-data="{ show: false }"
    x-on:open-modal.window="$event.detail === '{{ $id }}' ? show = true : null"
    x-on:close-modal.window="$event.detail === '{{ $id }}' ? show = false : null"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    id="{{ $id }}"
    class="fixed inset-0 z-50 overflow-hidden"
    x-cloak
    style="display: none"
>
    <div 
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        aria-hidden="true"
        x-on:click="show = false"
    ></div>
    
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full {{ $maxWidthClass }} sm:my-8"
                x-on:click.stop=""
            >
                @if($title || $closeButton)
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        @if($title)
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $title }}
                            </h3>
                        @else
                            <div></div>
                        @endif
                        
                        @if($closeButton)
                            <button type="button" class="text-gray-400 hover:text-gray-500" x-on:click="show = false">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif
                    </div>
                @endif
                
                <div class="px-6 py-4">
                    {{ $slot }}
                </div>
                
                @if($footer)
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-2">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: modalId
        }));
    }
    
    function closeModal(modalId) {
        window.dispatchEvent(new CustomEvent('close-modal', {
            detail: modalId
        }));
    }
</script>