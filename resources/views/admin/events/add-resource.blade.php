<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-teal-800 flex items-center gap-2">
                <i data-lucide="folder-plus" class="w-6 h-6"></i>
                Add Event Resource
            </h2>
            <a href="{{ route('admin.events.index') }}"
                class="text-sm flex items-center gap-2 text-teal-700 hover:underline">
                <i data-lucide="arrow-left"></i>
                <span>Back to Events</span>
            </a>
        </div>

        <form action="{{ route('admin.events.resources.store', $event) }}" method="POST" enctype="multipart/form-data"
            class="bg-white border border-gray-100 rounded-xl shadow p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-input label="Title" name="title" required icon="type" :value="old('title')" />

                <x-select label="Resource Type" name="type" required icon="list" :options="[
                    'file' => 'File',
                    'link' => 'Link',
                    'video' => 'Video',
                    'slide' => 'Slide',
                ]"
                    :selected="old('type')" />

                <x-input label="External Link" name="external_link" type="url" icon="link" :value="old('external_link')" />

                <x-input label="Upload File" name="file_path" type="file" icon="upload" />
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="5"
                    class="mt-1 block p-3 border w-full border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm resize-none"
                    placeholder="Brief description about this resource">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-1" />
            </div>

            <div class="flex items-center space-x-2">
                <input id="is_downloadable" name="is_downloadable" type="checkbox" value="1"
                    class="text-teal-600 border-gray-300 rounded">
                <label for="is_downloadable" class="text-sm text-gray-700">Make downloadable</label>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded hover:bg-teal-700 transition shadow-sm">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Save Resource
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
