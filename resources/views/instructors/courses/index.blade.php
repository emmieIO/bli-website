<x-app-layout>
    <div>
        <div class="lg:flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-primary leading-tight font-montserrat">
                    My Courses
                </h2>
                <p class="text-sm text-gray-500 mt-2">Overview of your published and draft courses — manage content, view
                    analytics, and track student progress.</p>
            </div>
            <a   href="{{ route('instructor.courses.create') }}"
                class=" btn btn-primary self-start my-3 bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md inline-flex items-center gap-2 transition duration-200">
                <i data-lucide="plus-circle" class="size-5"></i>
                Create New Course
            </a>
        </div>
    </div>

    <div class="py-6">
        <div class="">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Courses -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                            <i data-lucide="book-open" class=""></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 font-lato">Total Courses</p>
                            <p class="text-2xl font-bold text-gray-900 font-montserrat">{{ $instructorStats['total_courses'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Students -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <i data-lucide="users" class="fas fa-users text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Students</p>
                            <p class="text-2xl font-bold text-gray-900 font-montserrat">{{ $instructorStats['total_students'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Average Rating -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-amber-100 text-amber-600 mr-4">
                            <i data-lucide="star" class="fas fa-star text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Average Rating</p>
                            <p class="text-2xl font-bold text-gray-900 font-montserrat">{{ $instructorStats['average_rating'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-dollar-sign text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-700 font-bold">Wallet Balance</p>
                            <p class="text-2xl font-bold text-gray-900 font-montserrat">₦{{ number_format($instructorStats['total_earnings'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 font-montserrat">All Courses</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Course
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Students
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rating
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Price
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($courses as $course)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full"
                                                        src="{{ $course->thumbnail_url ?? 'https://via.placeholder.com/150' }}"
                                                        alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $course->title }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $course->category->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $course->students->count() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm text-gray-900">{{ number_format($course->average_rating, 1) }}</div>
                                                <div class="ml-1 text-yellow-400">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $course->price ? '₦' . number_format($course->price, 2) : 'Free' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $course->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($course->status->value) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href=""
                                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <a href="{{ route('courses.show', $course) }}"
                                                class="ml-4 text-gray-600 hover:text-gray-900">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-book-open text-gray-400 text-xl"></i>
                                            </div>
                                            <h4 class="text-lg font-medium text-gray-900 mb-2">No courses found</h4>
                                            <p class="text-gray-500 mb-6 max-w-md mx-auto">You haven't created any courses yet. Get started by creating a new course.</p>
                                            <a href="{{ route('instructor.courses.create') }}"
                                                class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md inline-flex items-center gap-2 transition duration-200">
                                                <i data-lucide="plus-circle" class="size-5"></i>
                                                Create New Course
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
