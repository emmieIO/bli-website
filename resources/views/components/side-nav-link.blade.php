@props([
    "title" => "",
    "icon" => "grid-2x2",
    "to" => "#"
])

<li>
    <a href="{{ $to }}"
        class="flex items-center p-3 text-sm font-medium text-slate-300 rounded-xl transition-all duration-200 group hover:bg-slate-700/50 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 {{ request()->url() == url($to) ? 'bg-blue-600/20 text-blue-300 ring-2 ring-blue-500/50' : '' }}">
        <i data-lucide="{{ $icon }}"
            class="flex-shrink-0 w-5 h-5 text-slate-400 transition-colors duration-200 group-hover:text-blue-400 {{ request()->url() == url($to) ? 'text-blue-400' : '' }}"></i>
        <span class="ms-3 font-medium">{{ $title }}</span>
    </a>
</li>
