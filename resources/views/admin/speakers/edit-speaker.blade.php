<x-app-layout>
    <div class="py-10 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-teal-800 flex items-center gap-2">
                <i data-lucide="mic" class="w-6 h-6"></i>
                Update {{ $speaker->name }}
            </h2>
            <a href="{{ route('admin.speakers.index') }}"
                class="inline-flex items-center text-sm font-medium text-teal-700 hover:underline">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Back to Speakers
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.speakers.update', $speaker) }}" method="POST" enctype="multipart/form-data"
            class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 space-y-6">
            @csrf
            @method("PUT")

            <!-- Input Fields Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-input label="Full Name" name="name" required icon="user" :value="old('name', $speaker->name ?? '')" />
                <x-input label="Title" name="title" icon="briefcase" :value="old('title', $speaker->title ?? '')" />
                <x-input label="Organization" name="organization" icon="building-2" :value="old('organization', $speaker->organization ?? '')" />
                <x-input label="Email" name="email" type="email" required icon="mail" :value="old('email', $speaker->email ?? '')" />
                <x-input label="Phone" name="phone" icon="phone" :value="old('phone', $speaker->phone ?? '')" />
                <x-input label="LinkedIn" name="linkedin" type="url" icon="linkedin" :value="old('linkedin', $speaker->linkedin ?? '')" />
                <x-input label="Website" name="website" type="url" icon="globe" :value="old('website', $speaker->website ?? '')" />
                <x-input label="Photo" name="photo" type="file" icon="image" accept=".jpg,.png,.JPG,.PNG" />
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                <textarea id="bio" name="bio" rows="6"
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm resize-none"
                    placeholder="Brief speaker biography...">{{ old('bio', $speaker->bio ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('bio')" class="mt-1" />
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded hover:bg-teal-700 transition shadow-sm">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                    Update Speaker
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
