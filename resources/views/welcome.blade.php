<x-guest-layout>
    <section class=" text-white bg-teal-800">
        <div class="md:flex md:justify-between lg:place-items-center py-10 w-[90%] mx-auto">
            <div class="hero-text">
                <h4 class="md:font-semibold">Beacon Leadership Institute</h4>
                <h1>Empowering Leaders for Influence & Impact</h1>
                <p>Developing visionary leaders to drive positive change in organizations and communities.</p>
                <button class="cta-btn text-white bg-teal-400">Get Started</button>
            </div>
            <div class="md:w-[450px] hidden lg:block">
                <img src="{{ asset('images/family.png') }}" alt="family" />
            </div>
        </div>
    </section>

    <section class="my-7 md:max-w-[1200px] mx-auto px-5">
        <div class="grid md:grid-cols-3 my-[3rem] gap-3">
            <div class=" p-2 rounded-md py-3 border border-teal-200  shadow-gray-200 shadow-md">
                <i data-lucide="telescope" class="size-8 stroke-1"></i>
                <h2 class="text-2xl font-medium">Our Vision</h2>
                <p>To raise a new generation of <b>visionary</b> value-drivven and spirit-led leaders commited to
                    personal and
                    global transformation</p>
            </div>
            <div class=" p-2 rounded-md py-3 border border-teal-200  shadow-gray-200 shadow-md">
                <i data-lucide="pin" class="size-8 stroke-1"></i>
                <h2 class="text-2xl font-medium">Our Mision</h2>
                <p>To equip emerging and existing leaders with the spiritual, intellectual, and practical tools needed
                    to lead
                    the excellence in the ministry, business, and public life</p>
            </div>
            <div class=" p-2 rounded-md py-3 border border-teal-200  shadow-gray-200 shadow-md">
                <i data-lucide="landmark" class="size-8 stroke-1"></i>
                <h2 class="text-2xl font-medium">Our Core Pillars - The 4Cs</h2>
                <ul class=" list-inside list-decimal">
                    <li>Character</li>
                    <li>Competence</li>
                    <li>Capacity</li>
                    <li>Clarity</li>
                </ul>
            </div>

        </div>
    </section>

    <section class="my-7 w-[90%] mx-auto">
        <div class="md:flex place-content-center justify-around items-center rounded-lg py-4 bg-teal-800 p-3">
            <div>
                <h2 class="text-3xl font-semibold inline-block py-2 text-white">What We Offer</h2>
                <ul class="space-y-2 font-semibold list-disc gap-3 list-inside text-white">
                    <li class="flex items-center"><i data-lucide="pin" class="stroke-1"></i><span>Leadership Training &
                            Certificate</span></li>
                    <li class="flex items-center"><i data-lucide="pin" class="stroke-1"></i><span>Mentorship & Coaching
                            Programming</span></li>
                    <li class="flex items-center"><i data-lucide="pin" class="stroke-1"></i><span>Faith-based Executive
                            Development</span></li>
                    <li class="flex items-center"><i data-lucide="pin" class="stroke-1"></i><span>Workshops, Retreats &
                            Conferences</span></li>
                    <li class="flex items-center"><i data-lucide="pin" class="stroke-1"></i><span>Online Courses &
                            Resources</span></li>
                </ul>
            </div>
            <div class="md:w-[350px] md:block hidden  bg-teal-300 rounded-full">
                <img src="{{ asset('images/offer.png') }}" alt="">
            </div>
        </div>
    </section>
</x-guest-layout>
