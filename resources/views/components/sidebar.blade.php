<nav class="text-slate-300">
    <ul class="space-y-1">
        @foreach ($sideLinks as $link)
        @if (isset($link['children']))
        @canAny($link['permission'] ?? null)
            <li>
                <button type="button"
                    class="flex items-center w-full p-3 text-sm font-medium text-slate-300 transition-all duration-200 rounded-xl group hover:bg-slate-700/50 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                    aria-controls="dropdown-{{ Str::slug($link['title']) }}"
                    data-collapse-toggle="dropdown-{{ Str::slug($link['title']) }}">
                    <i data-lucide="{{ $link['icon'] }}"
                        class="flex-shrink-0 w-5 h-5 text-slate-400 transition-colors duration-200 group-hover:text-blue-400"></i>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ $link['title'] }}</span>
                    <i data-lucide="chevron-down"
                        class="w-4 h-4 text-slate-400 transition-all duration-200 group-hover:text-white transform group-hover:rotate-180"></i>
                </button>
                <ul id="dropdown-{{ Str::slug($link['title']) }}" class="hidden mt-2 space-y-1 ml-4">
                    @foreach ($link['children'] as $childLink)
                        @can($childLink['permission'] ?? null)
                            <li>
                                <a href="{{ route($childLink['route'] ?? '#') }}"
                                    class="flex items-center w-full p-2.5 text-sm text-slate-400 transition-all duration-200 rounded-lg hover:bg-slate-700/30 hover:text-white hover:translate-x-1 border-l-2 border-slate-600 hover:border-blue-400 pl-4">
                                    <span
                                        class="w-2 h-2 bg-slate-500 rounded-full mr-3 group-hover:bg-blue-400 transition-colors duration-200"></span>
                                    {{ $childLink['title'] }}
                                </a>
                            </li>
                        @endcan
                    @endforeach
                </ul>
            </li>
            @endcanAny
        @else
        @can($link['permission'] ?? null)
            <x-side-nav-link title="{{ $link['title'] }}" icon="{{ $link['icon'] }}" :to="route($link['route'])" />
        @endcan
        @endif
        @endforeach
    </ul>
</nav>