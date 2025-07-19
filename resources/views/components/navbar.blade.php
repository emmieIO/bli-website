<nav class="bg-white border-gray-200 dark:bg-teal-900 py-2 px-3 sticky z-11 top-0">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto  md:px-4">
        <a href="{{ route('homepage') }}" class="flex w-20 h-10 items-center rtl:space-x-reverse">
            <img src="{{ asset('images/logo.jpg') }}" class="w-30 h-10 object-cover" alt="BLI Logo" />
            {{-- <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">BLI</span> --}}
        </a>
        <div class="flex items-center md:order-2 space-x-1 md:space-x-0 rtl:space-x-reverse">

            @auth()
                <button
                    class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    @if (Auth::user()->profile_image)
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ Auth::user()->profile_image }}"
                            alt="user photo">
                    @else
                        <span
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-teal-700 text-white font-bold text-sm">
                            {{ strtoupper(Str::substr(Auth::user()->name, 0, 1) . (Str::contains(Auth::user()->name, ' ') ? Str::substr(Str::after(Auth::user()->name, ' '), 0, 1) : '')) }}
                        </span>
                    @endif
                </button>
                <!-- Dropdown menu -->
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-teal-700 dark:divide-gray-600"
                    id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>
                        <span
                            class="block text-sm  text-gray-500 truncate dark:text-gray-400">{{ auth()->user()->email }}</span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="{{ route('user_dashboard') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Dashboard</a>
                        </li>

                        <li>
                            <a href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Profile</a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit"
                                    class="block px-4 w-full py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign
                                    out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth

            @guest
                <div class="flex space-x-1 md:space-x-3">
                    <a href="{{ route('login') }}"
                        class="flex items-center px-3 py-2 my-3 md:my-0 text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200 dark:bg-teal-800 dark:text-gray-200 dark:hover:bg-teal-700">
                        <i data-lucide="log-in" class="w-5 h-5 mr-2"></i>
                        Login
                    </a>
                    <x-dropdown label="Get Started" class="whitespace-nowrap" :items="[
                        ['label' => 'Become a Student', 'href' => route('register')],
                        ['label' => 'Become an Instructor', 'href' => route('register')],
                    ]" />
                   
                </div>
            @endguest

            <button data-collapse-toggle="navbar-user" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-user" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
        </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
            <ul
                class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-3 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-teal-900 md:dark:bg-teal-900 dark:border-gray-700">

                <x-navbar-link href="{{ route('homepage') }}" icon="home" label="Home" :isActive="request()->routeIs('homepage')" />
                <x-navbar-link href="{{ route('events.index') }}" icon="calendar" label="Events" :isActive="request()->routeIs('events.index')" />
                <x-navbar-link href="{{ route('courses.index') }}" icon="book" label="Courses" :isActive="request()->Routeis('courses.index')" />
                <x-navbar-link href="/about" icon="info" label="About Us" :isActive="request()->is('about')" />


            </ul>
        </div>
    </div>
</nav>
