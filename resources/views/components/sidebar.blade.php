<nav class="text-white">
    <ul class="transition-all ">
        <x-side-nav-link title="Dashoard" icon="chart-area" :to="route('user_dashboard')" />
        <x-side-nav-link title="My Courses" icon="library-big" />
        <x-side-nav-link title="My Events" icon="calendar-heart" :to="route('user.events')" />
        @can("manage events")
            <x-side-nav-link title="Manage Events" icon="calendar" :to="route('admin.events.index')" />
        @endcan
        @can(["create-speaker", 'view-speaker'])
            <x-side-nav-link title="Manage Speakers" icon="mic" :to="route('admin.speakers.index')" />
        @endcan
        <x-side-nav-link title="Transaction History" icon="arrow-right-left" />
        <x-side-nav-link title="System Settings" icon="cog" />
    </ul>
</nav>
