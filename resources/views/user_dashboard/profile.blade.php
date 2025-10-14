<x-app-layout>
    <section class="max-w-5xl mx-auto" x-data="{ tab: localStorage.getItem('userTab') || 'profile', showModal: false }" x-init="$watch('tab', value => localStorage.setItem('userTab', value))">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-primary to-primary-700 rounded-xl shadow-lg p-6 text-white mb-10"
            data-aos="fade-down" data-aos-duration="800">
            <div class="flex items-center gap-6">

                <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="relative group">
                        <img class="w-20 h-20 rounded-full object-cover border-2 border-white/50 group-hover:opacity-80 transition-opacity"
                            src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
                            alt="{{ auth()->user()->name }}'s profile photo">

                        <input type="file" name="photo" id="profile_photo_input" class="hidden"
                            accept="image/png, image/jpeg, image/gif" onchange="this.form.submit()">

                        <label for="profile_photo_input"
                            class="absolute bottom-0 right-0 bg-white rounded-full p-1.5 cursor-pointer shadow-md transform transition-transform group-hover:scale-110">
                            <svg class="w-4 h-4 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </label>
                    </div>
                </form>
                <div>
                    <h2 class="text-2xl font-bold font-montserrat">{{ __(auth()->user()->name) }}</h2>
                    <p class="text-sm opacity-90 font-lato">{{ __(auth()->user()->email) }}</p>
                    <p class="text-xs text-white/70 mt-1 font-lato">Member since
                        {{ \Carbon\Carbon::parse(auth()->user()->created_at)->format('M d, Y') }}</p>
                </div>

            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-6 border-b border-gray-200" data-aos="fade-up" data-aos-duration="600">
            <nav class="-mb-px flex space-x-6">
                <button @click="tab = 'profile'"
                    :class="tab === 'profile' ? 'border-primary text-primary' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm font-montserrat transition-colors">
                    Profile
                </button>
                <button @click="tab = 'security'"
                    :class="tab === 'security' ? 'border-primary text-primary' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm font-montserrat transition-colors">
                    Security
                </button>
                @if ($user->hasInstructorProfile())
                    <button @click="tab = 'instructor'"
                        :class="tab === 'notifications' ? 'border-primary text-primary' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm font-montserrat transition-colors">
                        Instructor Information
                    </button>
                @endif
                @if ($user->hasSpeakerProfile())
                <button @click="tab = 'speaker'"
                    :class="tab === 'notifications' ? 'border-primary text-primary' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm font-montserrat transition-colors">
                    Speaker Information
                </button>
                @endif
            </nav>
        </div>

        <!-- Profile Tab -->
        <div x-show="tab === 'profile'" x-transition data-aos="fade-up" data-aos-duration="600">
            @include('user_dashboard.partials.update-profile-information-form')
        </div>

        <!-- Security Tab -->
        <div x-show="tab === 'security'" x-transition data-aos="fade-up" data-aos-duration="600">
            @include('user_dashboard.partials.update-password-form')
        </div>

        <!-- Notifications Tab -->
        <div x-show="tab === 'instructor'" x-transition data-aos="fade-up" data-aos-duration="600">
            @include('user_dashboard.partials.update-instructor-info')
        </div>
        <div x-show="tab === 'speaker'" x-transition data-aos="fade-up" data-aos-duration="600">
            @include('user_dashboard.partials.update-speaker-information')
        </div>

        <!-- Danger Zone -->
        <div class="mt-10" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
            <div class="bg-white border border-secondary-200 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-secondary-700 mb-4 flex items-center gap-2 font-montserrat">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    Danger Zone
                </h3>
                <p class="text-sm text-gray-600 mb-4 font-lato">Permanently delete your account. This action cannot be
                    undone.</p>
                <button @click="showModal = true"
                    class="text-secondary-700 border border-secondary-500 hover:bg-secondary hover:text-white font-semibold px-5 py-2 rounded-lg transition-all duration-200 font-montserrat">
                    <i data-lucide="trash" class="w-4 h-4 inline-block mr-2"></i>
                    Deactivate Account
                </button>
            </div>
        </div>

        <!-- Delete Account Modal -->
        <div x-show="showModal" x-transition
            class="fixed inset-0 flex items-center justify-center z-50 bg-black/30 bg-opacity-50">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4" data-aos="zoom-in"
                data-aos-duration="300">
                <h2 class="text-lg font-bold mb-4 text-secondary-700 font-montserrat">Confirm Account Deactivation</h2>
                <p class="mb-6 text-gray-700 font-lato">Are you sure you want to deactivate your account? This action
                    cannot be undone.</p>
                <form method="POST" action="{{ route('account.destroy') }}" class="space-y-4">
                    @csrf
                    @method('DELETE')
                    <div>
                        <x-input label="Current Password" type="password" name="current_password_destroy" icon="lock"
                            required="true" />
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showModal = false"
                            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors font-lato">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-secondary text-white hover:bg-secondary-600 transition-colors font-montserrat">
                            Deactivate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
