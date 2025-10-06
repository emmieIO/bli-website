<!DOCTYPE html>
<html lang="en" x-data="{ mobileOpen: false }" x-cloak data-theme="mintlify">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BLI Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f3f4f6;
        }

        ::-webkit-scrollbar-thumb {
            background: #00275e;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 font-outfit relative">

    <!-- Navigation Bar -->
    <nav class="w-full sticky top-0 md:w-[inherit] md:ml-[250px] bg-gray-100 border-b-1 border-orange-400 py-2">
        <div class="px-3 py-2 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <!-- Logo Section -->
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar" type="button"
                        class="inline-flex items-center p-2 text-sm rounded-lg sm:hidden hover:text-white hover:bg-orange-400 focus:outline-none focus:ring-2 focus:ring-[#FF0000]/50">
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
                                class="flex text-sm bg-[#FF0000]/20 rounded-full focus:ring-2 focus:ring-[#FF0000]/50"
                                aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=FF0000&color=fff"
                                    alt="{{ auth()->user()->name }}">
                            </button>
                        </div>
                        <div class="z-50 absolute hidden my-4 text-base list-none divide-y divide-[#FF0000]/20 rounded-sm shadow-md bg-[#00275E]"
                            id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-white" role="none">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-sm font-medium text-white truncate" role="none">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <a href="{{ route('homepage') }}"
                                        class="block px-4 py-2 text-sm text-white hover:bg-[#FF0000]/20"
                                        role="menuitem">Go back home</a>
                                </li>
                                <li>
                                    <a href="{{ route('profile') }}"
                                        class="block px-4 py-2 text-sm text-white hover:bg-[#FF0000]/20"
                                        role="menuitem">Profile Settings</a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="post">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full px-4 py-2 text-sm text-white hover:bg-[#FF0000]/20 cursor-pointer"
                                            role="menuitem">Sign out</button>
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
        class="fixed top-0 left-0 z-40 w-[250px] h-screen transition-transform -translate-x-full border-r border-[#FF0000]/20 sm:translate-x-0 bg-gray-200"
        aria-label="Sidebar">
        <div class="h-full overflow-y-auto">
            <div class="flex items-center justify-between p-4 border-b-1 border-orange-400">
                <a href="{{ route('user_dashboard') }}" class="flex ms-2 md:me-24">
                    <img src="{{ asset('images/logo.jpg') }}" class="h-8 me-3" alt="FlowBite Logo" />
                    <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap">BLI</span>
                </a>
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-700 rounded-lg sm:hidden hover:bg-[#FF0000]/20 focus:outline-none focus:ring-2 focus:ring-[#FF0000]/50">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                    </svg>
                </button>
            </div>
            <x-sidebar />
        </div>
    </aside>

    <!-- Main Content -->
    <div class="p-4 sm:ml-[250px]">
        @if ($errors->any())
            <div class="mb-4 p-6 bg-red-50 border border-red-200 shadow-md rounded-lg text-red-700">
                <div class="flex items-center gap-3 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 9c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-red-800">Whoops!</h3>
                </div>
                <p class="text-sm mb-2">Please fix the following issues:</p>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{ $slot }}
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
                localStorage.removeItem("scrollPosition"); // optional
            }
        });
    </script>
</body>

</html>
