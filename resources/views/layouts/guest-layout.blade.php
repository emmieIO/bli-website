<!DOCTYPE html>
<html lang="en" x-data="{ mainNavOpen: false }" x-cloak>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Beacon Leadership Institute</title>
    <meta name="theme-color" content="#134e4a" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet" />
    <script>
        // Save scroll position before unload
        window.addEventListener('beforeunload', function () {
            sessionStorage.setItem('scrollY', window.scrollY);
        });

        // Restore scroll position after load
        window.addEventListener('load', function () {
            const scrollY = sessionStorage.getItem('scrollY');
            if (scrollY !== null) {
                window.scrollTo(0, parseInt(scrollY));
            }
        });
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-gray-800 min-h-screen antialiased">
    <!-- Skip to content -->
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:p-2 focus:bg-teal-600 focus:text-white">Skip to
        main content</a>

    <!-- Page Preloader (optional) -->
    <div id="page-preload"
        class="fixed inset-0 z-60 flex items-center justify-center bg-white transition-opacity duration-300 opacity-100 pointer-events-auto">
        <div>
            <img src="{{ asset('images/logo.jpg') }}"
                class="w-[100px] h-[100px] rounded-full animate-scale border-2 border-black"
                alt="Beacon Leadership Institute logo preloader" />
        </div>
    </div>

    <!-- Top Contact Bar -->
    <header class="bg-gray-100 text-sm text-gray-700">
        <div class="flex flex-col md:flex-row md:justify-between items-center gap-2 px-4 py-2 max-w-7xl mx-auto">
            <p class="flex items-center gap-1">
                <i data-lucide="mail" class="w-4 h-4"></i>
                <a href="mailto:info@beaconleadership.org" class="hover:underline">info@beaconleadership.org</a>
            </p>
            <p class="flex items-center gap-1">
                <i data-lucide="phone" class="w-4 h-4"></i>
                <a href="tel:+234-706-442-5639" class="hover:underline">+234-706-442-5639</a>
            </p>
        </div>
    </header>

    <!-- Primary Navigation -->
    <!-- NOTE: Converted from custom CSS classes to mostly Tailwind for portability -->
    <x-navbar/>

    <!-- Main Slot -->
    <main id="main-content" class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="mt-16">
        <div class="bg-teal-900 text-white p-4 text-center text-sm">
            <p>&copy; {{ now()->year }} Beacon Leadership Institute. All rights reserved.</p>
        </div>
    </footer>

    <x-toast />

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Lucide icons after script loads (fallback check)
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                window.lucide.createIcons();
            }

            // Fade out preloader once page ready
            const preload = document.getElementById('page-preload');
            if (preload) {
                requestAnimationFrame(() => {
                    preload.classList.add('opacity-0', 'pointer-events-none');
                    setTimeout(() => preload.remove(), 300);
                });
            }
        });
    </script>
</body>

</html>
