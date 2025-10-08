<x-app-layout>
    <div class="py-8">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-primary-100">
                <h2 class="text-3xl font-bold text-primary flex items-center gap-3">
                    <i data-lucide="folder-plus" class="w-8 h-8"></i>
                    Add Event Resource
                </h2>
                <a href="{{ route('admin.events.show', $event) }}"
                   class="inline-flex items-center gap-2 text-primary hover:text-primary-600 font-medium transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to Event
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.events.resources.store', $event) }}"
                  method="POST" enctype="multipart/form-data"
                  class="bg-white border border-primary-100 rounded-2xl shadow-lg p-8 space-y-8">
                @csrf

                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="Title" name="title" required icon="type" :value="old('title')" />

                    <x-select label="Resource Type" name="type" required icon="list"
                        :options="['file' => 'File', 'link' => 'Link']"
                        :selected="old('type')" />
                </div>

                <!-- External Link -->
                <x-input label="External Link" name="external_link" type="url" icon="link" :value="old('external_link')" />

                <!-- File Upload -->
                <div class="space-y-3">
                    <label class="text-sm font-medium text-gray-700">Upload File</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="file_path"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-primary-200 rounded-xl cursor-pointer bg-primary-50 hover:bg-primary-100 transition-all duration-200">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i data-lucide="upload" class="w-8 h-8 text-primary mb-2"></i>
                                <p class="text-sm text-gray-600"><span class="font-medium text-primary">Click to upload</span> or drag & drop</p>
                                <p class="text-xs text-gray-500 mt-1">PDF, DOCX, or media files up to 10MB</p>
                            </div>
                            <input id="file_path" name="file_path" type="file" class="hidden" />
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('file_path')" class="mt-1" />
                </div>

                <!-- Description -->
                <div class="space-y-3">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="5"
                        class="block p-4 w-full border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-primary focus:border-primary text-sm resize-none placeholder-gray-400 transition-all duration-200"
                        placeholder="Brief description about this resource...">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>

                <!-- Downloadable Option -->
                <div class="flex items-center gap-3 p-4 bg-primary-50 rounded-xl border border-primary-100">
                    <input id="is_downloadable" name="is_downloadable" type="checkbox" value="1"
                        class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <label for="is_downloadable" class="text-sm text-gray-700 font-medium">
                        Make resource downloadable for attendees
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-primary-100 mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-3 px-6 py-3 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        Save Resource
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // File upload preview and validation
        document.getElementById('file_path').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const label = this.parentElement;
            
            if (file) {
                // Update label with file info
                const fileName = file.name;
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                
                label.innerHTML = `
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <i data-lucide="file-text" class="w-8 h-8 text-accent mb-2"></i>
                        <p class="text-sm font-medium text-primary">${fileName}</p>
                        <p class="text-xs text-gray-500 mt-1">${fileSize} MB</p>
                        <p class="text-xs text-accent mt-2">File selected successfully</p>
                    </div>
                `;
                lucide.createIcons();
            }
        });

        // Drag and drop functionality
        const dropArea = document.querySelector('label[for="file_path"]');
        
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
            dropArea.classList.add('bg-accent-50', 'border-accent-300');
        }

        function unhighlight() {
            dropArea.classList.remove('bg-accent-50', 'border-accent-300');
        }

        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('file_path').files = files;
            
            // Trigger change event
            const event = new Event('change');
            document.getElementById('file_path').dispatchEvent(event);
        }
    </script>
</x-app-layout>