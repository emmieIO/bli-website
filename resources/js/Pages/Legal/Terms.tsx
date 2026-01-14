import { Head, Link } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Terms() {
    return (
        <GuestLayout>
            <Head title="Terms of Service" />

            <div className="max-w-5xl mx-auto py-12">
                <div className="bg-white rounded-2xl shadow-xl p-8 lg:p-12">
                    {/* Title Section */}
                    <div className="text-center mb-12 pb-8 border-b border-gray-200">
                        <div className="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4" style={{ backgroundColor: '#f0f9ff' }}>
                            <i className="fas fa-file-contract text-3xl" style={{ color: '#002147' }}></i>
                        </div>
                        <h1 className="text-4xl font-bold font-montserrat mb-3" style={{ color: '#002147' }}>Terms of Service</h1>
                        <p className="text-gray-600 text-lg">Last Updated: {new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                    </div>

                    {/* Content */}
                    <div className="prose prose-lg max-w-none">
                        {/* Acceptance of Terms */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>1. Acceptance of Terms</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                By accessing and using the Beacon Leadership Institute website and services ("Platform"), you accept and agree to be bound by these Terms of Service ("Terms"). If you do not agree to these Terms, please do not use our Platform.
                            </p>
                            <div className="bg-blue-50 border-l-4 p-4 rounded-r-lg mb-4" style={{ borderColor: '#002147' }}>
                                <p className="text-gray-700 leading-relaxed">
                                    <strong>Important:</strong> These Terms constitute a legally binding agreement between you and Beacon Leadership Institute. Please read them carefully before using our services.
                                </p>
                            </div>
                        </section>

                        {/* Use of Platform */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>2. Use of Platform</h2>

                            <h3 className="text-xl font-semibold font-montserrat mb-3 mt-6" style={{ color: '#002147' }}>2.1 Eligibility</h3>
                            <p className="text-gray-700 leading-relaxed mb-4">You must be at least 18 years old to use our Platform. By using our services, you represent and warrant that you meet this age requirement.</p>

                            <h3 className="text-xl font-semibold font-montserrat mb-3 mt-6" style={{ color: '#002147' }}>2.2 Account Registration</h3>
                            <p className="text-gray-700 leading-relaxed mb-4">To access certain features, you must create an account. You agree to:</p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                <li>Provide accurate, current, and complete information</li>
                                <li>Maintain and promptly update your account information</li>
                                <li>Keep your password secure and confidential</li>
                                <li>Accept responsibility for all activities under your account</li>
                                <li>Notify us immediately of any unauthorized access</li>
                            </ul>

                            <h3 className="text-xl font-semibold font-montserrat mb-3 mt-6" style={{ color: '#002147' }}>2.3 Prohibited Activities</h3>
                            <p className="text-gray-700 leading-relaxed mb-4">You agree NOT to:</p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                <li>Violate any laws or regulations</li>
                                <li>Infringe on intellectual property rights</li>
                                <li>Share copyrighted course materials without permission</li>
                                <li>Upload viruses or malicious code</li>
                                <li>Attempt to gain unauthorized access to our systems</li>
                                <li>Harass, abuse, or harm other users</li>
                                <li>Use the Platform for commercial purposes without authorization</li>
                                <li>Create multiple accounts or share account credentials</li>
                            </ul>
                        </section>

                        {/* Courses and Content */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>3. Courses and Educational Content</h2>

                            <h3 className="text-xl font-semibold font-montserrat mb-3 mt-6" style={{ color: '#002147' }}>3.1 Course Enrollment</h3>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                When you enroll in a course, you receive a limited, non-exclusive, non-transferable license to access and view the course content for personal, non-commercial use only.
                            </p>

                            <h3 className="text-xl font-semibold font-montserrat mb-3 mt-6" style={{ color: '#002147' }}>3.2 Course Access</h3>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                Course access is granted upon successful payment and remains active for the duration specified in the course description. We reserve the right to modify course content, instructors, or availability at any time.
                            </p>

                            <h3 className="text-xl font-semibold font-montserrat mb-3 mt-6" style={{ color: '#002147' }}>3.3 Certificates</h3>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                Certificates of completion are awarded upon successful completion of course requirements. Certificates are for recognition purposes only and do not constitute professional certification or accreditation unless explicitly stated.
                            </p>
                        </section>

                        {/* Payment and Refunds */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>4. Payment and Refunds</h2>

                            <h3 className="text-xl font-semibold font-montserrat mb-3 mt-6" style={{ color: '#002147' }}>4.1 Pricing</h3>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                All prices are displayed in Nigerian Naira (NGN) unless otherwise stated. We reserve the right to change prices at any time. Price changes will not affect existing enrollments.
                            </p>

                            <h3 className="text-xl font-semibold font-montserrat mb-3 mt-6" style={{ color: '#002147' }}>4.2 Payment Methods</h3>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                We accept various payment methods as displayed at checkout. All payments are processed securely through third-party payment processors.
                            </p>

                            <h3 className="text-xl font-semibold font-montserrat mb-3 mt-6" style={{ color: '#002147' }}>4.3 Refund Policy</h3>
                            <div className="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg mb-4">
                                <p className="text-gray-700 leading-relaxed mb-3">
                                    <strong>Course Refunds:</strong> You may request a full refund within 14 days of purchase if you have completed less than 25% of the course content.
                                </p>
                                <p className="text-gray-700 leading-relaxed">
                                    <strong>Event Refunds:</strong> Event registration refunds are available up to 7 days before the event date, minus a 10% processing fee.
                                </p>
                            </div>
                            <p className="text-gray-700 leading-relaxed mt-4">
                                To request a refund, contact us at <a href="mailto:support@beaconleadership.org" className="font-semibold underline hover:no-underline" style={{ color: '#002147' }}>support@beaconleadership.org</a>. Refunds are processed within 7-10 business days.
                            </p>
                        </section>

                        {/* Intellectual Property */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>5. Intellectual Property Rights</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                All content on the Platform, including but not limited to text, graphics, logos, videos, audio, software, and course materials, is the property of Beacon Leadership Institute or its content suppliers and is protected by intellectual property laws.
                            </p>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                You may not reproduce, distribute, modify, create derivative works, publicly display, or otherwise use our content without express written permission, except as explicitly allowed for personal educational use.
                            </p>
                        </section>

                        {/* User Generated Content */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>6. User-Generated Content</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                By submitting content (comments, reviews, forum posts, etc.), you grant us a non-exclusive, worldwide, royalty-free license to use, reproduce, modify, and display such content in connection with our services.
                            </p>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                You represent and warrant that you own or have the necessary rights to submit such content and that it does not violate any third-party rights or applicable laws.
                            </p>
                        </section>

                        {/* Instructor Terms */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>7. Instructor and Speaker Terms</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                If you apply to become an instructor or speaker, additional terms apply:
                            </p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                <li>You must submit accurate professional credentials</li>
                                <li>All course materials must be original or properly licensed</li>
                                <li>You grant us rights to promote and distribute your courses</li>
                                <li>Revenue sharing terms will be specified in a separate agreement</li>
                                <li>We reserve the right to remove content that violates our quality standards</li>
                            </ul>
                        </section>

                        {/* Disclaimers */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>8. Disclaimers</h2>
                            <div className="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg mb-4">
                                <p className="text-gray-700 leading-relaxed mb-3">
                                    <strong>No Warranties:</strong> The Platform and all content are provided "AS IS" and "AS AVAILABLE" without warranties of any kind, either express or implied.
                                </p>
                                <p className="text-gray-700 leading-relaxed mb-3">
                                    <strong>Educational Content:</strong> While we strive for accuracy, we do not guarantee that course content is complete, accurate, or current. Educational outcomes depend on individual effort and circumstances.
                                </p>
                                <p className="text-gray-700 leading-relaxed">
                                    <strong>No Professional Advice:</strong> Content on our Platform is for educational purposes only and does not constitute professional advice. Consult appropriate professionals for specific guidance.
                                </p>
                            </div>
                        </section>

                        {/* Limitation of Liability */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>9. Limitation of Liability</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                To the maximum extent permitted by law, Beacon Leadership Institute shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or any loss of profits or revenues, whether incurred directly or indirectly, or any loss of data, use, goodwill, or other intangible losses resulting from:
                            </p>
                            <ul className="list-disc pl-6 space-y-2 text-gray-700">
                                <li>Your access to or use of or inability to access or use the Platform</li>
                                <li>Any conduct or content of any third party on the Platform</li>
                                <li>Unauthorized access, use, or alteration of your content</li>
                                <li>Any interruption or cessation of transmission to or from the Platform</li>
                            </ul>
                        </section>

                        {/* Indemnification */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>10. Indemnification</h2>
                            <p className="text-gray-700 leading-relaxed">
                                You agree to indemnify, defend, and hold harmless Beacon Leadership Institute, its officers, directors, employees, and agents from any claims, liabilities, damages, losses, and expenses, including reasonable legal fees, arising out of or in any way connected with your access to or use of the Platform, your violation of these Terms, or your violation of any rights of another.
                            </p>
                        </section>

                        {/* Termination */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>11. Termination</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                We reserve the right to suspend or terminate your account and access to the Platform at our sole discretion, without notice, for conduct that we believe violates these Terms or is harmful to other users, us, or third parties, or for any other reason.
                            </p>
                            <p className="text-gray-700 leading-relaxed">
                                Upon termination, your right to use the Platform will immediately cease. You may also terminate your account at any time by contacting us.
                            </p>
                        </section>

                        {/* Governing Law */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>12. Governing Law and Dispute Resolution</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                These Terms shall be governed by and construed in accordance with the laws of the Federal Republic of Nigeria, without regard to its conflict of law provisions.
                            </p>
                            <p className="text-gray-700 leading-relaxed">
                                Any disputes arising from these Terms or your use of the Platform shall be resolved through good faith negotiations. If negotiations fail, disputes shall be submitted to the competent courts in Nigeria.
                            </p>
                        </section>

                        {/* Changes to Terms */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>13. Changes to Terms</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                We reserve the right to modify these Terms at any time. We will notify you of material changes by posting the new Terms on this page and updating the "Last Updated" date.
                            </p>
                            <p className="text-gray-700 leading-relaxed">
                                Your continued use of the Platform after such modifications constitutes your acceptance of the updated Terms. We encourage you to review these Terms periodically.
                            </p>
                        </section>

                        {/* Contact Information */}
                        <section className="mb-10">
                            <h2 className="text-2xl font-bold font-montserrat mb-4" style={{ color: '#002147' }}>14. Contact Information</h2>
                            <p className="text-gray-700 leading-relaxed mb-4">
                                If you have any questions about these Terms of Service, please contact us:
                            </p>
                            <div className="bg-gray-50 p-6 rounded-xl border border-gray-200">
                                <p className="text-gray-700 mb-2"><strong>Beacon Leadership Institute</strong></p>
                                <p className="text-gray-700 mb-2"><i className="fas fa-envelope mr-2" style={{ color: '#00a651' }}></i> Email: <a href="mailto:legal@beaconleadership.org" className="underline hover:no-underline" style={{ color: '#002147' }}>legal@beaconleadership.org</a></p>
                                <p className="text-gray-700 mb-2"><i className="fas fa-phone mr-2" style={{ color: '#00a651' }}></i> Phone: +234 (XXX) XXX-XXXX</p>
                                <p className="text-gray-700"><i className="fas fa-globe mr-2" style={{ color: '#00a651' }}></i> Website: <Link href={route('homepage')} className="underline hover:no-underline" style={{ color: '#002147' }}>www.beaconleadership.org</Link></p>
                            </div>
                        </section>

                        {/* Acknowledgment */}
                        <section className="mb-10">
                            <div className="bg-blue-50 border-2 p-6 rounded-xl" style={{ borderColor: '#002147' }}>
                                <h3 className="text-lg font-bold font-montserrat mb-3" style={{ color: '#002147' }}>
                                    <i className="fas fa-check-circle mr-2" style={{ color: '#00a651' }}></i>
                                    Acknowledgment
                                </h3>
                                <p className="text-gray-700 leading-relaxed">
                                    By using the Beacon Leadership Institute Platform, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.
                                </p>
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
