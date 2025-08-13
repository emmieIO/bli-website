<x-guest-layout>
    <section class="bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-teal-700 mb-3">Apply to Speak at {{ $event->title }}</h1>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Share your expertise with our audience. Complete the form below to apply as a speaker for this
                    event.
                </p>
                <div class="mt-4">
                    <span class="inline-block h-1 w-20 bg-teal-500 rounded-full"></span>
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
                            <div class="bg-teal-100 p-2 rounded-full mr-3">
                                <i data-lucide="user" class="w-5 h-5 text-teal-700"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">
                                Personal Information
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input label="Full Name" name="name" autofocus :value="auth()->user()->name"
                                    icon="user" />
                            </div>

                            <!-- Title -->
                            <div>
                                <x-input label="Professional Title" name="title"
                                    value="{!! old('title', $application->speaker->title ?? null) !!}"
                                    placeholder="E.g., CEO, Senior Developer, etc." icon="briefcase" />
                            </div>

                            <!-- Organization -->
                            <div>
                                <x-input label="Organization" name="organization"
                                    value="{!! old('organization', htmlspecialchars_decode($application->speaker->organization) ?? '') !!}"
                                    placeholder="Your company or affiliation" icon="building-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <div class="px-4 py-3 bg-gray-100 rounded-lg text-gray-700">
                                    {{$application->speaker->email ?? auth()->user()->email }}
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <div class="px-4 py-3 bg-gray-100 rounded-lg text-gray-700">
                                    {{ $application->speaker->phone ?? auth()->user()->phone }}
                                </div>
                            </div>

                            <!-- Photo -->
                            <div class="md:col-span-2">
                                <label for="photo" class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span>Profile Photo</span>
                                </label>
                                <div class="mt-1 flex items-center">
                                    <div class="mr-4 flex-shrink-0">
                                        <div
                                            class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                            @if (!empty($application->speaker->photo))
                                                <a href="{{ asset('storage/' . $application->speaker->photo)}}"
                                                    target="_blank" rel="noopener">
                                                    <img src="{{ asset('storage/' . $application->speaker->photo) }}"
                                                        alt="Profile Photo" class="h-16 w-16 object-cover rounded-full">
                                                </a>
                                            @else
                                                <i data-lucide="user" class="w-8 h-8 text-gray-400"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" id="photo" name="photo" accept="image/*" class="w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-md file:border-0
                                            file:text-sm file:font-medium
                                            file:bg-teal-50 file:text-teal-700
                                            hover:file:bg-teal-100
                                            focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                                        <p class="mt-1 text-xs text-gray-500">JPEG or PNG, max 2MB</p>
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
                            <div class="bg-teal-100 p-2 rounded-full mr-3">
                                <i data-lucide="briefcase" class="w-5 h-5 text-teal-700"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">
                                Professional Information
                            </h3>
                        </div>

                        <!-- Bio -->
                        <div class="mb-6">
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Professional
                                Bio</label>
                            <p class="text-xs text-gray-500 mb-2">This will be displayed in the event program if you're
                                selected.</p>
                            <textarea id="bio" name="bio" rows="4"
                                class="block w-full px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                placeholder="Tell us about your professional background and expertise">{!! old('bio', $application->speaker->bio ?? '') !!}</textarea>
                            @error('bio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- LinkedIn -->
                            <div>
                                <x-input label="LinkedIn Profile" name="linkedin" type="url"
                                    value="{{ old('linkedin', $application->speaker->linkedin ?? '') }}"
                                    placeholder="https://linkedin.com/in/yourprofile" icon="linkedin" />
                            </div>

                            <!-- Website -->
                            <div>
                                <x-input label="Website/Blog" name="website" type="url"
                                    value="{{ old('website', $application->speaker->website ?? '') }}"
                                    placeholder="https://yourwebsite.com" icon="globe" />
                            </div>
                        </div>
                    </div>

                    <!-- Session Proposal Section -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="bg-teal-100 p-2 rounded-full mr-3">
                                <i data-lucide="presentation" class="w-5 h-5 text-teal-700"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">
                                Session Proposal
                            </h3>
                        </div>

                        <!-- Topic Title -->
                        <div class="mb-6">
                            <x-input label="Proposed Topic Title" name="topic_title"
                                value="{!! old('topic_title', $application->topic_title ?? '') !!}" icon="type"
                                placeholder="An engaging title that describes your session" />
                        </div>

                        <!-- Topic Description -->
                        <div class="mb-6">
                            <label for="topic_description" class="block text-sm font-medium text-gray-700 mb-2">Session
                                Description</label>
                            <p class="text-xs text-gray-500 mb-2">What will attendees learn from your session?</p>
                            <textarea id="topic_description" name="topic_description" rows="5"
                                class="block w-full px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                placeholder="Describe your proposed session in detail, including key takeaways for attendees">{!! old('topic_description', $application->topic_description ?? '') !!}</textarea>
                            @error('topic_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session Format -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Preferred Session Format</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @foreach (\App\Enums\SessionFormat::cases() as $format)
                                    <label
                                        class="relative flex items-start p-3 rounded-lg border border-gray-200 bg-white shadow-sm cursor-pointer hover:border-teal-300 has-[:checked]:border-teal-500 has-[:checked]:ring-1 has-[:checked]:ring-teal-500">
                                        <input type="radio" name="session_format" value="{{ $format->value }}"
                                            class="h-4 w-4 mt-0.5 text-teal-600 focus:ring-teal-500" {{ old('session_format', $application->session_format->value ?? '') === $format->value ? 'checked' : '' }}>
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
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional
                                Notes</label>
                            <p class="text-xs text-gray-500 mb-2">Special requirements, equipment needs, or other
                                information.</p>
                            <textarea id="notes" name="notes" rows="3"
                                class="block w-full px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                placeholder="Any special requirements or additional information">{{ old('notes', $application->notes ?? '') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex justify-between">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                Submit Application
                                <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-guest-layout>