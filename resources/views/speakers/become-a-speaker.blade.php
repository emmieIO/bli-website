<x-guest-layout>
    <!-- Enhanced Become a Speaker Page -->
    <section class="min-h-screen py-12 bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center px-4 py-2 rounded-full mb-6"
                    style="background: rgba(237, 28, 36, 0.1);">
                    <i class="fas fa-microphone mr-2" style="color: #ed1c24;"></i>
                    <span class="text-sm font-semibold font-montserrat" style="color: #ed1c24;">Speaker Community</span>
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 font-montserrat" style="color: #002147;">
                    Become a
                    <span class="relative">
                        <span class="relative z-10" style="color: #ed1c24;">Speaker</span>
                        <div class="absolute -bottom-2 left-0 w-full h-4 opacity-30"
                            style="background: #ed1c24; transform: skew(-12deg);"></div>
                    </span>
                </h1>

                <p class="text-xl md:text-2xl mb-8 text-gray-600 leading-relaxed font-lato max-w-3xl mx-auto">
                    Join our prestigious speaker community and share your expertise with industry leaders,
                    <strong style="color: #00a651;">inspiring the next generation</strong> of professionals.
                </p>

                <!-- Trust Indicators -->
                <div
                    class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-8 mb-8">
                    <div class="flex items-center">
                        <i class="fas fa-users mr-2" style="color: #00a651;"></i>
                        <span class="text-sm font-medium text-gray-600 font-lato">Join 200+ Expert Speakers</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-globe mr-2" style="color: #ed1c24;"></i>
                        <span class="text-sm font-medium text-gray-600 font-lato">Global Audience Reach</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-award mr-2" style="color: #002147;"></i>
                        <span class="text-sm font-medium text-gray-600 font-lato">Premium Events Platform</span>
                    </div>
                </div>
            </div>

            <!-- Enhanced Registration Form -->
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden" data-aos="fade-up"
                data-aos-delay="200">
                <div class="grid grid-cols-1 lg:grid-cols-3">
                    <!-- Left Side - Form -->
                    <div class="lg:col-span-2 p-10 md:p-12">
                        <form method="POST" id="speaker-form" data-action="{{ route('become-a-speaker.store') }}"
                            enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <!-- Personal Information -->
                            <div class="space-y-6" data-aos="fade-up" data-aos-delay="100">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mr-4"
                                        style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%);">
                                        <i class="fas fa-user text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold font-montserrat" style="color: #002147;">Personal
                                            Information</h3>
                                        <p class="text-sm text-gray-500 font-lato">Tell us about yourself</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-user mr-2" style="color: #00a651;"></i>Full Name
                                        </label>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                            placeholder="Enter your full name"
                                            onfocus="this.style.borderColor='#00a651'"
                                            onblur="this.style.borderColor='#e5e7eb'">
                                        <small data-error="name" class="text-xs mt-1 block"
                                            style="color: #ed1c24;"></small>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-envelope mr-2" style="color: #ed1c24;"></i>Email Address
                                        </label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                            placeholder="your.email@example.com"
                                            onfocus="this.style.borderColor='#ed1c24'"
                                            onblur="this.style.borderColor='#e5e7eb'">
                                        <small data-error="email" class="text-xs mt-1 block"
                                            style="color: #ed1c24;"></small>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-phone mr-2" style="color: #002147;"></i>Phone Number
                                        </label>
                                        <input type="tel" name="phone" value="{{ old('phone') }}"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                            placeholder="+234 803 123 4567" onfocus="this.style.borderColor='#002147'"
                                            onblur="this.style.borderColor='#e5e7eb'">
                                        <small data-error="phone" class="text-xs mt-1 block"
                                            style="color: #ed1c24;"></small>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-briefcase mr-2" style="color: #00a651;"></i>Professional
                                            Title
                                        </label>
                                        <input type="text" name="headline" value="{{ old('headline') }}"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                            placeholder="e.g. Senior Developer, CEO, Consultant"
                                            onfocus="this.style.borderColor='#00a651'"
                                            onblur="this.style.borderColor='#e5e7eb'">
                                        <small data-error="title" class="text-xs mt-1 block"
                                            style="color: #ed1c24;"></small>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold mb-2 font-montserrat"
                                        style="color: #002147;">
                                        <i class="fas fa-building mr-2" style="color: #ed1c24;"></i>Organization
                                    </label>
                                    <input type="text" name="organization" value="{{ old('organization') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                        placeholder="Your company or organization (optional)"
                                        onfocus="this.style.borderColor='#ed1c24'"
                                        onblur="this.style.borderColor='#e5e7eb'">
                                    <small data-error="organization" class="text-xs mt-1 block"
                                        style="color: #ed1c24;"></small>
                                </div>
                            </div>

                            <!-- Account Security -->
                            <div class="space-y-6" data-aos="fade-up" data-aos-delay="200">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mr-4"
                                        style="background: linear-gradient(135deg, #ed1c24 0%, #dc2626 100%);">
                                        <i class="fas fa-lock text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold font-montserrat" style="color: #002147;">Account
                                            Security</h3>
                                        <p class="text-sm text-gray-500 font-lato">Set up your account credentials</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-key mr-2" style="color: #ed1c24;"></i>Password
                                        </label>
                                        <input type="password" name="password"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                            placeholder="Create a strong password"
                                            onfocus="this.style.borderColor='#ed1c24'"
                                            onblur="this.style.borderColor='#e5e7eb'">
                                        <small data-error="password" class="text-xs mt-1 block"
                                            style="color: #ed1c24;"></small>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 font-montserrat"
                                            style="color: #002147;">
                                            <i class="fas fa-check-double mr-2" style="color: #00a651;"></i>Confirm
                                            Password
                                        </label>
                                        <input type="password" name="password_confirmation"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none transition-all duration-300 font-lato"
                                            placeholder="Confirm your password"
                                            onfocus="this.style.borderColor='#00a651'"
                                            onblur="this.style.borderColor='#e5e7eb'">
                                    </div>
                                </div>
                            </div>

                            <!-- Professional Profile -->
                            <div class="space-y-4" data-aos="fade-up" data-aos-delay="300">
                                <div class="flex items-center mb-6">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mr-4"
                                        style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
                                        <i class="fas fa-briefcase text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold font-montserrat" style="color: #002147;">
                                            Professional Profile</h3>
                                        <p class="text-sm text-gray-500 font-lato">Share your expertise and background
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <label for="bio"
                                        class="block text-sm font-medium text-gray-700 mb-2 font-lato">Professional
                                        Bio</label>
                                    <textarea id="bio" name="bio" rows="4"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 font-lato placeholder-gray-400"
                                        placeholder="Tell us about your expertise, experience, and speaking background...">{{ old('bio') }}</textarea>
                                    <small data-error="bio" class="text-xs text-secondary"></small>
                                </div>

                                <!-- Profile Photo -->
                                <div class="space-y-3">
                                    <label for="photo" class="block text-sm font-medium text-gray-700 font-lato">Profile
                                        Photo</label>
                                    <input id="photo" name="photo" type="file" accept="image/*"
                                        class="block w-full px-4 border border-gray-300 rounded-xl file:bg-primary focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 font-lato placeholder-gray-400" />
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG up to 2MB</p>
                                    <small data-error="photo" class="text-xs text-secondary"></small>
                                </div>

                                <!-- Social Links -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input label="LinkedIn Profile" name="linkedin" type="url" icon="linkedin"
                                            :value="old('linkedin')"
                                            placeholder="https://linkedin.com/in/yourprofile" />
                                        <small data-error="linkedin" class="text-xs text-secondary"></small>
                                    </div>
                                    <div>
                                        <x-input label="Website" name="website" type="url" icon="globe"
                                            :value="old('website')" placeholder="https://yourwebsite.com" />
                                        <small data-error="website" class="text-xs text-secondary"></small>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Submit -->
                            <div class="space-y-4 pt-6 border-t border-gray-200" data-aos="fade-up"
                                data-aos-delay="400">
                                <div class="flex items-start gap-3 p-4 rounded-xl"
                                    style="background: rgba(0, 33, 71, 0.05);">
                                    <input id="agree_terms" name="agree_terms" type="checkbox"
                                        class="h-4 w-4 border-gray-300 rounded focus:ring-2 transition-all duration-300 mt-1"
                                        style="color: #002147; --tw-ring-color: #002147;">
                                    <label for="agree_terms" class="text-sm text-gray-700 font-lato leading-relaxed">
                                        I agree to the <a href="#"
                                            class="font-medium hover:underline transition-colors duration-300"
                                            style="color: #002147;">Terms of Service</a>
                                        and <a href="#"
                                            class="font-medium hover:underline transition-colors duration-300"
                                            style="color: #002147;">Privacy Policy</a>.
                                        I understand that my profile will be reviewed before being added to the speaker
                                        directory.
                                    </label>
                                </div>

                                <button type="submit" id="submit-btn"
                                    class="w-full text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl font-montserrat flex items-center justify-center gap-3"
                                    style="background: linear-gradient(135deg, #002147 0%, #ed1c24 100%); 
                                           border: none;">
                                    <span id="submit-loader" class="hidden">
                                        <i class="fas fa-spinner animate-spin text-lg"></i>
                                    </span>
                                    <i class="fas fa-paper-plane"></i>
                                    Register as Speaker
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Enhanced Right Side - Benefits -->
                    <div class="p-10 md:p-12" style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
                        <div class="space-y-8">
                            <div class="text-center">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6"
                                    style="background: rgba(0, 166, 81, 0.2); backdrop-filter: blur(10px);">
                                    <i class="fas fa-star text-2xl" style="color: #00a651;"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-4 font-montserrat">Speaker Benefits</h3>
                                <p class="text-gray-200 font-lato">Join our exclusive community of thought leaders</p>
                            </div>

                            <div class="space-y-6">
                                <div class="group p-4 rounded-2xl transition-all duration-300 hover:bg-white/10">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110"
                                            style="background: rgba(0, 166, 81, 0.2);">
                                            <i class="fas fa-users text-lg" style="color: #00a651;"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-white mb-2 font-montserrat">Global Audience Reach
                                            </h4>
                                            <p class="text-sm text-gray-200 font-lato leading-relaxed">Share your
                                                expertise with thousands of passionate learners and industry leaders
                                                worldwide.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="group p-4 rounded-2xl transition-all duration-300 hover:bg-white/10">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110"
                                            style="background: rgba(237, 28, 36, 0.2);">
                                            <i class="fas fa-award text-lg" style="color: #ed1c24;"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-white mb-2 font-montserrat">Build Your Brand</h4>
                                            <p class="text-sm text-gray-200 font-lato leading-relaxed">Enhance your
                                                professional reputation and establish yourself as a thought leader in
                                                your industry.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="group p-4 rounded-2xl transition-all duration-300 hover:bg-white/10">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110"
                                            style="background: rgba(0, 166, 81, 0.2);">
                                            <i class="fas fa-handshake text-lg" style="color: #00a651;"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-white mb-2 font-montserrat">Premium Networking
                                            </h4>
                                            <p class="text-sm text-gray-200 font-lato leading-relaxed">Connect with
                                                other experts, C-level executives, and thought leaders in exclusive
                                                events.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="group p-4 rounded-2xl transition-all duration-300 hover:bg-white/10">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110"
                                            style="background: rgba(237, 28, 36, 0.2);">
                                            <i class="fas fa-microphone-alt text-lg" style="color: #ed1c24;"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-white mb-2 font-montserrat">Exclusive
                                                Opportunities</h4>
                                            <p class="text-sm text-gray-200 font-lato leading-relaxed">Get priority
                                                access to high-profile speaking engagements and premium corporate
                                                events.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-white/20">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-white mb-1 font-montserrat"
                                        style="color: #00a651;">200+</div>
                                    <div class="text-xs text-gray-300 font-lato">Expert Speakers</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-white mb-1 font-montserrat"
                                        style="color: #ed1c24;">50K+</div>
                                    <div class="text-xs text-gray-300 font-lato">Audience Reached</div>
                                </div>
                            </div>

                            <!-- Already have account -->
                            <div class="pt-6 border-t border-white/20">
                                <p class="text-sm text-gray-200 font-lato text-center mb-4">
                                    Already have an account?
                                </p>
                                <a href="{{ route('login') }}"
                                    class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg font-montserrat border-2 border-white/30 text-white hover:bg-white/10">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    Sign In Here
                                </a>
                            </div>

                            <!-- Support Info -->
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                        style="background: rgba(0, 166, 81, 0.2);">
                                        <i class="fas fa-headset text-lg" style="color: #00a651;"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white text-sm font-montserrat">Need Help?</h4>
                                        <p class="text-xs text-gray-200 font-lato">Contact our speaker success team</p>
                                        <a href="mailto:speakers@beaconleadership.org"
                                            class="text-xs font-medium hover:underline font-montserrat"
                                            style="color: #00a651;">
                                            speakers@beaconleadership.org
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function () {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 100
            });
        });

        <script>
            const speakerForm = document.querySelector('#speaker-form')
            const loader = document.getElementById('submit-loader');
            const submitBtn = document.getElementById('submit-btn');


            let isLoading = false;
            speakerForm.addEventListener('submit', async function(e) {
                e.preventDefault();
            if (isLoading) return;
            isLoading = true
            loader.classList.remove('hidden');
            submitBtn.disabled = true;

            const actionUrl = speakerForm.getAttribute('data-action');
            const formData = new FormData(speakerForm);
            // Clear old validation errors
            speakerForm.querySelectorAll('[data-error]').forEach(el => el.textContent = '');
            try {
                const res = await axios.post(actionUrl, formData, {
                headers: {
                'Content-Type': 'multipart/form-data'
                    }
                });
            const {
                data,
                status
            } = res;
            window.notyf.success(data.message || "Form submitted successfully!");
            speakerForm.reset();
            isLoading = false;
            loader.classList.add('hidden');
            submitBtn.disabled = false;
            } catch (error) {
                // Handle Laravel validation errors (422)
                const errorResponse = error.response?.data || error.message;
            if (error.response?.status === 422) {
                window.notyf.error("There were validation errors. Please check the form.");
                    // Loop through each field error
                    Object.keys(errorResponse.errors).forEach((field) => {
                        const errorDiv = document.querySelector(`[data-error="${field}"]`);
            if (errorDiv) {
                errorDiv.textContent = errorResponse.errors[field][0];
                        }
                    });
                } else {
                // Generic error (server, network, etc.)
                alert('Something went wrong. Please try again.');
            console.error(error.response?.data || error.message);
                }
            isLoading = false
            loader.classList.add('hidden');
            submitBtn.disabled = false;
            }
        });
    </script>
</x-guest-layout>