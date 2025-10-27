@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? "BLI Auth" }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.jpg') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <!-- Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen text-gray-800 auth-layout">
    <!-- Main Content -->
    <div class="flex items-center justify-center">
        <div class="flex flex-col justify-center items-center px-8 py-12 sm:px-12 w-full max-w-xl">
            <div class="w-full">
                <!-- Title and Description -->
                <div class="mb-8 text-center"
                     data-aos="fade-down"
                     data-aos-duration="800">
                    <h1 class="text-3xl font-bold text-white font-montserrat">{{ $title ?? 'Welcome' }}</h1>
                    <p class="text-sm text-white mt-2 font-lato">{{ $description ?? '' }}</p>
                </div>

                <!-- Dynamic Form Content -->
                <div class="space-y-6 bg-white shadow-inner shadow-primary rounded-2xl p-8"
                     data-aos="fade-up"
                     data-aos-duration="800"
                     data-aos-delay="200">
                    {{ $slot }}
                </div>

                <!-- Footer Links -->
                <div class="mt-8 text-center space-y-4">
                    <a href="{{ route('homepage') }}"
                        class="text-sm font-medium text-secondary underline hover:text-secondary-600 transition font-lato">
                        Go back home
                    </a>
                    <p class="text-xs text-gray-500 font-lato">
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