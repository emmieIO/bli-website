{{-- resources/views/profile/partials/update-password-form.blade.php --}}
<div class="bg-white border border-teal-100 rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i data-lucide="shield" class="w-5 h-5 text-teal-600"></i>
        Account Security
    </h3>

    <form action="#" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf

        <div class="relative">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i>
                </span>
                <input type="password" id="password" name="password"
                    class="w-full rounded-md border border-gray-300 bg-white text-sm text-gray-800 pl-10 py-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
        </div>

        <div class="relative">
            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i>
                </span>
                <input type="password" id="confirm_password" name="confirm_password"
                    class="w-full rounded-md border border-gray-300 bg-white text-sm text-gray-800 pl-10 py-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
        </div>

        <div class="md:col-span-2 mt-4">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-medium px-6 py-2 rounded-md transition">
                <i data-lucide="lock" class="w-4 h-4"></i>
                Update Password
            </button>
        </div>
    </form>
</div>
