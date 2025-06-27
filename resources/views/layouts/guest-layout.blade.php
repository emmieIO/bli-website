<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Beacon Leadership Institute</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header>
        <div class="lg:flex items-center justify-between p-3">
            <p class="flex gap-2"><i data-lucide="mail"></i>:<a
                    href="mailto:info@beaconleadership.org">info@beaconleadership.org</a></p>
            <p class="flex gap-2"><i data-lucide="phone"></i>:<a href="tel:+234-706-442-5639">+234-706-442-5639</a></p>
        </div>
        <div class="bg-teal-900 text-white">
            <nav class="lg:flex lg:justify-between lg:place-items-center  w-[90%] m-auto py-5">
                <div class="logo_parent">
                    <div class="logo">BL<span>I</span></div>
                    <i data-lucide="menu" class="stroke-1 lg:hidden"></i>
                </div>
                <ul class="nav-links">
                    <li><a href="{{ route('homepage') }}">Home</a></li>
                    <li><a href="{{ route('events.index') }}">Upcoming events</a></li>
                    <li><a href="#">Courses</a></li>
                    <li><a href="#">The Team</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <button class="quote-btn bg-teal-400">Get Started</button>
            </nav>
        </div>
    </header>

    <main>


        {{ $slot }}
    </main>
    <footer class="">
        <div class="bg-teal-900 text-white p-3 text-center">
            <p>Beacon Leadership Institute &copy; 2025</p>
        </div>
    </footer>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>
