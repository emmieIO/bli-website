@props([
    "title" => "",
    "icon" => "grid-2x2",
    "to" => ""
])

<li  {{ $attributes->merge([
    'class' => "flex items-center-safe gap-3 hover:bg-teal-900 rounded-md py-2 px-2 whitespace-nowrap"
]) }}>
    <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
    <a href="{{$to}}" class="whitespace-nowrap">{{ $title }}</a>
</li>