<x-guest-layout>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 ">
        <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-x-12">

            <div class="lg:col-span-2 mb-8 lg:mb-0">
                <nav class="text-sm text-gray-500 mb-4">
                    <a href="{{ route('homepage') }}" class="hover:underline">Home</a> &gt;
                    <a href="{{ route('courses.index') }}" class="hover:underline">Courses</a> &gt;
                    <span class="text-gray-700">{{ $course->title }}</span>
                </nav>

                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-3">{{ $course->title }}
                </h1>

                <p class="text-lg text-gray-600 mb-6">3 in 1 Course: Learn to design websites with Figma, build with
                    Webflow, and make a living freelancing.</p>

                <div class="flex flex-wrap items-center gap-x-6 gap-y-4">
                    <div class="flex items-center">
                        <div>
                            <span class="text-sm text-gray-600">Created by:</span>
                            <p class="font-semibold text-gray-800">{{ $course->instructor->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 self-end">
                        <i data-lucide="star" class="fill-yellow-400 stroke-0"></i>
                        <span class="font-bold text-gray-800">4.8</span>
                        <span class="text-sm text-gray-500">(401,444 rating)</span>
                    </div>
                </div>
                <div>
                    <!-- replace VIDEO_ID with your Vimeo video id -->
                    <div class="">
                        <iframe
                            src="https://player.vimeo.com/video/{{ $course->preview_video_id }}?h=0&autoplay=0&title=0&byline=0&portrait=0"
                            class="" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen
                            loading="lazy" width="1280" height="720"
                            style="width:100%;max-width:1280px;height:auto;aspect-ratio:16/9;" title="Course preview">
                        </iframe>
                    </div>
                </div>

                <div class="my-8">
                    <nav class="tabs tabs-bordered " aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                        <button type="button" class="tab active-tab:tab-active active text-primary" id="overview-item"
                            data-tab="#overview" aria-controls="overview" role="tab" aria-selected="true">
                            Overview
                        </button>
                        <button type="button" class="tab active-tab:tab-active text-primary" id="curriculum-item"
                            data-tab="#curriculum" aria-controls="curriculum" role="tab" aria-selected="false">
                            Course Curriculum
                        </button>
                        <button type="button" class="tab active-tab:tab-active text-primary" id="tabs-basic-item-3"
                            data-tab="#instructor" aria-controls="instructor" role="tab" aria-selected="false">
                            Instructor
                        </button>
                    </nav>
                </div>
                {{-- tab section --}}
                <div class="mt-3">
                    {{-- overview section --}}
                    <div id="overview" role="tabpanel" aria-labelledby="overview-item">
                        <div class="mb-10">
                            <h2 class="text-2xl font-semibold font-montserrat my-3">
                                Description
                            </h2>
                            <p>{{ $course->description }}</p>
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold font-montserrat my-3">
                                Course Requirements
                            </h2>
                            @if($course->requirements->count())
                            <ul class="mt-2 list-disc list-inside space-y-2 text-gray-700">
                                @foreach ($course->requirements as $requirement )
                                <li>{{ $requirement->requirement }}</li>
                                @endforeach
                            </ul>
                            @else
                            <div class="text-sm text-gray-600">
                                <p class="mb-2">No course requirements listed.</p>
                                <p class="text-xs text-gray-500">You don’t need any prior experience to start this
                                    course.</p>
                            </div>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold font-montserrat my-3">
                                Course Outcomes
                            </h2>
                            @if($course->outcomes->count())
                            <ul class="mt-2 list-disc list-inside space-y-2 text-gray-700">
                                @foreach ($course->outcomes as $outcome)
                                <li class="text-sm leading-6">
                                    {{ $outcome->outcome }}
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <div class="text-sm text-gray-600">
                                <p class="mb-2">No course requirements listed.</p>
                                <p class="text-xs text-gray-500">You don’t need any prior experience to start this
                                    course.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    {{-- Course curriculum --}}
                    <div id="curriculum" class="hidden" role="tabpanel" aria-labelledby="curriculum-item">
                        <div class="accordion divide-neutral/20 divide-y">
                            @forelse($course->modules as $module)
                            <div class="accordion-item {{ $loop->first ? 'active' : '' }}"
                                id="module-{{ $module->id }}">
                                <button class="accordion-toggle inline-flex items-center gap-x-4 text-start w-full"
                                    aria-controls="module-{{ $module->id }}-collapse"
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                    <span
                                        class="icon-[tabler--plus] accordion-item-active:hidden text-base-content size-4.5 block shrink-0"></span>
                                    <span
                                        class="icon-[tabler--minus] accordion-item-active:block text-base-content size-4.5 hidden shrink-0"></span>

                                    <span class="font-medium text-gray-800 font-montserrat">{{ $module->title }}</span>
                                    <span class="ml-auto text-sm text-gray-500">{{ $module->lessons->count() }} {{
                                        Str::plural('lesson', $module->lessons->count()) }}</span>
                                </button>

                                <div id="module-{{ $module->id }}-collapse"
                                    class="accordion-content {{ $loop->first ? '' : 'hidden' }} w-full overflow-hidden transition-[height] duration-300"
                                    aria-labelledby="module-{{ $module->id }}" role="region">
                                    <div class="px-5 pb-4">
                                        @if($module->lessons->count())
                                        <ul class="space-y-2">
                                            @foreach($module->lessons as $lesson)
                                            <li class="flex items-center justify-between py-2">
                                                <div class="flex items-center gap-3">
                                                    <span
                                                        class="w-8 h-8 flex items-center justify-center rounded-sm bg-gray-100 text-gray-600 text-sm font-semibold">{{
                                                        $loop->iteration }}</span>

                                                    {{-- lesson type icon --}}
                                                    <span class="shrink-0"></span>
                                                    @switch($lesson->type)
                                                    @case('video')
                                                    <span
                                                        class="icon-[tabler--player-play] size-4.5 text-orange-500"></span>
                                                    @break
                                                    @case('pdf')
                                                    <span
                                                        class="icon-[tabler--file-text] size-4.5 text-blue-500"></span>
                                                    @break
                                                    @case('link')
                                                    <span class="icon-[tabler--link] size-4.5 text-green-500"></span>
                                                    @break
                                                    @default
                                                    <span class="icon-[tabler--file] size-4.5 text-gray-500"></span>
                                                    @endswitch
                                                    </span>

                                                    <div>
                                                        <div class="text-sm text-gray-800">{{ $lesson->title }}</div>
                                                        @if(!empty($lesson->subtitle))
                                                        <div class="text-xs text-gray-500">{{ $lesson->subtitle }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if(!empty($lesson->duration))
                                                <div class="text-xs text-gray-500">{{ $lesson->duration }}</div>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                        @else
                                        <p class="text-sm text-gray-600">This module has no lessons yet.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="px-5 py-4 text-sm text-gray-600">
                                No curriculum available for this course.
                            </div>
                            @endforelse
                        </div>
                    </div>
                    <div id="instructor" class="hidden" role="tabpanel" aria-labelledby="tabs-basic-item-3">
                        <div class="flex items-start gap-6">
                            <img src="{{ $course->instructor->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name).'&background=fff&color=4a5568&size=128' }}"
                                alt="{{ $course->instructor->name }}"
                                class="w-20 h-20 rounded-full object-cover shadow-sm">

                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $course->instructor->name }}</h3>

                                @if(!empty($course->instructor->title))
                                <div class="text-sm text-gray-500 mb-2">{{ $course->instructor->title }}</div>
                                @endif

                                <p class="text-sm text-gray-700">
                                    {{ $course->instructor->bio ?? 'No bio available for this instructor.' }}
                                </p>

                                <div class="mt-4 flex flex-wrap items-center gap-3">
                                    @if(!empty($course->instructor->email))
                                    <a href="mailto:{{ $course->instructor->email }}"
                                        class="inline-block px-3 py-2 bg-orange-500 text-white rounded-md text-sm font-semibold hover:bg-orange-600">
                                        Contact
                                    </a>
                                    @endif

                                    @if(!empty($course->instructor->website))
                                    <a href="{{ $course->instructor->website }}" target="_blank" rel="noopener"
                                        class="text-sm text-gray-700 hover:underline">Website</a>
                                    @endif

                                    @if(!empty($course->instructor->twitter))
                                    <a href="https://twitter.com/{{ ltrim($course->instructor->twitter, '@') }}"
                                        target="_blank" rel="noopener"
                                        class="text-sm text-gray-700 hover:underline">Twitter</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="border border-gray-200 rounded-lg shadow-md p-6 sticky top-8">
                    <div class="flex items-baseline justify-between mb-1">
                        <span class="text-3xl font-extrabold text-gray-900">₦{{ number_format($course->price, 2)
                            }}</span>
                        {{-- <span class="text-lg text-gray-500 line-through">$26.00</span> --}}
                        <span class="text-sm font-bold bg-orange-100 text-orange-600 px-2 py-1 rounded-md">56%
                            OFF</span>
                    </div>
                    <p class="text-sm text-red-600 font-medium mb-5">2 days left at this price!</p>

                    <div class="space-y-3 text-sm text-gray-600 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-800">Course Level</span><span>{{ $course->level }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-800">Students Enrolled</span><span>69,419,618</span>
                        </div>
                    </div>

                    <div class="space-y-3 mb-4">
                        <a href="#"
                            class="block w-full text-center text-white bg-orange-500 hover:bg-orange-600 font-bold py-3 rounded-lg transition-colors">Add
                            To Cart</a>
                        <a href="#"
                            class="block w-full text-center text-gray-800 bg-white border border-gray-400 hover:bg-gray-100 font-bold py-3 rounded-lg transition-colors">Buy
                            Now</a>
                    </div>

                    <div class="flex justify-center space-x-6 mb-4">
                        <a href="#" class="text-sm font-semibold text-gray-800 hover:underline">Add To
                            Wishlist</a>
                        <a href="#" class="text-sm font-semibold text-gray-800 hover:underline">Gift Course</a>
                    </div>

                    <p class="text-xs text-gray-500 text-center mb-6">Note: all course have 30-days money-back guarantee
                    </p>

                    <h3 class="font-bold text-gray-900 mb-3">This course includes:</h3>
                    <ul class="space-y-2.5 text-sm text-gray-700">
                        <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>Lifetime access</li>
                        <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>30-days money-back guarantee</li>
                        <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>Free exercises file & downloadable resources</li>
                        <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                </path>
                            </svg>Shareable certificate of completion</li>
                        <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>Access on mobile, tablet and TV</li>
                        <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5h12M9 3v2m4 0V3m4 4l-2 2-2-2m5.5 5.5h-13a1.5 1.5 0 000 3h13a1.5 1.5 0 000-3z">
                                </path>
                            </svg>English subtitles</li>
                        <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-gray-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 2v10m0 0l-3-3m3 3l3-3"></path>
                            </svg>100% online course</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('button[role=tab]');
            const tabPanels = document.querySelectorAll('div[role="tabpanel"]');
            const storageKey = 'activeTabId'

            // function to activate tab
            function activateTab(tabId) {
                tabs.forEach(tab => {
                    const isSelected = tab.getAttribute('data-tab') == tabId
                    tab.classList.toggle('active', isSelected);
                    tab.setAttribute('aria-selected', isSelected);
                })
                tabPanels.forEach(panel => {
                    const isSelected = '#' + panel.id === tabId;
                    panel.classList.toggle('hidden', !isSelected);
                });

                // Save the active tab ID to localStorage
                localStorage.setItem(storageKey, tabId);
            }

            // Event listener for tab clicks
            tabs.forEach(tab => {
                tab.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent default button behavior
                    const targetTabId = tab.getAttribute('data-tab');
                    activateTab(targetTabId);
                });
            });

            // On page load, check for a saved tab in localStorage
            const savedTabId = localStorage.getItem(storageKey);

            // Determine which tab to activate
            // If a saved tab exists and corresponds to a real tab, use it.
            // Otherwise, default to the first tab.
            const tabToActivate = savedTabId && document.querySelector(`button[data-tab="${savedTabId}"]`)
                ? savedTabId
                : tabs[0].getAttribute('data-tab');

            activateTab(tabToActivate);
        });
    </script>
</x-guest-layout>