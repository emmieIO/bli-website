<x-app-layout>
    <header class="mb-6">
        <h1 class="text-3xl font-bold mb-2">Add Lesson to {{ $module->title }}</h1>
        <p class="text-gray-600">Create a new lesson and assign it to the selected module.</p>
        <a class="bg-white p-2.5 text-gray-800 border rounded-md my-1 inline-block"
            href="{{ route('admin.courses.builder', $module->course) }}">Go back to builder</a>
    </header>
    <div class="">
        <form action="{{ route('admin.lessons.store', $module) }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">
            @csrf

            <div>
                <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Lesson
                    Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="bg-gray-50 border border-gray-300 focus:ring-orange-600 focus:border-orange-600 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                    required>
            </div>

            <div>
                <label for="type" class="block mb-2 text-sm font-medium text-gray-900">Lesson
                    Type</label>
                <select id="type" name="type"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-600 focus:border-orange-600 block w-full p-2.5">
                    <option selected disabled>Choose a lesson type</option>
                    @foreach ($lessontypes as $lessontype)
                        <option @if (old('type') == $lessontype['value']) selected @endif value="{{ $lessontype['value'] }}">
                            {{ $lessontype['label'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                <textarea name="description" id="description" rows="10"
                    class="bg-gray-50 border border-gray-300 resize-none text-gray-900 text-sm focus:ring-orange-600 focus:border-orange-600 rounded-lg block w-full p-2.5">{{ old('description') }}</textarea>
            </div>

            <div id="video_field">
                <label for="video" class="block mb-2 text-sm font-medium text-gray-900">Video
                    Resource</label>
                <input type="file" name="video_field" id="video_lesson"
                    class="bg-gray-50 border border-gray-300 file:bg-orange-600 text-gray-900 text-sm rounded-lg block w-full">
                <div class="flex items-center mt-3 px-1">
                    <input id="is_preview" type="checkbox" name="is_preview"
                        class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-orange-500 text-orange-600 accent-orange-700">
                    <label for="is_preview" class="ml-2 text-sm font-medium text-gray-900 ">Is
                        Preview?</label>
                </div>
                <mux-uploader endpoint="https://httpbin.org/put"></mux-uploader>
            </div>

            <div id="pdf_field">
                <label for="content_path" class="block mb-2 text-sm font-medium text-gray-900">PDF
                    Resource
                    File</label>
                <input type="file" name="content_path" id="content_path"
                    class="bg-gray-50 border border-gray-300 text-gray-900 focus:ring-orange-600 focus:border-orange-600 file:bg-orange-600 text-sm rounded-lg block w-full">
            </div>

            <div id="instruction_containter">
                <label for="assignment_instructions" class="block mb-2 text-sm font-medium text-gray-900">Assignment
                    Instructions</label>
                <textarea name="assignment_instructions" id="assignment_instructions" rows="4"
                    class="bg-gray-50 border resize-none border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"></textarea>
            </div>

            <div id="url_field">
                <label for="url" class="block mb-2 text-sm font-medium text-gray-900">Resource
                    Link</label>
                <input type="url" name="link_url" id="url"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full">
            </div>

            <div class="flex items-center mt-3 px-1" id="instruction_toggle">
                <input id="has_instruction" type="checkbox" name="has_instruction"
                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-orange-500 text-orange-600 accent-orange-700">
                <label for="has_instruction" class="ml-2 text-sm font-medium text-gray-900 ">
                    Has Instructions</label>
            </div>
            <button type="submit"
                class="w-full text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Save Lesson
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@mux/mux-uploader"></script>
    <script>
        const lessontype = document.getElementById('type');
        const pdf_field = document.getElementById("pdf_field");
        const link_field = document.getElementById("url_field");
        const video_field = document.getElementById("video_field");
        const instructionToggle = document.getElementById("has_instruction");
        const instructionTextbox = document.getElementById("instruction_containter");

        // Helper function to show/hide fields based on type
        function toggleFieldsByType(typeValue) {
            pdf_field.classList.toggle('hidden', typeValue !== 'pdf');
            link_field.classList.toggle('hidden', typeValue !== 'link');
            video_field.classList.toggle('hidden', typeValue !== 'video');

            // Hide instruction box unless explicitly toggled (we handle that separately)
            // But if type is not video/pdf/link, hide everything
            if (!['video', 'pdf', 'link'].includes(typeValue)) {
                pdf_field.classList.add('hidden');
                link_field.classList.add('hidden');
                video_field.classList.add('hidden');
            }
        }

        // Helper function to toggle instruction box
        function toggleInstructionBox(checked) {
            instructionTextbox.classList.toggle('hidden', !checked);
        }

        // On page load: restore state from old input (or default)
        document.addEventListener('DOMContentLoaded', function() {
            const currentType = lessontype.value;
            const hasInstructionChecked = instructionToggle.checked;

            toggleFieldsByType(currentType);
            toggleInstructionBox(hasInstructionChecked);
        });

        // On user change: update fields
        lessontype.addEventListener('change', function(e) {
            toggleFieldsByType(e.target.value);
            // Optional: hide instruction box when type changes (unless you want to preserve it)
        });

        instructionToggle.addEventListener('change', function() {
            toggleInstructionBox(this.checked);
        });
    </script>

</x-app-layout>
