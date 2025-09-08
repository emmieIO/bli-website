<x-auth-layout :title="'Welcome Back'" :description="'Sign in to your account'">
    <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
        @csrf

        <!-- Email -->
         <x-input label="E-mail" placeholder="Email Address" type="email" name="email" autofocus icon="mail"/>

         <!-- Password -->
         <x-input label="Password" type="password" name="password" autofocus icon="lock"/>

        <!-- Remember Me & Forgot -->
        <div class="flex items-center justify-between">
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="remember" placeholder="Password"
                    class="rounded border-gray-300 text-[#FF0000] shadow-sm focus:ring-[#FF0000]">
                <span class="ml-2 text-white">Remember me</span>
            </label>

            <a href="{{ route('password.request') }}" class="text-sm text-white hover:text-[#FF0000] hover:underline transition-colors">Forgot password?</a>
        </div>

        <!-- Submit -->
        <div>
            <button type="submit"
                class="w-full bg-[#FF0000] hover:bg-[#cc0000] text-white font-semibold py-2 px-4 rounded-md transition flex items-center justify-center gap-2">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                Sign In
            </button>
        </div>

        <!-- Social Login -->
        <div class="mt-6 text-center">
            <p class="text-sm text-white mb-4">or sign in with</p>
            <div class="flex justify-center gap-4">
                <a href=""
                    class="flex items-center gap-2 bg-white hover:bg-gray-100 text-[#00275E] px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    <i data-lucide="globe" class="w-4 h-4"></i> Google
                </a>
                <a href=""
                    class="flex items-center gap-2 bg-white hover:bg-gray-100 text-[#00275E] px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    <i data-lucide="facebook" class="w-4 h-4"></i> Facebook
                </a>
            </div>
        </div>

        <!-- Register -->
        <p class="text-center text-sm mt-6 text-white">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-[#FF0000] hover:underline font-medium">Sign up</a>
        </p>
    </form>
</x-auth-layout>