<div class="border-b border-primary-100 whitespace-nowrap mb-6">
    <ul class="-mb-px flex space-x-6 overflow-x-auto overflow-y-hidden scrollbar-thin scrollbar-thumb-primary/50 scrollbar-track-gray-100">
        <!-- Tab: All Speakers -->
        <x-tab-link label="Active Speakers" icon="mic" :to="route('admin.speakers.index')"
            :isActive="request()->routeIs('admin.speakers.index')" />
            {{-- Pending speakers --}}
        <x-tab-link label="Pending Speakers" icon="mic" :to="route('admin.speakers.pending')"
            :isActive="request()->routeIs('admin.speakers.pending')" />

        <!-- Tab: Pending Applications -->
        <x-tab-link label="Pending Applications" icon="clock" :to="route('admin.speakers.applications.pending')"
            :isActive="request()->routeIs('admin.speakers.applications.pending')" />

        <!-- Tab: Approved Applications -->
        <x-tab-link label="Approved Applications" icon="check" :to="route('admin.speakers.applications.approved')"
            :isActive="request()->routeIs('admin.speakers.applications.approved')" />

        <!-- Tab: Rejected Applications -->
        {{-- <x-tab-link label="Rejected Applications" icon="x" :to="route('admin.speakers.applications.rejected')"
            :isActive="request()->routeIs('admin.speakers.applications.rejected')" /> --}}

        <!-- Tab: Add Speaker -->
        <x-tab-link label="Add Speaker" icon="plus" :to="route('admin.speakers.create')"
            :isActive="request()->routeIs('admin.speakers.create')" />
    </ul>
</div>