@props([
    'name',
    'label' => '',
    'checked' => false,
    'disabled' => false,
])

<div class="flex items-center">
    <input
        type="checkbox"
        id="{{ $name }}"
        name="{{ $name }}"
        value="1"
        @checked(old($name, $checked))
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded']) }}
    >
    @if($label)
        <label for="{{ $name }}" class="ml-2 block text-sm text-white">{{ $label }}</label>
    @endif
</div>
