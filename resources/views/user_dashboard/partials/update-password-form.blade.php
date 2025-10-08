{{-- resources/views/profile/partials/update-password-form.blade.php --}}
<div class="bg-white border border-primary-100 rounded-xl shadow-sm p-6"
     data-aos="fade-up"
     data-aos-duration="600">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 font-montserrat">
        <i data-lucide="shield" class="w-5 h-5 text-primary-600"></i>
        Account Security
    </h3>

    <form action="{{ route('profile.update_password') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf
        @method('PATCH')

        <x-input type="password" name="current_password" label="Current Password" icon="lock" class="w-full"
            autocomplete="current-password" />

        <x-input type="password" name="password" label="New Password" icon="lock" class="w-full"
            autocomplete="new-password" />

        <x-input type="password" name="password_confirmation" label="Confirm Password" icon="lock" class="w-full"
            autocomplete="new-password" />

        <div class="md:col-span-2 mt-4">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-lg transition-all duration-200 transform hover:scale-105 font-montserrat">
                <i data-lucide="lock" class="w-4 h-4"></i>
                Update Password
            </button>
        </div>
    </form>
</div>