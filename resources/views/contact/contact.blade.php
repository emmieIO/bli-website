<x-guest-layout>
    <section class="py-16 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-teal-800">Contact Us</h2>
                <p class="text-gray-600 mt-2 max-w-xl mx-auto">
                    Have questions or want to get in touch with us? Fill out the form and our team will reach out to you
                    shortly.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-10 items-start">
                <!-- Contact Info -->
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <i data-lucide="map-pin" class="w-6 h-6 text-teal-600"></i>
                        <div>
                            <h4 class="font-semibold text-gray-800">Address</h4>
                            <p class="text-gray-600">123 Beacon Avenue, Lagos, Nigeria</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <i data-lucide="mail" class="w-6 h-6 text-teal-600"></i>
                        <div>
                            <h4 class="font-semibold text-gray-800">Email</h4>
                            <p class="text-gray-600">info@beaconleadership.org</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <i data-lucide="phone" class="w-6 h-6 text-teal-600"></i>
                        <div>
                            <h4 class="font-semibold text-gray-800">Phone</h4>
                            <p class="text-gray-600">+234-706-442-5639</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <i data-lucide="clock" class="w-6 h-6 text-teal-600"></i>
                        <div>
                            <h4 class="font-semibold text-gray-800">Office Hours</h4>
                            <p class="text-gray-600">Monday - Friday: 9:00 AM - 5:00 PM</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <form action="#" method="POST" class="bg-white p-6 rounded-lg shadow-md space-y-5">
                    <div>
                        <label for="name" class="block font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="name" name="name" required
                            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-teal-600 focus:border-teal-600">
                    </div>
                    <div>
                        <label for="email" class="block font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" required
                            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-teal-600 focus:border-teal-600">
                    </div>
                    <div>
                        <label for="message" class="block font-medium text-gray-700 mb-1">Message</label>
                        <textarea id="message" name="message" rows="5" required
                            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-teal-600 focus:border-teal-600"></textarea>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-teal-700 text-white rounded-lg hover:bg-teal-800 transition">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-guest-layout>
