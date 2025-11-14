<x-guest-layout>
    <!-- Hero Section -->
    <section class="relative py-20 overflow-hidden"
        style="background: linear-gradient(135deg, #002147 0%, #003875 50%, #002147 100%);">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 rounded-full animate-pulse"
                style="background: linear-gradient(135deg, #00a651, #ed1c24);"></div>
            <div class="absolute bottom-10 right-10 w-24 h-24 rounded-full animate-bounce"
                style="background: linear-gradient(135deg, #ed1c24, #00a651);"></div>
            <div class="absolute top-1/2 left-1/4 w-16 h-16 rounded-full animate-ping"
                style="background: rgba(0, 166, 81, 0.3);"></div>
        </div>

        <div class="relative z-10 container mx-auto px-4">
            <div class="text-center max-w-5xl mx-auto">
                <div class="mb-8" data-aos="fade-down">
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-6"
                        style="background: rgba(0, 166, 81, 0.2); color: #00a651; border: 1px solid rgba(0, 166, 81, 0.3);">
                        <i class="fas fa-microphone mr-2"></i>
                        Speaker Application
                    </div>
                </div>

                <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6 font-montserrat leading-tight"
                    data-aos="fade-up">
                    Apply to Speak at <br>
                    <span class="bg-gradient-to-r from-yellow-300 to-orange-400 bg-clip-text text-transparent">
                        {{ $event->title }}
                    </span>
                </h1>

                <p class="text-gray-200 text-xl max-w-3xl mx-auto leading-relaxed mb-8 font-lato" data-aos="fade-up"
                    data-aos-delay="200">
                    Share your expertise with our audience and make an impact. Complete the application below to join
                    our distinguished speaker lineup.
                </p>

                <!-- Event Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-12 max-w-4xl mx-auto" data-aos="fade-up"
                    data-aos-delay="400">
                    <div class="p-4 rounded-2xl backdrop-filter backdrop-blur-lg"
                        style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl mx-auto mb-3"
                            style="background: rgba(0, 166, 81, 0.2);">
                            <i class="fas fa-calendar text-xl" style="color: #00a651;"></i>
                        </div>
                        <h3 class="font-semibold text-white mb-1 font-montserrat">Event Date</h3>
                        <p class="text-gray-300 text-sm font-lato">
                            {{ $event->start_date ? $event->start_date->format('M d, Y') : 'TBD' }}</p>
                    </div>

                    <div class="p-4 rounded-2xl backdrop-filter backdrop-blur-lg"
                        style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl mx-auto mb-3"
                            style="background: rgba(237, 28, 36, 0.2);">
                            <i class="fas fa-users text-xl" style="color: #ed1c24;"></i>
                        </div>
                        <h3 class="font-semibold text-white mb-1 font-montserrat">Expected Audience</h3>
                        <p class="text-gray-300 text-sm font-lato">{{ $event->max_attendees ?? '500+' }} Professionals
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl backdrop-filter backdrop-blur-lg"
                        style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl mx-auto mb-3"
                            style="background: rgba(0, 33, 71, 0.3);">
                            <i class="fas fa-clock text-xl" style="color: #ffffff;"></i>
                        </div>
                        <h3 class="font-semibold text-white mb-1 font-montserrat">Application Deadline</h3>
                        <p class="text-gray-300 text-sm font-lato">
                            {{ $event->speaker_deadline ? $event->speaker_deadline->format('M d, Y') : 'Rolling' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Application Form Section -->
    <section class="py-16" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
        <div class="container mx-auto px-4">

            <!-- Application Form -->
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden" data-aos="fade-up"
                data-aos-delay="100">
                <div class="bg-gradient-to-r from-@theme-primary to-blue-700 p-8 text-white text-center">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                        style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px);">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold font-montserrat mb-2">Speaker Application Form</h2>
                    <p class="text-blue-100 font-lato">Please complete all sections to submit your application</p>
                </div>

                <form method="POST" action="{{ URL::signedRoute('event.speakers.store', $event) }}"
                    enctype="multipart/form-data" class="p-10">
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="mb-12" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center mb-8">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mr-4"
                                style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%);">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold font-montserrat" style="color: #002147;">Personal
                                    Information</h3>
                                <p class="text-gray-500 font-lato">Your basic details and contact information</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-gray-700 font-medium mb-2">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                                    disabled />
                            </div>

                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-gray-700 font-medium mb-2">Professional
                                    Title</label>
                                <input type="text" id="title" name="title"
                                    value="{{ old('title', $application->speaker->title ?? null) }}"
                                    placeholder="E.g., CEO, Senior Developer, etc."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" />
                            </div>

                            <!-- Organization -->
                            <div>
                                <label for="organization"
                                    class="block text-gray-700 font-medium mb-2">Organization</label>
                                <input type="text" id="organization" name="organization"
                                    value="{{ old('organization', $application->speaker->organization ?? '') }}"
                                    placeholder="Your company or affiliation"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" />
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Email</label>
                                <div class="px-4 py-3 bg-gray-100 rounded-lg text-gray-600 border border-gray-200">
                                    {{ $application->speaker->email ?? auth()->user()->email }}
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                                <div class="px-4 py-3 bg-gray-100 rounded-lg text-gray-600 border border-gray-200">
                                    {{ $application->speaker->phone ?? auth()->user()->phone }}
                                </div>
                            </div>

                            <!-- Photo -->
                            <div class="md:col-span-2">
                                <label for="photo" class="block text-gray-700 font-medium mb-3 flex items-center">
                                    <i data-lucide="image" class="w-5 h-5 mr-2 text-primary"></i>
                                    Profile Photo
                                </label>
                                <div
                                    class="mt-4 flex flex-col sm:flex-row items-center gap-6 p-6 bg-gray-50 rounded-xl border border-gray-200">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border-2 border-gray-300">
                                            @if (!empty($application->speaker->photo))
                                                <a href="{{ asset('storage/' . $application->speaker->photo) }}"
                                                    target="_blank" rel="noopener" class="w-full h-full">
                                                    <img src="{{ asset('storage/' . $application->speaker->photo) }}"
                                                        alt="Profile Photo" class="w-full h-full object-cover rounded-full">
                                                </a>
                                            @else
                                                <i data-lucide="user" class="w-8 h-8 text-gray-400"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <input type="file" id="photo" name="photo" accept="image/*"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" />
                                        <p class="mt-2 text-sm text-gray-500">JPEG or PNG, max 2MB. Square images work
                                            best.</p>
                                        @error('photo')
                                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information Section -->
                    <div class="mb-12" data-aos="fade-up" data-aos-delay="300">
                        <div class="flex items-center mb-8">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mr-4"
                                style="background: linear-gradient(135deg, #ed1c24 0%, #dc2626 100%);">
                                <i class="fas fa-briefcase text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold font-montserrat" style="color: #002147;">Professional
                                    Information</h3>
                                <p class="text-gray-500 font-lato">Share your background and expertise</p>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="mb-8">
                            <label for="bio" class="block text-gray-700 font-medium mb-3 flex items-center">
                                <i data-lucide="file-text" class="w-5 h-5 mr-2 text-primary"></i>
                                Professional Bio
                            </label>
                            <p class="text-gray-500 text-sm mb-4">This will be displayed in the event program if you're
                                selected.</p>
                            <textarea id="bio" name="bio" rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors resize-none"
                                placeholder="Tell us about your professional background, achievements, and areas of expertise...">{!! old('bio', $application->speaker->bio ?? '') !!}</textarea>
                            @error('bio')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- LinkedIn -->
                            <div>
                                <label for="linkedin" class="block text-gray-700 font-medium mb-2 flex items-center">
                                    <i data-lucide="linkedin" class="w-5 h-5 mr-2 text-primary"></i>
                                    LinkedIn Profile
                                </label>
                                <input type="url" id="linkedin" name="linkedin"
                                    value="{{ old('linkedin', $application->speaker->linkedin ?? '') }}"
                                    placeholder="https://linkedin.com/in/yourprofile"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" />
                            </div>

                            <!-- Website -->
                            <div>
                                <label for="website" class="block text-gray-700 font-medium mb-2 flex items-center">
                                    <i data-lucide="globe" class="w-5 h-5 mr-2 text-primary"></i>
                                    Website/Blog
                                </label>
                                <input type="url" id="website" name="website"
                                    value="{{ old('website', $application->speaker->website ?? '') }}"
                                    placeholder="https://yourwebsite.com"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" />
                            </div>
                        </div>
                    </div>

                    <!-- Session Proposal Section -->
                    <div class="mb-12" data-aos="fade-up" data-aos-delay="400">
                        <div class="flex items-center mb-8">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mr-4"
                                style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
                                <i class="fas fa-microphone text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold font-montserrat" style="color: #002147;">Session Proposal
                                </h3>
                                <p class="text-gray-500 font-lato">Tell us about your proposed session</p>
                            </div>
                        </div>

                        <!-- Topic Title -->
                        <div class="mb-8">
                            <label for="topic_title" class="block text-gray-700 font-medium mb-2">Proposed Topic
                                Title</label>
                            <input type="text" id="topic_title" name="topic_title"
                                value="{{ old('topic_title', $application->topic_title ?? '') }}"
                                placeholder="An engaging title that describes your session"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                required />
                        </div>

                        <!-- Topic Description -->
                        <div class="mb-8">
                            <label for="topic_description"
                                class="block text-gray-700 font-medium mb-3 flex items-center">
                                <i data-lucide="align-left" class="w-5 h-5 mr-2 text-primary"></i>
                                Session Description
                            </label>
                            <p class="text-gray-500 text-sm mb-4">What will attendees learn from your session? Be
                                specific and compelling.</p>
                            <textarea id="topic_description" name="topic_description" rows="8"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors resize-none"
                                placeholder="Describe your proposed session in detail, including key takeaways, target audience, and why this topic matters now...">{!! old('topic_description', $application->topic_description ?? '') !!}</textarea>
                            @error('topic_description')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session Format -->
                        <div class="mb-8">
                            <label class="block text-gray-700 font-medium mb-3 flex items-center">
                                <i data-lucide="grid" class="w-5 h-5 mr-2 text-primary"></i>
                                Preferred Session Format
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                @foreach (\App\Enums\SessionFormat::cases() as $format)
                                    <label
                                        class="flex items-center p-4 rounded-xl border border-gray-300 bg-white shadow-sm cursor-pointer hover:border-primary hover:shadow-md transition-all duration-200">
                                        <input type="radio" name="session_format" value="{{ $format->value }}"
                                            class="form-radio h-4 w-4 text-primary border-gray-300 focus:ring-primary mr-3"
                                            {{ old('session_format', $application->session_format->value ?? '') === $format->value ? 'checked' : '' }}>
                                        <span class="text-gray-700 text-sm font-medium">
                                            {{ $format->displayName() }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('session_format')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="notes" class="block text-gray-700 font-medium mb-3 flex items-center">
                                <i data-lucide="sticky-note" class="w-5 h-5 mr-2 text-primary"></i>
                                Additional Notes
                            </label>
                            <p class="text-gray-500 text-sm mb-4">Special requirements, equipment needs, accessibility
                                requests, or other information.</p>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors resize-none"
                                placeholder="Any special requirements or additional information">{{ old('notes', $application->notes ?? '') }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-8 border-t border-gray-200" data-aos="fade-up" data-aos-delay="500">
                        <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                    style="background: rgba(0, 166, 81, 0.1);">
                                    <i class="fas fa-info-circle text-lg" style="color: #00a651;"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold font-montserrat mb-2" style="color: #002147;">Next Steps
                                    </h4>
                                    <p class="text-sm text-gray-600 font-lato leading-relaxed">
                                        After submitting your application, our team will review your proposal within 3-5
                                        business days.
                                        Selected speakers will be notified via email with further details about the
                                        event.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-4">
                            <a href="{{ url()->previous() }}"
                                class="px-8 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 text-center font-medium font-montserrat flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-8 py-4 text-white rounded-xl font-semibold font-montserrat transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center"
                                style="background: linear-gradient(135deg, #002147 0%, #ed1c24 100%);">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Submit Application
                            </button>
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
        });
    </script>
</x-guest-layout>