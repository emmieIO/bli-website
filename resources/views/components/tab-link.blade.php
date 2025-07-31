@props([
    'label' => 'tab-link',
    'icon' => 'grid',
    'to' => '',
    'isActive' => false
])



<li class="me-2">
    <a href="{{ $to }}"
        aria-current="{{ $isActive ? 'page' : false }}"
        class="inline-flex space-x-3 items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 group {{ $isActive ? "border-b-teal-600 text-teal-600" : '' }}">
        <i data-lucide="{{ $icon }}" class="size-5"></i>
        <span>{{ $label }}</span>
        
    </a>
</li>
