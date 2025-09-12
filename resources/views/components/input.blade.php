@props([
    'label' => '',
    'type' => 'text',
    'name',
    'value' => '',
    'required' => false,
    'icon' => '',
    'autofocus' => false,
    'disabled' => false,
    'readonly' => false
])

<div class="space-y-1" id="{{ $name }}">
    <!-- Label -->
    @if($label)
        <label for="{{ $name }}" class="inline-block text-sm font-medium text-gray-900 bg-white rounded-2xl px-1">
            {{ $label }}
            @if($required)
                <span class="text-[#FF0000]">*</span>
            @endif
        </label>
    @endif

    <!-- Input Container -->
    <div 
        class="relative rounded-md shadow-sm transition-all duration-150 border border-gray-300 hover:border-[#00275E] focus-within:ring-2 focus-within:ring-[#00275E]/30 focus-within:border-[#00275E] {{ $disabled ? 'bg-gray-100 opacity-75 cursor-not-allowed' : 'bg-white' }}"
        @if($type === 'password') x-data="{ show: false }" @endif
    >
        <!-- Icon -->
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="{{ $icon }}" class="h-4 w-4 text-gray-500"></i>
            </div>
        @endif

        <!-- Input Field -->
        <input
            @if($type === 'password')
                x-bind:type="show ? 'text' : 'password'"
            @else
                type="{{ $type }}"
            @endif
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            {{ $required ? 'required' : '' }}
            {{ $autofocus ? 'autofocus' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes->merge([
                'class' => 'block w-full pl-'.($icon ? '10' : '3').' pr-10 py-2.5 text-gray-900 font-medium rounded-md border-none focus:ring-0 sm:text-sm placeholder-gray-400'
            ]) }}
        />

        <!-- Password Toggle Button -->
        @if($type === 'password')
            <button
                type="button"
                tabindex="-1"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-[#00275E] focus:outline-none"
                @click="show = !show"
            >
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.249-2.383A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.293 5.032M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 6L6 6" />
                </svg>
            </button>
        @endif
    </div>

    <!-- Error Message -->
    <x-input-error :messages="$errors->get($name)" class="mt-1 text-[#FF0000] text-xs" />
</div>
