<x-guest-layout>
    <div class="w-[85%] m-auto mb-10">
        <h2 class="font-montserrat font-bold text-4xl mb-3">Explore Courses</h2>
        <p class="text-gray-600 font-lato text-sm mb-6">
            Browse our curated selection of courses to upskill, learn new technologies, and advance your career. New courses added regularly.
        </p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-3 lg:w-[85%] m-auto">
        @if($courses->count())
            @foreach ($courses as $course )
            <div class="border-1 border-gray-400 rounded-lg">
                <div class="h-40 w-full overflow-hidden">
                    <img class="h-full w-full object-cover object-top"
                        src="{{ asset("storage/".$course->thumbnail_path) }}" alt="">
                </div>
                <div class="">
                    <div class="flex justify-between p-2 ">
                        <p class="text-sm font-semibold">{{ $course->category->name }}</p>
                        <p class="text-sm font-semibold text-green-700">₦{{ number_format($course->price, 2) }}</p>
                    </div>
                    <div class="p-2">
                        <a href="{{ route('courses.show', $course) }}" class="text-lg text-primary hover:text-secondary font-bold">{{ $course->title }}</a>
                    </div>
                    <div class="flex justify-between p-2 border-t-1 border-gray-400 ">
                        <div class="flex items-center gap-x-1">
                            <i data-lucide="star" class="fill-yellow-300 stroke-0 size-5"></i>
                            <span class="font-bold text-sm">4.0</span>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="user" class=" stroke-1 size-5 font-lato"></i>
                            <span class="text-sm font-bold">444,000 (students)</span>
                        </div>
                    </div>
                    <div class="p-2">
                        <a href="{{ route("courses.show", $course) }}" class="bg-primary rounded-lg py-1 px-3 text-white inline-flex items-center">
                            <i data-lucide="arrow-right-circle" class="stroke-1 size-5 mr-2"></i>
                            Enroll
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</x-guest-layout>
