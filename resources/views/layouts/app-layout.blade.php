<!DOCTYPE html>
<html lang="en" x-data="{ mobileOpen: false }" x-cloak data-theme="mintlify">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'BLI' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.jpg') }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Lato:wght@300;400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>


</head>

<body class="bg-gray-50 text-gray-800 font-lato relative min-h-screen">

    <!-- Navigation Bar -->
    <nav
        class="w-full sticky z-40 top-0 md:w-[inherit] md:ml-64 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-lg transition-all duration-300">
        <div class="px-4 lg:px-6 py-3">
            <div class="flex items-center justify-between">
                <!-- Left Section: Mobile Menu + Breadcrumbs -->
                <div class="flex items-center justify-start gap-4">
                    <!-- Mobile Menu Button -->
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar" type="button"
                        class="inline-flex items-center p-2 text-slate-600 rounded-xl sm:hidden hover:bg-primary-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-primary-500/50 transition-all duration-200 shadow-sm">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>

                    <!-- Breadcrumb Navigation -->
                    <nav class="hidden sm:flex items-center space-x-2 text-sm" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li>
                                <a href="{{ route('user_dashboard') }}"
                                    class="flex items-center text-slate-500 hover:text-primary-600 transition-colors duration-200">
                                    <i data-lucide="home" class="w-4 h-4 mr-1"></i>
                                    <span class="font-medium">Dashboard</span>
                                </a>
                            </li>
                            @if(request()->routeIs('admin.*'))
                                <li>
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                                </li>
                                <li>
                                    <span class="text-slate-700 font-medium">Admin</span>
                                </li>
                            @endif
                            @if(request()->routeIs('instructor.*'))
                                <li>
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                                </li>
                                <li>
                                    <span class="text-slate-700 font-medium">Instructor</span>
                                </li>
                            @endif
                        </ol>
                    </nav>
                </div>

                <!-- Center Section: Search Bar (Desktop) -->
                <div class="hidden lg:flex flex-1 max-w-lg mx-8">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                        </div>
                        <input type="search"
                            class="block w-full pl-10 pr-4 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder:text-slate-400"
                            placeholder="Search events, courses, speakers..." />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <kbd
                                class="px-2 py-1 text-xs font-medium text-slate-400 bg-slate-100 border border-slate-200 rounded-md">
                                ⌘K
                            </kbd>
                        </div>
                    </div>
                </div>

                <!-- Right Section: Actions + Profile -->
                <div class="flex items-center gap-3">
                    <!-- Quick Actions -->
                    <div class="hidden md:flex items-center gap-2">
                        <a href="{{ route('homepage') }}"
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-primary-600 hover:text-white transition-all duration-200 shadow-sm"
                            title="Visit Website">
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                            <span class="hidden lg:inline">Website</span>
                        </a>

                        <!-- Notifications -->
                        <button
                            class="relative p-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors duration-200"
                            title="Notifications">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                        </button>
                    </div>

                    <!-- Mobile Search Toggle -->
                    <button
                        class="p-2 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors duration-200 lg:hidden"
                        onclick="toggleMobileSearch()" title="Search">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative">
                        <button type="button"
                            class="flex items-center gap-2 p-1.5 text-sm bg-slate-50 rounded-xl focus:ring-2 focus:ring-primary-500/50 hover:bg-slate-100 transition-all duration-200 group"
                            aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <img class="w-8 h-8 rounded-lg object-cover border border-slate-200 group-hover:border-primary-300 transition-colors"
                                src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=1e293b&color=fff' }}"
                                alt="{{ auth()->user()->name }}">
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold text-slate-700 leading-tight">
                                    {{ Str::limit(auth()->user()->name, 12) }}
                                </p>
                                <p class="text-xs text-slate-500 leading-tight">
                                    {{ Str::limit(auth()->user()->email, 20) }}
                                </p>
                            </div>
                            <i data-lucide="chevron-down"
                                class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="z-50 absolute hidden mt-2 text-base list-none bg-white divide-y divide-slate-100 rounded-xl shadow-xl border border-slate-200 top-full right-0 min-w-[220px] overflow-hidden"
                            id="dropdown-user">
                            <div class="px-4 py-3 bg-gradient-to-r from-primary-50 to-slate-50" role="none">
                                <p class="text-sm font-semibold text-slate-800" role="none">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-sm text-slate-600 truncate" role="none">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                            <ul class="py-2" role="none">
                                <li>
                                    <a href="{{ route('homepage') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary-600 transition-colors group"
                                        role="menuitem">
                                        <i data-lucide="external-link"
                                            class="w-4 h-4 text-slate-400 group-hover:text-primary-600"></i>
                                        Visit Website
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary-600 transition-colors group"
                                        role="menuitem">
                                        <i data-lucide="user"
                                            class="w-4 h-4 text-slate-400 group-hover:text-primary-600"></i>
                                        Profile Settings
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary-600 transition-colors group"
                                        role="menuitem">
                                        <i data-lucide="bell"
                                            class="w-4 h-4 text-slate-400 group-hover:text-primary-600"></i>
                                        Notifications
                                    </a>
                                </li>
                                <li class="border-t border-slate-100 mt-1 pt-1">
                                    <form action="{{ route('logout') }}" method="post">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors group"
                                            role="menuitem">
                                            <i data-lucide="log-out" class="w-4 h-4 text-red-500"></i>
                                            Sign out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Search Bar (Hidden by default) -->
            <div id="mobile-search" class="lg:hidden mt-4 pt-4 border-t border-slate-200 hidden">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                    </div>
                    <input type="search"
                        class="block w-full pl-10 pr-4 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder:text-slate-400"
                        placeholder="Search events, courses, speakers..." />
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-gradient-to-b from-slate-900 to-slate-800 shadow-2xl"
        aria-label="Sidebar">
        <div class="h-full px-4 py-6 overflow-y-auto">
            <!-- Logo Section -->
            <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-700/50">
                <a href="{{ route('user_dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <img src="{{ asset('images/logo.jpg') }}"
                            class="h-10 w-10 rounded-xl shadow-lg ring-2 ring-white/10 group-hover:ring-white/20 transition-all duration-300"
                            alt="BLI Logo" />
                        <div
                            class="absolute inset-0 rounded-xl bg-gradient-to-tr from-transparent to-white/5 group-hover:to-white/10 transition-all duration-300">
                        </div>
                    </div>
                    <span
                        class="text-white font-bold text-lg tracking-tight group-hover:text-blue-300 transition-colors duration-300">BLI</span>
                </a>
                <!-- Mobile close button -->
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-slate-400 rounded-lg sm:hidden hover:bg-slate-700/50 hover:text-white focus:outline-none focus:ring-2 focus:ring-slate-600 transition-all duration-200">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <div class="space-y-1">
                <x-sidebar />
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="p-4 sm:ml-64">
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-6 bg-secondary-50 border border-secondary-200 rounded-2xl shadow-sm" data-aos="fade-down">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-secondary/20 rounded-xl flex items-center justify-center">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-secondary"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-secondary-800 font-montserrat mb-2">Please check the
                            following issues:</h3>
                        <ul class="list-disc list-inside space-y-1 text-secondary-700 font-lato">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()"
                        class="flex-shrink-0 text-secondary/60 hover:text-secondary transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Main Content Slot -->
        <div data-aos="fade-up" data-aos-duration="600">
            {{ $slot }}
        </div>
    </div>

    <!-- Toast Notifications -->
    <x-toast />
    {{-- <x-confirm-modal />
    <x-feedback-modal /> --}}

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('scripts')
    <script>
        // Wait for DOM to be ready and Lucide to load
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Save scroll position before unload
        window.addEventListener("beforeunload", function () {
            localStorage.setItem("scrollPosition", window.scrollY);
        });

        // Restore on load
        window.addEventListener("load", function () {
            const scroll = localStorage.getItem("scrollPosition");
            if (scroll !== null) {
                window.scrollTo(0, parseInt(scroll));
                localStorage.removeItem("scrollPosition");
            }
        });

        // Initialize AOS if available
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 600,
                once: true,
                offset: 100
            });
        }

        // Mobile search toggle function
        function toggleMobileSearch() {
            const mobileSearch = document.getElementById('mobile-search');
            mobileSearch.classList.toggle('hidden');

            if (!mobileSearch.classList.contains('hidden')) {
                // Focus on search input when opened
                const searchInput = mobileSearch.querySelector('input[type="search"]');
                setTimeout(() => searchInput.focus(), 100);
            }
        }

        // Keyboard shortcut for search (⌘K or Ctrl+K)
        document.addEventListener('keydown', function (e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[type="search"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });

        // Mobile sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('logo-sidebar');
            const toggleButtons = document.querySelectorAll('[data-drawer-toggle="logo-sidebar"]');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    sidebar.classList.toggle('-translate-x-full');
                });
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function (event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = Array.from(toggleButtons).some(btn => btn.contains(event.target));

                if (!isClickInsideSidebar && !isClickOnToggle && window.innerWidth < 640) {
                    sidebar.classList.add('-translate-x-full');
                }
            });

            // Handle window resize
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 640) {
                    sidebar.classList.remove('-translate-x-full');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });

            // Enhanced dropdown functionality
            const userDropdown = document.getElementById('dropdown-user');
            const dropdownToggle = document.querySelector('[data-dropdown-toggle="dropdown-user"]');

            if (dropdownToggle && userDropdown) {
                dropdownToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function (e) {
                    if (!userDropdown.contains(e.target) && !dropdownToggle.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>

</html>