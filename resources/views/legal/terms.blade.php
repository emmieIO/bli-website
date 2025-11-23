<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Beacon Leadership Institute</title>
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-lato">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('homepage') }}" class="flex items-center space-x-3 group">
                    <img src="/images/logo.jpg" alt="BLI Logo" class="w-12 h-12 rounded-xl object-cover shadow-md group-hover:shadow-lg transition-all">
                    <div>
                        <span class="text-xl font-bold font-montserrat" style="color: #002147;">Beacon Leadership Institute</span>
                    </div>
                </a>
                <a href="{{ route('homepage') }}" class="text-sm font-semibold hover:underline flex items-center gap-2" style="color: #002147;">
                    <i class="fas fa-arrow-left" style="color: #00a651;"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12">
            <!-- Title Section -->
            <div class="text-center mb-12 pb-8 border-b border-gray-200">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4" style="background-color: #f0f9ff;">
                    <i class="fas fa-file-contract text-3xl" style="color: #002147;"></i>
                </div>
                <h1 class="text-4xl font-bold font-montserrat mb-3" style="color: #002147;">Terms of Service</h1>
                <p class="text-gray-600 text-lg">Last Updated: {{ date('F j, Y') }}</p>
            </div>

            <!-- Content -->
            <div class="prose prose-lg max-w-none">
                <!-- Introduction -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">1. Agreement to Terms</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Welcome to Beacon Leadership Institute ("Company," "we," "our," or "us"). These Terms of Service ("Terms") govern your access to and use of our website, courses, events, and services (collectively, the "Services").
                    </p>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        By accessing or using our Services, you agree to be bound by these Terms and our Privacy Policy. If you do not agree to these Terms, you may not access or use our Services.
                    </p>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                        <p class="text-yellow-800 font-semibold">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Please read these Terms carefully before using our Services.
                        </p>
                    </div>
                </section>

                <!-- Eligibility -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">2. Eligibility</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        You must be at least 18 years old to use our Services. By using our Services, you represent and warrant that:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>You are at least 18 years of age</li>
                        <li>You have the legal capacity to enter into these Terms</li>
                        <li>You will provide accurate and complete information when creating an account</li>
                        <li>You will not use the Services for any illegal or unauthorized purpose</li>
                    </ul>
                </section>

                <!-- Account Registration -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">3. Account Registration and Security</h2>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">3.1 Account Creation</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        To access certain features of our Services, you must create an account. You agree to:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>Provide accurate, current, and complete information</li>
                        <li>Maintain and promptly update your account information</li>
                        <li>Keep your password confidential and secure</li>
                        <li>Notify us immediately of any unauthorized access</li>
                        <li>Be responsible for all activities under your account</li>
                    </ul>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">3.2 Account Termination</h3>
                    <p class="text-gray-700 leading-relaxed">
                        We reserve the right to suspend or terminate your account at any time for violation of these Terms or for any other reason at our sole discretion.
                    </p>
                </section>

                <!-- Services -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">4. Use of Services</h2>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">4.1 Courses and Educational Content</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Our platform offers various courses, events, and educational content. When you enroll in a course or event:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700 mb-4">
                        <li>You receive a limited, non-exclusive, non-transferable license to access the content</li>
                        <li>You may not share, distribute, or resell course materials</li>
                        <li>Course access may be time-limited or subject to specific terms</li>
                        <li>Certificates are awarded upon successful completion of course requirements</li>
                    </ul>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">4.2 Events and Registrations</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        When registering for events:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>Registration is subject to availability</li>
                        <li>You may cancel your registration according to our cancellation policy</li>
                        <li>We reserve the right to cancel or reschedule events</li>
                        <li>Refunds, if applicable, will be processed according to our refund policy</li>
                    </ul>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">4.3 Instructor and Speaker Applications</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Applications to become an instructor or speaker are subject to our review and approval process. We reserve the right to accept or reject applications at our sole discretion.
                    </p>
                </section>

                <!-- Prohibited Conduct -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">5. Prohibited Conduct</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">You agree not to:</p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>Use the Services for any illegal purpose or in violation of any laws</li>
                        <li>Infringe upon the intellectual property rights of others</li>
                        <li>Upload or transmit viruses, malware, or harmful code</li>
                        <li>Harass, abuse, or harm other users</li>
                        <li>Impersonate any person or entity</li>
                        <li>Attempt to gain unauthorized access to our systems</li>
                        <li>Interfere with or disrupt the Services</li>
                        <li>Scrape, crawl, or use automated tools to access the Services</li>
                        <li>Share account credentials with others</li>
                        <li>Download or redistribute course content without permission</li>
                    </ul>
                </section>

                <!-- Intellectual Property -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">6. Intellectual Property Rights</h2>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">6.1 Our Content</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        All content on our platform, including but not limited to text, graphics, logos, images, videos, audio, software, and course materials, is the property of Beacon Leadership Institute or our content providers and is protected by copyright, trademark, and other intellectual property laws.
                    </p>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">6.2 User Content</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        By submitting content to our platform (comments, reviews, applications, etc.), you grant us a non-exclusive, worldwide, royalty-free license to use, reproduce, modify, and display such content in connection with our Services.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        You represent and warrant that you own or have the necessary rights to submit such content and that it does not violate any third-party rights.
                    </p>
                </section>

                <!-- Payment and Refunds -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">7. Payment and Refunds</h2>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">7.1 Pricing</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Course and event prices are displayed on our platform and are subject to change. We reserve the right to modify prices at any time, but price changes will not affect orders already placed.
                    </p>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">7.2 Payment Processing</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Payments are processed through secure third-party payment processors. By making a payment, you agree to the terms and conditions of the payment processor.
                    </p>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">7.3 Refund Policy</h3>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <p class="text-blue-900 mb-2">
                            <strong>Course Refunds:</strong> Refund requests must be submitted within 14 days of enrollment, provided less than 30% of the course content has been accessed.
                        </p>
                        <p class="text-blue-900">
                            <strong>Event Refunds:</strong> Event cancellations must be made at least 7 days before the event date for a full refund. Cancellations within 7 days may be subject to a processing fee.
                        </p>
                    </div>
                </section>

                <!-- Disclaimers -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">8. Disclaimers and Limitation of Liability</h2>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">8.1 Service "As Is"</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Our Services are provided "as is" and "as available" without warranties of any kind, either express or implied. We do not warrant that:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>The Services will be uninterrupted, secure, or error-free</li>
                        <li>The results obtained from using the Services will be accurate or reliable</li>
                        <li>Any errors in the Services will be corrected</li>
                    </ul>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">8.2 Limitation of Liability</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        To the maximum extent permitted by law, Beacon Leadership Institute shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>Loss of profits, data, or goodwill</li>
                        <li>Service interruption</li>
                        <li>Computer damage or system failure</li>
                        <li>Cost of substitute services</li>
                    </ul>
                </section>

                <!-- Indemnification -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">9. Indemnification</h2>
                    <p class="text-gray-700 leading-relaxed">
                        You agree to indemnify, defend, and hold harmless Beacon Leadership Institute, its officers, directors, employees, and agents from any claims, liabilities, damages, losses, costs, or expenses (including reasonable attorney fees) arising from:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700 mt-4">
                        <li>Your use of the Services</li>
                        <li>Your violation of these Terms</li>
                        <li>Your violation of any rights of another party</li>
                        <li>Your content or submissions</li>
                    </ul>
                </section>

                <!-- Dispute Resolution -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">10. Dispute Resolution and Governing Law</h2>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">10.1 Governing Law</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        These Terms shall be governed by and construed in accordance with the laws of [Your Jurisdiction], without regard to its conflict of law provisions.
                    </p>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">10.2 Dispute Resolution</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Any disputes arising from these Terms or your use of the Services shall first be attempted to be resolved through good faith negotiations. If negotiations fail, disputes may be resolved through binding arbitration or in the courts of [Your Jurisdiction].
                    </p>
                </section>

                <!-- Changes to Terms -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">11. Changes to Terms</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We reserve the right to modify these Terms at any time. We will notify users of material changes by:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>Posting the updated Terms on our website</li>
                        <li>Updating the "Last Updated" date</li>
                        <li>Sending email notifications for significant changes</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mt-4">
                        Your continued use of the Services after changes become effective constitutes acceptance of the revised Terms.
                    </p>
                </section>

                <!-- Termination -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">12. Termination</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We may terminate or suspend your account and access to the Services immediately, without prior notice or liability, for any reason, including but not limited to:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-gray-700">
                        <li>Breach of these Terms</li>
                        <li>Violation of applicable laws</li>
                        <li>Fraudulent or illegal activity</li>
                        <li>Extended inactivity</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mt-4">
                        Upon termination, your right to use the Services will immediately cease. All provisions of these Terms that by their nature should survive termination shall survive.
                    </p>
                </section>

                <!-- Miscellaneous -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">13. Miscellaneous</h2>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">13.1 Entire Agreement</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        These Terms, together with our Privacy Policy, constitute the entire agreement between you and Beacon Leadership Institute regarding the Services.
                    </p>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">13.2 Severability</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        If any provision of these Terms is found to be unenforceable, the remaining provisions will continue in full force and effect.
                    </p>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">13.3 Waiver</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights.
                    </p>

                    <h3 class="text-xl font-semibold font-montserrat mb-3 mt-6" style="color: #002147;">13.4 Assignment</h3>
                    <p class="text-gray-700 leading-relaxed">
                        You may not assign or transfer these Terms without our prior written consent. We may assign these Terms without restriction.
                    </p>
                </section>

                <!-- Contact Us -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold font-montserrat mb-4" style="color: #002147;">14. Contact Information</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        If you have any questions about these Terms, please contact us:
                    </p>
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <p class="text-gray-700 mb-2"><strong>Beacon Leadership Institute</strong></p>
                        <p class="text-gray-700 mb-2"><i class="fas fa-envelope mr-2" style="color: #00a651;"></i> Email: <a href="mailto:legal@beaconleadership.org" class="underline" style="color: #002147;">legal@beaconleadership.org</a></p>
                        <p class="text-gray-700 mb-2"><i class="fas fa-phone mr-2" style="color: #00a651;"></i> Phone: +234 (XXX) XXX-XXXX</p>
                        <p class="text-gray-700"><i class="fas fa-globe mr-2" style="color: #00a651;"></i> Website: <a href="{{ route('homepage') }}" class="underline" style="color: #002147;">{{ config('app.url') }}</a></p>
                    </div>
                </section>

                <!-- Acceptance -->
                <section class="mb-10">
                    <div class="bg-green-50 border-l-4 border-green-400 p-6 rounded-r-xl">
                        <h3 class="text-xl font-semibold font-montserrat mb-3 text-green-900">
                            <i class="fas fa-check-circle mr-2"></i>
                            Acceptance of Terms
                        </h3>
                        <p class="text-green-900 leading-relaxed">
                            By using our Services, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service and our Privacy Policy. If you do not agree to these terms, please do not use our Services.
                        </p>
                    </div>
                </section>
            </div>

            <!-- Back to Home Button -->
            <div class="mt-12 pt-8 border-t border-gray-200 text-center">
                <a href="{{ route('homepage') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg" style="background-color: #002147;">
                    <i class="fas fa-home"></i>
                    <span>Return to Homepage</span>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-600 text-sm">
            <p>&copy; {{ date('Y') }} Beacon Leadership Institute. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
