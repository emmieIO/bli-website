<nav class="text-gray-700 my-2 ">
    <ul class="transition-all space-y-1">
        <x-side-nav-link title="Dashoard" icon="chart-area" :to="route('user_dashboard')" />
        <x-side-nav-link title="My Courses" icon="library-big" />
        <x-side-nav-link title="My Events" icon="calendar-heart" :to="route('user.events')" />
        @can(['track-applications'])
        <x-side-nav-link title="My Applications" icon="file-text" :to="route('user.events')" />
        @endcan
        <x-side-nav-link title="My Invitations" icon="send" :to="route('invitations.index')" />
        @can('manage events')
        <x-side-nav-link title="Event Manager " icon="calendar" :to="route('admin.events.index')" />
        @endcan
        @can(['create-speaker', 'view-speaker'])
        <x-side-nav-link title="Speaker Manager" icon="mic" :to="route('admin.speakers.index')" />
        @endcan

        @can(['category-view', 'category-delete', 'category-update'])
        <li class="flex items-center flex-col gap-3 hover:text-[#FFF] rounded-md px-3 transition duration-300 whitespace-nowrap">
            <button id="dropdownDefaultButton" data-collapse-toggle="course-management-dropdown"
                class="text-gray-700  hover:bg-orange-500 flex items-center w-full px-2 py-2 rounded-md text-md gap-2 font-medium hover:text-white transition duration-300 whitespace-nowrap"
                type="button">
                <i data-lucide="graduation-cap" class="size-5"></i>
                Course Manager
                <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 4 4 4-4" />
                </svg>
            </button>
            <div id="course-management-dropdown"
                class="hidden divide-orange-300 w-full">
                <ul class="text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                    <li>
                        <a href="{{ route('admin.category.index') }}"
                            class="block px-4 py-2 hover:bg-gray-100">Course Categories</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.courses.index') }}"
                            class="block px-4 py-2 hover:bg-gray-100">Course Management</a>
                    </li>
                </ul>
            </div>
        </li>
        @endcan


        @can(['manage-instructor-applications'])
        <x-side-nav-link title="Instructor Manager" icon="users" :to="route('admin.instructors.index')" />
        @endcan

        <x-side-nav-link title="Transaction History" icon="arrow-right-left" />
        <x-side-nav-link title="Activity Logs" icon="list" />
    </ul>
</nav>