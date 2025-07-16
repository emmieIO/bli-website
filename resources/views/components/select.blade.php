@props(['name', 'label' => '', 'options' => [], 'selected' => '', 'icon' => null, 'required' => false])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif

    <div class="mt-1 relative rounded-md shadow-sm">
        @if ($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="{{ $icon }}" class="w-5 h-5 text-gray-400"></i>
            </div>
        @endif

        <select name="{{ $name }}" id="{{ $name }}" @if($required) required @endif
            class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
            @foreach($options as $value => $display)
                <option value="{{ $value }}" {{ $value == old($name, $selected) ? 'selected' : '' }}>
                    {{ $display }}
                </option>
            @endforeach
        </select>
    </div>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
