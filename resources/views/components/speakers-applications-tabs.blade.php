<div class="border-b border-[#FF0000]/20">
    <ul
        class="-mb-px flex space-x-4 overflow-x-auto overflow-y-hidden scrollbar-thin scrollbar-thumb-[#FF0000]/50 scrollbar-track-gray-100">
        <!-- Tab: All Speakers -->
        <x-tab-link label="All Speakers" icon="mic" :to="route('admin.speakers.index')"
            :isActive="request()->routeIs('admin.speakers.index')" />

        <!-- Tab: Pending Applications -->
        <x-tab-link label="Pending Applications" icon="clock" :to="route('admin.speakers.applications.pending')"
            :isActive="request()->routeIs('admin.speakers.applications.pending')" />

        <!-- Tab: Approved Applications -->
        <x-tab-link label="Approved Applications" icon="check" :to="route('admin.speakers.applications.approved')"
            :isActive="request()->routeIs('admin.speakers.applications.approved')" />
        <!-- Tab: Rejected Applications -->
        <x-tab-link label="Rejected Applications" icon="x" :to="route('admin.speakers.applications.approved')"
            :isActive="request()->routeIs('admin.speakers.applications.approved')" />

        <!-- Tab: Add Speaker -->
        <x-tab-link label="Add Speaker" icon="plus" :to="route('admin.speakers.create')"
            :isActive="request()->routeIs('admin.speakers.create')" />
    </ul>
</div>
