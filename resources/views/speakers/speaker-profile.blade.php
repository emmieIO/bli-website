<x-guest-layout>
    <!-- Enhanced Hero Section -->
    <section class="relative py-16 overflow-hidden"
        style="background: linear-gradient(135deg, #002147 0%, #003875 50%, #002147 100%);">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 rounded-full animate-pulse"
                style="background: linear-gradient(135deg, #00a651, #ed1c24);"></div>
            <div class="absolute bottom-10 right-10 w-24 h-24 rounded-full animate-bounce"
                style="background: linear-gradient(135deg, #ed1c24, #00a651);"></div>
        </div>

        <div class="relative z-10 container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="mb-8" data-aos="fade-down">
                <ul class="flex items-center space-x-2 text-sm font-lato">
                    <li><a href="{{ route('homepage') }}"
                            class="text-blue-200 hover:text-white transition-colors">Home</a></li>
                    <li><i class="fas fa-chevron-right text-blue-300 text-xs"></i></li>
                    <li><a href="{{ route('events.index') }}"
                            class="text-blue-200 hover:text-white transition-colors">Events</a></li>
                    <li><i class="fas fa-chevron-right text-blue-300 text-xs"></i></li>
                    <li><span class="text-white">Speaker Profile</span></li>
                </ul>
            </nav>

            <!-- Speaker Hero Info -->
            <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8">
                <!-- Speaker Photo -->
                <div class="flex-shrink-0" data-aos="fade-right">
                    <div class="relative">
                        @if (!empty($speaker->user->photo))
                            <img src="{{ asset('storage/' . $speaker->user->photo) }}" alt="{{ $speaker->user->name }}"
                                class="w-40 h-40 rounded-3xl object-cover border-4 border-white shadow-2xl">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($speaker->user->name) }}&background=00a651&color=fff&size=320&bold=true"
                                alt="{{ $speaker->user->name }}"
                                class="w-40 h-40 rounded-3xl object-cover border-4 border-white shadow-2xl">
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute -bottom-2 -right-2 px-3 py-1 rounded-full text-xs font-semibold"
                            style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%); color: white;">
                            <i class="fas fa-check-circle mr-1"></i>
                            Verified Speaker
                        </div>
                    </div>
                </div>

                <!-- Speaker Info -->
                <div class="flex-1 text-center lg:text-left" data-aos="fade-left">
                    <h1 class="text-4xl lg:text-5xl font-bold text-white mb-4 font-montserrat">
                        {{ $speaker->user->name }}
                    </h1>

                    @if ($speaker->user->headline)
                        <p class="text-2xl font-semibold mb-4 font-montserrat" style="color: #00a651;">
                            {{ $speaker->user->headline }}
                        </p>
                    @endif

                    <div class="flex flex-col sm:flex-row gap-4 mb-6 justify-center lg:justify-start">
                        <div class="flex items-center gap-2 text-blue-200">
                            <i class="fas fa-envelope"></i>
                            <span class="font-lato">{{ $speaker->user->email }}</span>
                        </div>
                        @if($speaker->user->phone)
                            <div class="flex items-center gap-2 text-blue-200">
                                <i class="fas fa-phone"></i>
                                <span class="font-lato">{{ $speaker->user->phone }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Social Links -->
                    @if ($speaker->user->twitter || $speaker->user->linkedin || $speaker->user->website)
                        <div class="flex justify-center lg:justify-start space-x-4">
                            @if ($speaker->user->twitter)
                                <a href="{{ $speaker->user->twitter }}" target="_blank"
                                    class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 hover:scale-110"
                                    style="background: rgba(29, 161, 242, 0.2); color: #1da1f2;">
                                    <i class="fab fa-twitter text-lg"></i>
                                </a>
                            @endif
                            @if ($speaker->user->linkedin)
                                <a href="{{ $speaker->user->linkedin }}" target="_blank"
                                    class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 hover:scale-110"
                                    style="background: rgba(0, 119, 181, 0.2); color: #0077b5;">
                                    <i class="fab fa-linkedin text-lg"></i>
                                </a>
                            @endif
                            @if ($speaker->user->website)
                                <a href="{{ $speaker->user->website }}" target="_blank"
                                    class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 hover:scale-110"
                                    style="background: rgba(0, 166, 81, 0.2); color: #00a651;">
                                    <i class="fas fa-globe text-lg"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Speaker Profile Content -->
    <section class="py-16" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Enhanced Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden sticky top-6"
                        data-aos="fade-right">
                        <!-- Speaker Stats Header -->
                        <div class="p-6 text-center"
                            style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                                style="background: rgba(0, 166, 81, 0.2);">
                                <i class="fas fa-star text-2xl" style="color: #00a651;"></i>
                            </div>
                            <h3 class="text-lg font-bold text-white font-montserrat">Speaker Profile</h3>
                            <p class="text-blue-200 text-sm font-lato">Professional Details</p>
                        </div>

                        <!-- Quick Stats -->
                        <div class="p-6 space-y-6">
                            <!-- Events Count -->
                            <div class="text-center p-4 rounded-2xl" style="background: rgba(0, 33, 71, 0.05);">
                                <div class="text-3xl font-bold font-montserrat mb-1" style="color: #002147;">
                                    {{ $speaker->events->count() }}
                                </div>
                                <p class="text-sm text-gray-600 font-lato">Speaking Events</p>
                            </div>

                            <!-- Expertise Count -->
                            @if ($speaker->expertise)
                                <div class="text-center p-4 rounded-2xl" style="background: rgba(237, 28, 36, 0.05);">
                                    <div class="text-3xl font-bold font-montserrat mb-1" style="color: #ed1c24;">
                                        {{ count(explode(',', $speaker->expertise)) }}
                                    </div>
                                    <p class="text-sm text-gray-600 font-lato">Areas of Expertise</p>
                                </div>
                            @endif

                            <!-- Contact Actions -->
                            <div class="space-y-3">
                                <a href="mailto:{{ $speaker->user->email }}"
                                    class="w-full py-3 px-4 rounded-xl text-white font-semibold font-montserrat transition-all duration-300 hover:scale-105 flex items-center justify-center"
                                    style="background: linear-gradient(135deg, #002147 0%, #ed1c24 100%);">
                                    <i class="fas fa-envelope mr-2"></i>
                                    Contact Speaker
                                </a>

                                @if($speaker->user->phone)
                                    <a href="tel:{{ $speaker->user->phone }}"
                                        class="w-full py-3 px-4 border-2 rounded-xl font-semibold font-montserrat transition-all duration-300 hover:bg-gray-50 flex items-center justify-center"
                                        style="border-color: #002147; color: #002147;">
                                        <i class="fas fa-phone mr-2"></i>
                                        Call Speaker
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Main Content -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Enhanced Bio Section -->
                    @if ($speaker->bio)
                        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden"
                            data-aos="fade-up">
                            <div class="p-6 border-b border-gray-100"
                                style="background: linear-gradient(135deg, #00a651 0%, #15803d 100%);">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mr-4"
                                        style="background: rgba(255, 255, 255, 0.2);">
                                        <i class="fas fa-user text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-white font-montserrat">About
                                            {{ $speaker->user->name }}</h2>
                                        <p class="text-green-100 font-lato">Professional Background & Experience</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-8">
                                <div class="prose max-w-none text-gray-700 leading-relaxed font-lato text-lg">
                                    {!! nl2br(e($speaker->bio)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Enhanced Upcoming Events -->
                    @if ($speaker->events->count())
                        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden"
                            data-aos="fade-up" data-aos-delay="200">
                            <div class="p-6 border-b border-gray-100"
                                style="background: linear-gradient(135deg, #ed1c24 0%, #dc2626 100%);">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mr-4"
                                        style="background: rgba(255, 255, 255, 0.2);">
                                        <i class="fas fa-calendar text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-white font-montserrat">Speaking Events</h2>
                                        <p class="text-red-100 font-lato">{{ $speaker->events->count() }} upcoming
                                            presentations</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-8">
                                <div class="grid gap-6">
                                    @foreach ($speaker->events as $event)
                                        <div class="group p-6 rounded-2xl border-2 border-gray-100 hover:border-gray-200 hover:shadow-lg transition-all duration-300"
                                            style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                                            <div class="flex items-start gap-6">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('storage/' . $event->program_cover) }}"
                                                        alt="{{ $event->title }}"
                                                        class="w-20 h-20 rounded-2xl object-cover shadow-lg group-hover:scale-105 transition-transform duration-300">
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                                                        <h3 class="text-xl font-bold font-montserrat group-hover:text-red-600 transition-colors"
                                                            style="color: #002147;">
                                                            <a href="{{ route('events.show', $event->slug) }}">
                                                                {{ $event->title }}
                                                            </a>
                                                        </h3>
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                                            style="background: rgba(0, 166, 81, 0.1); color: #00a651;">
                                                            <i class="fas fa-calendar-check mr-1"></i>
                                                            {{ \Carbon\Carbon::parse($event->start_date)->format('M j, Y') }}
                                                        </span>
                                                    </div>

                                                    @if($event->description)
                                                        <p class="text-gray-600 font-lato leading-relaxed mb-3">
                                                            {{ Str::limit(strip_tags($event->description), 120) }}
                                                        </p>
                                                    @endif

                                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                                        @if($event->location)
                                                            <div class="flex items-center">
                                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                                <span class="font-lato">{{ $event->location }}</span>
                                                            </div>
                                                        @endif
                                                        <div class="flex items-center">
                                                            <i class="fas fa-users mr-1"></i>
                                                            <span class="font-lato">{{ $event->max_attendees ?? '500+' }}
                                                                attendees</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Enhanced Expertise/Skills -->
                    @if ($speaker->expertise)
                        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden"
                            data-aos="fade-up" data-aos-delay="400">
                            <div class="p-6 border-b border-gray-100"
                                style="background: linear-gradient(135deg, #002147 0%, #003875 100%);">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mr-4"
                                        style="background: rgba(255, 255, 255, 0.2);">
                                        <i class="fas fa-lightbulb text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-white font-montserrat">Areas of Expertise</h2>
                                        <p class="text-blue-200 font-lato">Professional skills and knowledge domains</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-8">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach (explode(',', $speaker->expertise) as $index => $skill)
                                        <div class="group p-4 rounded-2xl border-2 border-gray-100 hover:border-blue-200 hover:shadow-lg transition-all duration-300 text-center"
                                            style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3 transition-all duration-300 group-hover:scale-110"
                                                style="background: {{ $index % 3 == 0 ? 'rgba(0, 33, 71, 0.1)' : ($index % 3 == 1 ? 'rgba(237, 28, 36, 0.1)' : 'rgba(0, 166, 81, 0.1)') }};">
                                                <i class="fas fa-{{ $index % 3 == 0 ? 'briefcase' : ($index % 3 == 1 ? 'chart-line' : 'code') }} text-lg"
                                                    style="color: {{ $index % 3 == 0 ? '#002147' : ($index % 3 == 1 ? '#ed1c24' : '#00a651') }};"></i>
                                            </div>
                                            <h3 class="font-semibold font-montserrat mb-1" style="color: #002147;">
                                                {{ trim($skill) }}
                                            </h3>
                                            <p class="text-xs text-gray-500 font-lato">Specialization</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
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