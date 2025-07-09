<!DOCTYPE html>
<html lang="en" x-data="{mainNavOpen:false}" x-cloak>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Beacon Leadership Institute</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-[Outfit] text-gray-800 bg-white min-h-screen">
<div class="preload">
   <div>
    <img src="{{ asset("images/logo.jpg") }}" class="w-[100px] h-[100px] rounded-full animate-scale border-2 border-black" alt=""/>
   </div>
</div>
    <!-- Top Contact Bar -->
    <header class="bg-gray-100 text-sm text-gray-700">
        <div class="flex flex-col md:flex-row md:justify-between items-center gap-2 px-4 py-2 max-w-7xl mx-auto">
            <p class="flex items-center gap-1"><i data-lucide="mail" class="w-4 h-4"></i> <a
                    href="mailto:info@beaconleadership.org" class="hover:underline">info@beaconleadership.org</a></p>
            <p class="flex items-center gap-1"><i data-lucide="phone" class="w-4 h-4"></i> <a
                    href="tel:+234-706-442-5639" class="hover:underline">+234-706-442-5639</a></p>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="navigation__container">
        <div class="navigation__parent">
            <!-- Logo -->
            <div class="logo__container">
                <a href="{{ route("homepage") }}">
                    <img src="{{ asset("images/logo.jpg") }}" class="w-30 h-10 object-cover" alt="app-logo">
                </a>
                <button class="mobile__toggle-btn" @click="mainNavOpen = !mainNavOpen" aria-label="Menu"><i data-lucide="menu"
                        class="w-6 h-6"></i></button>
            </div>
            <div class="nav-link__containter" :class="{'hide':!mainNavOpen}">
                <!-- Nav Links -->
                <ul class="nav-link__parent">
                    <li class="flex items-center gap-1">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        <a href="{{ route('homepage') }}" class="hover:text-teal-300">Home</a>
                    </li>
                    <li class="flex items-center gap-1">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <a href="{{ route('events.index') }}" class="hover:text-teal-300">Events</a>
                    </li>
                    <li class="flex items-center gap-1">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                        <a href="{{ route('courses.index') }}">Courses</a>
                    </li>
                    <li class="flex items-center gap-1">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        <a href="#">The Team</a>
                    </li>
                    <li class="flex items-center gap-1">
                        <i data-lucide="pen-line" class="w-4 h-4"></i>
                        <a href="#">Blog</a>
                    </li>
                    <li class="flex items-center gap-1">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        <a href="{{ route("contact-us") }}">Contact</a>
                    </li>

                </ul>

                <!-- Call to Action -->
                <div class="py-5 action__container">
                    @auth
                        <a href="{{ route("user_dashboard") }}"
                            class="flex items-center gap-2">
                            <i data-lucide="grid" class="h-4 w-4"></i>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route("login") }}"
                            class="flex items-center gap-2">
                            <i data-lucide="log-in" class="h-4 w-4"></i>
                            <span>Login</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Slot -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer>
        <div class="bg-teal-900 text-white p-4 text-center text-sm">
            <p>&copy; {{ now()->year }} Beacon Leadership Institute. All rights reserved.</p>
        </div>
    </footer>
    <x-toast />
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // lucide.createIcons();
    </script>
</body>

</html>
