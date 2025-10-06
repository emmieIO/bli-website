<x-guest-layout>
    <section class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl lg:text-5xl font-bold text-orange-800 mb-4">Apply to Speak at {{ $event->title }}</h1>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto leading-relaxed">
                    Share your expertise with our audience. Complete the form below to apply as a speaker for this
                    event.
                </p>
                <div class="mt-6">
                    <div class="w-16 h-1 bg-orange-600 rounded-full mx-auto"></div>
                </div>
            </div>

            <!-- Application Form -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <form method="POST" action="{{ URL::signedRoute('event.speakers.store', $event) }}"
                    enctype="multipart/form-data" class="p-8">
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="mb-12">
                        <div class="flex items-center mb-8">
                            <div class="bg-orange-100 p-3 rounded-full mr-4">
                                <i data-lucide="user" class="w-6 h-6 text-orange-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Personal Information</h3>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors" />
                            </div>

                            <!-- Organization -->
                            <div>
                                <label for="organization"
                                    class="block text-gray-700 font-medium mb-2">Organization</label>
                                <input type="text" id="organization" name="organization"
                                    value="{{ old('organization', $application->speaker->organization ?? '') }}"
                                    placeholder="Your company or affiliation"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors" />
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
                                    <i data-lucide="image" class="w-5 h-5 mr-2 text-orange-600"></i>
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
                                                        alt="Profile Photo"
                                                        class="w-full h-full object-cover rounded-full">
                                                </a>
                                            @else
                                                <i data-lucide="user" class="w-8 h-8 text-gray-400"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <input type="file" id="photo" name="photo" accept="image/*"
                                            class="w-full px-3  border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors" />
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
                    <div class="mb-12">
                        <div class="flex items-center mb-8">
                            <div class="bg-orange-100 p-3 rounded-full mr-4">
                                <i data-lucide="briefcase" class="w-6 h-6 text-orange-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Professional Information</h3>
                        </div>

                        <!-- Bio -->
                        <div class="mb-8">
                            <label for="bio" class="block text-gray-700 font-medium mb-3 flex items-center">
                                <i data-lucide="file-text" class="w-5 h-5 mr-2 text-orange-600"></i>
                                Professional Bio
                            </label>
                            <p class="text-gray-500 text-sm mb-4">This will be displayed in the event program if you're
                                selected.</p>
                            <textarea id="bio" name="bio" rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors resize-none"
                                placeholder="Tell us about your professional background, achievements, and areas of expertise...">
                                {!! old('bio', $application->speaker->bio ?? '') !!}
                            </textarea>
                            @error('bio')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- LinkedIn -->
                            <div>
                                <label for="linkedin" class="block text-gray-700 font-medium mb-2 flex items-center">
                                    <i data-lucide="linkedin" class="w-5 h-5 mr-2 text-orange-600"></i>
                                    LinkedIn Profile
                                </label>
                                <input type="url" id="linkedin" name="linkedin"
                                    value="{{ old('linkedin', $application->speaker->linkedin ?? '') }}"
                                    placeholder="https://linkedin.com/in/yourprofile"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors" />
                            </div>

                            <!-- Website -->
                            <div>
                                <label for="website" class="block text-gray-700 font-medium mb-2 flex items-center">
                                    <i data-lucide="globe" class="w-5 h-5 mr-2 text-orange-600"></i>
                                    Website/Blog
                                </label>
                                <input type="url" id="website" name="website"
                                    value="{{ old('website', $application->speaker->website ?? '') }}"
                                    placeholder="https://yourwebsite.com"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors" />
                            </div>
                        </div>
                    </div>

                    <!-- Session Proposal Section -->
                    <div class="mb-12">
                        <div class="flex items-center mb-8">
                            <div class="bg-orange-100 p-3 rounded-full mr-4">
                                <i data-lucide="mic" class="w-6 h-6 text-orange-600"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Session Proposal</h3>
                        </div>

                        <!-- Topic Title -->
                        <div class="mb-8">
                            <label for="topic_title" class="block text-gray-700 font-medium mb-2">Proposed Topic
                                Title</label>
                            <input type="text" id="topic_title" name="topic_title"
                                value="{{ old('topic_title', $application->topic_title ?? '') }}"
                                placeholder="An engaging title that describes your session"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                required />
                        </div>

                        <!-- Topic Description -->
                        <div class="mb-8">
                            <label for="topic_description"
                                class="block text-gray-700 font-medium mb-3 flex items-center">
                                <i data-lucide="align-left" class="w-5 h-5 mr-2 text-orange-600"></i>
                                Session Description
                            </label>
                            <p class="text-gray-500 text-sm mb-4">What will attendees learn from your session? Be
                                specific and compelling.</p>
                            <textarea id="topic_description" name="topic_description" rows="8"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors resize-none"
                                placeholder="Describe your proposed session in detail, including key takeaways, target audience, and why this topic matters now...">{!! old('topic_description', $application->topic_description ?? '') !!}</textarea>
                            @error('topic_description')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session Format -->
                        <div class="mb-8">
                            <label class="block text-gray-700 font-medium mb-3 flex items-center">
                                <i data-lucide="grid" class="w-5 h-5 mr-2 text-orange-600"></i>
                                Preferred Session Format
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                @foreach (\App\Enums\SessionFormat::cases() as $format)
                                    <label
                                        class="flex items-center p-4 rounded-xl border border-gray-300 bg-white shadow-sm cursor-pointer hover:border-orange-500 hover:shadow-md transition-all duration-200">
                                        <input type="radio" name="session_format" value="{{ $format->value }}"
                                            class="form-radio h-4 w-4 text-orange-600 border-gray-300 focus:ring-orange-500 mr-3"
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
                                <i data-lucide="sticky-note" class="w-5 h-5 mr-2 text-orange-600"></i>
                                Additional Notes
                            </label>
                            <p class="text-gray-500 text-sm mb-4">Special requirements, equipment needs, accessibility
                                requests, or other information.</p>
                            <textarea id="notes" name="notes" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors resize-none"
                                placeholder="Any special requirements or additional information">
                                {{ old('notes', $application->notes ?? '') }}
                            </textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-8 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-end gap-4">
                            <a href="{{ url()->previous() }}"
                                class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-center font-medium flex items-center justify-center">
                                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-8 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 focus:ring-4 focus:ring-orange-200 transition-colors font-medium flex items-center justify-center">
                                <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                                Submit Application
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Custom styles for better textarea content display
        const style = document.createElement('style');
        style.textContent = `
            textarea {
                white-space: pre-wrap;
                word-wrap: break-word;
            }
            
            input:focus, textarea:focus {
                outline: none;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            }
            
            .transition-all {
                transition: all 0.2s ease-in-out;
            }
            
            /* Ensure Lucide icons are properly sized */
            [data-lucide] {
                width: 1em;
                height: 1em;
            }
        `;
        document.head.appendChild(style);
    </script>
</x-guest-layout>
