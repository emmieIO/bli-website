<x-guest-layout>
    <!-- Breadcrumb -->
    <section class="py-4 bg-gray-50 border-b">
        <div class="container mx-auto px-4">
            <nav class="breadcrumb">
                <ul class="flex items-center space-x-2 text-sm font-lato">
                    <li><a href="{{ route('homepage') }}" class="text-secondary hover:text-primary">Home</a></li>
                    <li><span class="text-gray-400">/</span></li>
                    <li><a href="{{ route('events.index') }}" class="text-secondary hover:text-primary">Events</a></li>
                    <li><span class="text-gray-400">/</span></li>
                    <li><span class="text-gray-600">Speaker Profile</span></li>
                </ul>
            </nav>
        </div>
    </section>

    <!-- Speaker Profile -->
    <section class="py-8 md:py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-6">
                        <!-- Speaker Photo -->
                        <div class="text-center mb-6">
                            @if (!empty($speaker->user->photo))
                                <img src="{{ asset('storage/' . $speaker->user->photo) }}"
                                    alt="{{ $speaker->user->name }}"
                                    class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($speaker->user->name) }}&background=00275E&color=fff&size=256&bold=true"
                                    alt="{{ $speaker->user->name }}"
                                    class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                            @endif
                        </div>

                        <!-- Quick Info -->
                        <div class="space-y-4">
                            <div class="text-center">
                                <h1 class="text-2xl font-bold text-primary text-center font-montserrat">
                                    {{ $speaker->user->name }}
                                </h1>
                                <p class="block text-xs font-bold">{{ $speaker->user->email }}</p>
                                <p class="block text-xs font-bold">{{ $speaker->user->phone }}</p>
                                @if ($speaker->user->headline)
                                    <p class="text-secondary font-semibold text-center mt-2 font-montserrat">
                                        {{ $speaker->user->headline }}
                                    </p>
                                @endif
                            </div>

                            <!-- Social Links -->
                            @if ($speaker->user->twitter || $speaker->user->linkedin || $speaker->user->website)
                                <div class="flex justify-center space-x-4 pt-4">
                                    @if ($speaker->user->twitter)
                                        <a href="{{ $speaker->user->twitter }}" target="_blank"
                                            class="text-gray-400 hover:text-blue-400 transition-colors">
                                            <i data-lucide="twitter" class="w-5 h-5"></i>
                                        </a>
                                    @endif
                                    @if ($speaker->user->linkedin)
                                        <a href="{{ $speaker->user->linkedin }}" target="_blank"
                                            class="text-gray-400 hover:text-blue-600 transition-colors">
                                            <i data-lucide="linkedin" class="w-5 h-5"></i>
                                        </a>
                                    @endif
                                    @if ($speaker->user->website)
                                        <a href="{{ $speaker->user->website }}" target="_blank"
                                            class="text-gray-400 hover:text-primary transition-colors">
                                            <i data-lucide="globe" class="w-5 h-5"></i>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Bio Section -->
                    @if ($speaker->bio)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                            <h2 class="text-xl md:text-2xl font-bold text-primary mb-4 font-montserrat">
                                About Me
                            </h2>
                            <div class="prose max-w-none text-gray-700 leading-relaxed font-lato">
                                {!! nl2br(e($speaker->bio)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Upcoming Events -->
                    @if ($speaker->events->count())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                            <h2 class="text-xl md:text-2xl font-bold text-primary mb-6 font-montserrat">
                                Upcoming Events
                            </h2>
                            <div class="space-y-4">
                                @foreach ($speaker->events as $event)
                                    <div
                                        class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-md transition-all">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('storage/' . $event->program_cover) }}"
                                                alt="{{ $event->title }}" class="w-16 h-16 rounded-lg object-cover">
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-bold text-primary font-montserrat">
                                                <a href="{{ route('events.show', $event->slug) }}"
                                                    class="hover:text-secondary transition-colors">
                                                    {{ $event->title }}
                                                </a>
                                            </h3>
                                            <p class="text-gray-600 text-sm font-lato">
                                                {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Expertise/Skills -->
                    @if ($speaker->expertise)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                            <h2 class="text-xl md:text-2xl font-bold text-primary mb-4 font-montserrat">
                                Areas of Expertise
                            </h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach (explode(',', $speaker->expertise) as $skill)
                                    <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-lato">
                                        {{ trim($skill) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
