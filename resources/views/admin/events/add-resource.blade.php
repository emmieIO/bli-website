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
                    <label for="file_path" class="block text-sm font-medium text-gray-700">Upload File</label>
                    <input id="file_path" name="file_path" type="file"
                        class="block w-full text-sm text-gray-700 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200" />
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

    </script>
</x-app-layout>