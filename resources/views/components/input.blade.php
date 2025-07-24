@props([
    'label' => '',
    'type' => 'text',
    'name',
    'value' => '',
    'required' => false,
    'icon' => '',
    'autofocus' => false,
    'disabled' => false,
])

<div class="space-y-1">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-900">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative rounded-md shadow-sm transition-all duration-150
                border border-gray-300 hover:border-teal-600
                focus-within:ring-2 focus-within:ring-teal-800/30 focus-within:border-teal-800
                {{ $disabled ? 'bg-gray-100 opacity-75' : 'bg-white' }}">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="{{ $icon }}" class="h-4 w-4 text-gray-500"></i>
            </div>
        @endif

        <input
            id="{{ $name }}"
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            {{ $required ? 'required' : '' }}
            {{ $autofocus ? 'autofocus' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => 'block w-full pl-'.($icon ? '10' : '3').' pr-3 py-2.5 text-gray-900 font-medium rounded-md border-none focus:ring-0 sm:text-sm placeholder-gray-400'
            ]) }}
        />
    </div>

    <x-input-error :messages="$errors->get($name)" class="mt-1" />
</div>
