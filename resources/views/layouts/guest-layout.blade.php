<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Beacon Leadership Institute' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.jpg') }}">

    <link rel="stylesheet" href="{{ asset('theme-assets/css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('theme-assets/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme-assets/css/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome for Social Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>

<body class="overflow-x-hidden min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex justify-between items-center py-4 px-4 md:px-8">
            <!-- Logo -->
            <a href="{{ route('homepage') }}" class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo"
                    class="w-14 h-14 rounded-md object-cover shadow">
                <span class="text-lg font-bold text-primary tracking-tight hidden sm:inline-block font-montserrat">
                    Beacon Leadership Institute
                </span>
            </a>

            <!-- Desktop Navigation -->
            <ul class="hidden lg:flex gap-x-6 items-center font-bold text-sm">
                <li><a href="{{ route('homepage') }}" class="text-gray-700 hover:text-secondary transition">Home</a>
                </li>
                <li><a href="{{ route('events.index') }}" class="text-gray-700 hover:text-secondary transition">Events</a>
                </li>
                <li><a href="{{ route('courses.index') }}"
                        class="text-gray-700 hover:text-secondary transition">Courses</a></li>
                <!-- Join Us Dropdown -->
                <li class="relative" x-data="{ open: false }" @mouseleave="open = false">
                    <a href="#" @mouseenter="open = true" @click.prevent="open = !open"
                        class="flex items-center text-gray-700 hover:text-secondary transition">
                        Join Us
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </a>
                    <ul x-show="open" x-transition
                        class="absolute left-0 bg-white shadow-lg rounded-lg p-2 mt-3 w-56 border border-gray-100 z-50"
                        @mouseenter="open = true" @mouseleave="open = false" style="display: none;">
                        <li>
                            <a href="{{ route('instructors.become-an-instructor') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-secondary/20 hover:text-secondary rounded transition">
                                Become an Instructor
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="block px-4 py-2 text-gray-700  hover:bg-secondary/20 hover:text-secondary rounded transition">
                                Become a Speaker
                            </a>
                        </li>
                    </ul>
                </li>
                <li><a href="#" class="text-gray-700 hover:text-secondary transition">Blog</a></li>
            </ul>

            <!-- Account/Login -->
            <div class="hidden lg:flex items-center space-x-4">
                @auth
                    <a href="{{ route('user_dashboard') }}"
                        class="bg-primary text-white flex items-center text-sm justify-center px-4 py-2 rounded-lg hover:bg-secondary/90 transition shadow">
                        <span>
                            <i data-lucide="user" class="text-white size-5 p-0.5"></i>
                        </span>
                        <span>
                            My Account
                        </span>

                    </a>
                @else
                    <a href="{{ route('login.store') }}" class="text-gray-700 hover:text-primary transition">Login</a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="lg:hidden text-gray-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-200 shadow-md">
            <ul class="flex flex-col space-y-3 p-4 text-gray-700 font-medium">
                <li><a href="{{ route('homepage') }}" class="hover:text-primary">Home</a></li>
                <li><a href="{{ route('events.index') }}" class="hover:text-primary">Events</a></li>
                <li><a href="{{ route('courses.index') }}" class="hover:text-primary">Courses</a></li>
                <li><a href="{{ route('instructors.become-an-instructor') }}" class="hover:text-primary">Become an
                        Instructor</a></li>
                <li><a href="#" class="hover:text-primary">Become a Speaker</a></li>
                <li><a href="#" class="hover:text-primary">Blog</a></li>
                <li>
                    @auth
                        <a href="{{ route('user_dashboard') }}"
                            class="block bg-orange-600 text-white text-center px-4 py-2 rounded-lg hover:bg-primary">My
                            Account</a>
                    @else
                        <a href="{{ route('login.store') }}"
                            class="block text-center text-gray-700 hover:text-primary">Login</a>
                    @endauth
                </li>
            </ul>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-1 min-h-[60vh] w-full px-4 md:px-8 py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 py-8 mt-16 border-t border-gray-200">
        <div
            class="max-w-7xl mx-auto px-4 md:px-8 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <p class="text-center md:text-left text-gray-700 text-sm">
                &copy; {{ date('Y') }}
                <span class="text-primary font-semibold">Beacon Leadership Institute</span>.
                All rights reserved.
            </p>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">Connect:</span>
                <div class="flex space-x-3">
                    <a href="#" class="hover:text-primary transition"><i
                            class="fab fa-facebook text-xl"></i></a>
                    <a href="#" class="hover:text-primary transition"><i
                            class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="hover:text-primary transition"><i
                            class="fab fa-twitter text-xl"></i></a>
                </div>
            </div>
        </div>
    </footer>
    <x-toast />
    <script src="{{ asset('theme-assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('theme-assets/js/aos.js') }}"></script>
    <script src="{{ asset('theme-assets/js/swiper.min.js') }}"></script>
    <script src="{{ asset('theme-assets/js/plugins.js') }}"></script>
    <script src="{{ asset('theme-assets/js/purecounter.js') }}"></script>
    <script src="{{ asset('theme-assets/js/main.js') }}"></script>
    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
