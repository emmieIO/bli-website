<x-app-layout>
    <div class="">
        <div class="flex items-center justify-between mb-8 border-b pb-3">
            <h2 class="text-3xl font-semibold text-orange-700 flex items-center gap-2">
                <i data-lucide="folder-plus" class="w-7 h-7"></i>
                Add Event Resource
            </h2>
            <a href="{{ route('admin.events.index') }}"
               class="inline-flex items-center gap-1 text-orange-600 hover:text-orange-800 font-medium transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Events
            </a>
        </div>

        <form action="{{ route('admin.events.resources.store', $event) }}"
              method="POST" enctype="multipart/form-data"
              class="bg-white border border-gray-100 rounded-2xl shadow-md p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <x-input label="Title" name="title" required icon="type" :value="old('title')" />

                <x-select label="Resource Type" name="type" required icon="list"
                    :options="['file' => 'File', 'link' => 'Link']"
                    :selected="old('type')" />
            </div>

            <x-input label="External Link" name="external_link" type="url" icon="link" :value="old('external_link')" />

            <div class="flex flex-col">
                <label for="file_path" class="text-sm font-medium text-gray-700 mb-2">Upload File</label>
                <div class="flex items-center justify-center w-full">
                    <label for="file_path"
                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-orange-50 transition">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i data-lucide="upload" class="w-8 h-8 text-orange-600 mb-2"></i>
                            <p class="text-sm text-gray-500"><span class="font-medium text-orange-700">Click to upload</span> or drag & drop</p>
                            <p class="text-xs text-gray-400">PDF, DOCX, or media files up to 10MB</p>
                        </div>
                        <input id="file_path" name="file_path" type="file" class="hidden" />
                    </label>
                </div>
                <x-input-error :messages="$errors->get('file_path')" class="mt-2" />
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="5"
                    class="block p-3 w-full border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm resize-none placeholder-gray-400 transition"
                    placeholder="Brief description about this resource...">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="flex items-center gap-2">
                <input id="is_downloadable" name="is_downloadable" type="checkbox" value="1"
                    class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                <label for="is_downloadable" class="text-sm text-gray-700">Make downloadable</label>
            </div>

            <div class="pt-6 border-t mt-6 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-1 transition-all">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                    Save Resource
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
