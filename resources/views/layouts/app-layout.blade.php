<!DOCTYPE html>
<html lang="en" x-data="{ mobileOpen: false }" x-cloak>

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
</head>

<body class="font-[Outfit] bg-gray-50 text-gray-800" >

    <div class="dashboard_layout">
        <div class="content__container">
            <!-- header -->
            <div class="top-nav__container bg-teal-800">
                <button  x-on:click="mobileOpen = !mobileOpen" class="toggle-btn lg:hidden ">
                    <i data-lucide="menu" class="cursor-pointer"></i>
                </button>
                <div class="relative w-[30px] h-[30px] border-2 rounded-full cursor-pointer"
                    x-data="{ profilePopup: false }"
                    @click.outside="profilePopup = false"
                    @keydown.escape.window="profilePopup = false">
                    <!-- Profile Image -->
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}" alt="Profile"
                        class="w-full h-full object-cover rounded-full ring-4 ring-white shadow-lg"
                        @click="profilePopup = !profilePopup">

                    <!-- Dropdown -->
                    <div
                        x-show="profilePopup"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        x-cloak
                        class="absolute right-0 mt-2 w-48 bg-white text-sm text-gray-700 rounded-md shadow-lg p-4 z-50">
                        <!-- Example Content -->
                        <p class="font-semibold">{{ auth()->user()->name }}</p>
                        <a href="{{ route('profile') }}" class="block mt-2 hover:underline text-teal-600">View
                            Profile</a>
                        <a href="{{ route('homepage') }}" class="block mt-2 hover:underline text-teal-600">Go back home</a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="text-red-600 hover:underline">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="content__parent">
                {{ $slot }}
            </div>
        </div>
        <!-- sidebar-->
        <x-sidebar />
    </div>

    <x-toast />
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // lucide.createIcons();
    </script>
</body>

</html>
