@php
    $expertiseRaw = old('expertise') ?? ($profile->area_of_expertise ?? '');

    // Normalize to array
    $expertiseTags = is_array($expertiseRaw)
        ? $expertiseRaw
        : array_filter(array_map('trim', explode(',', $expertiseRaw)));
@endphp
<x-guest-layout>
    <!-- Enhanced Application Form Section -->
    <section class="min-h-screen py-12 bg-gradient-to-br from-gray-50 via-white to-gray-100 font-lato">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12" data-aos="fade-up">
                <div class="inline-flex items-center px-4 py-2 rounded-full mb-6"
                    style="background: rgba(0, 33, 71, 0.1);">
                    <i class="fas fa-file-alt mr-2" style="color: #002147;"></i>
                    <span class="text-sm font-semibold font-montserrat" style="color: #002147;">Application Form</span>
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 font-montserrat" style="color: #002147;">
                    Instructor Application
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto font-lato">
                    Complete all sections below to submit your instructor profile for review
                </p>

                <!-- Progress Indicator -->
                <div class="flex items-center justify-center mt-8 space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full" style="background: #00a651;"></div>
                        <span class="text-sm font-medium" style="color: #00a651;">Personal Info</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full" style="background: #ed1c24;"></div>
                        <span class="text-sm font-medium" style="color: #ed1c24;">Experience</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full" style="background: #002147;"></div>
                        <span class="text-sm font-medium" style="color: #002147;">Documents</span>
                    </div>
                    <div class="w-8 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full" style="background: #00a651;"></div>
                        <span class="text-sm font-medium" style="color: #00a651;">Submit</span>
                    </div>
                </div>
            </div>

            <!-- Main Form Container -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100" data-aos="fade-up"
                data-aos-delay="200">
                <form method="POST" action="{{ route('instructors.submit-application', $user) }}"
                    enctype="multipart/form-data" class="p-8 md:p-12">
                    @csrf

                    <div class="grid lg:grid-cols-2 gap-12">
                        <!-- Left Column -->
                        <div class="space-y-8">
                            <!-- Personal Information Card -->
                            <div class="bg-gradient-to-br from-white to-gray-50 border-2 border-gray-100 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300"
                                data-aos="fade-right">
                                <div class="px-8 py-6 border-b border-gray-200"
                                    style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%);">
                                    <h3 class="text-xl font-bold text-white flex items-center font-montserrat">
                                        <div
                                            class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-user text-lg"></i>
                                        </div>
                                        Personal Information
                                        <span
                                            class="ml-auto bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm">Step
                                            1</span>
                                    </h3>
                                </div>
                                <div class="p-8 space-y-6">
                                    <!-- Full Name Field -->
                                    <div>
                                        <label for="name" class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-user mr-2" style="color: #00a651;"></i>Full Name
                                        </label>
                                        <div class="relative">
                                            <input type="text" id="name" name="name" readonly
                                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-50 font-lato"
                                                value="{{ old('first_name', $user->name) }}">
                                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                                <i class="fas fa-lock text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('name')
                                            <p class="mt-2 text-sm flex items-center" style="color: #ed1c24;">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Phone Number Field -->
                                    <div>
                                        <label for="phone" class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-phone mr-2" style="color: #ed1c24;"></i>Phone Number *
                                        </label>
                                        <input type="tel" id="phone" name="phone"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                            value="{{ old('phone', $user->phone ?? '') }}"
                                            placeholder="e.g. +234 803 123 4567"
                                            onfocus="this.style.borderColor='#ed1c24'"
                                            onblur="this.style.borderColor='#e5e7eb'">
                                        @error('phone')
                                            <p class="mt-2 text-sm flex items-center" style="color: #ed1c24;">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Professional Headline Field -->
                                    <div>
                                        <label for="headline" class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-briefcase mr-2" style="color: #00a651;"></i>Professional
                                            Headline *
                                        </label>
                                        <input type="text" id="headline" name="headline"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                            placeholder="e.g. Senior Web Developer & Tech Educator"
                                            value="{{ old('headline', $profile->user->headline) }}"
                                            onfocus="this.style.borderColor='#00a651'"
                                            onblur="this.style.borderColor='#e5e7eb'">
                                        @error('headline')
                                            <p class="mt-2 text-sm flex items-center" style="color: #ed1c24;">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Bio Field -->
                                    <div>
                                        <label for="bio" class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-edit mr-2" style="color: #002147;"></i>Professional Bio *
                                        </label>
                                        <textarea id="bio" name="bio" rows="4"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato resize-none"
                                            placeholder="Share your professional background, achievements, and what makes you a great instructor..."
                                            onfocus="this.style.borderColor='#002147'"
                                            onblur="this.style.borderColor='#e5e7eb'">{{ old('bio', $profile->bio) }}</textarea>
                                        @error('bio')
                                            <p class="mt-2 text-sm flex items-center" style="color: #ed1c24;">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Save Button -->
                                    <div class="pt-4">
                                        <button type="submit" name='submit_section' value='personal'
                                            class="w-full inline-flex cursor-pointer items-center justify-center px-6 py-3 rounded-xl text-sm font-bold transition-all duration-300 transform hover:scale-105 hover:shadow-lg font-montserrat"
                                            style="background: #00a651; color: white;"
                                            onmouseover="this.style.background='#15803d'"
                                            onmouseout="this.style.background='#00a651'">
                                            <i class="fas fa-save mr-2"></i>
                                            Save Personal Information
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Card -->
                            <div class="bg-gradient-to-br from-white to-gray-50 border-2 border-gray-100 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300"
                                data-aos="fade-right" data-aos-delay="200">
                                <div class="px-8 py-6 border-b border-gray-200"
                                    style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
                                    <h3 class="text-xl font-bold text-white flex items-center font-montserrat">
                                        <div
                                            class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-file-upload text-lg"></i>
                                        </div>
                                        Documents & Media
                                        <span
                                            class="ml-auto bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm">Step
                                            3</span>
                                    </h3>
                                </div>
                                <div class="p-8 space-y-6">
                                    <!-- Resume/CV Upload -->
                                    <div>
                                        <label for="resume" class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-file-alt mr-2" style="color: #ed1c24;"></i>Resume/CV *
                                        </label>
                                        <div
                                            class="border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-gray-400 transition-all duration-300">
                                            <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx"
                                                class="hidden" onchange="handleFileSelect(this, 'resume-info')">
                                            <div class="text-center">
                                                <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4"
                                                    style="background: rgba(237, 28, 36, 0.1);">
                                                    <i class="fas fa-cloud-upload-alt text-2xl"
                                                        style="color: #ed1c24;"></i>
                                                </div>
                                                <label for="resume"
                                                    class="cursor-pointer inline-flex items-center px-6 py-3 rounded-xl font-bold text-white transition-all duration-300 hover:shadow-lg font-montserrat"
                                                    style="background: #ed1c24;"
                                                    onmouseover="this.style.background='#dc2626'"
                                                    onmouseout="this.style.background='#ed1c24'">
                                                    <i class="fas fa-upload mr-2"></i>
                                                    Choose File
                                                </label>
                                                <p class="text-sm text-gray-500 mt-2 font-lato">PDF, DOC, or DOCX files
                                                    only</p>
                                            </div>
                                            <div id="resume-info" class="mt-4 hidden">
                                                <div
                                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-file text-gray-600 mr-2"></i>
                                                        <span class="text-sm font-medium" id="resume-name"></span>
                                                    </div>
                                                    <i class="fas fa-check-circle" style="color: #00a651;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($profile->resume_path)
                                            <div class="mt-3 p-3 rounded-lg" style="background: rgba(0, 166, 81, 0.1);">
                                                <p class="text-sm font-medium" style="color: #00a651;">
                                                    <i class="fas fa-file-check mr-2"></i>
                                                    Current file: {{ basename($profile->resume_path) }}
                                                </p>
                                            </div>
                                        @endif
                                        @error('resume')
                                            <p class="mt-2 text-sm flex items-center" style="color: #ed1c24;">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Video URL -->
                                    <div>
                                        <label for="video_url" class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-video mr-2" style="color: #00a651;"></i>Introduction Video
                                            URL *
                                        </label>
                                        <input type="url" id="video_url" name="video_url"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                            placeholder="https://youtube.com/watch?v=your-video"
                                            value="{{ old('video_url', $profile->intro_video_url) }}"
                                            onfocus="this.style.borderColor='#00a651'"
                                            onblur="this.style.borderColor='#e5e7eb'">
                                        <div class="mt-3 p-4 rounded-lg" style="background: rgba(0, 166, 81, 0.1);">
                                            <p class="text-sm font-lato" style="color: #002147;">
                                                <i class="fas fa-lightbulb mr-2" style="color: #00a651;"></i>
                                                <strong>Pro Tip:</strong> Create a 1-2 minute introduction video on
                                                YouTube, Vimeo, or Loom.
                                                This helps us understand your teaching style and communication skills.
                                            </p>
                                        </div>
                                        @error('video_url')
                                            <p class="mt-2 text-sm flex items-center" style="color: #ed1c24;">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Save Button -->
                                    <div class="pt-4">
                                        <button type="submit" name="submit_section" value="docs"
                                            class="w-full inline-flex cursor-pointer items-center justify-center px-6 py-3 rounded-xl text-sm font-bold transition-all duration-300 transform hover:scale-105 hover:shadow-lg font-montserrat"
                                            style="background: #002147; color: white;"
                                            onmouseover="this.style.background='#003875'"
                                            onmouseout="this.style.background='#002147'">
                                            <i class="fas fa-save mr-2"></i>
                                            Save Documents & Media
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-8">
                            <!-- Experience Card -->
                            <div class="bg-gradient-to-br from-white to-gray-50 border-2 border-gray-100 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300"
                                data-aos="fade-left">
                                <div class="px-8 py-6 border-b border-gray-200"
                                    style="background: linear-gradient(135deg, #ed1c24 0%, #dc2626 100%);">
                                    <h3 class="text-xl font-bold text-white flex items-center font-montserrat">
                                        <div
                                            class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-graduation-cap text-lg"></i>
                                        </div>
                                        Experience & Expertise
                                        <span
                                            class="ml-auto bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm">Step
                                            2</span>
                                    </h3>
                                </div>
                                <div class="p-8 space-y-6">
                                    <!-- Teaching Experience -->
                                    <div>
                                        <label for="experience" class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-chalkboard-teacher mr-2"
                                                style="color: #ed1c24;"></i>Teaching Experience *
                                        </label>
                                        <textarea id="experience" name="experience" rows="4"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato resize-none"
                                            placeholder="Describe your teaching or mentorship background. Include courses taught, institutions, duration, or informal mentoring roles..."
                                            onfocus="this.style.borderColor='#ed1c24'"
                                            onblur="this.style.borderColor='#e5e7eb'">{{ old('experience', $profile->teaching_history) }}</textarea>
                                        @error('experience')
                                            <p class="mt-2 text-sm flex items-center" style="color: #ed1c24;">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Years of Experience -->
                                    <div>
                                        <label for="experience_years"
                                            class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-calendar-alt mr-2" style="color: #00a651;"></i>Years of
                                            Experience *
                                        </label>
                                        <select id="experience_years" name="experience_years"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                            required onfocus="this.style.borderColor='#00a651'"
                                            onblur="this.style.borderColor='#e5e7eb'">
                                            <option value="" disabled {{ old('experience_years', $profile->experience_years) === null ? 'selected' : '' }}>
                                                Select your years of experience</option>
                                            @for ($i = 0; $i <= 30; $i++)
                                                <option value="{{ $i }}" {{ old('experience_years', $profile->experience_years) == $i ? 'selected' : '' }}>
                                                    {{ $i }} {{ Str::plural('year', $i) }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('experience_years')
                                            <p class="mt-2 text-sm flex items-center" style="color: #ed1c24;">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Areas of Expertise -->
                                    <div id="expertise-tags-container" data-tags='@json($expertiseTags)'>
                                        <input type="hidden" name="expertise" id="expertise-input">

                                        <label class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-tags mr-2" style="color: #002147;"></i>Areas of Expertise *
                                        </label>

                                        <!-- Tags Display -->
                                        <div id="tags-container" class="flex flex-wrap gap-2 mb-3"></div>

                                        <!-- Input Field -->
                                        <div class="relative">
                                            <input type="text" id="expertise-text-input"
                                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                                placeholder="Type an expertise area and press Enter or comma..."
                                                onfocus="this.style.borderColor='#002147'"
                                                onblur="this.style.borderColor='#e5e7eb'">
                                            <div
                                                class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                                <span class="text-gray-400 text-sm font-lato">Press ⏎ or ,</span>
                                            </div>
                                        </div>

                                        <div class="mt-3 p-4 rounded-lg" style="background: rgba(0, 33, 71, 0.1);">
                                            <p class="text-sm font-lato" style="color: #002147;">
                                                <i class="fas fa-lightbulb mr-2" style="color: #00a651;"></i>
                                                <strong>Examples:</strong> Web Development, Data Science, Digital
                                                Marketing, UI/UX Design, Project Management
                                            </p>
                                        </div>

                                        @error('expertise')
                                            <p class="mt-2 text-sm flex items-center" style="color: #ed1c24;">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Social Links -->
                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="linkedin"
                                                class="block text-sm font-semibold mb-2 font-montserrat"
                                                style="color: #002147;">
                                                <i class="fab fa-linkedin mr-2" style="color: #0077B5;"></i>LinkedIn
                                                Profile
                                            </label>
                                            <input type="url" id="linkedin" name="linkedin"
                                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                                placeholder="https://linkedin.com/in/yourprofile"
                                                value="{{ old('linkedin', $profile->user->linkedin) }}"
                                                onfocus="this.style.borderColor='#0077B5'"
                                                onblur="this.style.borderColor='#e5e7eb'">
                                        </div>

                                        <div>
                                            <label for="website"
                                                class="block text-sm font-semibold mb-2 font-montserrat"
                                                style="color: #002147;">
                                                <i class="fas fa-globe mr-2" style="color: #00a651;"></i>Personal
                                                Website
                                            </label>
                                            <input type="url" id="website" name="website"
                                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                                placeholder="https://yourwebsite.com"
                                                value="{{ old('website', $profile->user->website) }}"
                                                onfocus="this.style.borderColor='#00a651'"
                                                onblur="this.style.borderColor='#e5e7eb'">
                                            @error('website')
                                                <p class="mt-2 text-sm flex items-center" style="color: #ed1c24;">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="pt-4">
                                        <button type="submit" name="submit_section" value="experience"
                                            class="w-full inline-flex cursor-pointer items-center justify-center px-6 py-3 rounded-xl text-sm font-bold transition-all duration-300 transform hover:scale-105 hover:shadow-lg font-montserrat"
                                            style="background: #ed1c24; color: white;"
                                            onmouseover="this.style.background='#dc2626'"
                                            onmouseout="this.style.background='#ed1c24'">
                                            <i class="fas fa-save mr-2"></i>
                                            Save Experience & Expertise
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Enhanced Submission Card -->
                            <div class="bg-gradient-to-br from-white to-gray-50 border-2 border-gray-100 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300"
                                data-aos="fade-left" data-aos-delay="400">
                                <div class="px-8 py-6 border-b border-gray-200"
                                    style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%);">
                                    <h3 class="text-xl font-bold text-white flex items-center font-montserrat">
                                        <div
                                            class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-paper-plane text-lg"></i>
                                        </div>
                                        Submit Application
                                        <span
                                            class="ml-auto bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm">Final
                                            Step</span>
                                    </h3>
                                </div>
                                <div class="p-8 space-y-6">
                                    <!-- Terms & Conditions -->
                                    <div class="p-6 rounded-xl border-2 border-dashed border-gray-300">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex items-center h-6">
                                                <input id="terms" name="terms" type="checkbox"
                                                    class="h-5 w-5 rounded border-2 transition-all duration-300"
                                                    style="accent-color: #00a651; border-color: #d1d5db;"
                                                    onchange="toggleSubmitButton()">
                                            </div>
                                            <div class="flex-1">
                                                <label for="terms"
                                                    class="font-semibold text-gray-700 font-montserrat cursor-pointer">
                                                    I agree to the terms and conditions
                                                </label>
                                                <p class="text-sm text-gray-600 mt-2 font-lato">
                                                    By checking this box, you agree to our
                                                    <a href="#" class="font-semibold hover:underline"
                                                        style="color: #002147;">Instructor Terms</a>,
                                                    <a href="#" class="font-semibold hover:underline"
                                                        style="color: #002147;">Privacy Policy</a>,
                                                    and <a href="#" class="font-semibold hover:underline"
                                                        style="color: #002147;">Code of Conduct</a>.
                                                </p>
                                                @error('terms')
                                                    <p class="mt-2 text-sm flex items-center" style="color: #ed1c24;">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                    </p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Application Summary -->
                                    <div class="p-6 rounded-xl" style="background: rgba(0, 166, 81, 0.1);">
                                        <h4 class="font-bold mb-3 font-montserrat flex items-center"
                                            style="color: #002147;">
                                            <i class="fas fa-clipboard-list mr-2" style="color: #00a651;"></i>
                                            Application Review Process
                                        </h4>
                                        <ul class="space-y-2 text-sm font-lato" style="color: #002147;">
                                            <li class="flex items-center">
                                                <i class="fas fa-check-circle mr-2 text-xs" style="color: #00a651;"></i>
                                                Immediate confirmation email sent
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-clock mr-2 text-xs" style="color: #ed1c24;"></i>
                                                Review within 5-7 business days
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-envelope mr-2 text-xs" style="color: #002147;"></i>
                                                Updates sent via email
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="pt-4">
                                        <button type="submit" name="submit_section" value="finalize" id="submit-btn"
                                            class="w-full py-4 px-6 rounded-xl text-lg font-bold transition-all duration-300 font-montserrat disabled:opacity-50 disabled:cursor-not-allowed"
                                            style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%); color: white;"
                                            disabled
                                            onmouseover="if(!this.disabled) { this.style.transform='scale(1.02)'; this.style.boxShadow='0 10px 30px rgba(0, 166, 81, 0.3)' }"
                                            onmouseout="if(!this.disabled) { this.style.transform='scale(1)'; this.style.boxShadow='none' }">
                                            <i class="fas fa-rocket mr-2"></i>
                                            Submit My Application
                                        </button>
                                        <p class="text-center text-sm text-gray-500 mt-3 font-lato">
                                            <i class="fas fa-shield-alt mr-1" style="color: #00a651;"></i>
                                            Your information is secure and will only be used for application review
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function () {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 100
            });

            // Expertise Tags Functionality
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
                    tagEl.className = 'group flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:shadow-lg transform hover:scale-105';
                    tagEl.style.background = 'linear-gradient(135deg, rgba(0, 33, 71, 0.1) 0%, rgba(0, 166, 81, 0.1) 100%)';
                    tagEl.style.color = '#002147';
                    tagEl.style.border = '2px solid rgba(0, 166, 81, 0.3)';

                    const span = document.createElement('span');
                    span.textContent = tag;
                    span.className = 'font-montserrat';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'ml-2 w-5 h-5 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300 hover:scale-110';
                    removeBtn.style.background = '#ed1c24';
                    removeBtn.style.color = 'white';
                    removeBtn.innerHTML = '×';
                    removeBtn.addEventListener('click', () => {
                        tags.splice(index, 1);
                        updateTags();
                    });

                    tagEl.appendChild(span);
                    tagEl.appendChild(removeBtn);
                    tagsContainer.appendChild(tagEl);
                });

                // Update hidden input with current tags
                hiddenInput.value = tags.join(',');
            }

            input.addEventListener('keydown', function (e) {
                if (e.key === ',' || e.key === 'Enter') {
                    e.preventDefault();
                    const newTag = input.value.trim().replace(/,$/, '');
                    if (newTag && !tags.includes(newTag)) {
                        tags.push(newTag);
                        updateTags();
                        // Add visual feedback
                        input.style.borderColor = '#00a651';
                        setTimeout(() => {
                            input.style.borderColor = '#e5e7eb';
                        }, 500);
                    }
                    input.value = '';
                }
            });
        });

        // File Upload Handler
        function handleFileSelect(input, infoId) {
            const file = input.files[0];
            const info = document.getElementById(infoId);
            const nameSpan = document.getElementById(infoId.replace('-info', '-name'));

            if (file) {
                info.classList.remove('hidden');
                nameSpan.textContent = file.name;
            }
        }

        // Submit Button Toggle
        function toggleSubmitButton() {
            const checkbox = document.getElementById('terms');
            const submitBtn = document.getElementById('submit-btn');

            if (checkbox.checked) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                submitBtn.style.background = 'linear-gradient(135deg, #00a651 0%, #15803d 100%)';
            } else {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
                submitBtn.style.background = '#9ca3af';
            }
        }

        // Form Section Progress
        document.addEventListener('DOMContentLoaded', function () {
            // Add smooth scroll to sections
            const progressDots = document.querySelectorAll('.progress-dot');
            progressDots.forEach(dot => {
                dot.addEventListener('click', function () {
                    const target = this.getAttribute('data-target');
                    document.getElementById(target).scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });
            });

            // Add form validation feedback
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('blur', function () {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.style.borderColor = '#ed1c24';
                    } else if (this.value.trim()) {
                        this.style.borderColor = '#00a651';
                    }
                });
            });
        });
    </script>
</x-guest-layout>