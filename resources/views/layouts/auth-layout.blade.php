@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLI Auth</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-[Outfit] bg-gray-50 text-gray-800">
    <div class="min-h-screen grid  lg:grid-cols-2">
        <!-- Left: Form -->
        <div class="flex flex-col justify-center px-8 py-12 sm:px-12 lg:px-20 bg-white">
            <div class="max-w-md w-full mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-teal-800">{{ $title ?? 'Welcome' }}</h1>
                    <p class="text-sm text-gray-600 mt-2">{{ $description ?? '' }}</p>
                </div>

                <!-- Slot for dynamic form content -->
                {{ $slot }}

                <a href="{{ route("homepage") }}" class="text-xs block text-teal-800 text-center mt-8 underline">go
                    back home</a>
                <p class="text-xs text-gray-500 text-center mt-8">&copy; 2025 Beacon Leadership Institute. All rights
                    reserved.</p>
            </div>
        </div>

        <!-- Right: Image / Branding -->
        <div class="hidden md:hidden lg:flex items-center justify-center bg-teal-800 text-white relative">
            <div class="p-12 text-center">
                <h2 class="md:text-2xl lg:text-3xl font-bold leading-tight">Beacon Leadership Institute</h2>
                <p class="text-white/80 mt-4">Raising visionary and value-driven leaders for global impact.</p>
                <img src="{{ asset('images/logo.jpg') }}" alt="Leadership"
                    class="mt-10 w-full max-w-sm mx-auto object-contain">
            </div>
        </div>
    </div>
    <x-toast />
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // lucide.createIcons();
    </script>
</body>

</html>