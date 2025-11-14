<x-app-layout>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Course Management</h1>
            <p class="text-slate-600">Manage all available courses, add new ones, or update existing course details.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="#" data-modal-target="authentication-modal" data-modal-toggle="authentication-modal"
                class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 py-3 px-6 rounded-lg font-medium text-white transition-all duration-200 shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Create Course</span>
            </a>
        </div>
    </div>



    <div class="relative overflow-x-auto shadow-lg rounded-xl bg-white border border-slate-200">
        <table class="w-full text-sm text-left rtl:text-right text-slate-600">
            <thead class="text-xs text-slate-600 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">
                        Course Details
                    </th>
                    <th scope="col" class="px-6 py-4 font-semibold">
                        Thumbnail
                    </th>
                    <th scope="col" class="px-6 py-4 font-semibold">
                        Price
                    </th>
                    <th scope="col" class="px-6 py-4 font-semibold">
                        Instructor
                    </th>
                    <th scope="col" class="px-6 py-4 font-semibold">
                        Category
                    </th>
                    <th scope="col" class="px-6 py-4 font-semibold">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-4 font-semibold">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($courses))
                    @foreach ($courses as $course)
                                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-1 min-w-0">
                                                <h3 class="font-semibold text-slate-900 text-base leading-tight">{{ $course->title }}
                                                </h3>
                                                <p class="text-sm text-slate-500 mt-1 line-clamp-2">
                                                    {{ Str::limit($course->description ?? 'No description available', 80) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($course->thumbnail_path)
                                            <img class="w-16 h-12 rounded-lg object-cover shadow-sm border border-slate-200"
                                                src="{{ url('storage/' . $course->thumbnail_path) }}" alt="{{ $course->title }}">
                                        @else
                                            <div class="w-16 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                                <i data-lucide="image" class="w-5 h-5 text-slate-400"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($course->price == 0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Free
                                            </span>
                                        @else
                                            <span class="font-semibold text-slate-900">₦{{ number_format($course->price) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-xs font-semibold text-primary-700">{{ substr($course->instructor->name, 0, 1) }}</span>
                                            </div>
                                            <span
                                                class="font-medium text-slate-700">{{ Str::limit($course->instructor->name, 15) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            {{ Str::ucfirst($course->category->name) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'draft' => 'bg-yellow-100 text-yellow-800',
                                                'published' => 'bg-green-100 text-green-800',
                                                'archived' => 'bg-gray-100 text-gray-800',
                                            ];
                                            $status = strtolower($course->status->value);
                                            $colorClass = $statusColors[$status] ?? 'bg-slate-100 text-slate-800';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                            {{ Str::ucfirst($course->status->value) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button title="Edit Course" data-modal-target="update-course-modal"
                                                data-modal-toggle="update-course-modal" data-course="{{ json_encode([
                            'id' => $course->id,
                            'title' => $course->title,
                            'description' => $course->description,
                            'price' => $course->price,
                            'level' => $course->level,
                            'category_id' => $course->category_id,
                            'thumbnail_path' => $course->thumbnail_path
                        ]) }}" data-action="{{ route('admin.courses.update', $course) }}"
                                                onclick="populateUpdateModal(this)"
                                                class="inline-flex items-center justify-center w-8 h-8 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors duration-200">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </button>
                                            <a href="{{ route('admin.courses.builder', $course) }}" title="Course Builder"
                                                class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                                <i data-lucide="settings" class="w-4 h-4"></i>
                                            </a>
                                            <button title="Delete Course"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                    @endforeach
                @endif

            </tbody>
        </table>
    </div>

    <!-- Main modal -->
    <div id="authentication-modal" tabindex="-1" aria-hidden="true" data-modal-backdrop="static"
        class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full hidden">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-6 border-b border-slate-200 rounded-t-lg">
                    <h3 class="text-xl font-semibold text-slate-900">
                        Create New Course
                    </h3>
                    <button type="button"
                        class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-600 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors duration-200"
                        data-modal-hide="authentication-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6">
                    <form class="space-y-4 md:grid md:grid-cols-2 md:gap-4" action="{{ route('admin.courses.store') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="col-span-2">
                            <label for="title" class="block mb-2 text-sm font-medium text-slate-700">Course
                                Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3 transition-colors duration-200"
                                placeholder="Enter course title" required />
                        </div>
                        <div class="col-span-2">
                            <label for="description" class="block mb-2 text-sm font-medium text-slate-700">Course
                                Description</label>
                            <textarea id="description" rows="4" name="description"
                                class="block p-3 w-full resize-none text-sm text-slate-900 bg-slate-50 rounded-lg border border-slate-300 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                placeholder="Enter course description">{{ old('description') }}</textarea>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-slate-700" for="thumbnail_path">Course
                                Cover Image</label>
                            <input
                                class="block w-full text-sm text-slate-900 border border-slate-300 rounded-lg cursor-pointer bg-slate-50 focus:outline-none file:bg-primary-600 file:text-white file:border-0 file:py-2 file:px-4 file:rounded-l-lg file:hover:bg-primary-700 transition-colors duration-200"
                                id="thumbnail_path" type="file" name="thumbnail_path" accept="image/*">
                        </div>
                        <div>
                            <label for="level" class="block mb-2 text-sm font-medium text-slate-700">Course
                                Level</label>
                            <select id="level" name="level"
                                class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3 transition-colors duration-200">
                                <option value="" disabled {{ old('level') === null ? 'selected' : '' }}>Select course
                                    level</option>
                                @foreach (\App\Enums\CourseLevel::options() as $level)
                                    <option value="{{ $level['value'] }}" {{ old('level') == $level['value'] ? 'selected' : '' }}>
                                        {{ $level['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="category_id"
                                class="block mb-2 text-sm font-medium text-slate-700">Category</label>
                            <select id="category_id" name="category_id"
                                class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3 transition-colors duration-200">
                                <option value="" disabled selected>Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ Str::ucfirst($category->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="price" class="block mb-2 text-sm font-medium text-slate-700">Price (₦)</label>
                            <input type="number" min="0" id="price" name="price"
                                class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3 transition-colors duration-200"
                                placeholder="Enter course price (0 for free)" value="{{ old('price', 0) }}" />
                        </div>

                        <div class="col-span-2 pt-4">
                            <button type="submit"
                                class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-200 font-medium rounded-lg text-sm px-6 py-3 text-center transition-colors duration-200">
                                <i data-lucide="plus" class="w-4 h-4 inline mr-2"></i>
                                Create Course
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Course Modal -->
    <div id="update-course-modal" tabindex="-1" aria-hidden="true" data-modal-backdrop="static"
        class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full hidden">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-6 border-b border-slate-200 rounded-t-lg">
                    <h3 class="text-xl font-semibold text-slate-900">
                        Update Course
                    </h3>
                    <button type="button"
                        class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-600 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors duration-200"
                        data-modal-hide="update-course-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6">
                    <form id="update-course-form" class="space-y-4 md:grid md:grid-cols-2 md:gap-4" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-span-2">
                            <label for="update_title" class="block mb-2 text-sm font-medium text-slate-700">Course
                                Title</label>
                            <input type="text" name="title" id="update_title"
                                class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3 transition-colors duration-200"
                                placeholder="Enter course title" required />
                        </div>
                        <div class="col-span-2">
                            <label for="update_description" class="block mb-2 text-sm font-medium text-slate-700">Course
                                Description</label>
                            <textarea id="update_description" rows="4" name="description"
                                class="block p-3 w-full resize-none text-sm text-slate-900 bg-slate-50 rounded-lg border border-slate-300 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                placeholder="Enter course description"></textarea>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-slate-700"
                                for="update_thumbnail_path">Course Cover Image</label>
                            <input
                                class="block w-full text-sm text-slate-900 border border-slate-300 rounded-lg cursor-pointer bg-slate-50 focus:outline-none file:bg-primary-600 file:text-white file:border-0 file:py-2 file:px-4 file:rounded-l-lg file:hover:bg-primary-700 transition-colors duration-200"
                                id="update_thumbnail_path" type="file" name="thumbnail_path" accept="image/*">
                            <p class="mt-1 text-sm text-slate-500">Leave empty to keep current image</p>
                            <div id="current_image_preview" class="mt-2 hidden">
                                <p class="text-sm text-slate-600 mb-1">Current image:</p>
                                <img id="current_thumbnail"
                                    class="w-20 h-15 rounded-lg object-cover border border-slate-200" src=""
                                    alt="Current thumbnail">
                            </div>
                        </div>
                        <div>
                            <label for="update_level" class="block mb-2 text-sm font-medium text-slate-700">Course
                                Level</label>
                            <select id="update_level" name="level"
                                class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3 transition-colors duration-200">
                                <option value="" disabled>Select course level</option>
                                @foreach (\App\Enums\CourseLevel::options() as $level)
                                    <option value="{{ $level['value'] }}">
                                        {{ $level['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="update_category_id"
                                class="block mb-2 text-sm font-medium text-slate-700">Category</label>
                            <select id="update_category_id" name="category_id"
                                class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3 transition-colors duration-200">
                                <option value="" disabled>Select category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ Str::ucfirst($category->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="update_price" class="block mb-2 text-sm font-medium text-slate-700">Price
                                (₦)</label>
                            <input type="number" min="0" id="update_price" name="price"
                                class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-3 transition-colors duration-200"
                                placeholder="Enter course price (0 for free)" />
                        </div>

                        <div class="col-span-2 pt-4">
                            <button type="submit"
                                class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-200 font-medium rounded-lg text-sm px-6 py-3 text-center transition-colors duration-200">
                                <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                                Update Course
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function populateUpdateModal(button) {
            // Get course data from button attributes
            const courseData = JSON.parse(button.getAttribute('data-course'));
            const actionUrl = button.getAttribute('data-action');

            // Set form action
            document.getElementById('update-course-form').action = actionUrl;

            // Populate form fields
            document.getElementById('update_title').value = courseData.title || '';
            document.getElementById('update_description').value = courseData.description || '';
            document.getElementById('update_price').value = courseData.price || 0;

            // Set selected options for dropdowns
            if (courseData.level) {
                document.getElementById('update_level').value = courseData.level;
            }

            if (courseData.category_id) {
                document.getElementById('update_category_id').value = courseData.category_id;
            }

            // Show current image if exists
            const imagePreview = document.getElementById('current_image_preview');
            const currentThumbnail = document.getElementById('current_thumbnail');

            if (courseData.thumbnail_path) {
                currentThumbnail.src = `{{ url('storage') }}/${courseData.thumbnail_path}`;
                currentThumbnail.alt = courseData.title;
                imagePreview.classList.remove('hidden');
            } else {
                imagePreview.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>