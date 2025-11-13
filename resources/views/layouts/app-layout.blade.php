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
        class="w-full sticky z-40 top-0 md:w-[inherit] md:ml-[280px] bg-white border-b border-primary-200 shadow-sm py-3 transition-all duration-300">
        <div class="px-6 py-2">
            <div class="flex items-center justify-between">
                <!-- Mobile Menu Button -->
                <div class="flex items-center justify-start gap-3">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar" type="button"
                        class="inline-flex items-center p-2 text-sm rounded-lg sm:hidden hover:bg-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all duration-200">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <a class="inline-flex items-center gap-2 font-bold text-sm text-primary bg-primary-200 px-2 py-3 rounded-lg hover:text-secondary"
                        href="{{ route('homepage') }}">
                        <i data-lucide="home" class="size-5"></i>
                        Go back Home
                    </a>
                </div>

                <!-- User Profile Dropdown -->
                <div class="flex items-center relative">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button"
                                class="flex text-sm bg-primary/10 rounded-full focus:ring-2 focus:ring-primary/50 hover:bg-primary/20 transition-all duration-200 group"
                                aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-full border-2 object-cover border-primary/20 group-hover:border-primary/40 transition-colors"
                                    src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=002147&color=fff' }}"
                                    alt="{{ auth()->user()->name }}">
                            </button>
                        </div>
                        <div class="z-50 absolute hidden my-4 text-base list-none divide-y divide-primary/20 rounded-xl shadow-2xl bg-white border border-primary-100 top-full right-0 min-w-[200px] overflow-hidden"
                            id="dropdown-user">
                            <div class="px-4 py-3 bg-primary/5" role="none">
                                <p class="text-sm font-semibold text-primary font-montserrat" role="none">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-sm text-primary/70 truncate font-lato" role="none">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="{{ route('homepage') }}"
                                        class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-primary/5 hover:text-primary transition-colors font-lato group"
                                        role="menuitem">
                                        <i data-lucide="home"
                                            class="w-4 h-4 text-primary/60 group-hover:text-primary"></i>
                                        Go back home
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile') }}"
                                        class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-primary/5 hover:text-primary transition-colors font-lato group"
                                        role="menuitem">
                                        <i data-lucide="user"
                                            class="w-4 h-4 text-primary/60 group-hover:text-primary"></i>
                                        Profile Settings
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="post">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center gap-2 w-full px-4 py-3 text-sm text-secondary hover:bg-secondary/5 hover:text-secondary transition-colors font-lato group cursor-pointer"
                                            role="menuitem">
                                            <i data-lucide="log-out"
                                                class="w-4 h-4 text-secondary/60 group-hover:text-secondary"></i>
                                            Sign out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
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
        lucide.createIcons();

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
        });
    </script>
</body>

</html>