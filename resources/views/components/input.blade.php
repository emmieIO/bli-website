@props([
    'label' => '',
    "type" => "text",
    "name",
    "value" => '',
    "required" => false,
    'icon' => '',
    'autofocus' => false
])
    <div>
        <label for={{ $name }} class="block text-sm font-medium text-gray-700">{{ $label }}</label>
        <div class="mt-1 relative">
                <input
                id="{{ $name }}"
                type="{{ $type }}"
                name="{{ $name }}"
                {{ $required ? 'required' : '' }}
                {{ $autofocus ? 'autofocus':'' }}
                value="{{ old($name, $value) }}"
                {{ $attributes->merge(['class'=>"block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"]) }}
                 />
                <i data-lucide="{{ $icon }}" class="w-4 h-4 absolute left-3 top-2.5 text-gray-400"></i>
        </div>
        <x-input-error :messages="$errors->get($name)" />
    </div>
