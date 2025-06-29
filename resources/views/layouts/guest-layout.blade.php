<!DOCTYPE html>
<html lang="en">

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

<body class="font-[Outfit] text-gray-800 bg-white">

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
    <nav class="bg-teal-900 text-white">
        <div class="flex justify-between items-center max-w-7xl mx-auto px-4 py-5">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <img src="{{ asset("images/logo.jpg") }}" class="w-30 h-10 object-cover" alt="app-logo">
                <!-- <div class="text-2xl font-extrabold tracking-wide">BL<span class="text-teal-300">I</span></div> -->
                <button class="lg:hidden" aria-label="Menu"><i data-lucide="menu" class="w-6 h-6"></i></button>
            </div>

            <!-- Nav Links -->
            <ul class="hidden lg:flex gap-6 items-center text-sm font-medium">
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
            <div class="hidden lg:block">
                <a href="{{ route("login") }}" class="bg-teal-400 hover:bg-teal-300 text-white px-4 py-2 rounded-lg transition">Get
                    Started</a>
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

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>
