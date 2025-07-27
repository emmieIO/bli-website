@props(['name', 'options', 'selected' => []])

<div class="grid grid-cols-2 md:grid-cols-3 gap-3">
    @foreach ($options as $option)
        <div class="flex items-center">
            <input id="{{ $name }}_{{ Str::slug($option) }}" name="{{ $name }}[]" type="checkbox" value="{{ $option }}"
                class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded" {{ in_array($option, old($name, $selected)) ? 'checked' : '' }}>
            <label for="{{ $name }}_{{ Str::slug($option) }}" class="ml-2 text-sm text-gray-700 whitespace-nowrap">
                {{ $option }}
            </label>
        </div>
        @endforeach
</div>
