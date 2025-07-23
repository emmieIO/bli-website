@props([
    'href' => '#',
    'icon' => 'home',
    'label' => 'Home',
    'isActive' => false,
])
<li>
    <a href="{{ $href }}"
        class="flex items-center space-x-1.5 text-sm py-2 px-3  text-white {{ $isActive ? 'bg-teal-500' : 'md:bg-transparent' }} rounded-md md:text-white md:py-2 md:px-3 md:dark:text-white"
        aria-current="{{ $isActive ? 'page' : false }}">
        <i data-lucide="{{ $icon }}" class="size-4"></i>
        <span>{{ $label }}</span>
    </a>
</li>
