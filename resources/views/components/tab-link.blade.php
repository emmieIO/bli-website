@props([
    'label' => 'tab-link',
    'icon' => 'grid',
    'to' => '',
    'isActive' => false
])

<li class="me-2">
    <a href="{{ $to }}"
        aria-current="{{ $isActive ? 'page' : false }}"
        class="inline-flex space-x-1 items-center justify-center p-3 text-sm font-medium border-b-2 rounded-t-lg transition-colors duration-300
            {{ $isActive
    ? 'border-b-[#FF0000] text-[#00275E]'
    : 'border-transparent text-gray-600 hover:text-[#FF0000] hover:border-b-[#FF0000]/50' }}">
        <i data-lucide="{{ $icon }}" class="size-4 {{ $isActive ? 'text-[#FF0000]' : 'text-gray-600 group-hover:text-[#FF0000]' }}"></i>
        <span>{{ $label }}</span>
    </a>
</li>
