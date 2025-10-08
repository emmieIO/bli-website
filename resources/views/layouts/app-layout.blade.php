<!DOCTYPE html>
<html lang="en" x-data="{ mobileOpen: false }" x-cloak data-theme="mintlify">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BLI Dashboard</title>
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
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom scrollbar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(0, 33, 71, 0.2);
            border-radius: 10px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 33, 71, 0.4);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 font-lato relative min-h-screen">

    <!-- Navigation Bar -->
    <nav
        class="w-full sticky z-40 top-0 md:w-[inherit] md:ml-[280px] bg-white border-b border-primary-200 shadow-sm py-3 transition-all duration-300">
        <div class="px-6 py-2">
            <div class="flex items-center justify-between">
                <!-- Mobile Menu Button -->
                <div class="flex items-center justify-start">
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
                </div>

                <!-- User Profile Dropdown -->
                <div class="flex items-center relative">
                    <div class="flex items-center ms-3">
                        <div>
                            <button type="button"
                                class="flex text-sm bg-primary/10 rounded-full focus:ring-2 focus:ring-primary/50 hover:bg-primary/20 transition-all duration-200 group"
                                aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-full border-2 border-primary/20 group-hover:border-primary/40 transition-colors"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=002147&color=fff"
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
        class="fixed top-0 left-0 z-40 w-[280px] h-screen transition-transform -translate-x-full border-r border-primary-100 sm:translate-x-0 bg-white shadow-lg"
        aria-label="Sidebar">
        <div class="h-full overflow-y-auto sidebar-scroll">
            <!-- Logo Section -->
            <div
                class="flex items-center justify-between p-6 border-b border-primary-100 bg-gradient-to-r from-primary to-primary-700">
                <a href="{{ route('user_dashboard') }}" class="flex items-center gap-3 group">
                    <div
                        class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/30">
                        <i data-lucide="sparkles" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <span
                            class="self-center text-xl font-bold text-white font-montserrat whitespace-nowrap">BLI</span>
                        <p class="text-xs text-white/80 font-lato">Dashboard</p>
                    </div>
                </a>
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-white/80 rounded-lg sm:hidden hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-200">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar Content -->
            <div class="p-4">
                <x-sidebar />
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="p-6 sm:ml-[280px] min-h-screen bg-gradient-to-br from-gray-50 to-primary-50/30">
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-6 bg-secondary-50 border border-secondary-200 rounded-2xl shadow-sm"
                data-aos="fade-down">
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
    <x-confirm-modal />
    <x-feedback-modal />

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('scripts')
    <script>
        lucide.createIcons();

        // Save scroll position before unload
        window.addEventListener("beforeunload", function() {
            localStorage.setItem("scrollPosition", window.scrollY);
        });

        // Restore on load
        window.addEventListener("load", function() {
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
    </script>
</body>

</html>
