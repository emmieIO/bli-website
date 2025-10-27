@props([
    'label' => '',
    'type' => 'text',
    'name',
    'value' => '',
    'required' => false,
    'icon' => '',
    'autofocus' => false,
    'disabled' => false,
    'readonly' => false,
])

<div class="space-y-1" id="{{ $name }}">
    <!-- Label -->
    @if ($label)
        <label for="{{ $name }}" class="inline-block text-sm font-medium text-gray-700 bg-white rounded-2xl px-1 font-lato">
            {{ $label }}
            @if ($required)
                <span class="text-secondary">*</span>
            @endif
        </label>
    @endif

    <!-- Input Container -->
    <div class="flex items-center p-2 rounded-lg transition-all duration-150 border border-gray-300 hover:border-primary focus-within:ring-2 focus-within:ring-primary-500/30 focus-within:border-primary {{ $disabled ? 'bg-gray-100 opacity-75 cursor-not-allowed' : 'bg-white' }}"
        @if ($type === 'password') x-data="{ show: false }" @endif>
        <!-- Icon -->
        @if ($icon)
            <div class="flex items-center pointer-events-none pl-2">
                <i data-lucide="{{ $icon }}" class="h-4 w-4 text-gray-500"></i>
            </div>
        @endif

        <!-- Input Field -->
        <input
            @if ($type === 'password') x-bind:type="show ? 'text' : 'password'"
            @else
                type="{{ $type }}" @endif
            name="{{ $name }}" value="{{ old($name, $value) }}" {{ $required ? 'required' : '' }}
            {{ $autofocus ? 'autofocus' : '' }} {{ $disabled ? 'disabled' : '' }} {{ $readonly ? 'readonly' : '' }}
            {{ $attributes->merge([
                'class' =>
                    'block w-full pl-' .
                    ($icon ? '3' : '3') .
                    ' pr-3 text-gray-900 font-lato rounded-lg border-none focus:ring-0 sm:text-sm placeholder-gray-400',
            ]) }} />

        <!-- Password Toggle Button -->
        @if ($type === 'password')
            <button type="button" tabindex="-1"
                class="flex items-center text-gray-500 hover:text-primary focus:outline-none pr-2"
                @click="show = !show">
                <i x-show="!show" data-lucide="eye" class="h-5 w-5"></i>
                <i x-show="show" data-lucide="eye-off" class="h-5 w-5"></i>
            </button>
        @endif
    </div>

    <!-- Error Message -->
    <x-input-error :messages="$errors->get($name)"  class="mt-1 text-secondary text-xs font-lato" />
</div>