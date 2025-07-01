<!DOCTYPE html>
<html lang="en" x-data="{ mobileOpen: false }">

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

<body class="font-[Outfit] bg-gray-50 text-gray-800">
    <div class="min-h-screen grid grid-cols-1 md:grid-cols-[16rem_1fr]">
        <!-- Sidebar -->
        <aside :class="mobileOpen ? 'block' : 'hidden md:flex'"
            class="md:col-span-1 bg-teal-800 text-white fixed md:static inset-y-0 left-0 z-30 flex-col p-6 transition duration-300 ease-in-out w-64">
            <div class="mb-10 flex justify-between items-center md:block">
                <div>
                    <h1 class="text-2xl font-extrabold">BLI</h1>
                    <p class="text-sm opacity-80 hidden md:block">Leadership LMS</p>
                </div>
                <button @click="mobileOpen = false" class="md:hidden text-white hover:text-teal-300">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <nav class="space-y-4 text-sm font-medium">
                <a href="#" class="flex items-center gap-2 hover:text-teal-300">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    Dashboard
                </a>
                <a href="#" class="flex items-center gap-2 hover:text-teal-300">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    My Courses
                </a>
                <a href="#" class="flex items-center gap-2 hover:text-teal-300">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    Events
                </a>
                <a href="#" class="flex items-center gap-2 hover:text-teal-300">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    Settings
                </a>
            </nav>
            <div class="mt-auto pt-6 border-t border-white/10">
                <a href="#" class="flex items-center gap-2 text-sm hover:text-teal-300">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col md:col-span-1">
            <!-- Topbar -->
            <header class="bg-white shadow px-6 py-4 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <button @click="mobileOpen = true" class="text-teal-700 md:hidden">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-teal-800">Dashboard</h2>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-700">Hi, John Doe</span>
                    <img src="https://ui-avatars.com/api/?name=John+Doe" alt="User" class="w-8 h-8 rounded-full" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 py-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    <x-toast />
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        <script>
                window.notyf.error('Please fill out the form');
        </script>
    </script>
</body>

</html>
