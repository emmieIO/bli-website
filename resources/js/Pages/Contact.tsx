import { Head, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { FormEvent } from 'react';

export default function Contact() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        message: '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post(route('contact.submit'));
    };

    return (
        <GuestLayout>
            <Head title="Contact Us" />

            <section className="public-section min-h-screen">
                <div className="section-shell">
                    {/* Section Header */}
                    <div className="mb-12 text-center">
                        <p className="enterprise-label mb-4">Contact</p>
                        <h2 className="public-hero-title text-primary">Contact Us</h2>
                        <p className="public-hero-copy mx-auto mt-3">
                            Have questions or want to get in touch with us? Fill out the form and our team will reach out to you
                            shortly.
                        </p>
                    </div>

                    {/* Main Content Grid */}
                    <div className="grid md:grid-cols-2 gap-10 items-start">
                        {/* Contact Info */}
                        <div className="public-card space-y-6 p-6 lg:p-8">
                            <div className="flex items-start gap-4">
                                <i className="fas fa-map-marker-alt mt-1 text-lg text-accent"></i>
                                <div>
                                    <h4 className="font-semibold text-primary">Address</h4>
                                    <p className="text-gray-600">123 Beacon Avenue, Lagos, Nigeria</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <i className="fas fa-envelope mt-1 text-lg text-accent"></i>
                                <div>
                                    <h4 className="font-semibold text-primary">Email</h4>
                                    <p className="text-gray-600">info@beaconleadership.org</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <i className="fas fa-phone mt-1 text-lg text-accent"></i>
                                <div>
                                    <h4 className="font-semibold text-primary">Phone</h4>
                                    <p className="text-gray-600">+234-706-442-5639</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <i className="fas fa-clock mt-1 text-lg text-accent"></i>
                                <div>
                                    <h4 className="font-semibold text-primary">Office Hours</h4>
                                    <p className="text-gray-600">Monday - Friday: 9:00 AM - 5:00 PM</p>
                                </div>
                            </div>
                        </div>

                        {/* Contact Form */}
                        <form
                            onSubmit={handleSubmit}
                            className="public-card space-y-5 p-6 lg:p-8"
                        >
                            <div>
                                <label htmlFor="name" className="mb-1 block font-medium text-primary">
                                    Full Name
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    required
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    className="public-input"
                                />
                                {errors.name && <p className="mt-1 text-sm text-secondary">{errors.name}</p>}
                            </div>

                            <div>
                                <label htmlFor="email" className="mb-1 block font-medium text-primary">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    required
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    className="public-input"
                                />
                                {errors.email && <p className="mt-1 text-sm text-secondary">{errors.email}</p>}
                            </div>

                            <div>
                                <label htmlFor="message" className="mb-1 block font-medium text-primary">
                                    Message
                                </label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows={5}
                                    required
                                    value={data.message}
                                    onChange={(e) => setData('message', e.target.value)}
                                    className="public-input"
                                ></textarea>
                                {errors.message && <p className="mt-1 text-sm text-secondary">{errors.message}</p>}
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="enterprise-button enterprise-button-primary disabled:opacity-50"
                            >
                                <i className="fas fa-paper-plane"></i>
                                {processing ? 'Sending...' : 'Send Message'}
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
