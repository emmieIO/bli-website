@props([
    'label' => '',
    'name',
    'options' => [],
    'value' => '',
    'required' => false,
    'icon' => '',
    'autofocus' => false,
    'disabled' => false,
])

<div class="space-y-1">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
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

        <select
            id="{{ $name }}"
            name="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $autofocus ? 'autofocus' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => 'block w-full pl-'.($icon ? '10' : '3').' pr-10 py-2.5 text-gray-900 rounded-md border-none focus:ring-0 sm:text-sm'
            ]) }}
        >
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>

        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    <x-input-error :messages="$errors->get($name)" class="mt-1" />
</div>
