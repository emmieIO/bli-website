@props(['name', 'label' => '', 'options' => [], 'selected' => '', 'icon' => null, 'required' => false])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif

    <div
        class="rounded-md mt-1 flex border-gray-200 border p-2 focus:ring-2 focus:ring-orange-600 focus:border-orange-600 sm:text-sm">
        @if ($icon)
            <div class="flex items-center pointer-events-none">
                <i data-lucide="{{ $icon }}" class="w-5 h-5 text-gray-400 "></i>
            </div>
        @endif

        <select name="{{ $name }}" id="{{ $name }}" @if ($required) required @endif
            class="block w-full @if ($icon)  @endif border border-none rounded-md focus:ring-0 focus:border-transparent sm:text-sm">
            @foreach ($options as $value => $display)
                <option value="{{ $value }}" {{ $value == old($name, $selected) ? 'selected' : '' }}>
                    {{ $display }}
                </option>
            @endforeach
        </select>
    </div>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
