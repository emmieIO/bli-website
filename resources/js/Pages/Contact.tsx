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

            <section className="py-16 bg-gray-50 min-h-screen">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Section Header */}
                    <div className="text-center mb-12">
                        <h2 className="text-4xl font-bold text-primary font-montserrat">Contact Us</h2>
                        <p className="text-gray-600 mt-2 max-w-xl mx-auto font-lato">
                            Have questions or want to get in touch with us? Fill out the form and our team will reach out to you
                            shortly.
                        </p>
                    </div>

                    {/* Main Content Grid */}
                    <div className="grid md:grid-cols-2 gap-10 items-start">
                        {/* Contact Info */}
                        <div className="space-y-6">
                            <div className="flex items-start gap-4">
                                <i className="fas fa-map-marker-alt text-xl text-secondary"></i>
                                <div>
                                    <h4 className="font-semibold text-primary font-montserrat">Address</h4>
                                    <p className="text-gray-600 font-lato">123 Beacon Avenue, Lagos, Nigeria</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <i className="fas fa-envelope text-xl text-secondary"></i>
                                <div>
                                    <h4 className="font-semibold text-primary font-montserrat">Email</h4>
                                    <p className="text-gray-600 font-lato">info@beaconleadership.org</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <i className="fas fa-phone text-xl text-secondary"></i>
                                <div>
                                    <h4 className="font-semibold text-primary font-montserrat">Phone</h4>
                                    <p className="text-gray-600 font-lato">+234-706-442-5639</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <i className="fas fa-clock text-xl text-secondary"></i>
                                <div>
                                    <h4 className="font-semibold text-primary font-montserrat">Office Hours</h4>
                                    <p className="text-gray-600 font-lato">Monday - Friday: 9:00 AM - 5:00 PM</p>
                                </div>
                            </div>
                        </div>

                        {/* Contact Form */}
                        <form
                            onSubmit={handleSubmit}
                            className="bg-white p-6 rounded-lg shadow-md space-y-5 border border-secondary/20"
                        >
                            <div>
                                <label htmlFor="name" className="block font-medium text-primary mb-1 font-montserrat">
                                    Full Name
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    required
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    className="w-full border border-secondary/20 rounded-md px-4 py-2 focus:ring-secondary focus:border-secondary font-lato"
                                />
                                {errors.name && <p className="mt-1 text-sm text-secondary">{errors.name}</p>}
                            </div>

                            <div>
                                <label htmlFor="email" className="block font-medium text-primary mb-1 font-montserrat">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    required
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    className="w-full border border-secondary/20 rounded-md px-4 py-2 focus:ring-secondary focus:border-secondary font-lato"
                                />
                                {errors.email && <p className="mt-1 text-sm text-secondary">{errors.email}</p>}
                            </div>

                            <div>
                                <label htmlFor="message" className="block font-medium text-primary mb-1 font-montserrat">
                                    Message
                                </label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows={5}
                                    required
                                    value={data.message}
                                    onChange={(e) => setData('message', e.target.value)}
                                    className="w-full border border-secondary/20 rounded-md px-4 py-2 focus:ring-secondary focus:border-secondary font-lato"
                                ></textarea>
                                {errors.message && <p className="mt-1 text-sm text-secondary">{errors.message}</p>}
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="inline-flex items-center gap-2 px-6 py-3 bg-secondary text-white rounded-lg hover:bg-primary transition font-montserrat disabled:opacity-50"
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
