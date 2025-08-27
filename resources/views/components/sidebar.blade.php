<nav class="text-white">
    <ul class="transition-all ">
        <x-side-nav-link title="Dashoard" icon="chart-area" :to="route('user_dashboard')" />
        <x-side-nav-link title="My Courses" icon="library-big" />
        <x-side-nav-link title="My Events" icon="calendar-heart" :to="route('user.events')" />
        @can(['track-applications'])
        <x-side-nav-link title="My Applications" icon="file-text" :to="route('user.events')" />
        @endcan
        @can("manage events")
            <x-side-nav-link title="Event Manager " icon="calendar" :to="route('admin.events.index')" />
        @endcan
        @can(["create-speaker", 'view-speaker'])
            <x-side-nav-link title="Speaker Manager" icon="mic" :to="route('admin.speakers.index')" />
        @endcan
        @can(['manage-instructor-applications'])
        <x-side-nav-link title="Instructor Manager"  icon="users" :to="route('admin.instructors.index')" />
        @endcan
        <x-side-nav-link title="Transaction History" icon="arrow-right-left" />
        <x-side-nav-link title="Activity Logs" icon="list" />
    </ul>
</nav>
