@props([
    "title" => "",
    "icon" => "grid-2x2",
    "to" => "#"
])

<li {{ $attributes->merge([
    'class' => "flex items-center gap-3 hover:text-[#FFF] rounded-md px-3 transition duration-300 whitespace-nowrap"
]) }}>
    <!-- Icon -->
    
    <!-- Title -->
    <a href="{{ $to }}"

    class="text-gray-700 hover:bg-orange-500 flex items-center w-full px-2 py-2 rounded-md text-md gap-2 font-medium hover:text-white transition duration-300 whitespace-nowrap {{ request()->url() == url($to) ? 'bg-orange-500 text-white' : '' }}">
        <span>
            <i data-lucide="{{ $icon }}" class="size-5 rounded-md hover:text-white"></i>
        </span>
        <span>
        {{ $title }}
        </span>
    </a>
</li>
