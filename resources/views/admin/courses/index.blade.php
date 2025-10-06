<x-app-layout>
    <div>
        <h1 class="text-3xl font-bold mb-4">Courses Administration</h1>
        <p class="text-gray-600 mb-6">Manage all available courses, add new ones, or update existing course details.</p>
        <div>
            <div class="">
                <a href="#" data-modal-target="authentication-modal" data-modal-toggle="authentication-modal"
                    class="btn inline-flex items-center gap-2 bg-orange-500 py-2 px-2 rounded-md font-medium text-white hover:bg-orange-600 transition-all">
                    <i data-lucide="graduation-cap"></i>
                    <span>Create course</span>
                </a>
            </div>
        </div>
    </div>



    <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-700">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Course Title
                    </th>
                    {{-- <th scope="col" class="px-6 py-3">
                        Description
                    </th> --}}
                    <th scope="col" class="px-6 py-3">
                        Thumbnail Photo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Price
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Instructor
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Category
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($courses))
                    @foreach ($courses as $course)
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 whitespace-nowrap">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $course->title }}
                        </th>
                        {{-- <td class="px-6 py-4 line-clamp-1">
                            {{ $course->description }}
                        </td> --}}
                        <td class="px-6 py-4">
                            <img class="w-10 h-auto rounded-md" src="{{ url('storage/'.$course->thumbnail_path) }}" alt="{{ $course->title }}">
                        </td>
                        <td class="px-6 py-4">
                            {{ $course->price == 0 ? 'Free Course' : '₦' . number_format($course->price) }}
                        </td>
                        <td class="px-6 py-4 font-black">
                            {{ Str::ucfirst($course->instructor->name) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ Str::ucfirst($course->category->name) }}
                        </td>
                        <td class="px-6 py-4 font-black">
                            {{ Str::ucfirst($course->status->value) }}
                        </td>
                        <td class="flex items-center px-6 py-4 gap-4">
                            <button title="Edit" class="text-orange-600 text-sm hover:text-orange-800 mr-2">
                                <i data-lucide="edit" class="size-5"></i>
                            </button>
                            <button title="Remove" class="text-red-600 hover:text-red-800">
                                <i data-lucide="trash-2" class="size-5"></i>
                            </button>
                            <a href="{{ route('admin.courses.builder', $course) }}" title="Build Course" class="text-red-600 hover:text-red-800">
                                <i data-lucide="cog" class="size-5"></i>
                            </a>
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
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-tborder-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Create a new course
                    </h3>
                    <button type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
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
                <div class="p-4 md:p-5">
                    <form class="space-y-4 md:grid md:grid-cols-2 md:gap-1" action="{{ route('admin.courses.store') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="col-span-2">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Course
                                title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5"
                                placeholder="Enter course title" required />
                        </div>
                        <div class="col-span-2">
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Course
                                description</label>
                            <textarea id="description" rows="8" name="description"
                                class="block p-2.5 w-full resize-none text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500"
                                placeholder="Enter course description">{{ old('description') }}</textarea>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900" for="thumbnail_path">Course
                                cover
                                image</label>
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:bg-orange-500"
                                id="thumbnail_path" type="file" name="thumbnail_path">
                        </div>
                        <div>
                            <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Select an
                                option</label>
                            <select id="level" name="level"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 ">
                                <option value="" disabled {{ old('level') === null ? 'selected' : '' }}>Select a course level</option>
                                @foreach (\App\Enums\CourseLevel::options() as $level)
                                    <option value="{{ $level['value'] }}" {{ old('level') == $level['value'] ? 'selected' : '' }}>
                                        {{ $level['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Select an
                                option</label>
                            <select id="category_id" name="category_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 ">
                                <option value="" disabled selected>Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ Str::ucfirst($category->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="number-input" class="block mb-2 text-sm font-medium text-gray-900">(&#8358;)
                                Price</label>
                            <input type="number" min="0" id="price" name="price"
                                aria-describedby="helper-text-explanation"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5"
                                placeholder="Enter Course Price" required />
                        </div>

                        <button type="submit"
                            class="w-full col-span-2 text-white bg-orange-500 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Create
                            Course</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
