<x-app-layout>
    <section class="min-h-screen bg-gradient-to-br from-gray-50 to-primary-50/30">
        <div class="space-y-8">
            <!-- Header Section -->
            <div class="mb-8" data-aos="fade-down">
                <h1 class="text-3xl font-bold text-primary font-montserrat">Dashboard</h1>
                @hasanyrole(['admin', 'super-admin'])
                    <p class="text-gray-600 mt-2 font-lato leading-relaxed">As an admin, you can manage users, courses, and view platform analytics from this dashboard.</p>
                @else
                    <p class="text-gray-600 mt-2 font-lato leading-relaxed">Welcome to your dashboard. Here you can track your course progress and continue your learning journey.</p>
                @endhasanyrole
            </div>

            <!-- Course Overview Cards -->
            @hasanyrole(['admin', 'super-admin'])
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Total Users -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Total Users</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">1,842</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-primary/10 text-primary group-hover:bg-primary/20 transition-colors">
                                <i data-lucide="users" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="text-xs font-medium text-accent bg-accent/10 px-3 py-1.5 rounded-full flex items-center gap-1 font-lato">
                                <i data-lucide="trending-up" class="w-3 h-3"></i>
                                +12.5% this month
                            </span>
                        </div>
                    </div>

                    <!-- Active Users -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Active Users</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">1,294</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-accent/10 text-accent group-hover:bg-accent/20 transition-colors">
                                <i data-lucide="activity" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="text-xs font-medium text-accent bg-accent/10 px-3 py-1.5 rounded-full font-lato">72% engagement</span>
                        </div>
                    </div>

                    <!-- Total Courses -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Total Courses</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">86</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-primary/10 text-primary group-hover:bg-primary/20 transition-colors">
                                <i data-lucide="book-open" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="text-xs font-medium text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full font-lato">24 in development</span>
                        </div>
                    </div>

                    <!-- Events Scheduled -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="400">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Events Scheduled</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">32</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-secondary/10 text-secondary group-hover:bg-secondary/20 transition-colors">
                                <i data-lucide="calendar" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="text-xs font-medium text-primary bg-primary/10 px-3 py-1.5 rounded-full font-lato">5 happening today</span>
                        </div>
                    </div>

                    <!-- Total Attendees -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Total Attendees</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">4,217</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-accent/10 text-accent group-hover:bg-accent/20 transition-colors">
                                <i data-lucide="user-check" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <span class="text-xs font-medium text-accent bg-accent/10 px-3 py-1.5 rounded-full font-lato">+827 this month</span>
                        </div>
                    </div>
                </div>

            @elsehasanyrole(['instructor'])
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Courses Taught -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Courses Taught</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">12</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-primary/10 text-primary group-hover:bg-primary/20 transition-colors">
                                <i data-lucide="book-open" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3 font-lato">2 new courses this month</p>
                    </div>

                    <!-- Active Students -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Active Students</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">158</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-accent/10 text-accent group-hover:bg-accent/20 transition-colors">
                                <i data-lucide="users" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3 font-lato">+15 active this week</p>
                    </div>

                    <!-- Assignments Graded -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Assignments Graded</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">47</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-primary/10 text-primary group-hover:bg-primary/20 transition-colors">
                                <i data-lucide="check-square" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3 font-lato">8 graded this week</p>
                    </div>

                    <!-- Upcoming Sessions -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="400">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Upcoming Sessions</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">3</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-secondary/10 text-secondary group-hover:bg-secondary/20 transition-colors">
                                <i data-lucide="calendar" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3 font-lato">Next session: Friday 10am</p>
                    </div>

                    <!-- Feedback Received -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Feedback Received</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">21</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-accent/10 text-accent group-hover:bg-accent/20 transition-colors">
                                <i data-lucide="message-square" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3 font-lato">5 new feedbacks this month</p>
                    </div>
                </div>

            @else
                <!-- Student Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Courses -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Total Courses</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">24</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-primary/10 text-primary group-hover:bg-primary/20 transition-colors">
                                <i data-lucide="book-open" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3 font-lato">+2 from last month</p>
                    </div>

                    <!-- In Progress -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">In Progress</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">5</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-accent/10 text-accent group-hover:bg-accent/20 transition-colors">
                                <i data-lucide="clock" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3 font-lato">2 active this week</p>
                    </div>

                    <!-- Completed -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Completed</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">14</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-accent/10 text-accent group-hover:bg-accent/20 transition-colors">
                                <i data-lucide="check-circle" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3 font-lato">3 certifications earned</p>
                    </div>

                    <!-- Hours Spent -->
                    <div class="bg-white rounded-2xl shadow-lg border border-primary-100 p-6 transition-all duration-300 hover:shadow-xl hover:border-primary-200 group" data-aos="fade-up" data-aos-delay="400">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 font-lato">Hours Spent</p>
                                <h3 class="text-2xl font-bold text-primary mt-1 font-montserrat">42.5</h3>
                            </div>
                            <div class="p-3 rounded-xl bg-secondary/10 text-secondary group-hover:bg-secondary/20 transition-colors">
                                <i data-lucide="watch" class="w-6 h-6"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3 font-lato">+8.2h this month</p>
                    </div>
                </div>
            @endhasanyrole

        </div>
    </section>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>