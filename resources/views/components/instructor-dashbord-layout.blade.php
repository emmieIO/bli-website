<div>
     <div class="border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <x-tab-link
            label="Instructors"
            icon="users"
            :to="route('admin.instructors.index')"
            :isActive="request()->routeIs('admin.instructors.index')"
            />
            <x-tab-link
            label="Applications"
            icon="mail-question-mark"
            :to="route('admin.instructors.applications')"
            :isActive="request()->routeIs('admin.instructors.applications')"
            />
            <x-tab-link
            label="Applications Logs"
            icon="logs"
            :to="route('admin.instructors.application-logs')"
            :isActive="request()->routeIs('admin.instructors.application-logs')"
            />



        </ul>
    </div>
    {{ $slot }}
</div>
