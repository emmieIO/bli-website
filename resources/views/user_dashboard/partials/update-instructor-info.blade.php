{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<div class="space-y-5">
    @if ($user->hasInstructorProfile())
        <div class="bg-white border border-primary-100 rounded-xl shadow-sm p-6" data-aos="fade-up"
            data-aos-duration="600">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 font-montserrat">
                <i data-lucide="book-open" class="w-5 h-5 text-primary-600"></i>
                Instructor Information
            </h3>

            <form action="{{ route('profile.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                @method('PATCH')

                {{-- Instructor-specific fields --}}
                <div class="relative md:col-span-2">
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                        Biography <span class="text-red-500">*</span>
                    </label>
                    <textarea name="bio" id="bio" rows="10" required
                        class="w-full rounded-lg resize-none border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm text-sm"
                        placeholder="Write a short professional bio...">{{ old('bio', $user->instructorProfile->bio ?? '') }}</textarea>
                    @error('bio')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative md:col-span-2">
                    <label for="expertise" class="block text-sm font-medium text-gray-700 mb-1">
                        Areas of Expertise
                    </label>
                    @php
                        $aoe = explode(',', $user->instructorProfile->area_of_expertise);
                    @endphp
                    <div class="flex" id="expertise-container">
                        @foreach ($aoe as $expertise)
                            <span
                                class="pill">{{ $expertise }}
                            </span>
                        @endforeach
                    </div>
                    <div>
                        <label for="expertise-input"
                            class="block ml-auto mb-2 text-sm font-medium text-gray-900 dark:text-white">Small input</label>
                        <input type="text" id="expertise-input"
                            class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-primary-500 focus:border-primary-500"
                            placeholder="enter area of expertise preceded with a comma."
                            onpaste="return false"
                            oncopy="return false"
                            autocomplete="off"
                            autocorrect="off"
                            pattern="^[A-Za-z,\-_]+$"
                            oninput="this.value = this.value.replace(/[^A-Za-z,\-_]/g, '')">
                    </div>
                    {{-- <textarea name="expertise" id="expertise" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm text-sm"
                        placeholder="List your areas of specialization (e.g. Leadership, Communication, Management)...">{{ old('expertise', $user->instructorProfile->expertise ?? '') }}</textarea> --}}
                    @error('expertise')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative md:col-span-2">
                    <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-1">
                        LinkedIn Profile
                    </label>
                    <input type="url" name="linkedin" id="linkedin"
                        class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm text-sm"
                        placeholder="https://linkedin.com/in/username"
                        vprimary="{{ old('linkedin', $user->instructorProfile->linkedin ?? '') }}">
                    @error('linkedin')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2 mt-4">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-lg transition-all duration-200 transform hover:scale-105 font-montserrat">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
