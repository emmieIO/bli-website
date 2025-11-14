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
    <nav class="bg-white shadow-lg sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto flex justify-between items-center py-3 px-4 md:px-8">
            <!-- Logo -->
            <a href="{{ route('homepage') }}" class="flex items-center space-x-3 group">
                <!-- Logo Image with Simple Enhancement -->
                <div class="relative">
                    <img src="{{ asset('images/logo.jpg') }}" alt="BLI Logo"
                        class="w-14 h-14 rounded-xl object-cover shadow-md group-hover:shadow-lg transition-all duration-300 transform group-hover:scale-105">

                    <!-- Simple accent indicator -->
                    <div class="absolute -top-1 -right-1 w-3 h-3 rounded-full opacity-80 group-hover:opacity-100 transition-opacity duration-300"
                        style="background-color: #00a651;"></div>
                </div>

                <!-- Clean Brand Text -->
                <div class="hidden sm:block">
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-bold tracking-tight font-montserrat leading-tight"
                            style="color: #002147;">
                            Beacon Leadership
                        </span>
                        <i class="fas fa-lightbulb text-sm group-hover:text-yellow-500 transition-colors duration-300"
                            style="color: #00a651;"></i>
                    </div>

                    <span class="text-sm font-semibold tracking-wide font-lato block" style="color: #00a651;">
                        Institute
                    </span>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <ul class="hidden lg:flex gap-x-8 items-center font-semibold text-sm">
                <li>
                    <a href="{{ route('homepage') }}"
                        class="flex items-center gap-2 relative py-2 px-1 transition-all duration-300 group"
                        style="color: #002147;">
                        <i class="fas fa-home text-sm group-hover:scale-110 transition-transform duration-300"
                            style="color: #00a651;"></i>
                        <span>Home</span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full"
                            style="background-color: #00a651;"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('events.index') }}"
                        class="flex items-center gap-2 relative py-2 px-1 transition-all duration-300 group"
                        style="color: #002147;">
                        <i class="fas fa-calendar-alt text-sm group-hover:scale-110 transition-transform duration-300"
                            style="color: #00a651;"></i>
                        <span>Events</span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full"
                            style="background-color: #00a651;"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('courses.index') }}"
                        class="flex items-center gap-2 relative py-2 px-1 transition-all duration-300 group"
                        style="color: #002147;">
                        <i class="fas fa-graduation-cap text-sm group-hover:scale-110 transition-transform duration-300"
                            style="color: #00a651;"></i>
                        <span>Courses</span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full"
                            style="background-color: #00a651;"></span>
                    </a>
                </li>

                <!-- Join Us Dropdown -->
                <li class="relative" x-data="{ open: false }" @mouseleave="open = false">
                    <a href="#" @mouseenter="open = true" @click.prevent="open = !open"
                        class="flex items-center gap-2 py-2 px-1 transition-all duration-300 group relative"
                        style="color: #002147;">
                        <i class="fas fa-user-plus text-sm group-hover:scale-110 transition-transform duration-300"
                            style="color: #00a651;"></i>
                        <span>Join Us</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transition-transform duration-300"
                            :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full"
                            style="background-color: #00a651;"></span>
                    </a>
                    <ul x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute left-0 bg-white shadow-xl rounded-xl p-3 mt-3 w-64 border border-gray-100 z-50"
                        @mouseenter="open = true" @mouseleave="open = false" style="display: none;">
                        <li>
                            <a href="{{ route('instructors.become-an-instructor') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-300 group/item hover:shadow-md"
                                style="color: #002147;" onmouseover="this.style.backgroundColor='rgba(0, 166, 81, 0.1)'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <i class="fas fa-chalkboard-teacher text-sm" style="color: #00a651;"></i>
                                <div>
                                    <span class="font-semibold">Become an Instructor</span>
                                    <p class="text-xs text-gray-500 mt-1">Share your expertise with others</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('become-a-speaker') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-300 group/item hover:shadow-md"
                                style="color: #002147;" onmouseover="this.style.backgroundColor='rgba(0, 166, 81, 0.1)'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <i class="fas fa-microphone text-sm" style="color: #00a651;"></i>
                                <div>
                                    <span class="font-semibold">Become a Speaker</span>
                                    <p class="text-xs text-gray-500 mt-1">Inspire through speaking engagements</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-2 relative py-2 px-1 transition-all duration-300 group"
                        style="color: #002147;">
                        <i class="fas fa-blog text-sm group-hover:scale-110 transition-transform duration-300"
                            style="color: #00a651;"></i>
                        <span>Blog</span>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 transition-all duration-300 group-hover:w-full"
                            style="background-color: #00a651;"></span>
                    </a>
                </li>
            </ul>

            <!-- Account/Login -->
            <div class="hidden lg:flex items-center space-x-3">
                @auth
                    <a href="{{ route('user_dashboard') }}"
                        class="flex items-center gap-2 text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105"
                        style="background-color: #002147; color: white;" onmouseover="this.style.backgroundColor='#1e3a8a'"
                        onmouseout="this.style.backgroundColor='#002147'">
                        <i class="fas fa-user-circle text-lg"></i>
                        <span>My Account</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="flex items-center justify-center w-10 h-10 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105"
                            style="background-color: #ed1c24; color: white;"
                            onmouseover="this.style.backgroundColor='#dc2626'"
                            onmouseout="this.style.backgroundColor='#ed1c24'" title="Logout">
                            <i class="fas fa-power-off text-sm"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login.store') }}"
                        class="font-semibold px-6 py-2.5 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105"
                        style="background-color: #00a651; color: white;" onmouseover="this.style.backgroundColor='#15803d'"
                        onmouseout="this.style.backgroundColor='#00a651'">
                        Login
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn"
                class="lg:hidden p-2 rounded-lg transition-colors duration-300 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2"
                style="color: #002147; focus:ring-color: #00a651;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-200 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <ul class="flex flex-col space-y-2 font-semibold">
                    <li>
                        <a href="{{ route('homepage') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300"
                            style="color: #002147;" onmouseover="this.style.backgroundColor='rgba(0, 166, 81, 0.1)'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            <i class="fas fa-home text-sm" style="color: #00a651;"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('events.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300"
                            style="color: #002147;" onmouseover="this.style.backgroundColor='rgba(0, 166, 81, 0.1)'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            <i class="fas fa-calendar text-sm" style="color: #00a651;"></i>
                            <span>Events</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300"
                            style="color: #002147;" onmouseover="this.style.backgroundColor='rgba(0, 166, 81, 0.1)'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            <i class="fas fa-graduation-cap text-sm" style="color: #00a651;"></i>
                            <span>Courses</span>
                        </a>
                    </li>

                    <!-- Mobile Join Us Section -->
                    <li class="pt-2">
                        <div class="px-4 py-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Join Us</span>
                        </div>
                        <a href="{{ route('instructors.become-an-instructor') }}"
                            class="flex items-center gap-3 px-6 py-3 rounded-xl transition-all duration-300 ml-2"
                            style="color: #002147;" onmouseover="this.style.backgroundColor='rgba(0, 166, 81, 0.1)'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            <i class="fas fa-chalkboard-teacher text-sm" style="color: #00a651;"></i>
                            <span>Become an Instructor</span>
                        </a>
                        <a href="{{ route('become-a-speaker') }}"
                            class="flex items-center gap-3 px-6 py-3 rounded-xl transition-all duration-300 ml-2"
                            style="color: #002147;" onmouseover="this.style.backgroundColor='rgba(0, 166, 81, 0.1)'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            <i class="fas fa-microphone text-sm" style="color: #00a651;"></i>
                            <span>Become a Speaker</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300"
                            style="color: #002147;" onmouseover="this.style.backgroundColor='rgba(0, 166, 81, 0.1)'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            <i class="fas fa-blog text-sm" style="color: #00a651;"></i>
                            <span>Blog</span>
                        </a>
                    </li>

                    <!-- Mobile Account Section -->
                    <li class="pt-4 mt-4 border-t border-gray-200">
                        @auth
                            <div class="flex flex-col gap-3">
                                <a href="{{ route('user_dashboard') }}"
                                    class="flex items-center justify-center gap-2 font-semibold px-4 py-3 rounded-xl transition-all duration-300 shadow-md"
                                    style="background-color: #002147; color: white;">
                                    <i class="fas fa-user-circle"></i>
                                    <span>My Account</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 font-semibold px-4 py-3 rounded-xl transition-all duration-300 shadow-md"
                                        style="background-color: #ed1c24; color: white;">
                                        <i class="fas fa-power-off"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login.store') }}"
                                class="flex items-center justify-center gap-2 font-semibold px-4 py-3 rounded-xl transition-all duration-300 shadow-md w-full"
                                style="background-color: #00a651; color: white;">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Login</span>
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
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
                    <a href="#" class="hover:text-primary transition"><i class="fab fa-facebook text-xl"></i></a>
                    <a href="#" class="hover:text-primary transition"><i class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="hover:text-primary transition"><i class="fab fa-twitter text-xl"></i></a>
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