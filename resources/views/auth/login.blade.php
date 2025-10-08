<x-auth-layout :title="'Welcome Back'" :description="'Sign in to your account'">
    <x-slot name="pageTitle">Login - Beacon Leadership Institute</x-slot>
    <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <x-input label="E-mail" placeholder="Email Address" type="email" name="email" autofocus icon="mail" />

        <!-- Password -->
        <x-input label="Password" type="password" name="password" autofocus icon="lock" />

        <!-- Remember Me & Forgot -->
        <div class="flex items-center justify-between">
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="remember" placeholder="Password"
                    class="rounded border-gray-300 text-secondary focus:ring-secondary shadow-sm">
                <span class="ml-2 text-gray-700 font-lato">Remember me</span>
            </label>

            <a href="{{ route('password.request') }}"
                class="text-sm text-secondary hover:text-secondary-600 hover:underline transition-colors font-lato">Forgot password?</a>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                class="w-full bg-secondary hover:bg-secondary-600 text-white text-lg font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2 font-montserrat">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                Sign In
            </button>
        </div>

        <!-- Social Login -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 mb-4 font-lato">or sign in with</p>
            <div class="flex justify-center gap-4">
                <a href=""
                    class="flex items-center gap-2 bg-white hover:bg-gray-100 text-primary border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium transition-colors font-lato">
                    <i data-lucide="globe" class="w-4 h-4"></i> Google
                </a>
                <a href=""
                    class="flex items-center gap-2 bg-white hover:bg-gray-100 text-primary border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium transition-colors font-lato">
                    <i data-lucide="facebook" class="w-4 h-4"></i> Facebook
                </a>
            </div>
        </div>

        <!-- Register -->
        <p class="text-center text-sm mt-6 text-gray-600 font-lato">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-secondary hover:text-secondary-600 hover:underline font-medium font-montserrat">Sign up</a>
        </p>
    </form>
</x-auth-layout>