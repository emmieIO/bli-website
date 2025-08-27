<div class="space-y-6">
    <!-- Enhanced Tab Navigation -->
    <div class="border-b border-gray-200">
        <nav class="flex" aria-label="Instructor management tabs">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center space-x-1">
                <x-tab-link
                    label="Instructors"
                    icon="users"
                    :to="route('admin.instructors.index')"
                    :isActive="request()->routeIs('admin.instructors.index')"
                    :count="$instructorCount ?? null"
                    activeColor="teal"
                />
                <x-tab-link
                    label="Applications"
                    icon="mail-question"
                    :to="route('admin.instructors.applications')"
                    :isActive="request()->routeIs('admin.instructors.applications')"
                    :count="$pendingApplicationsCount ?? null"
                    activeColor="blue"
                />
                <x-tab-link
                    label="Application Logs"
                    icon="history"
                    :to="route('admin.instructors.application-logs')"
                    :isActive="request()->routeIs('admin.instructors.application-logs')"
                    activeColor="purple"
                />
            </ul>


        </nav>
    </div>

    <!-- Tab Content Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        {{ $slot }}
    </div>
</div>
