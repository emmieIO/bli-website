<x-app-layout>
    <div>
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-montserrat font-bold text-primary leading-tight">
                    Create New Course
                </h2>
                <p class="text-sm text-gray-600 mt-1">Build and publish your new course</p>
            </div>
            <div class="flex gap-3">
                <button type="button"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md flex items-center gap-2 transition duration-200">
                    <i class="fas fa-eye"></i>
                    Preview
                </button>
                <button type="submit" form="courseForm"
                    class="bg-primary-600 hover:bg-secondary-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition duration-200">
                    <i class="fas fa-save"></i>
                    Save Draft
                </button>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="">
            <form id="courseForm" action="" method="POST" enctype="multipart/form-data"
                class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @csrf

                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 font-montserrat">Course Information</h3>
                    <p class="text-sm text-gray-700 mt-1">Basic details about your course</p>
                </div>

                <div class="p-6 space-y-8">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Course Title
                            *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Enter Course Title" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Course Description
                            *</label>
                        <textarea id="description" name="description" rows="6"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Describe what students will learn in this course..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Course Image</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="image" name="image" type="file" class="sr-only"
                                            accept="image/*">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Course Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Level -->
                        <div>
                            <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level
                                *</label>
                            <select id="level" name="level"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                <option value="">Select Level</option>
                                <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Beginner
                                </option>
                                <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>
                                    Intermediate</option>
                                <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Advanced
                                </option>
                            </select>
                            @error('level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category
                                *</label>
                            <select id="category_id" name="category_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price ($)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" id="price" name="price" value="{{ old('price', 0) }}"
                                    min="0" step="0.01"
                                    class="pl-7 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="0.00">
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Estimated
                                Duration (hours)</label>
                            <input type="number" id="duration" name="duration" value="{{ old('duration', 0) }}"
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="e.g., 10">
                            @error('duration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Course Content -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Course Content</label>
                        <div class="border border-gray-300 rounded-md p-4">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-md font-medium text-gray-900">Sections & Lessons</h4>
                                <button type="button"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">
                                    Add Section
                                </button>
                            </div>
                            <div class="space-y-3">
                                <div class="border border-gray-200 rounded-md p-3">
                                    <div class="flex justify-between items-center">
                                        <h5 class="font-medium">Introduction to Laravel</h5>
                                        <div class="flex gap-2">
                                            <button type="button" class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex justify-between items-center text-sm text-gray-600">
                                            <span>1. What is Laravel?</span>
                                            <span>15 min</span>
                                        </div>
                                        <div class="flex justify-between items-center text-sm text-gray-600">
                                            <span>2. Setting up your environment</span>
                                            <span>25 min</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Course Status</label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="draft"
                                    {{ old('status', 'draft') == 'draft' ? 'checked' : '' }}
                                    class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2">Draft</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="published"
                                    {{ old('status') == 'published' ? 'checked' : '' }}
                                    class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2">Published</span>
                            </label>
                        </div>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button"
                        class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit" name="action" value="draft"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Save as Draft
                    </button>
                    <button type="submit" name="action" value="publish"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Publish Course
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .drag-active {
            border-color: #4f46e5;
            background-color: #f8fafc;
        }
    </style>

    <script>
        // Image upload drag and drop
        const dropArea = document.querySelector('.border-dashed');
        const fileInput = document.getElementById('image');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.classList.add('drag-active');
        }

        function unhighlight() {
            dropArea.classList.remove('drag-active');
        }

        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
        }

        // Form validation
        document.getElementById('courseForm').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;

            if (!title || !description) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    </script>
</x-app-layout>
