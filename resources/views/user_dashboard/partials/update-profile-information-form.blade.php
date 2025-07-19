{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<div class="bg-white border border-teal-100 rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i data-lucide="user" class="w-5 h-5 text-teal-600"></i>
        Personal Information
    </h3>

    <form action="#" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf

        <div class="relative">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                </span>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full rounded-md border border-gray-300 bg-white text-sm text-gray-800 pl-10 py-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
        </div>

        <div class="relative">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                </span>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full rounded-md border border-gray-300 bg-white text-sm text-gray-800 pl-10 py-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
        </div>

        <div class="md:col-span-2 relative">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                </span>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                    @if($user->hasRole(['student','user'])) readonly @endif
                    class="w-full rounded-md border border-gray-300 bg-white text-sm text-gray-800 pl-10 py-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
        </div>

        <div class="md:col-span-2 mt-4">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-medium px-6 py-2 rounded-md transition">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save Changes
            </button>
        </div>
    </form>
</div>
