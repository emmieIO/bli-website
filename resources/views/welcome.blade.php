{{-- <x-navbar /> --}}
<x-guest-layout>
{{-- <x-navbar /> --}}
    <!-- HERO Section -->
    <section class="text-white bg-[#ff0000] hero">
        <div class="flex items-center py-16 gap-6">
            <div class="space-y-5 text-center hero-text">
                <h4 class="text-md font-semibold tracking-wide uppercase">
                    Beacon Leadership Institute
                </h4>
                <h1 class="hero-headline text-3xl md:text-3xl lg:text-5xl font-extrabold leading-tight">
                    Empowering Leaders for Influence & Impact 
                </h1>
                <p class="text-white/90 text-lg leading-relaxed">
                    Developing visionary leaders to drive positive change in organizations and communities.
                </p>
                <div class="flex space-x-2 justify-center items-center">
                    <a href="{{ route('events.index') }}"
                        class=" btn btn-primary btn-lg flex items-center gap-2 px-4 py-3 bg-[#00275e] hover:bg-blue-800">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        Browse Events
                    </a>
                    <a href="{{ route('events.index') }}"
                        class="btn btn-primary text-white btn-lg flex items-center gap-2  bg-[#ff0000]  hover:bg-red-700">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        Discover Events
                    </a>
                </div>
            </div>


        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-5 text-center space-y-10" data-aos="zoom-in">
            <h2 class="text-3xl font-bold text-black">Why Choose Beacon Leadership Institute?</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 text-left">
                <div class="p-6 border rounded-lg bg-gray-50 shadow">
                    <i data-lucide="brain" class="w-6 h-6 text-[#00275e] mb-2 "></i>
                    <h4 class="text-lg font-semibold">Spirit-Led Learning</h4>
                    <p>Rooted in Christian values, our training transforms the heart as much as the mind.</p>
                </div>
                <div class="p-6 border rounded-lg bg-gray-50 shadow">
                    <i data-lucide="layout-dashboard" class="w-6 h-6 text-[#00275e] mb-2"></i>
                    <h4 class="text-lg font-semibold">Event Dashboard</h4>
                    <p>Track registrations, invitations, and leadership formation opportunities.</p>
                </div>
                <div class="p-6 border rounded-lg bg-gray-50 shadow">
                    <i data-lucide="graduation-cap" class="w-6 h-6 text-[#ff0000] mb-2"></i>
                    <h4 class="text-lg font-semibold">Guided Formation</h4>
                    <p>Join events and community pathways that equip and empower your leadership journey.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Events -->
    <section id="events" class="py-16 bg-gray-100">
        <div class="max-w-6xl mx-auto px-5">
            <h2 class="text-3xl font-bold text-center text-[#00275e] mb-8">Featured Events</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
                    <img src="{{ asset('assets/img/banner.png') }}" alt="Leadership event" class="w-full h-40 object-cover" />
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800">Foundations of Leadership</h3>
                        <p class="text-gray-600 text-sm mt-2">Learn the core principles of faith-driven leadership.</p>
                        <a href="{{ route('events.index') }}"
                            class="text-[#ff0000] text-sm font-medium mt-4 inline-block hover:text-red-700 transition">View
                            Events</a>
                    </div>
                </div>
                <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
                    <img src="{{ asset('assets/img/banner.png') }}" alt="Leadership event" class="w-full h-40 object-cover" />
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800">Spiritual Leadership in the Marketplace</h3>
                        <p class="text-gray-600 text-sm mt-2">Equip yourself to lead with integrity in business.</p>
                        <a href="{{ route('events.index') }}"
                            class="text-[#ff0000] text-sm font-medium mt-4 inline-block hover:text-red-700 transition">View
                            Events</a>
                    </div>
                </div>
                <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
                    <img src="{{ asset('assets/img/banner.png') }}" alt="Leadership event" class="w-full h-40 object-cover" />
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800">Vision Clarity Bootcamp</h3>
                        <p class="text-gray-600 text-sm mt-2">Gain clarity, confidence, and spiritual focus as a leader.
                        </p>
                        <a href="{{ route('events.index') }}"
                            class="text-[#ff0000] text-sm font-medium mt-4 inline-block hover:text-red-700 transition">View
                            Events</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-5 text-center space-y-10">
            <h2 class="text-3xl font-bold text-[#00275e]">Transformational Stories</h2>
            <div class="grid md:grid-cols-2 gap-8 text-left">
                <div>
                    <blockquote class="italic text-gray-600">"The leadership training at Beacon changed my ministry
                        completely. I now lead with clarity and confidence."</blockquote>
                    <p class="mt-2 font-semibold text-[#ff0000]">&mdash; Pastor Ada, Abuja</p>
                </div>
                <div>
                    <blockquote class="italic text-gray-600">"I finally found a platform that aligns faith and
                        leadership. Highly recommend."</blockquote>
                    <p class="mt-2 font-semibold text-[#ff0000]">&mdash; David O., Lagos</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Tracks -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-5 text-center space-y-10">
            <h2 class="text-3xl font-bold text-[#00275e]">Leadership Tracks</h2>
            <div class="grid md:grid-cols-3 gap-6 text-left">
                <div>
                    <h4 class="font-bold text-lg text-[#00275e]">Foundations of Leadership</h4>
                    <p class="text-gray-600 mt-2">Start your journey with faith-based principles of influence and
                        stewardship.</p>
                </div>
                <div>
                    <h4 class="font-bold text-lg text-[#ff0000]">Strategic Leadership</h4>
                    <p class="text-gray-600 mt-2">Build advanced leadership strategies for ministry and business
                        success.</p>
                </div>
                <div>
                    <h4 class="font-bold text-lg text-[#00275e]">Marketplace Leadership</h4>
                    <p class="text-gray-600 mt-2">Equip yourself to influence government, education, finance, and the
                        arts.</p>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
