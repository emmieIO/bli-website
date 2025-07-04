<div class="sidebar__container bg-teal-800 text-light" :class="{ 'toggle-sidebar': !mobileOpen }">
    <div class="">
        <div class=" my-2 py-3 flex justify-between">
            <h2 class="font-bold hover:bg-teal-950 text-left px-2 rounded-md  whitespace-nowrap">Beacon Leadership
                Institute
            </h2>
            <button>
                <i data-lucide="x-circle" class="cursor-pointer" x-on:click="mobileOpen = !mobileOpen"></i>
            </button>
        </div>

        <nav>
            <ul class="transition-all "></ul>
            <x-side-nav-link title="Dashoard" icon="chart-area" :to="route('user_dashboard')" />
            <x-side-nav-link title="My Courses" icon="library-big" />
            <x-side-nav-link title="My Events" icon="calendar-heart" :to="route('user.events')" />
            <x-side-nav-link title="Transaction History" icon="arrow-right-left" />
            <x-side-nav-link title="System Settings" icon="cog" />
            </ul>
        </nav>
    </div>
    <div class="">
        <div class="flex items-center gap-3 px-2 bg-teal-900 py-3 rounded-md">
            <div class="border-2 w-[30px] h-[30px] rounded-full">
                <img src="https://ui-avatars.com/api/?name={{ __(auth()->user()->name) }}" alt="Profile"
                    class="object-fit  rounded-full ring-4 ring-white shadow-lg">
            </div>
                <a href="{{ route("profile") }}" class="">View Profile</a>
        </div>
        <form action="{{ route("logout") }}" method="post">
            @csrf
            <div>
                <button type="submit"
                    class="w-full flex items-center space-x-2 bg-teal-900 rounded-md my-3 py-3 px-2 cursor-pointer">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Sign out</span>
                </button>
            </div>
        </form>
    </div>
</div>
