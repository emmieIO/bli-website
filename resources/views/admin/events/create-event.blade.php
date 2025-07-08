<x-app-layout>
    <div class="py-6">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-teal-800 flex items-center gap-2">
                <i data-lucide="calendar-plus" class="w-6 h-6"></i>
                Create New Event
            </h1>
            <a href="{{ route('admin.events.index') }}"
                class="text-sm text-gray-600 hover:text-teal-600 flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Events
            </a>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information Section -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h2>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="inline-block text-sm font-medium text-gray-700">Event Title *</label>
                            <x-input icon="pen" title="Event Title" type="text" name="title" id="title"
                                class="mt-1 inline-block focus:outline-none focus:ring-0 sm:text-sm"/>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description
                                *</label>
                            <textarea name="description" id="description" rows="5"
                                class="mt-1 block w-full border border-gray-300 resize-none rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">{{old('description')}}</textarea>
                        </div>

                        <!-- Program Cover -->
                        <div>
                            <label for="program_cover" class="inline-block text-sm font-medium text-gray-700">Event Cover
                                Image</label>
                            <div class="mt-1 flex items-center w-1/3 ">
                                <x-input icon="image" type="file" name="program_cover" id="program_cover" accept="image/*"
                                    class="inline-block text-sm text-gray-500 file:mr-4 file:py-2 file:px-5 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Details Section -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Event Details</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mode -->
                        <div>
                            <x-select label="Select Event Mode" name="mode"
                            :options="[
                                '' => 'Select Mode',
                                'online' => 'Online',
                                'offline' => 'In-Person',
                                'hybrid' => 'Hybrid'
                                ]"
                            value="{{ old('example_select') }}" required icon="chevron-down" autofocus />
                        </div>

                        <!-- Location/URL -->
                        <div id="location-field">
                            <label for="location" class="block text-sm font-medium text-gray-700">Location/Meeting
                                URL</label>
                            <x-input icon="map-pin" type="text" name="location" id="location"
                                class="inline-block focus:outline-none focus:ring-0 sm:text-sm"/>
                        </div>

                        <!-- Start Date -->
                        <div>

                            <x-input label="Start Date & Time*" icon="clock" type="datetime-local" min="{{ now()->format('Y-m-d\TH:i') }}" name="start_date" id="start_date"
                                class=" inline-block focus:outline-none focus:ring-0 sm:text-sm"/>
                        </div>

                        <!-- End Date -->
                    <div>

                        <x-input label="End Date & Time*" icon="clock" type="datetime-local" min="{{ now()->format('Y-m-d\TH:i') }}" name="end_date"
                            id="start_date"
                            class="inline-block focus:outline-none focus:ring-0 sm:text-sm" />
                    </div>

                    </div>
                </div>

                <!-- Additional Options -->
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Additional Options</h2>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" checked
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Make this event active</label>
                    </div>

                    <!-- Metadata -->
                    {{-- <div x-data="metadataHandler()" class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Custom Metadata</label>

                        <template x-for="(field, index) in fields" :key="index">
                            <div class="flex items-center gap-2 mb-2">
                                <input type="text" :name="'metadata[' + field.key + ']'" x-model="field.key"
                                    placeholder="Key"
                                    class="w-1/2 border border-gray-300 rounded-md py-1 px-2 text-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                <input type="text" :name="'metadata[' + field.key + ']'" x-model="field.value"
                                    placeholder="Value"
                                    class="w-1/2 border border-gray-300 rounded-md py-1 px-2 text-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                <button type="button" @click="removeField(index)"
                                    class="text-red-600 hover:text-red-800 text-xs font-semibold">Remove</button>
                            </div>
                        </template>

                        <button type="button" @click="addField()"
                            class="mt-2 inline-flex items-center px-3 py-1 bg-teal-100 text-teal-800 rounded-md text-xs hover:bg-teal-200">
                            <i data-lucide="plus" class="w-3 h-3 mr-1"></i> Add Field
                        </button>
                    </div> --}}
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-3 bg-gray-50 text-right">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        Create Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle location label based on mode
        document.getElementById('mode').addEventListener('change', function () {
            const locationField = document.getElementById('location-field');
            const label = locationField.querySelector('label');
            const input = locationField.querySelector('input');
            const mode = this.value;

            if (mode === 'online') {
                label.textContent = 'Meeting URL *';
                input. = true;
            } else if (mode === 'offline') {
                label.textContent = 'Physical Location *';
                input. = true;
            } else if (mode === 'hybrid') {
                label.textContent = 'Location/Meeting URL *';
                input. = true;
            } else {
                label.textContent = 'Location/Meeting URL';
                input. = false;
            }
        });

        // Metadata handler
        function metadataHandler() {
            return {
                fields: [{ key: '', value: '' }],
                addField() {
                    this.fields.push({ key: '', value: '' });
                },
                removeField(index) {
                    this.fields.splice(index, 1);
                }
            };
        }
    </script>
</x-app-layout>
