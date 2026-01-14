import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Privacy() {
    return (
        <GuestLayout>
            <Head title="Privacy Policy" />

            <div className="max-w-5xl mx-auto py-12">
                <div className="bg-white rounded-2xl shadow-xl p-8 lg:p-12">
                    {/* Title Section */}
                    <div className="text-center mb-12 pb-8 border-b border-gray-200">
                        <div className="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4" style={{ backgroundColor: '#f0f9ff' }}>
                            <i className="fas fa-shield-alt text-3xl" style={{ color: '#002147' }}></i>
                        </div>
                        <h1 className="text-4xl font-bold font-montserrat mb-3" style={{ color: '#002147' }}>Privacy Policy</h1>
                        <p className="text-gray-600 text-lg">Last Updated: {new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                    </div>

                    {/* Content */}
                    <div className="prose prose-lg max-w-none">
                        {/* Introduction */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>1. Introduction</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                Welcome to Beacon Leadership Institute ("we," "our," or "us"). We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website and use our services.
                            </p>
                            <p className="text-gray-700 leading-relaxed">
                                Please read this privacy policy carefully. If you do not agree with the terms of this privacy policy, please do not access the site or use our services.
                            </p>
                        </section>

                        {/* Information We Collect */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>2. Information We Collect</h2>

                            <h3 className="text-xl font-semibold font-montserrat mb-3 mt-6" style={{ color: '#002147' }}>2.1 Personal Information</h3>
                            <p className="text-gray-700 leading-relaxed mb-4">We collect personal information that you voluntarily provide to us when you:</p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700 mb-4">
                                <li>Register for an account</li>
                                <li>Enroll in courses or events</li>
                                <li>Apply to become an instructor or speaker</li>
                                <li>Subscribe to our newsletter</li>
                                <li>Contact us with inquiries</li>
                            </ul>
                            <p className="text-gray-700 leading-relaxed mb-4">This information may include:</p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                <li>Full name</li>
                                <li>Email address</li>
                                <li>Phone number</li>
                                <li>Professional information (for instructors/speakers)</li>
                                <li>Payment information (processed securely by third-party providers)</li>
                            </ul>

                            <h3 className="text-xl font-semibold font-montserrat mb-3 mt-6" style={{ color: '#002147' }}>2.2 Automatically Collected Information</h3>
                            <p className="text-gray-700 leading-relaxed mb-4">When you visit our website, we automatically collect certain information about your device, including:</p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                <li>IP address</li>
                                <li>Browser type and version</li>
                                <li>Operating system</li>
                                <li>Pages visited and time spent on pages</li>
                                <li>Referral source</li>
                            </ul>
                        </section>

                        {/* How We Use Your Information */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>3. How We Use Your Information</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">We use the information we collect for the following purposes:</p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                <li><strong>Account Management:</strong> To create and manage your user account</li>
                                <li><strong>Course Delivery:</strong> To provide access to courses, events, and educational content</li>
                                <li><strong>Communication:</strong> To send you important updates, notifications, and marketing materials (with your consent)</li>
                                <li><strong>Payment Processing:</strong> To process course enrollments and event registrations</li>
                                <li><strong>Certificates:</strong> To generate and verify course completion certificates</li>
                                <li><strong>Analytics:</strong> To understand how users interact with our platform and improve our services</li>
                                <li><strong>Security:</strong> To protect against fraud, unauthorized access, and maintain platform security</li>
                                <li><strong>Legal Compliance:</strong> To comply with applicable laws and regulations</li>
                            </ul>
                        </section>

                        {/* Information Sharing */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>4. How We Share Your Information</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">We may share your information in the following situations:</p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                <li><strong>Service Providers:</strong> With third-party vendors who perform services on our behalf (e.g., payment processing, email delivery, analytics)</li>
                                <li><strong>Instructors:</strong> Course enrollment information may be shared with instructors for educational purposes</li>
                                <li><strong>Legal Requirements:</strong> When required by law or to protect our rights and safety</li>
                                <li><strong>Business Transfers:</strong> In connection with a merger, sale, or acquisition of all or part of our organization</li>
                            </ul>
                            <p className="text-gray-700 leading-relaxed mt-4">
                                <strong>We do not sell your personal information to third parties.</strong>
                            </p>
                        </section>

                        {/* Data Security */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>5. Data Security</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These measures include:
                            </p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                <li>SSL/TLS encryption for data transmission</li>
                                <li>Secure password hashing</li>
                                <li>Regular security audits</li>
                                <li>Access controls and authentication</li>
                                <li>Regular backups</li>
                            </ul>
                            <p className="text-gray-700 leading-relaxed mt-4">
                                However, no method of transmission over the internet or electronic storage is 100% secure. While we strive to protect your information, we cannot guarantee absolute security.
                            </p>
                        </section>

                        {/* Your Rights */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>6. Your Privacy Rights</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">Depending on your location, you may have the following rights:</p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                <li><strong>Access:</strong> Request access to your personal information</li>
                                <li><strong>Correction:</strong> Request correction of inaccurate or incomplete information</li>
                                <li><strong>Deletion:</strong> Request deletion of your personal information</li>
                                <li><strong>Data Portability:</strong> Request a copy of your information in a structured format</li>
                                <li><strong>Opt-Out:</strong> Unsubscribe from marketing communications</li>
                                <li><strong>Objection:</strong> Object to processing of your information for certain purposes</li>
                            </ul>
                            <p className="text-gray-700 leading-relaxed mt-4">
                                To exercise these rights, please contact us at <a href="mailto:privacy@beaconleadership.org" className="font-semibold underline hover:no-underline" style={{ color: '#002147' }}>privacy@beaconleadership.org</a>
                            </p>
                        </section>

                        {/* Cookies */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>7. Cookies and Tracking Technologies</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                We use cookies and similar tracking technologies to enhance your experience on our platform. Cookies are small text files stored on your device that help us:
                            </p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                <li>Remember your login information</li>
                                <li>Understand user behavior and preferences</li>
                                <li>Improve website functionality</li>
                                <li>Provide personalized content</li>
                            </ul>
                            <p className="text-gray-700 leading-relaxed mt-4">
                                You can control cookie settings through your browser. However, disabling cookies may limit some functionality of our platform.
                            </p>
                        </section>

                        {/* Contact Us */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>8. Contact Us</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                If you have any questions or concerns about this Privacy Policy or our data practices, please contact us:
                            </p>
                            <div className="bg-gray-50 p-6 rounded-xl border border-gray-200">
                                <p className="text-gray-700 mb-2"><strong>Beacon Leadership Institute</strong></p>
                                <p className="text-gray-700 mb-2"><i className="fas fa-envelope mr-2" style={{ color: '#00a651' }}></i> Email: <a href="mailto:privacy@beaconleadership.org" className="underline hover:no-underline" style={{ color: '#002147' }}>privacy@beaconleadership.org</a></p>
                                <p className="text-gray-700 mb-2"><i className="fas fa-phone mr-2" style={{ color: '#00a651' }}></i> Phone: +234 (XXX) XXX-XXXX</p>
                                <p className="text-gray-700"><i className="fas fa-globe mr-2" style={{ color: '#00a651' }}></i> Website: <Link href={route('homepage')} className="underline hover:no-underline" style={{ color: '#002147' }}>www.beaconleadership.org</Link></p>
                            </div>
                        </section>
                    </div>

                    {/* Back to Home Button */}
                    <div className="mt-12 pt-8 border-t border-gray-200 text-center">
                        <Link href={route('homepage')} className="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-semibold text-white transition-all duration-300 hover:shadow-lg" style={{ backgroundColor: '#002147' }}>
                            <i className="fas fa-home"></i>
                            <span>Return to Homepage</span>
                        </Link>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}
