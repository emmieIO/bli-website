<x-guest-layout>
    <!-- Enhanced View Application Page -->
    <section class="min-h-screen py-12 bg-gradient-to-br from-gray-50 via-white to-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-12" data-aos="fade-up">
                <div class="inline-flex items-center px-4 py-2 rounded-full mb-6"
                    style="background: rgba(0, 33, 71, 0.1);">
                    <i class="fas fa-file-alt mr-2" style="color: #002147;"></i>
                    <span class="text-sm font-semibold font-montserrat" style="color: #002147;">Application
                        Review</span>
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 font-montserrat" style="color: #002147;">
                    Your Instructor Application
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto font-lato">
                    Review your submitted information and track your application status
                </p>
            </div>

            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Application Status Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
                        data-aos="fade-right">
                        <div class="px-6 py-6 border-b border-gray-200"
                            style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%);">
                            <h3 class="text-xl font-bold text-white flex items-center font-montserrat">
                                <div
                                    class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-chart-line text-lg"></i>
                                </div>
                                Application Status
                            </h3>
                        </div>
                        <div class="p-6">
                            <!-- Status Indicator -->
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                                    style="background: rgba(237, 28, 36, 0.1);">
                                    <i class="fas fa-clock text-2xl" style="color: #ed1c24;"></i>
                                </div>
                                <div class="px-4 py-2 rounded-full text-sm font-bold"
                                    style="background: rgba(237, 28, 36, 0.1); color: #ed1c24;">
                                    Under Review
                                </div>
                            </div>

                            <!-- Timeline -->
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-4" style="background: #00a651;"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold font-montserrat" style="color: #002147;">
                                            Application Submitted</p>
                                        <p class="text-xs text-gray-500 font-lato">
                                            {{ $application->created_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-4" style="background: #ed1c24;"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold font-montserrat" style="color: #002147;">Under
                                            Review</p>
                                        <p class="text-xs text-gray-500 font-lato">In progress</p>
                                    </div>
                                </div>
                                <div class="flex items-center opacity-50">
                                    <div class="w-3 h-3 rounded-full mr-4 bg-gray-300"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold font-montserrat text-gray-400">Decision
                                            Notification</p>
                                        <p class="text-xs text-gray-400 font-lato">Pending</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Expected Timeline -->
                            <div class="mt-6 p-4 rounded-xl" style="background: rgba(0, 166, 81, 0.1);">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-info-circle mr-2" style="color: #00a651;"></i>
                                    <span class="text-sm font-semibold font-montserrat" style="color: #002147;">Expected
                                        Timeline</span>
                                </div>
                                <p class="text-sm font-lato" style="color: #002147;">
                                    We typically complete reviews within <strong>5-7 business days</strong>.
                                    You'll receive an email notification once a decision is made.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Details Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
                        data-aos="fade-left">
                        <div class="px-8 py-6 border-b border-gray-200"
                            style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
                            <h2 class="text-xl font-bold text-white flex items-center font-montserrat">
                                <div
                                    class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-user-graduate text-lg"></i>
                                </div>
                                Application Details
                            </h2>
                            <p class="text-gray-200 font-lato">Review your submitted information below</p>
                        </div>
                        <div class="p-8">
                            <div class="space-y-8">
                                <!-- Personal Information Section -->
                                <div>
                                    <h3 class="text-lg font-bold mb-4 font-montserrat flex items-center"
                                        style="color: #002147;">
                                        <i class="fas fa-user mr-3" style="color: #00a651;"></i>
                                        Personal Information
                                    </h3>
                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div class="p-4 rounded-xl border border-gray-200">
                                            <dt class="text-sm font-semibold mb-2 font-montserrat"
                                                style="color: #002147;">Full Name</dt>
                                            <dd class="font-lato" style="color: #002147;">{{ $application->user->name }}
                                            </dd>
                                        </div>
                                        <div class="p-4 rounded-xl border border-gray-200">
                                            <dt class="text-sm font-semibold mb-2 font-montserrat"
                                                style="color: #002147;">Email Address</dt>
                                            <dd class="font-lato" style="color: #002147;">
                                                {{ $application->user->email }}</dd>
                                        </div>
                                        <div class="p-4 rounded-xl border border-gray-200">
                                            <dt class="text-sm font-semibold mb-2 font-montserrat"
                                                style="color: #002147;">Phone Number</dt>
                                            <dd class="font-lato" style="color: #002147;">
                                                {{ $application->user->phone ?? 'Not provided' }}</dd>
                                        </div>
                                        <div class="p-4 rounded-xl border border-gray-200">
                                            <dt class="text-sm font-semibold mb-2 font-montserrat"
                                                style="color: #002147;">LinkedIn Profile</dt>
                                            <dd class="font-lato">
                                                @if($application->linkedin_profile)
                                                    <a href="{{ $application->linkedin_profile }}" target="_blank"
                                                        class="inline-flex items-center font-semibold hover:underline"
                                                        style="color: #0077B5;">
                                                        <i class="fab fa-linkedin mr-2"></i>
                                                        View Profile
                                                    </a>
                                                @else
                                                    <span class="text-gray-500">Not provided</span>
                                                @endif
                                            </dd>
                                        </div>
                                    </div>
                                </div>

                                <!-- Expertise Section -->
                                <div>
                                    <h3 class="text-lg font-bold mb-4 font-montserrat flex items-center"
                                        style="color: #002147;">
                                        <i class="fas fa-tags mr-3" style="color: #ed1c24;"></i>
                                        Areas of Expertise
                                    </h3>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach(explode(',', $application->area_of_expertise) as $expertise)
                                            <span
                                                class="px-4 py-2 rounded-xl text-sm font-semibold border-2 font-montserrat"
                                                style="background: rgba(0, 33, 71, 0.1); color: #002147; border-color: rgba(0, 166, 81, 0.3);">
                                                {{ trim($expertise) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Bio Section -->
                                <div>
                                    <h3 class="text-lg font-bold mb-4 font-montserrat flex items-center"
                                        style="color: #002147;">
                                        <i class="fas fa-user-edit mr-3" style="color: #00a651;"></i>
                                        Professional Bio
                                    </h3>
                                    <div class="p-6 rounded-xl border border-gray-200 bg-gray-50">
                                        <p class="font-lato leading-relaxed" style="color: #002147;">
                                            {{ $application->bio }}</p>
                                    </div>
                                </div>

                                <!-- Documents Section -->
                                <div>
                                    <h3 class="text-lg font-bold mb-4 font-montserrat flex items-center"
                                        style="color: #002147;">
                                        <i class="fas fa-file-alt mr-3" style="color: #002147;"></i>
                                        Documents & Media
                                    </h3>
                                    <div class="space-y-4">
                                        <!-- Resume -->
                                        <div
                                            class="p-6 rounded-xl border-2 border-dashed border-gray-300 hover:border-gray-400 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4"
                                                        style="background: rgba(237, 28, 36, 0.1);">
                                                        <i class="fas fa-file-pdf text-lg" style="color: #ed1c24;"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-semibold font-montserrat"
                                                            style="color: #002147;">Resume/CV</h4>
                                                        <p class="text-sm text-gray-500 font-lato">Professional
                                                            background document</p>
                                                    </div>
                                                </div>
                                                @if($application->resume_path)
                                                    <a href="{{ asset($application->resume_path) }}" download
                                                        class="inline-flex items-center px-4 py-2 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg font-montserrat"
                                                        style="background: #ed1c24; color: white;"
                                                        onmouseover="this.style.background='#dc2626'"
                                                        onmouseout="this.style.background='#ed1c24'">
                                                        <i class="fas fa-download mr-2"></i>
                                                        Download
                                                    </a>
                                                @else
                                                    <span class="text-gray-500 text-sm font-lato">Not provided</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Video -->
                                        @if($application->intro_video_url)
                                            <div class="p-6 rounded-xl border border-gray-200 bg-gray-50">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4"
                                                            style="background: rgba(0, 166, 81, 0.1);">
                                                            <i class="fas fa-video text-lg" style="color: #00a651;"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="font-semibold font-montserrat"
                                                                style="color: #002147;">Introduction Video</h4>
                                                            <p class="text-sm text-gray-500 font-lato">Personal introduction
                                                                and teaching style</p>
                                                        </div>
                                                    </div>
                                                    <a href="{{ $application->intro_video_url }}" target="_blank"
                                                        class="inline-flex items-center px-4 py-2 rounded-xl font-semibold transition-all duration-300 hover:shadow-lg font-montserrat"
                                                        style="background: #00a651; color: white;"
                                                        onmouseover="this.style.background='#15803d'"
                                                        onmouseout="this.style.background='#00a651'">
                                                        <i class="fas fa-play mr-2"></i>
                                                        Watch Video
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Submission Details -->
                                <div class="pt-6 border-t border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-alt mr-3" style="color: #002147;"></i>
                                            <div>
                                                <h4 class="font-semibold font-montserrat" style="color: #002147;">
                                                    Submitted On</h4>
                                                <p class="text-sm text-gray-500 font-lato">
                                                    {{ $application->created_at->format('F j, Y, g:i a') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="px-4 py-2 rounded-full text-sm font-bold"
                                                style="background: rgba(0, 166, 81, 0.1); color: #00a651;">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Submitted Successfully
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-12" data-aos="fade-up" data-aos-delay="400">
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('homepage') }}"
                        class="inline-flex items-center px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl font-montserrat"
                        style="background: linear-gradient(135deg, #002147 0%, #003875 100%); color: white;"
                        onmouseover="this.style.boxShadow='0 20px 40px rgba(0, 33, 71, 0.3)'"
                        onmouseout="this.style.boxShadow='0 10px 30px rgba(0, 33, 71, 0.2)'">
                        <i class="fas fa-home mr-3"></i>
                        Back to Homepage
                    </a>

                    <a href="mailto:instructors@example.com"
                        class="inline-flex items-center px-8 py-4 rounded-xl font-bold text-lg border-2 transition-all duration-300 hover:shadow-lg font-montserrat"
                        style="color: #ed1c24; border-color: #ed1c24;"
                        onmouseover="this.style.backgroundColor='rgba(237, 28, 36, 0.1)'"
                        onmouseout="this.style.backgroundColor='transparent'">
                        <i class="fas fa-envelope mr-3"></i>
                        Contact Support
                    </a>
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
    </script>
</x-guest-layout>