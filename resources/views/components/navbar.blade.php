<nav x-data="{ mainNavOpen: false, joinMenuOpen: false }"
    @keydown.escape.window="mainNavOpen = false; joinMenuOpen = false"
    class="top-0 sticky z-50 w-full border-b border-[#FF0000]/20 shadow-sm bg-[#00275E]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('homepage') }}" class="inline-flex items-center" aria-label="Beacon Leadership Institute home">
                    <img src="{{ asset('images/logo.jpg') }}" class="h-10 w-auto object-cover"
                        alt="Beacon Leadership Institute logo" />
                </a>
            </div>

            <!-- Desktop Nav Links -->
            <div class="hidden lg:flex lg:items-center lg:gap-8 text-white">
                <ul class="flex items-center gap-6 text-sm font-medium text-white">
                    <li>
                        <a href="{{ route('homepage') }}"
                            @class(['hover:text-[#FF0000] transition', 'text-[#FF0000] font-semibold' => request()->routeIs('homepage')])>
                            <span class="inline-flex items-center gap-1"><i data-lucide="home" class="w-4 h-4"></i>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('events.index') }}"
                            @class(['hover:text-[#FF0000] transition', 'text-[#FF0000] font-semibold' => request()->routeIs('events.*')])>
                            <span class="inline-flex items-center gap-1"><i data-lucide="calendar" class="w-4 h-4"></i>Events</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}"
                            @class(['hover:text-[#FF0000] transition', 'text-[#FF0000] font-semibold' => request()->routeIs('courses.*')])>
                            <span class="inline-flex items-center gap-1"><i data-lucide="book-open" class="w-4 h-4"></i>Courses</span>
                        </a>
                    </li>

                    <!-- JOIN DROPDOWN (Desktop) -->
                    <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button type="button"
                        class="inline-flex items-center gap-1 hover:text-[#FF0000] transition focus:outline-none focus-visible:ring focus-visible:ring-[#FF0000]/50 rounded"
                        @click.prevent="open = !open" :aria-expanded="open.toString()" aria-haspopup="menu">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        <span>Join Us</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-150"
                        :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-cloak x-show="open" x-transition.origin.top.left @click.away="open = false"
                    class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-[#00275E] ring-1 ring-black/5 divide-y divide-gray-100 text-sm z-50 text-white"
                    role="menu">
                    <a href="{{ route('instructors.become-an-instructor') }}"
                    class="block px-4 py-2 hover:bg-[#FF0000]/20 hover:text-[#FF0000]" role="menuitem">Become an Instructor</a>
                    @guest
                    <a href="{{ route('register') }}"
                        class="block px-4 py-2 hover:bg-[#FF0000]/20 hover:text-[#FF0000]" role="menuitem">Become a Student
                        </a>
                        @endguest
                        </div>
                    </li>

                    <li>
                        <a href="#" class="hover:text-[#FF0000] transition">
                            <span class="inline-flex items-center gap-1"><i data-lucide="users" class="w-4 h-4"></i>The Team</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-[#FF0000] transition">
                            <span class="inline-flex items-center gap-1"><i data-lucide="pen-line" class="w-4 h-4"></i>Blog</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact-us') }}"
                            @class(['hover:text-[#FF0000] transition', 'text-[#FF0000] font-semibold' => request()->routeIs('contact-us')])>
                            <span class="inline-flex items-center gap-1"><i data-lucide="mail" class="w-4 h-4"></i>Contact</span>
                        </a>
                    </li>
                </ul>

                <!-- Auth CTA -->
                <div class="pl-4 border-l border-[#FF0000]/20">
                    @auth
                        <a href="{{ route('user_dashboard') }}"
                            class="inline-flex items-center gap-2 text-sm hover:text-[#FF0000] transition">
                            <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 text-sm hover:text-[#FF0000] transition">
                            <i data-lucide="log-in" class="h-4 w-4"></i>
                            <span>Login</span>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="lg:hidden flex items-center">
                <button type="button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-[#FF0000] hover:bg-[#FF0000]/10 focus:outline-none focus-visible:ring focus-visible:ring-[#FF0000]/50"
                    aria-label="Toggle navigation menu" @click="mainNavOpen = !mainNavOpen"
                    :aria-expanded="mainNavOpen.toString()">
                    <i x-show="!mainNavOpen" data-lucide="menu" class="w-6 h-6"></i>
                    <i x-show="mainNavOpen" data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Panel -->
    <div x-cloak x-show="mainNavOpen" x-transition class="lg:hidden border-t border-[#FF0000]/20 bg-white shadow-inner">
        <div class="px-4 pt-4 pb-6 space-y-4 text-sm">
            <a href="{{ route('homepage') }}"
                class="flex items-center gap-2 py-2 text-[#00275E] hover:text-[#FF0000]">
                <i data-lucide="home" class="w-4 h-4"></i>Home
            </a>
            <a href="{{ route('events.index') }}"
                class="flex items-center gap-2 py-2 text-[#00275E] hover:text-[#FF0000]">
                <i data-lucide="calendar" class="w-4 h-4"></i>Events
            </a>
            <a href="{{ route('courses.index') }}"
                class="flex items-center gap-2 py-2 text-[#00275E] hover:text-[#FF0000]">
                <i data-lucide="book-open" class="w-4 h-4"></i>Courses
            </a>

            <!-- JOIN DROPDOWN (Mobile accordion style) -->
            @guest()
            <div x-data="{ open: false }" class="border-y border-[#FF0000]/20 py-2">
                <button type="button"
                    class="w-full flex items-center justify-between gap-2 py-2 text-left text-[#00275E] hover:text-[#FF0000] focus:outline-none"
                    @click="open = !open" :aria-expanded="open.toString()">
                    <span class="inline-flex items-center gap-2"><i data-lucide="user-plus" class="w-4 h-4"></i>Join</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-150"
                        :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-cloak x-show="open" x-collapse class="mt-2 pl-6 space-y-2">
                    <a href="{{ route("instructors.become-an-instructor") }}"
                        class="block py-1 text-[#00275E] hover:text-[#FF0000]">Become an Instructor</a>
                    <a href="{{ route('register') }}"
                        class="block py-1 text-[#00275E] hover:text-[#FF0000]">Become a Student</a>
                </div>
            </div>
            @endguest

            <a href="#"
                class="flex items-center gap-2 py-2 text-[#00275E] hover:text-[#FF0000]">
                <i data-lucide="users" class="w-4 h-4"></i>The Team
            </a>
            <a href="#"
                class="flex items-center gap-2 py-2 text-[#00275E] hover:text-[#FF0000]">
                <i data-lucide="pen-line" class="w-4 h-4"></i>Blog
            </a>
            <a href="{{ route('contact-us') }}"
                class="flex items-center gap-2 py-2 text-[#00275E] hover:text-[#FF0000]">
                <i data-lucide="mail" class="w-4 h-4"></i>Contact
            </a>

            <div class="pt-4 border-t border-[#FF0000]/20">
                @auth
                    <a href="{{ route('user_dashboard') }}"
                        class="flex items-center gap-2 py-2 text-[#00275E] hover:text-[#FF0000]">
                        <i data-lucide="grid" class="h-4 w-4"></i>Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-2 py-2 text-[#00275E] hover:text-[#FF0000]">
                        <i data-lucide="log-in" class="h-4 w-4"></i>Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
