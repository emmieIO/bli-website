<x-guest-layout>
    <section class="bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#00275E] mb-3">Apply to Speak at {{ $event->title }}</h1>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Share your expertise with our audience. Complete the form below to apply as a speaker for this event.
                </p>
                <div class="mt-4">
                    <span class="inline-block h-1 w-20 bg-[#00275E] rounded-full"></span>
                </div>
            </div>

            <!-- Application Form -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <form method="POST" action="{{ URL::signedRoute('event.speakers.store', $event) }}"
                    enctype="multipart/form-data" class="p-6 md:p-8">
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="bg-[#00275E]/10 p-2.5 rounded-full mr-3">
                                <i data-lucide="user" class="w-5 h-5 text-[#00275E]"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">
                                Personal Information
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input label="Full Name" name="name" disabled autofocus :value="auth()->user()->name" icon="user" required />
                            </div>

                            <!-- Title -->
                            <div>
                                <x-input label="Professional Title" name="title"
                                    :value="old('title', $application->speaker->title ?? null)"
                                    placeholder="E.g., CEO, Senior Developer, etc." icon="briefcase" />
                            </div>

                            <!-- Organization -->
                            <div>
                                <x-input label="Organization" name="organization"
                                    :value="old('organization', $application->speaker->organization ?? '')"
                                    placeholder="Your company or affiliation" icon="building-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <div class="px-4 py-3 bg-gray-100 rounded-lg text-gray-700 border border-gray-200">
                                    {{ $application->speaker->email ?? auth()->user()->email }}
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <div class="px-4 py-3 bg-gray-100 rounded-lg text-gray-700 border border-gray-200">
                                    {{ $application->speaker->phone ?? auth()->user()->phone }}
                                </div>
                            </div>

                            <!-- Photo -->
                            <div class="md:col-span-2">
                                <label for="photo" class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <i data-lucide="image" class="w-4 h-4 mr-2 text-[#00275E]"></i>
                                    Profile Photo
                                </label>
                                <div class="mt-3 flex flex-col sm:flex-row sm:items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex-shrink-0">
                                        <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border-2 border-gray-300">
                                            @if (!empty($application->speaker->photo))
                                                <a href="{{ asset('storage/' . $application->speaker->photo) }}" target="_blank" rel="noopener">
                                                    <img src="{{ asset('storage/' . $application->speaker->photo) }}" alt="Profile Photo" class="h-20 w-20 object-cover rounded-full">
                                                </a>
                                            @else
                                                <i data-lucide="user" class="w-10 h-10 text-gray-400"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" id="photo" name="photo" accept="image/*"
                                            class="block w-full text-sm text-gray-700
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-md file:border-0
                                            file:text-sm file:font-medium
                                            file:bg-[#00275E] file:text-white
                                            hover:file:bg-[#FF0000]
                                            focus:outline-none focus:ring-2 focus:ring-[#00275E] focus:ring-offset-2" />
                                        <p class="mt-2 text-xs text-gray-500">JPEG or PNG, max 2MB. Square images work best.</p>
                                        @error('photo')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information Section -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="bg-[#00275E]/10 p-2.5 rounded-full mr-3">
                                <i data-lucide="briefcase" class="w-5 h-5 text-[#00275E]"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">
                                Professional Information
                            </h3>
                        </div>

                        <!-- Bio -->
                        <div class="mb-6">
                            <label for="bio" class=" text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i data-lucide="file-text" class="w-4 h-4 mr-2 text-[#00275E]"></i>
                                Professional Bio
                            </label>
                            <p class="text-xs text-gray-500 mb-3">This will be displayed in the event program if you're selected.</p>
                            <textarea id="bio" name="bio" rows="5"
                                class="block w-full p-3 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E] resize-none"
                                placeholder="Tell us about your professional background, achievements, and areas of expertise...">{!! old('bio', $application->speaker->bio ?? '') !!}</textarea>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- LinkedIn -->
                            <div>
                                <x-input label="LinkedIn Profile" name="linkedin" type="url"
                                    :value="old('linkedin', $application->speaker->linkedin ?? '')"
                                    placeholder="https://linkedin.com/in/yourprofile" icon="linkedin" />
                            </div>

                            <!-- Website -->
                            <div>
                                <x-input label="Website/Blog" name="website" type="url"
                                    :value="old('website', $application->speaker->website ?? '')"
                                    placeholder="https://yourwebsite.com" icon="globe" />
                            </div>
                        </div>
                    </div>

                    <!-- Session Proposal Section -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="bg-[#00275E]/10 p-2.5 rounded-full mr-3">
                                <i data-lucide="presentation" class="w-5 h-5 text-[#00275E]"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">
                                Session Proposal
                            </h3>
                        </div>

                        <!-- Topic Title -->
                        <div class="mb-6">
                            <x-input label="Proposed Topic Title" name="topic_title"
                                :value="old('topic_title', $application->topic_title ?? '')"
                                icon="type" placeholder="An engaging title that describes your session" required />
                        </div>

                        <!-- Topic Description -->
                        <div class="mb-6">
                            <label for="topic_description" class=" text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i data-lucide="align-left" class="w-4 h-4 mr-2 text-[#00275E]"></i>
                                Session Description
                            </label>
                            <p class="text-xs text-gray-500 mb-3">What will attendees learn from your session? Be specific and compelling.</p>
                            <textarea id="topic_description" name="topic_description" rows="6"
                                class="block w-full p-3 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E] resize-none"
                                placeholder="Describe your proposed session in detail, including key takeaways, target audience, and why this topic matters now...">{!! old('topic_description', $application->topic_description ?? '') !!}</textarea>
                            @error('topic_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session Format -->
                        <div class="mb-6">
                            <label class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <i data-lucide="layout" class="w-4 h-4 mr-2 text-[#00275E]"></i>
                                Preferred Session Format
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @foreach (\App\Enums\SessionFormat::cases() as $format)
                                    <label
                                        class="relative flex items-start p-4 rounded-xl border border-gray-200 bg-white shadow-sm cursor-pointer hover:border-[#00275E] hover:shadow-md transition
                                        {{ old('session_format', $application->session_format->value ?? '') === $format->value ? 'border-[#00275E] ring-2 ring-[#00275E]/20 bg-[#00275E]/5' : '' }}">
                                        <input type="radio" name="session_format" value="{{ $format->value }}"
                                            class="h-4 w-4 mt-0.5 text-[#00275E] focus:ring-[#00275E]"
                                            {{ old('session_format', $application->session_format->value ?? '') === $format->value ? 'checked' : '' }}>
                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            {{ $format->displayName() }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('session_format')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="notes" class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i data-lucide="message-square" class="w-4 h-4 mr-2 text-[#00275E]"></i>
                                Additional Notes
                            </label>
                            <p class="text-xs text-gray-500 mb-3">Special requirements, equipment needs, accessibility requests, or other information.</p>
                            <textarea id="notes" name="notes" rows="3"
                                class="block w-full p-3 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#00275E] focus:border-[#00275E] resize-none"
                                placeholder="Any special requirements or additional information">{{ old('notes', $application->notes ?? '') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-end gap-3">
                            <a href="{{ url()->previous() }}"
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 inline-flex items-center text-sm font-medium text-white bg-[#00275E] rounded-lg hover:bg-[#FF0000] focus:ring-4 focus:ring-blue-300 focus:outline-none transition shadow-sm">
                                <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                                Submit Application
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-guest-layout>