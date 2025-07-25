<!-- resources/views/instructor/application-form.blade.php -->
<x-guest-layout>
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 md:p-10 rounded-xl shadow-sm">
                <!-- Progress Steps -->
                <div class="mb-8">
                    <ol class="flex items-center justify-between w-full">
                        <li class="flex items-center text-teal-600 after:content-[''] after:w-full after:h-1 after:border-b after:border-teal-100 after:border-4 after:inline-block">
                            <span class="flex items-center justify-center w-10 h-10 bg-teal-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                                <svg class="w-5 h-5 text-teal-600 lg:w-6 lg:h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </li>
                        <li class="flex items-center text-teal-600 after:content-[''] after:w-full after:h-1 after:border-b after:border-teal-100 after:border-4 after:inline-block">
                            <span class="flex items-center justify-center w-10 h-10 bg-teal-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                                2
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500 after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-100 after:border-4 after:inline-block">
                            <span class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                                3
                            </span>
                        </li>
                        <li class="flex items-center text-gray-500">
                            <span class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                                4
                            </span>
                        </li>
                    </ol>
                    <div class="flex justify-between mt-2 text-xs font-medium text-gray-500">
                        <div>Email Verified</div>
                        <div>Personal Info</div>
                        <div>Experience</div>
                        <div>Submit</div>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-center text-gray-900 mb-2">Complete Your Instructor Profile</h2>
                <p class="text-gray-600 text-center mb-8">Step 2 of 4: Tell us about yourself</p>

                <form method="POST" action="{{ route("instructors.submit-application") }}" enctype="multipart/form-data" class="space-y-6" x-data="form" x-init="init">
                    @csrf
                    <input type="hidden" name="current_step" value="1">

                    <!-- Step 1: Personal Information -->
                    <div x-show="currentStep === 1" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" id="first_name" name="first_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"
                                    value="{{ old('first_name', $user->first_name) }}">
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" id="last_name" name="last_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"
                                    value="{{ old('last_name', $user->last_name) }}">
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="headline" class="block text-sm font-medium text-gray-700 mb-1">Professional Headline</label>
                            <input type="text" id="headline" name="headline" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"
                                placeholder="e.g. Senior Web Developer, Education Specialist"
                                value="{{ old('headline', $profile->headline) }}">
                            @error('headline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                            <textarea id="bio" name="bio" rows="4" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"
                                placeholder="Tell us about yourself and your teaching philosophy">{{ old('bio', $profile->bio) }}</textarea>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="button" @click="currentStep = 2"
                                class="bg-teal-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-teal-700 transition-colors">
                                Next: Experience
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Experience -->
                    <div x-show="currentStep === 2" class="space-y-6">
                        <div>
                            <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">Teaching Experience</label>
                            <textarea id="experience" name="experience" rows="4" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"
                                placeholder="Describe your teaching or mentoring experience">{{ old('experience', $profile->experience) }}</textarea>
                            @error('experience')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Areas of Expertise</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach(['Web Development', 'Data Science', 'Business', 'Design', 'Marketing', 'Photography', 'Music', 'Health', 'Language', 'Other'] as $expertise)
                                <div class="flex items-center">
                                    <input id="expertise_{{ Str::slug($expertise) }}" name="expertise[]" type="checkbox" value="{{ $expertise }}"
                                        class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
                                        {{ in_array($expertise, old('expertise', json_decode($profile->expertise ?? '[]', true) ?? [])) ? 'checked' : '' }}>
                                    <label for="expertise_{{ Str::slug($expertise) }}" class="ml-2 text-sm text-gray-700">{{ $expertise }}</label>
                                </div>
                                @endforeach
                            </div>
                            @error('expertise')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn Profile</label>
                                <input type="url" id="linkedin" name="linkedin"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"
                                    placeholder="https://linkedin.com/in/yourprofile"
                                    value="{{ old('linkedin', $profile->linkedin) }}">
                                @error('linkedin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Personal Website</label>
                                <input type="url" id="website" name="website"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"
                                    placeholder="https://yourwebsite.com"
                                    value="{{ old('website', $profile->website) }}">
                                @error('website')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" @click="currentStep = 1"
                                class="text-gray-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                Back
                            </button>
                            <button type="button" @click="currentStep = 3"
                                class="bg-teal-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-teal-700 transition-colors">
                                Next: Documents
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Documents -->
                    <div x-show="currentStep === 3" class="space-y-6">
                        <div>
                            <label for="resume" class="block text-sm font-medium text-gray-700 mb-1">Resume/CV</label>
                            <div class="mt-1 flex items-center">
                                <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx"
                                    class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-teal-50 file:text-teal-700
                                    hover:file:bg-teal-100">
                            </div>
                            @if($profile->resume_path)
                                <p class="mt-2 text-sm text-gray-500">Current file: {{ basename($profile->resume_path) }}</p>
                            @endif
                            @error('resume')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">Intro Video URL</label>
                            <input type="url" id="video_url" name="video_url" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500"
                                placeholder="https://youtube.com/yourvideo"
                                value="{{ old('video_url', $profile->video_url) }}">
                            <p class="mt-1 text-sm text-gray-500">Upload a 1-2 minute introduction video to YouTube/Vimeo and paste the link here</p>
                            @error('video_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between">
                            <button type="button" @click="currentStep = 2"
                                class="text-gray-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                Back
                            </button>
                            <button type="button" @click="currentStep = 4"
                                class="bg-teal-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-teal-700 transition-colors">
                                Next: Review
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Review & Submit -->
                    <div x-show="currentStep === 4" class="space-y-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="font-medium text-lg mb-4">Review Your Application</h3>

                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-medium text-gray-900">Personal Information</h4>
                                    <p class="text-gray-600" x-text="document.getElementById('first_name').value + ' ' + document.getElementById('last_name').value"></p>
                                    <p class="text-gray-600" x-text="document.getElementById('headline').value"></p>
                                </div>

                                <div>
                                    <h4 class="font-medium text-gray-900">Bio</h4>
                                    <p class="text-gray-600 whitespace-pre-line" x-text="document.getElementById('bio').value"></p>
                                </div>

                                <div>
                                    <h4 class="font-medium text-gray-900">Experience</h4>
                                    <p class="text-gray-600 whitespace-pre-line" x-text="document.getElementById('experience').value"></p>
                                </div>

                                <div>
                                    <h4 class="font-medium text-gray-900">Expertise</h4>
                                    <p class="text-gray-600" x-text="Array.from(document.querySelectorAll('input[name=\"expertise[]\"]:checked')).map(el => el.nextElementSibling.textContent).join(', ')"></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" name="terms" type="checkbox" required
                                    class="focus:ring-teal-500 h-4 w-4 text-teal-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="font-medium text-gray-700">I agree to the <a href="#" class="text-teal-600 hover:underline">Instructor Terms</a> and <a href="#" class="text-teal-600 hover:underline">Privacy Policy</a></label>
                                @error('terms')
                                    <p class="mt-1 text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" @click="currentStep = 3"
                                class="text-gray-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                Back
                            </button>
                            <button type="submit"
                                class="bg-teal-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-teal-700 transition-colors">
                                Submit Application
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- AlpineJS for multi-step form -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('form', () => ({
                currentStep: 1,

                init() {
                    // Set initial step from server if validation failed
                    const currentStep = document.querySelector('input[name="current_step"]')?.value;
                    if (currentStep) {
                        this.currentStep = parseInt(currentStep);
                    }
                }
            }));
        });
    </script>
</x-guest-layout>
