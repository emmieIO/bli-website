@props([
    "title" => "",
    "icon" => "grid-2x2",
    "to" => "#"
])

<li {{ $attributes->merge([
    'class' => "flex items-center gap-3 hover:bg-[#FF0000]/10 rounded-md py-2 px-3 transition duration-300 whitespace-nowrap"
]) }}>
    <!-- Icon -->
     <i data-lucide="{{ $icon }}" class="w-5 h-5 text-white"></i> <!-- Ensure icon is visible -->

    <!-- Title -->
    <a href="{{ $to }}"
       class="text-white font-medium text-sm hover:text-[#FF0000] transition duration-300 whitespace-nowrap">
        {{ $title }}
    </a>
</li>
