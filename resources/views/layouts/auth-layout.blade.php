@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLI Auth</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-gray-800 bg-orange-600 auth-layout">
    <!-- Navbar -->
    {{-- <x-navbar /> --}}

    <!-- Main Content -->
    <div class="min-h-screen">
        <div class="flex flex-col justify-center items-center px-8 py-12 sm:px-12">
            <div class="w-full lg:w-120">
                <!-- Title and Description -->
                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-bold text-white">{{ $title ?? 'Welcome' }}</h1>
                    <p class="text-sm text-gray-400 mt-2">{{ $description ?? '' }}</p>
                </div>

                <!-- Dynamic Form Content -->
                <div class="space-y-6">
                    {{ $slot }}
                </div>

                <!-- Footer Links -->
                <div class="mt-8 text-center space-y-4">
                    <a href="{{ route('homepage') }}"
                        class="text-xs text-[#FF0000] underline hover:text-red-600 transition">
                        Go back home
                    </a>
                    <p class="text-xs text-gray-500">
                        &copy; 2025 Beacon Leadership Institute. All rights reserved.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!-- Toast Notifications -->
    <x-toast />

    <!-- Lucide Icons Script -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>
