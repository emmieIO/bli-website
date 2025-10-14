<x-app-layout>
    <div class="py-10 px-4 sm:px-6 mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-lg bg-[#00275E]/10">
                    <i data-lucide="mic" class="w-6 h-6 text-[#00275E]"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-[#00275E]">Create New Speaker</h2>
                    <p class="text-sm text-gray-500 mt-1">Fill in the details to add a new speaker to your platform.</p>
                </div>
            </div>

            <a href="{{ route('admin.speakers.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-[#00275E] bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E] transition">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Speakers
            </a>
        </div>

        <!-- Status Tabs -->
        <x-speakers-applications-tabs class="mb-8" />

        <!-- Form Card -->
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 md:p-8">
            <form action="{{ route('admin.speakers.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf

                <!-- Section: Personal & Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">👤 Personal & Contact Info</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-input label="Full Name" name="name" required icon="user" :value="old('name')" />
                        <x-input label="Professional headline" name="headline" icon="briefcase" :value="old('headline')" />
                        <x-input label="Organization" name="organization" icon="building-2" :value="old('organization')" />
                        <x-input label="Email Address" name="email" type="email" required icon="mail" :value="old('email')" />
                        <x-input label="Phone Number" name="phone" icon="phone" :value="old('phone')" />
                        <x-input label="Password" name="password" type="password" icon="key"  />
                        <x-input label="Password Confirmation" type="password" name="password_confirmation" icon="key" />

                        <x-input label="LinkedIn Profile" name="linkedin" type="url" icon="linkedin" placeholder="https://linkedin.com/in/..." :value="old('linkedin')" />
                        <x-input label="Personal Website" name="website" type="url" icon="globe" placeholder="https://yoursite.com" :value="old('website')" />
                    </div>
                </div>

                <!-- Section: Speaker Bio -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">📝 Speaker Bio</h3>
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio (Public-facing description)</label>
                        <textarea id="bio" name="bio" rows="6"
                            class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E] resize-none"
                            placeholder="Write a compelling bio that highlights expertise, achievements, and speaking style...">{{ old('bio') }}</textarea>
                        <x-input-error :messages="$errors->get('bio')" class="mt-1" />
                        <p class="mt-2 text-xs text-gray-500">Ideal length: 100–300 words. This will be shown on event pages.</p>
                    </div>
                </div>

                <!-- Section: Photo Upload -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">🖼️ Profile Photo</h3>
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Upload Photo (JPG/PNG)</label>
                        <input type="file" name="photo" id="photo" accept=".jpg,.jpeg,.png,.JPG,.PNG"
                            class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E]
                            file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium
                            file:bg-[#00275E] file:text-white hover:file:bg-[#FF0000] transition" />
                        <p class="mt-2 text-xs text-gray-500">Recommended size: 400x400px or higher. Max file size: 5MB.</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.speakers.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 inline-flex items-center text-sm font-medium text-white bg-[#00275E] rounded-lg hover:bg-[#FF0000] focus:ring-4 focus:ring-blue-300 focus:outline-none transition">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        Save Speaker
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>