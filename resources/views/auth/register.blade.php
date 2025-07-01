<x-auth-layout :title="'Create Account'" :description="'Start your leadership journey with us'">
    <form method="POST" action="{{ route('register.store') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <x-input name="name" label="Full Name" icon="user" autofocus  />

        <!-- Email -->
        <x-input name="email" type="email" label="Email address" icon="mail" />

        <!-- Password -->
        <x-input name="password" type="password" label="Password" icon="lock" />

        <!-- Confirm Password -->
        <x-input name="password_confirmation" type="password" label="Confirm Password" icon="lock" />

        <!-- Submit -->
        <div>
            <button type="submit"
                class="w-full bg-teal-700 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-md transition flex items-center justify-center gap-2">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                Create Account
            </button>
        </div>

        <!-- Social Registration -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500 mb-4">or sign up with</p>
            <div class="flex justify-center gap-4">
                <a href=""
                    class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i data-lucide="globe" class="w-4 h-4"></i> Google
                </a>
                <a href=""
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i data-lucide="facebook" class="w-4 h-4"></i> Facebook
                </a>
            </div>
        </div>

        <!-- Login Redirect -->
        <p class="text-center text-sm mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-teal-700 hover:underline font-medium">Sign in</a>
        </p>


    </form>

</x-auth-layout>
