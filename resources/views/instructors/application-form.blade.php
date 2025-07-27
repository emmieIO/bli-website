@php
    $expertiseRaw = old('expertise') ?? ($profile->expertise ?? '');

    // Normalize to array
    $expertiseTags = is_array($expertiseRaw)
        ? $expertiseRaw
        : array_filter(array_map('trim', explode(',', $expertiseRaw)));
@endphp
<x-guest-layout>
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 md:p-10 rounded-xl shadow-sm">
                <div class="mb-8 border-b pb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Instructor Application</h1>
                    <p class="text-gray-600 mt-2">Complete all sections to submit your instructor profile</p>
                </div>

                <form method="POST" action="{{ route('instructors.submit-application', $user) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="save_as_draft" value="0" id="save_as_draft">

                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-8">
                            <!-- Personal Information Card -->
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                        <span
                                            class="flex items-center justify-center w-6 h-6 bg-teal-100 text-teal-800 rounded-full mr-3 text-sm font-medium">1</span>
                                        Personal Information
                                    </h3>
                                </div>
                                <div class="p-6 space-y-4">
                                    <div class="grid md:grid-cols-1 gap-4">
                                        <div>
                                            <label for="first_name"
                                                class="block text-sm font-medium text-gray-700 mb-1">Fullname*</label>
                                            <input type="text" id="name" name="name"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                                                value="{{ old('first_name', $user->name) }}">
                                            @error('first_name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="headline"
                                            class="block text-sm font-medium text-gray-700 mb-1">Professional
                                            Headline*</label>
                                        <input type="text" id="headline" name="headline"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                                            placeholder="e.g. Senior Web Developer, Education Specialist"
                                            value="{{ old('headline', $profile->headline) }}">
                                        @error('headline')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="bio"
                                            class="block text-sm font-medium text-gray-700 mb-1">Bio*</label>
                                        <textarea id="bio" name="bio" rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                                            placeholder="Tell us about yourself and your teaching philosophy">{{ old('bio', $profile->bio) }}</textarea>
                                        @error('bio')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="pt-2">
                                        <button type="submit" name='submit_section' value='personal'
                                            class="inline-flex cursor-pointer items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                            Save Personal Info
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Card -->
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                        <span
                                            class="flex items-center justify-center w-6 h-6 bg-teal-100 text-teal-800 rounded-full mr-3 text-sm font-medium">3</span>
                                        Documents & Media
                                    </h3>
                                </div>
                                <div class="p-6 space-y-4">
                                    <div>
                                        <label for="resume"
                                            class="block text-sm font-medium text-gray-700 mb-1">Resume/CV*</label>
                                        <div class="mt-1 flex items-center">
                                            <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx"
                                                class="block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-md file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-teal-50 file:text-teal-700
                                                hover:file:bg-teal-100">
                                        </div>
                                        @if ($profile->resume_path)
                                            <p class="mt-2 text-sm text-gray-500">Current file:
                                                {{ basename($profile->resume_path) }}</p>
                                        @endif
                                        @error('resume')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="video_url"
                                            class="block text-sm font-medium text-gray-700 mb-1">Intro Video
                                            URL*</label>
                                        <input type="url" id="video_url" name="video_url"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                                            placeholder="https://youtube.com/yourvideo"
                                            value="{{ old('video_url', $profile->video_url) }}">
                                        <p class="mt-1 text-sm text-gray-500">Upload a 1-2 minute introduction video to
                                            YouTube/Vimeo</p>
                                        @error('video_url')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="pt-2">
                                        <button type="button"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                            Save Documents
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-8">
                            <!-- Experience Card -->
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                        <span
                                            class="flex items-center justify-center w-6 h-6 bg-teal-100 text-teal-800 rounded-full mr-3 text-sm font-medium">2</span>
                                        Experience & Expertise
                                    </h3>
                                </div>
                                <div class="p-6 space-y-4">
                                    <div>
                                        <label for="experience"
                                            class="block text-sm font-medium text-gray-700 mb-1">Teaching
                                            Experience*</label>
                                        <textarea id="experience" name="experience" rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                                            placeholder="Describe your teaching or mentorship background. For example: courses taught, institutions, duration, or informal mentoring roles.">{{ old('experience', $profile->experience) }}</textarea>
                                        @error('experience')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="experience_years"
                                            class="block text-sm font-medium text-gray-700 mb-1">
                                            Years of Experience*
                                        </label>
                                        <select id="experience_years" name="experience_years"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                                            required>
                                            <option value="" disabled
                                                {{ old('experience_years', $profile->experience_years) === null ? 'selected' : '' }}>
                                                Select years</option>
                                            @for ($i = 0; $i <= 30; $i++)
                                                <option value="{{ $i }}"
                                                    {{ old('experience_years', $profile->experience_years) == $i ? 'selected' : '' }}>
                                                    {{ $i }} {{ Str::plural('year', $i) }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('experience_years')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>


                                    <div id="expertise-tags-container" data-tags='@json($expertiseTags)'>
                                        <!-- Hidden input that will contain the array as JSON -->
                                        <input type="hidden" name="expertise" id="expertise-input">

                                        <label class="block text-sm font-medium text-gray-700 mb-2">Areas of
                                            Expertise*</label>

                                        <!-- Display the tags/chips -->
                                        <div id="tags-container" class="flex flex-wrap gap-2 mb-2"></div>

                                        <!-- Input field for adding new tags -->
                                        <div class="relative">
                                            <input type="text" id="expertise-text-input"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                                                placeholder="Type expertise areas, separated by commas">
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <span class="text-gray-500 text-sm">Press comma or enter</span>
                                            </div>
                                        </div>

                                        <p class="mt-1 text-xs text-gray-500">
                                            Type your expertise areas and press comma or enter to create tags
                                        </p>

                                        @error('expertise')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>



                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="linkedin"
                                                class="block text-sm font-medium text-gray-700 mb-1">LinkedIn
                                                Profile</label>
                                            <input type="url" id="linkedin" name="linkedin"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                                                placeholder="https://linkedin.com/in/yourprofile"
                                                value="{{ old('linkedin', $profile->linkedin) }}">

                                        </div>

                                        <div>
                                            <label for="website"
                                                class="block text-sm font-medium text-gray-700 mb-1">Personal
                                                Website</label>
                                            <input type="url" id="website" name="website"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                                                placeholder="https://yourwebsite.com"
                                                value="{{ old('website', $profile->website) }}">
                                            @error('website')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="pt-2">
                                        <button type="submit" name="submit_section" value="experience"
                                            class="inline-flex cursor-pointer items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                            Save Experience
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Submission Card -->
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                        <span
                                            class="flex items-center justify-center w-6 h-6 bg-teal-100 text-teal-800 rounded-full mr-3 text-sm font-medium">4</span>
                                        Submit Application
                                    </h3>
                                </div>
                                <div class="p-6 space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="terms" name="terms" type="checkbox"
                                                class="focus:ring-teal-500 h-4 w-4 text-teal-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="terms" class="font-medium text-gray-700">I agree to the <a
                                                    href="#" class="text-teal-600 hover:underline">Instructor
                                                    Terms</a> and <a href="#"
                                                    class="text-teal-600 hover:underline">Privacy Policy</a></label>
                                            @error('terms')
                                                <p class="mt-1 text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        <button type="submit"
                                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                            Submit Application
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('expertise-tags-container');
            const input = document.getElementById('expertise-text-input');
            const tagsContainer = document.getElementById('tags-container');
            const hiddenInput = document.getElementById('expertise-input');

            // Load initial tags from data attribute
            let tags = JSON.parse(container.getAttribute('data-tags') || '[]');
            updateTags();

            function updateTags() {
                tagsContainer.innerHTML = '';
                tags.forEach((tag, index) => {
                    const tagEl = document.createElement('div');
                    tagEl.className =
                        'flex items-center bg-teal-100 text-teal-800 text-sm px-2 py-1 rounded-full';

                    const span = document.createElement('span');
                    span.textContent = tag;

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'ml-1 text-teal-500 hover:text-red-700 font-bold';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.addEventListener('click', () => {
                        tags.splice(index, 1);
                        updateTags();
                    });

                    tagEl.appendChild(span);
                    tagEl.appendChild(removeBtn);
                    tagsContainer.appendChild(tagEl);
                });

                // Update hidden input with current tags as JSON or CSV
                hiddenInput.value = tags.join(',');
            }

            input.addEventListener('keydown', function(e) {
                if (e.key === ',' || e.key === 'Enter') {
                    e.preventDefault();
                    const newTag = input.value.trim().replace(/,$/, '');
                    if (newTag && !tags.includes(newTag)) {
                        tags.push(newTag);
                        updateTags();
                    }
                    input.value = '';
                }
            });
        });
    </script>

</x-guest-layout>
