<x-app-layout>
    <section class="min-h-screen bg-gray-50">
        <div class="">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-teal-800">Dashboard</h1>
                @hasanyrole(['admin', 'super-admin'])
                    <p class="text-gray-500 mt-1 text-sm">As an admin, you can manage users, courses, and view platform
                        analytics from this dashboard.</p>
                @else
                    <p class="text-gray-600 mt-2">Welcome to your dashboard. Here you can track your course progress and
                        continue your learning journey.</p>
                @endhasanyrole
            </div>

            <!-- Course Overview Cards -->
            @hasanyrole(['admin', 'super-admin'])
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Total Users -->
                    <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Users</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">1,842</h3>
                            </div>
                            <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span
                                class="text-xs font-medium text-green-500 bg-green-50 px-2 py-1 rounded-full flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                +12.5% this month
                            </span>
                        </div>
                    </div>

                    <!-- Active Users -->
                    <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Active Users</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">1,294</h3>
                            </div>
                            <div class="p-3 rounded-full bg-green-50 text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="text-xs font-medium text-green-500 bg-green-50 px-2 py-1 rounded-full">72%
                                engagement</span>
                        </div>
                    </div>

                    <!-- Total Courses -->
                    <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Courses</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">86</h3>
                            </div>
                            <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="text-xs font-medium text-gray-600 bg-gray-50 px-2 py-1 rounded-full">24 in
                                development</span>
                        </div>
                    </div>

                    <!-- Events Scheduled -->
                    <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Events Scheduled</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">32</h3>
                            </div>
                            <div class="p-3 rounded-full bg-purple-50 text-purple-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="text-xs font-medium text-blue-500 bg-blue-50 px-2 py-1 rounded-full">5 happening
                                today</span>
                        </div>
                    </div>

                    <!-- Total Attendees -->
                    <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Attendees</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">4,217</h3>
                            </div>
                            <div class="p-3 rounded-full bg-orange-50 text-orange-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="text-xs font-medium text-green-500 bg-green-50 px-2 py-1 rounded-full">+827 this
                                month</span>
                        </div>
                    </div>
                </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stat Card 1 - Total Courses -->
                <div class="bg-white rounded-lg shadow border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Courses</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">24</h3>
                        </div>
                        <div class="p-3 rounded-full bg-teal-50 text-teal-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">+2 from last month</p>
                </div>

                <!-- Stat Card 2 - In Progress -->
                <div class="bg-white rounded-lg shadow border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">In Progress</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">5</h3>
                        </div>
                        <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">2 active this week</p>
                </div>

                <!-- Stat Card 3 - Completed -->
                <div class="bg-white rounded-lg shadow border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Completed</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">14</h3>
                        </div>
                        <div class="p-3 rounded-full bg-green-50 text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">3 certifications earned</p>
                </div>

                <!-- Stat Card 4 - Hours Spent -->
                <div class="bg-white rounded-lg shadow border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Hours Spent</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">42.5</h3>
                        </div>
                        <div class="p-3 rounded-full bg-purple-50 text-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">+8.2h this month</p>
                </div>
            </div>
                
            @endhasanyrole

    </section>
</x-app-layout>
