<x-app-layout>
    <!-- Success/Error Messages -->
    @if (session('message'))
        <div class="mb-4 p-4 rounded-lg {{ session('type') === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
            <div class="flex items-center">
                <i data-lucide="{{ session('type') === 'success' ? 'check-circle' : 'alert-circle' }}" class="w-5 h-5 mr-2"></i>
                {{ session('message') }}
            </div>
        </div>
    @endif

    <div>
        <div class="lg:flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-montserrat font-bold text-primary leading-tight">
                    Create New Course
                </h2>
                <p class="text-sm text-gray-600 mt-2">Build engaging course content and start teaching students worldwide</p>
            </div>
            <div class="flex gap-3 mt-4 lg:mt-0">
                <a href="{{ route('instructor.courses.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 transition duration-200">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to Courses
                </a>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <form id="courseForm" action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data"
                class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200">
                @csrf

                <div class="bg-primary px-6 py-8 text-white">
                    <h3 class="text-xl font-bold font-montserrat">Course Information</h3>
                    <p class="text-blue-100 mt-2">Let's start with the basics - tell us about your course</p>
                </div>

                <div class="p-8 space-y-8">
                    <!-- Course Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-3">Course Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('title') ? 'border-red-300' : '' }}"
                            placeholder="e.g., Complete Web Development with Laravel"
                            maxlength="255" required>
                        <p class="text-xs text-gray-500 mt-2">Create an engaging title that clearly describes what students will learn</p>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Course Subtitle -->
                    <div>
                        <label for="subtitle" class="block text-sm font-semibold text-gray-700 mb-3">Course Subtitle</label>
                        <input type="text" id="subtitle" name="subtitle" value="{{ old('subtitle') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('subtitle') ? 'border-red-300' : '' }}"
                            placeholder="e.g., Build modern web applications from scratch"
                            maxlength="500">
                        <p class="text-xs text-gray-500 mt-2">Optional tagline to give more context (max 500 characters)</p>
                        @error('subtitle')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Course Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-3">Course Description *</label>
                        <textarea id="description" name="description" rows="8"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors resize-none {{ $errors->has('description') ? 'border-red-300' : '' }}"
                            placeholder="Provide a comprehensive description of what students will learn in this course. Include key topics, skills, outcomes, and target audience. Minimum 100 characters required."
                            minlength="100" required>{{ old('description') }}</textarea>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-500">Detailed description helps students understand the value of your course (min. 100 characters)</p>
                            <span class="text-xs text-gray-400" id="description-count">0</span>
                        </div>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Course Language -->
                    <div>
                        <label for="language" class="block text-sm font-semibold text-gray-700 mb-3">Course Language *</label>
                        <select id="language" name="language" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('language') ? 'border-red-300' : '' }}"
                            required>
                            <option value="">Select Course Language</option>
                            <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Spanish" {{ old('language') == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                            <option value="French" {{ old('language') == 'French' ? 'selected' : '' }}>French</option>
                            <option value="German" {{ old('language') == 'German' ? 'selected' : '' }}>German</option>
                            <option value="Portuguese" {{ old('language') == 'Portuguese' ? 'selected' : '' }}>Portuguese</option>
                            <option value="Italian" {{ old('language') == 'Italian' ? 'selected' : '' }}>Italian</option>
                            <option value="Chinese" {{ old('language') == 'Chinese' ? 'selected' : '' }}>Chinese</option>
                            <option value="Japanese" {{ old('language') == 'Japanese' ? 'selected' : '' }}>Japanese</option>
                            <option value="Arabic" {{ old('language') == 'Arabic' ? 'selected' : '' }}>Arabic</option>
                            <option value="Hindi" {{ old('language') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                            <option value="Other" {{ old('language') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-2">Select the primary language for course content and instruction</p>
                        @error('language')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Course Thumbnail -->
                    <div>
                        <label for="thumbnail" class="block text-sm font-semibold text-gray-700 mb-3">Course Thumbnail *</label>
                        <div id="thumbnail-drop-area"
                            class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary transition-colors cursor-pointer {{ $errors->has('thumbnail') ? 'border-red-300 bg-red-50' : 'hover:bg-gray-50' }}">
                            <div class="space-y-4">
                                <div class="flex justify-center">
                                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center">
                                        <i data-lucide="image" class="w-8 h-8 text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-lg font-medium text-gray-700">Choose or drag a thumbnail image</p>
                                    <p class="text-sm text-gray-500 mt-1">Upload a compelling course thumbnail to attract students</p>
                                </div>
                                <div class="flex justify-center">
                                    <label for="thumbnail"
                                        class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors cursor-pointer">
                                        <i data-lucide="upload" class="w-4 h-4 mr-2"></i>
                                        Select Thumbnail
                                    </label>
                                    <input id="thumbnail" name="thumbnail" type="file" class="sr-only"
                                        accept="image/jpeg,image/jpg,image/png,image/webp" required>
                                </div>
                                <p class="text-xs text-gray-500">JPG, PNG, WEBP up to 2MB • Recommended: 1200x675px (16:9 ratio)</p>
                            </div>
                            
                            <!-- Preview -->
                            <div id="thumbnail-preview" class="hidden mt-4">
                                <img id="thumbnail-image" class="mx-auto max-h-32 rounded-lg shadow-md" alt="Thumbnail Preview">
                                <p id="thumbnail-name" class="text-sm text-gray-600 mt-2"></p>
                            </div>
                        </div>
                        @error('thumbnail')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Preview Video -->
                    <div>
                        <label for="preview_video" class="block text-sm font-semibold text-gray-700 mb-3">Preview Video (Optional)</label>
                        <div id="video-drop-area"
                            class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary transition-colors cursor-pointer {{ $errors->has('preview_video') ? 'border-red-300 bg-red-50' : 'hover:bg-gray-50' }}">
                            <div class="space-y-4">
                                <div class="flex justify-center">
                                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center">
                                        <i data-lucide="video" class="w-8 h-8 text-blue-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-lg font-medium text-gray-700">Upload preview video</p>
                                    <p class="text-sm text-gray-500 mt-1">Give students a taste of what they'll learn (optional but recommended)</p>
                                </div>
                                <div class="flex justify-center">
                                    <label for="preview_video"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                                        <i data-lucide="video" class="w-4 h-4 mr-2"></i>
                                        Select Video
                                    </label>
                                    <input id="preview_video" name="preview_video" type="file" class="sr-only"
                                        accept="video/mp4,video/mov,video/avi,video/wmv">
                                </div>
                                <p class="text-xs text-gray-500">MP4, MOV, AVI, WMV up to 50MB • Keep it short (2-3 minutes max)</p>
                            </div>
                            
                            <!-- Preview -->
                            <div id="video-preview" class="hidden mt-4">
                                <video id="preview-video-element" class="mx-auto max-h-32 rounded-lg shadow-md" controls>
                                    Your browser does not support the video tag.
                                </video>
                                <p id="video-name" class="text-sm text-gray-600 mt-2"></p>
                            </div>
                        </div>
                        @error('preview_video')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Course Details Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Level -->
                        <div>
                            <label for="level" class="block text-sm font-semibold text-gray-700 mb-3">Difficulty Level *</label>
                            <select id="level" name="level"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('level') ? 'border-red-300' : '' }}"
                                required>
                                <option value="">Choose difficulty level</option>
                                <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>
                                    🟢 Beginner
                                </option>
                                <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>
                                    🟡 Intermediate
                                </option>
                                <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>
                                    🔴 Advanced
                                </option>
                            </select>
                            @error('level')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-3">Category *</label>
                            <select id="category_id" name="category_id"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('category_id') ? 'border-red-300' : '' }}"
                                required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
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

                        <!-- Course Pricing -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-4">Course Pricing *</label>
                            
                            <!-- Free/Paid Toggle -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-6">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="is_free" value="1" 
                                            {{ old('is_free', '1') == '1' ? 'checked' : '' }}
                                            class="text-primary focus:ring-primary border-gray-300" 
                                            id="course-free">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Free Course</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="is_free" value="0" 
                                            {{ old('is_free') == '0' ? 'checked' : '' }}
                                            class="text-primary focus:ring-primary border-gray-300" 
                                            id="course-paid">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Paid Course</span>
                                    </label>
                                </div>

                                <!-- Price Input (shown when paid is selected) -->
                                <div id="price-section" class="hidden">
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Course Price</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-lg font-semibold">$</span>
                                        </div>
                                        <input type="number" id="price" name="price" value="{{ old('price', '') }}"
                                            step="0.01" min="0" max="9999.99"
                                            class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors {{ $errors->has('price') ? 'border-red-300' : '' }}"
                                            placeholder="29.99">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Set a competitive price for your course content</p>
                                </div>
                            </div>

                            @error('is_free')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                            @error('price')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Course Info -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i data-lucide="info" class="w-5 h-5 mr-2 text-primary"></i>
                            What's Next?
                        </h4>
                        <div class="space-y-3 text-sm text-gray-600">
                            <div class="flex items-start">
                                <i data-lucide="check-circle" class="w-4 h-4 mt-0.5 mr-3 text-green-500"></i>
                                <span>After creating your course, you'll be able to add modules and lessons using our course builder</span>
                            </div>
                            <div class="flex items-start">
                                <i data-lucide="check-circle" class="w-4 h-4 mt-0.5 mr-3 text-green-500"></i>
                                <span>Upload video content, create assignments, and structure your curriculum</span>
                            </div>
                            <div class="flex items-start">
                                <i data-lucide="check-circle" class="w-4 h-4 mt-0.5 mr-3 text-green-500"></i>
                                <span>Submit for review once your content is ready to publish</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex flex-col sm:flex-row gap-3 sm:justify-between">
                    <a href="{{ route('instructor.courses.index') }}"
                        class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        Cancel
                    </a>
                    
                    <button type="submit"
                        class="inline-flex items-center justify-center px-8 py-3 bg-primary hover:bg-secondary rounded-xl text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        Create Course Draft
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .drag-active {
            border-color: #002147 !important;
            background-color: rgba(0, 33, 71, 0.05) !important;
        }
        
        .preview-image {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Thumbnail upload functionality
            const thumbnailDropArea = document.getElementById('thumbnail-drop-area');
            const thumbnailInput = document.getElementById('thumbnail');
            const thumbnailPreviewDiv = document.getElementById('thumbnail-preview');
            const thumbnailPreviewImage = document.getElementById('thumbnail-image');
            const thumbnailNameEl = document.getElementById('thumbnail-name');

            // Video upload functionality
            const videoDropArea = document.getElementById('video-drop-area');
            const videoInput = document.getElementById('preview_video');
            const videoPreviewDiv = document.getElementById('video-preview');
            const videoPreviewEl = document.getElementById('preview-video-element');
            const videoNameEl = document.getElementById('video-name');

            // Handle thumbnail upload
            thumbnailInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    previewThumbnail(e.target.files[0]);
                }
            });

            // Handle video upload
            videoInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    previewVideo(e.target.files[0]);
                }
            });

            function previewThumbnail(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbnailPreviewImage.src = e.target.result;
                    thumbnailNameEl.textContent = file.name;
                    thumbnailPreviewDiv.classList.remove('hidden');
                    thumbnailPreviewImage.classList.add('preview-image');
                }
                reader.readAsDataURL(file);
            }

            function previewVideo(file) {
                const url = URL.createObjectURL(file);
                videoPreviewEl.src = url;
                videoNameEl.textContent = file.name;
                videoPreviewDiv.classList.remove('hidden');
            }

            // Drag and drop functionality for thumbnail
            setupDragAndDrop(thumbnailDropArea, thumbnailInput, previewThumbnail);
            // Drag and drop functionality for video
            setupDragAndDrop(videoDropArea, videoInput, previewVideo);

            function setupDragAndDrop(dropArea, fileInput, previewFunction) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropArea.addEventListener(eventName, () => dropArea.classList.add('drag-active'), false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, () => dropArea.classList.remove('drag-active'), false);
                });

                dropArea.addEventListener('drop', function(e) {
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput.files = files;
                        previewFunction(files[0]);
                    }
                });
            }

            // Description character count
            const descriptionTextarea = document.getElementById('description');
            const descriptionCount = document.getElementById('description-count');
            
            function updateDescriptionCount() {
                const count = descriptionTextarea.value.length;
                descriptionCount.textContent = `${count} characters`;
                
                if (count < 100) {
                    descriptionCount.classList.add('text-red-500');
                    descriptionCount.classList.remove('text-green-500');
                } else {
                    descriptionCount.classList.add('text-green-500');
                    descriptionCount.classList.remove('text-red-500');
                }
            }
            
            descriptionTextarea.addEventListener('input', updateDescriptionCount);
            updateDescriptionCount(); // Initial count

            // Free/Paid course functionality
            const courseFreeRadio = document.getElementById('course-free');
            const coursePaidRadio = document.getElementById('course-paid');
            const priceSection = document.getElementById('price-section');
            const priceInput = document.getElementById('price');
            
            function togglePriceSection() {
                if (coursePaidRadio.checked) {
                    priceSection.classList.remove('hidden');
                    priceInput.required = true;
                } else {
                    priceSection.classList.add('hidden');
                    priceInput.required = false;
                    priceInput.value = '';
                }
            }
            
            courseFreeRadio.addEventListener('change', togglePriceSection);
            coursePaidRadio.addEventListener('change', togglePriceSection);
            togglePriceSection(); // Initial state

            // Form validation
            document.getElementById('courseForm').addEventListener('submit', function(e) {
                const title = document.getElementById('title').value.trim();
                const description = document.getElementById('description').value.trim();
                const language = document.getElementById('language').value;
                const level = document.getElementById('level').value;
                const category = document.getElementById('category_id').value;
                const thumbnail = document.getElementById('thumbnail').files;
                const isFree = document.querySelector('input[name="is_free"]:checked')?.value;
                const price = document.getElementById('price').value;

                let errors = [];

                // Validate required fields
                if (!title) errors.push('Course title is required');
                if (!description || description.length < 100) errors.push('Course description must be at least 100 characters');
                if (!language) errors.push('Course language is required');
                if (!level) errors.push('Difficulty level is required');
                if (!category) errors.push('Category selection is required');
                if (thumbnail.length === 0) errors.push('Course thumbnail is required');
                if (!isFree) errors.push('Please specify if this is a free or paid course');
                
                // Validate paid course pricing
                if (isFree === '0' && (!price || price <= 0)) {
                    errors.push('Price is required for paid courses');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    alert('Please fix the following errors:\n\n' + errors.join('\n'));
                    return false;
                }

                // Show loading state
                const submitButton = e.target.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 mr-2 animate-spin"></i>Creating Course...';
                submitButton.disabled = true;

                // Re-enable if there's an error (though form should submit)
                setTimeout(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }, 10000);
            });

            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</x-app-layout>
