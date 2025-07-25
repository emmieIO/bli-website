@props([
    'label' => 'Dropdown button',
    'items' => [
        ['label' => 'Dashboard', 'href' => '#'],
        ['label' => 'Settings', 'href' => '#'],
        ['label' => 'Earnings', 'href' => '#'],
        ['label' => 'Sign out', 'href' => '#'],
    ],
])

<button id="{{ $attributes->get('id', 'dropdownDefaultButton') }}" data-dropdown-toggle="dropdown"
    {{ $attributes->merge(['class' => 'text-white bg-teal-900 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-teal-900 dark:hover:bg-teal-700 dark:focus:ring-teal-800']) }}
    type="button">
    {{ $label }}
    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
        fill="none" viewBox="0 0 10 6">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
    </svg>
</button>

<!-- Dropdown menu -->
<div id="dropdown" class="z-10 hidden divide-y divide-gray-100 rounded-lg shadow-sm w-44 bg-teal-700">
    <ul class="py-2 text-sm text-gray-200" aria-labelledby="{{ $attributes->get('id', 'dropdownDefaultButton') }}">
        @foreach ($items as $item)
            <li>
                <a href="{{ $item['href'] ?? '#' }}"
                    class="block px-4 py-2 hover:bg-teal-100 dark:hover:bg-teal-600 dark:hover:text-white">
                    {{ $item['label'] ?? '' }}
                </a>
            </li>
        @endforeach
    </ul>
</div>