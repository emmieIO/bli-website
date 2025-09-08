<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-lg bg-[#00275E]/10">
                    <i data-lucide="User" class="w-6 h-6 text-[#00275E]"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Edit Instructor Profile</h1>
                    <p class="text-sm text-gray-500 mt-1">Update instructor details and manage their profile information</p>
                </div>
            </div>

            <!-- Back Button -->
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00275E] transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
            <form action="{{ route('admin.instructors.update', $instructor) }}" method="post" class="space-y-8" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Section: User Information -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">👤 User Information</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <x-input label="Full Name" name="name" :value="old('name', $instructor->user->name)" type="text" icon="user" required />
                        <x-input label="Email Address" name="email" :value="old('email', $instructor->user->email)" type="email" icon="mail" required />
                        <x-input label="Phone Number" name="phone" :value="old('phone', $instructor->user->phone)" type="tel" icon="phone" />
                        <x-input label="Professional Headline" name="headline" :value="old('headline', $instructor->headline)" type="text" icon="type" />
                    </div>
                </div>

                <!-- Section: Professional Bio -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">📝 Professional Bio</h2>
                    <div>
                        <label for="bio" class="block mb-2 text-sm font-medium text-gray-900">Bio (Tell us about yourself)</label>
                        <textarea id="bio" rows="5" name="bio"
                            class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E] resize-none"
                            placeholder="Write a short professional bio...">{{ old('bio', $instructor->bio) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Max 500 characters recommended.</p>
                    </div>
                </div>

                <!-- Section: Teaching & Experience -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">🎓 Teaching & Experience</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <x-input label="Years of Experience" name="experience_years" :value="old('experience_years', $instructor->experience_years)" type="number" min="0" required />
                        </div>
                        <div>
                            <label for="area_of_expertise" class="block mb-2 text-sm font-medium text-gray-900">Areas of Expertise</label>
                            <input type="text" id="area_of_expertise" name="area_of_expertise" value="{{ old('area_of_expertise', $instructor->area_of_expertise) }}"
                                class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E]"
                                placeholder="e.g. JavaScript, Python, UI/UX Design" />
                            <p class="mt-1 text-xs text-gray-500">Separate with commas (e.g. “React, Node.js, Agile”)</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="teaching_history" class="block mb-2 text-sm font-medium text-gray-900">Teaching Philosophy & History</label>
                        <textarea id="teaching_history" rows="5" name="teaching_history"
                            class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E] resize-none"
                            placeholder="Describe your teaching approach, past experience, and educational background...">{{ old('teaching_history', $instructor->teaching_history) }}</textarea>
                    </div>
                </div>

                <!-- Section: Media & Links -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">🔗 Media & Links</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <x-input label="Intro Video URL (YouTube/Vimeo)" name="intro_video_url" :value="old('intro_video_url', $instructor->intro_video_url)" type="url" placeholder="https://youtube.com/..." />
                        <x-input label="LinkedIn Profile URL" name="linkedin_url" :value="old('linkedin_url', $instructor->linkedin_url)" type="url" placeholder="https://linkedin.com/in/..." />
                        <x-input label="Personal Website" name="website" :value="old('website', $instructor->website)" type="url" placeholder="https://yourwebsite.com" />
                    </div>
                </div>

                <!-- Section: Documents & Status -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">📄 Documents & Status</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="resume_path" class="block mb-2 text-sm font-medium text-gray-900">Resume (PDF, DOCX)</label>
                            <input type="file" name="resume_path" id="resume_path" accept=".pdf,.doc,.docx"
                                class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E]
                                file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium
                                file:bg-[#00275E] file:text-white hover:file:bg-[#001a44] transition" />
                            @if($instructor->resume_path)
                                <p class="mt-2 text-xs text-gray-600">
                                    📄 Current: <a href="{{ asset('storage/' . $instructor->resume_path) }}" target="_blank" class="underline hover:text-[#00275E]">View Resume</a>
                                </p>
                            @endif
                        </div>

                        <div>
                            <label for="application_status" class="block mb-2 text-sm font-medium text-gray-900">Application Status</label>
                            <select id="application_status" name="application_status"
                                class="block w-full p-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E] transition">
                                @foreach(\App\Enums\ApplicationStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('application_status', $instructor->status) === $status->value)>
                                        {{ ucfirst(str_replace('_', ' ', $status->name)) }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Changing status may trigger notifications.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ url()->previous() }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-[#00275E] rounded-lg hover:bg-[#001a44] focus:ring-4 focus:ring-blue-300 focus:outline-none transition flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Instructor Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>