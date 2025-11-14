<x-app-layout>
    <!-- Success/Error Messages -->
    @if (session('message'))
        <div
            class="mb-4 p-4 rounded-lg {{ session('type') === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
            <div class="flex items-center">
                <i data-lucide="{{ session('type') === 'success' ? 'check-circle' : 'alert-circle' }}"
                    class="w-5 h-5 mr-2"></i>
                {{ session('message') }}
            </div>
        </div>
    @endif

    <div>
        <div class="lg:flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-montserrat font-bold text-primary leading-tight">
                    Edit Course
                </h2>
                <p class="text-sm text-gray-600 mt-2">Update your course information and settings</p>
            </div>
            <div class="flex gap-3 mt-4 lg:mt-0">
                <a href="{{ route('instructor.courses.show', $course) }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 transition duration-200">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to Course
                </a>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <form id="courseForm" action="{{ route('instructor.courses.update', $course) }}" method="POST"
                enctype="multipart/form-data"
                class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200">
                @csrf
                @method('PUT')

                <div class="bg-primary px-6 py-8 text-white">
                    <h3 class="text-xl font-bold font-montserrat">Course Information</h3>
                    <p class="text-blue-100 mt-2">Update your course details</p>
                </div>

                <div class="p-8 space-y-8">
                    <!-- Course Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-3">Course Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $course->title) }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('title') ? 'border-red-300' : '' }}"
                            placeholder="e.g., Complete Web Development with Laravel" maxlength="255" required>
                        <p class="text-xs text-gray-500 mt-2">Create an engaging title that clearly describes what
                            students will learn</p>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Course Subtitle -->
                    <div>
                        <label for="subtitle" class="block text-sm font-semibold text-gray-700 mb-3">Course
                            Subtitle</label>
                        <input type="text" id="subtitle" name="subtitle"
                            value="{{ old('subtitle', $course->subtitle) }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('subtitle') ? 'border-red-300' : '' }}"
                            placeholder="A brief, compelling subtitle that expands on your title" maxlength="120">
                        <p class="text-xs text-gray-500 mt-2">Optional: Add a subtitle to provide more context (max 120
                            characters)</p>
                        @error('subtitle')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Course Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-3">Course
                            Description *</label>
                        <textarea id="description" name="description" rows="6"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('description') ? 'border-red-300' : '' }}"
                            placeholder="Describe your course in detail. What will students learn? What projects will they build?"
                            required>{{ old('description', $course->description) }}</textarea>
                        <p class="text-xs text-gray-500 mt-2">Provide a detailed description that helps students
                            understand the value of your course</p>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Course Category and Level -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-3">Category
                                *</label>
                            <select id="category_id" name="category_id"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('category_id') ? 'border-red-300' : '' }}"
                                required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Level -->
                        <div>
                            <label for="level" class="block text-sm font-semibold text-gray-700 mb-3">Course Level
                                *</label>
                            <select id="level" name="level"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('level') ? 'border-red-300' : '' }}"
                                required>
                                <option value="">Select course level</option>
                                <option value="beginner" {{ old('level', $course->level?->value) === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('level', $course->level?->value) === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('level', $course->level?->value) === 'advanced' ? 'selected' : '' }}>Advanced</option>
                                <option value="all_levels" {{ old('level', $course->level?->value) === 'all_levels' ? 'selected' : '' }}>All Levels</option>
                            </select>
                            @error('level')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Language -->
                    <div>
                        <label for="language" class="block text-sm font-semibold text-gray-700 mb-3">Course Language
                            *</label>
                        <select id="language" name="language"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('language') ? 'border-red-300' : '' }}"
                            required>
                            <option value="">Select language</option>
                            <option value="english" {{ old('language', $course->language) === 'english' ? 'selected' : '' }}>English</option>
                            <option value="french" {{ old('language', $course->language) === 'french' ? 'selected' : '' }}>French</option>
                            <option value="spanish" {{ old('language', $course->language) === 'spanish' ? 'selected' : '' }}>Spanish</option>
                            <option value="german" {{ old('language', $course->language) === 'german' ? 'selected' : '' }}>German</option>
                            <option value="portuguese" {{ old('language', $course->language) === 'portuguese' ? 'selected' : '' }}>Portuguese</option>
                        </select>
                        @error('language')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Pricing Section -->
                    <div x-data="{ isFree: {{ old('is_free', $course->is_free) ? 'true' : 'false' }} }">
                        <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                            <h4 class="text-lg font-bold font-montserrat text-primary mb-4 flex items-center">
                                <i data-lucide="tag" class="w-5 h-5 mr-2"></i>
                                Course Pricing
                            </h4>

                            <!-- Free/Paid Toggle -->
                            <div class="flex items-center mb-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_free" value="1" x-model="isFree" class="sr-only">
                                    <div class="relative">
                                        <div class="block bg-gray-300 w-14 h-8 rounded-full"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"
                                            :class="{ 'transform translate-x-6 bg-green-500': isFree }"></div>
                                    </div>
                                    <span class="ml-3 text-sm font-semibold text-gray-700">
                                        Make this course free
                                    </span>
                                </label>
                            </div>

                            <!-- Price Input -->
                            <div x-show="!isFree" x-transition>
                                <label for="price" class="block text-sm font-semibold text-gray-700 mb-3">Course Price
                                    (₦) *</label>
                                <input type="number" id="price" name="price" value="{{ old('price', $course->price) }}"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('price') ? 'border-red-300' : '' }}"
                                    placeholder="0.00" min="0" step="0.01">
                                <p class="text-xs text-gray-500 mt-2">Set a competitive price for your course</p>
                                @error('price')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div x-show="isFree" x-transition>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <i data-lucide="gift" class="w-5 h-5 text-green-600 mr-2"></i>
                                        <p class="text-green-800 font-semibold">This course will be free for all
                                            students</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Thumbnail -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Course Thumbnail</label>

                        <!-- Current Thumbnail -->
                        @if($course->thumbnail_path)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Current thumbnail:</p>
                                <img src="{{ $course->thumbnail_url }}" alt="Current thumbnail"
                                    class="w-32 h-20 object-cover rounded-lg border border-gray-200">
                            </div>
                        @endif

                        <div x-data="thumbnailUpload()"
                            class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary transition-colors duration-300"
                            @drop.prevent="handleDrop($event)" @dragover.prevent @dragenter.prevent>

                            <div x-show="!preview">
                                <i data-lucide="image" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                                <h4 class="text-lg font-semibold text-gray-700 mb-2">Upload Course Thumbnail</h4>
                                <p class="text-sm text-gray-500 mb-4">
                                    Drag and drop an image here, or
                                    <button type="button" @click="$refs.thumbnailInput.click()"
                                        class="text-primary hover:text-secondary font-semibold">browse files</button>
                                </p>
                                <p class="text-xs text-gray-400">Recommended: 1280x720px (16:9 ratio) • Max 5MB • JPG,
                                    PNG</p>
                            </div>

                            <div x-show="preview" class="relative">
                                <img :src="preview" alt="Thumbnail preview"
                                    class="max-w-full max-h-48 mx-auto rounded-lg border border-gray-200">
                                <button type="button" @click="removeImage()"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>

                            <input type="file" name="thumbnail" x-ref="thumbnailInput"
                                @change="handleFileSelect($event)" accept="image/*" class="hidden">
                        </div>
                        @error('thumbnail')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Course Preview Video -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Course Preview Video</label>

                        <!-- Current Preview Video -->
                        @if($course->preview_video_path)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Current preview video:</p>
                                <video controls class="w-64 h-36 rounded-lg border border-gray-200">
                                    <source src="{{ $course->getPreviewVideo() }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @endif

                        <div x-data="videoUpload()"
                            class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary transition-colors duration-300"
                            @drop.prevent="handleDrop($event)" @dragover.prevent @dragenter.prevent>

                            <div x-show="!preview">
                                <i data-lucide="video" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                                <h4 class="text-lg font-semibold text-gray-700 mb-2">Upload Preview Video</h4>
                                <p class="text-sm text-gray-500 mb-4">
                                    Drag and drop a video here, or
                                    <button type="button" @click="$refs.videoInput.click()"
                                        class="text-primary hover:text-secondary font-semibold">browse files</button>
                                </p>
                                <p class="text-xs text-gray-400">Recommended: 2-3 minutes • Max 100MB • MP4, MOV, AVI
                                </p>
                            </div>

                            <div x-show="preview" class="relative">
                                <video :src="preview" controls
                                    class="max-w-full max-h-48 mx-auto rounded-lg border border-gray-200"></video>
                                <button type="button" @click="removeVideo()"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>

                            <input type="file" name="preview_video" x-ref="videoInput"
                                @change="handleFileSelect($event)" accept="video/*" class="hidden">
                        </div>
                        @error('preview_video')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">
                            <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                            Course will be saved as draft for review
                        </p>
                        <div class="flex gap-3">
                            <a href="{{ route('instructor.courses.show', $course) }}"
                                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-primary hover:bg-secondary text-white font-semibold rounded-lg transition-colors duration-200 flex items-center gap-2">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                Update Course
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function thumbnailUpload() {
            return {
                preview: null,
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.preview = URL.createObjectURL(file);
                    }
                },
                handleDrop(event) {
                    const file = event.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        this.$refs.thumbnailInput.files = event.dataTransfer.files;
                        this.preview = URL.createObjectURL(file);
                    }
                },
                removeImage() {
                    this.preview = null;
                    this.$refs.thumbnailInput.value = '';
                }
            }
        }

        function videoUpload() {
            return {
                preview: null,
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.preview = URL.createObjectURL(file);
                    }
                },
                handleDrop(event) {
                    const file = event.dataTransfer.files[0];
                    if (file && file.type.startsWith('video/')) {
                        this.$refs.videoInput.files = event.dataTransfer.files;
                        this.preview = URL.createObjectURL(file);
                    }
                },
                removeVideo() {
                    this.preview = null;
                    this.$refs.videoInput.value = '';
                }
            }
        }
    </script>
</x-app-layout>