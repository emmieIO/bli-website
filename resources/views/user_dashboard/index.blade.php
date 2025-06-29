<x-app-layout>
    <section class="min-h-screen bg-gray-50 px-6">
        <div class="">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-teal-800">My Courses</h1>
                <p class="text-gray-600 mt-2">Welcome back! Here are your active and completed courses.</p>
            </div>

            <!-- Course Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Example Course Card -->
                <div class="bg-white rounded-lg shadow border border-teal-100 p-5 flex flex-col justify-between">
                    <div>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full inline-block">In Progress</span>
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Foundations of Leadership</h3>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            Dive into leadership essentials rooted in faith, purpose, and influence.
                        </p>
                    </div>
                    <div class="mt-4 flex justify-between items-center">
                        <a href="#" class="text-sm text-teal-700 hover:underline font-medium">Continue Course</a>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-teal-700"></i>
                    </div>
                </div>

                <!-- Repeat similar cards for other courses -->

                <div class="bg-white rounded-lg shadow border border-teal-100 p-5 flex flex-col justify-between">
                    <div>
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full inline-block">Not
                            Started</span>
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Marketplace Leadership</h3>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            Learn to lead with integrity and innovation in your workplace and industry.
                        </p>
                    </div>
                    <div class="mt-4 flex justify-between items-center">
                        <a href="#" class="text-sm text-teal-700 hover:underline font-medium">Start Course</a>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-teal-700"></i>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow border border-teal-100 p-5 flex flex-col justify-between">
                    <div class="space-y-2">
                        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">Completed</span>
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800">Vision Clarity Bootcamp</h3>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            Solidify your calling with crystal clear direction and biblical wisdom.
                        </p>
                    </div>
                    <div class="mt-4 flex justify-between items-center">
                        <a href="#" class="text-sm text-teal-700 hover:underline font-medium">View Certificate</a>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-teal-700"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
