{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<div class="space-y-5">
    @if ($user->hasSpeakerProfile())
        <div class="bg-white border border-primary-100 rounded-xl shadow-sm p-6" data-aos="fade-up"
            data-aos-duration="600">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 font-montserrat">
                <i data-lucide="mic" class="w-5 h-5 text-primary-600"></i>
                Speaker Information
            </h3>

            <form action="{{ route('profile.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                @method('PATCH')

                <div class="relative">
                    <x-input label="Full Name" type="text" name="name" value="{{ old('name', $user->name) }}"
                        icon="user" required="true" autofocus="true" />
                </div>

                <div class="relative">
                    <x-input label="Phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        icon="phone" />
                </div>

                <div class="relative">
                    <x-input label="Email" type="email" name="email" value="{{ old('email', $user->email) }}"
                        icon="mail"
                        readonly="{{ $user->hasRole(['student', 'user', 'instructor']) ? 'readonly' : '' }}"
                        required="true" />
                </div>
                <div class="relative">
                    <x-input label="Current Password" type="password" name="current_password" icon="lock"
                        required="true" />
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
