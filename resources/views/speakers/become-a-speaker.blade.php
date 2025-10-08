<x-guest-layout>
    <section class="min-h-screen bg-gradient-to-br from-primary-50 to-white py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-12" data-aos="fade-down">
                <div class="inline-flex items-center gap-3 bg-primary/10 rounded-2xl px-6 py-4 border border-primary/20 mb-6">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center">
                        <i data-lucide="mic" class="w-6 h-6 text-white"></i>
                    </div>
                    <div class="text-left">
                        <h1 class="text-2xl font-bold text-primary font-montserrat">Join Our Speaker Community</h1>
                        <p class="text-sm text-primary/70 font-lato">Share your expertise and inspire others</p>
                    </div>
                </div>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto font-lato leading-relaxed">
                    Become a valued speaker at Beacon Leadership Institute and share your knowledge with our community of leaders and learners.
                </p>
            </div>

            <!-- Registration Form -->
            <div class="bg-white rounded-2xl shadow-xl border border-primary-100 overflow-hidden" data-aos="fade-up">
                <div class="grid grid-cols-1 lg:grid-cols-3">
                    <!-- Left Side - Form -->
                    <div class="lg:col-span-2 p-8">
                        <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <!-- Personal Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-primary font-montserrat border-b border-primary-100 pb-2">Personal Information</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-input label="Full Name" name="name" required icon="user" :value="old('name')" />
                                    <x-input label="Email Address" name="email" type="email" required icon="mail" :value="old('email')" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-input label="Phone Number" name="phone" type="tel" required icon="phone" :value="old('phone')" />
                                    <x-input label="Professional Title" name="title" required icon="briefcase" :value="old('title')" 
                                             placeholder="e.g. Senior Developer, CEO, Consultant" />
                                </div>

                                <x-input label="Organization" name="organization" icon="building" :value="old('organization')" 
                                         placeholder="Your company or organization (optional)" />
                            </div>

                            <!-- Account Security -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-primary font-montserrat border-b border-primary-100 pb-2">Account Security</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-input label="Password" name="password" type="password" required icon="lock" />
                                    <x-input label="Confirm Password" name="password_confirmation" type="password" required icon="lock" />
                                </div>
                            </div>

                            <!-- Professional Profile -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-primary font-montserrat border-b border-primary-100 pb-2">Professional Profile</h3>
                                
                                <div>
                                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2 font-lato">Professional Bio</label>
                                    <textarea id="bio" name="bio" rows="4" 
                                              class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 font-lato placeholder-gray-400"
                                              placeholder="Tell us about your expertise, experience, and speaking background...">{{ old('bio') }}</textarea>
                                    <x-input-error :messages="$errors->get('bio')" class="mt-1" />
                                </div>

                                <!-- Profile Photo -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700 font-lato">Profile Photo</label>
                                    <div class="flex items-center gap-6">
                                        <div class="flex-shrink-0">
                                            <div id="photoPreview" class="w-20 h-20 rounded-xl bg-primary/10 border-2 border-dashed border-primary/30 flex items-center justify-center">
                                                <i data-lucide="user" class="w-8 h-8 text-primary/50"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <label for="photo" class="flex flex-col items-center justify-center w-full h-20 border-2 border-dashed border-primary-200 rounded-xl cursor-pointer bg-primary-50 hover:bg-primary-100 transition-all duration-200">
                                                <div class="flex flex-col items-center justify-center pt-2">
                                                    <i data-lucide="upload" class="w-5 h-5 text-primary mb-1"></i>
                                                    <p class="text-sm text-gray-600"><span class="font-medium text-primary">Upload photo</span></p>
                                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG up to 2MB</p>
                                                </div>
                                                <input id="photo" name="photo" type="file" class="hidden" accept="image/*" />
                                            </label>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('photo')" class="mt-1" />
                                </div>

                                <!-- Social Links -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-input label="LinkedIn Profile" name="linkedin" type="url" icon="linkedin" :value="old('linkedin')" 
                                             placeholder="https://linkedin.com/in/yourprofile" />
                                    <x-input label="Website" name="website" type="url" icon="globe" :value="old('website')" 
                                             placeholder="https://yourwebsite.com" />
                                </div>
                            </div>

                            <!-- Terms and Submit -->
                            <div class="space-y-4 pt-4 border-t border-primary-100">
                                <div class="flex items-start gap-3">
                                    <input id="agree_terms" name="agree_terms" type="checkbox" required
                                           class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary mt-1">
                                    <label for="agree_terms" class="text-sm text-gray-700 font-lato leading-relaxed">
                                        I agree to the <a href="#" class="text-primary hover:text-primary-600 font-medium">Terms of Service</a> 
                                        and <a href="#" class="text-primary hover:text-primary-600 font-medium">Privacy Policy</a>. 
                                        I understand that my profile will be reviewed before being added to the speaker directory.
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('agree_terms')" class="mt-1" />

                                <button type="submit"
                                        class="w-full bg-primary hover:bg-primary-600 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl font-montserrat flex items-center justify-center gap-3">
                                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                                    Register as Speaker
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Right Side - Benefits -->
                    <div class="bg-primary-50 border-l border-primary-100 p-8">
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-primary font-montserrat">Speaker Benefits</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-accent rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="users" class="w-4 h-4 text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-primary font-montserrat">Reach Engaged Audience</h4>
                                        <p class="text-sm text-gray-600 font-lato">Share your expertise with our community of passionate learners and leaders.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-accent rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="award" class="w-4 h-4 text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-primary font-montserrat">Build Your Brand</h4>
                                        <p class="text-sm text-gray-600 font-lato">Enhance your professional reputation and visibility in your industry.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-accent rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="network" class="w-4 h-4 text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-primary font-montserrat">Network with Peers</h4>
                                        <p class="text-sm text-gray-600 font-lato">Connect with other experts and thought leaders in your field.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-accent rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="mic" class="w-4 h-4 text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-primary font-montserrat">Exclusive Opportunities</h4>
                                        <p class="text-sm text-gray-600 font-lato">Get first access to speaking opportunities at our premium events.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Already have account -->
                            <div class="pt-6 border-t border-primary-100">
                                <p class="text-sm text-gray-600 font-lato text-center">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="text-primary hover:text-primary-600 font-semibold font-montserrat">
                                        Sign in here
                                    </a>
                                </p>
                            </div>

                            <!-- Support Info -->
                            {{-- <div class="bg-white rounded-xl p-4 border border-primary-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                        <i data-lucide="help-circle" class="w-5 h-5 text-primary"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-primary text-sm font-montserrat">Need Help?</h4>
                                        <p class="text-xs text-gray-600 font-lato">Contact our speaker team for assistance.</p>
                                        <a href="mailto:speakers@beaconleadership.org" class="text-xs text-primary hover:text-primary-600 font-medium">
                                            speakers@beaconleadership.org
                                        </a>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Photo preview functionality
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('photoPreview');
            
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }

                if (!file.type.match('image.*')) {
                    alert('Please select an image file');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full rounded-xl object-cover" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // Form validation and enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(e) {
                const password = document.querySelector('input[name="password"]');
                const confirmPassword = document.querySelector('input[name="password_confirmation"]');
                
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('Passwords do not match');
                    confirmPassword.focus();
                }
            });

            // Initialize Lucide icons
            lucide.createIcons();
        });
    </script>
</x-guest-layout>