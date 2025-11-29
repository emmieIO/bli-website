import { Head, router } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { FormEvent, useState } from 'react';
import axios from 'axios';

interface Course {
    id: number;
    slug: string;
    title: string;
    subtitle: string | null;
    description: string;
    price: number;
    thumbnail_path: string | null;
    category: {
        name: string;
    };
    instructor: {
        name: string;
        photo: string | null;
    };
}

interface CheckoutProps {
    course: Course;
    paystackPublicKey: string;
}

export default function Checkout({ course, paystackPublicKey }: CheckoutProps) {
    const [email, setEmail] = useState(route().params.email || '');
    const [phone, setPhone] = useState('');
    const [isProcessing, setIsProcessing] = useState(false);

    const handleSubmit = async (e: FormEvent) => {
        e.preventDefault();
        setIsProcessing(true);

        try {
            // Initialize payment on backend
            const response = await axios.post(route('payment.initialize', course.slug), {
                email,
                phone,
            });

            if (response.data.success && response.data.payment_data.data.authorization_url) {
                // Redirect to Paystack payment page
                window.location.href = response.data.payment_data.data.authorization_url;
            } else {
                alert(response.data.message || 'Failed to initialize payment');
                setIsProcessing(false);
            }
        } catch (error: any) {
            // Payment initialization failed
            setIsProcessing(false);
            alert(error.response?.data?.message || 'Failed to initialize payment. Please try again.');
        }
    };

    return (
        <GuestLayout>
            <Head title={`Checkout - ${course.title}`} />

            <div className="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
                <div className="max-w-4xl mx-auto">
                    {/* Header */}
                    <div className="text-center mb-8">
                        <h1 className="text-3xl font-bold text-gray-900 font-montserrat">Complete Your Purchase</h1>
                        <p className="mt-2 text-gray-600 font-lato">You're one step away from accessing this amazing course</p>
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Order Summary */}
                        <div className="lg:col-span-2">
                            <div className="bg-white rounded-lg shadow-md p-6 mb-6">
                                <h2 className="text-xl font-bold text-gray-900 mb-4 font-montserrat">Order Summary</h2>

                                <div className="flex gap-4">
                                    {course.thumbnail_path && (
                                        <img
                                            src={`/storage/${course.thumbnail_path}`}
                                            alt={course.title}
                                            className="w-32 h-20 object-cover rounded-lg"
                                        />
                                    )}
                                    <div className="flex-1">
                                        <h3 className="font-semibold text-gray-900 font-montserrat">{course.title}</h3>
                                        {course.subtitle && (
                                            <p className="text-sm text-gray-600 font-lato">{course.subtitle}</p>
                                        )}
                                        <p className="text-sm text-gray-500 mt-1 font-lato">
                                            by {course.instructor.name}
                                        </p>
                                        <p className="text-sm text-primary mt-1 font-lato">
                                            {course.category.name}
                                        </p>
                                    </div>
                                    <div className="text-right">
                                        <p className="text-2xl font-bold text-primary font-montserrat">
                                            ₦{Number(course.price).toLocaleString()}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {/* Payment Form */}
                            <div className="bg-white rounded-lg shadow-md p-6">
                                <h2 className="text-xl font-bold text-gray-900 mb-4 font-montserrat">Payment Details</h2>

                                <form onSubmit={handleSubmit} className="space-y-4">
                                    <div>
                                        <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-1 font-montserrat">
                                            Email Address
                                        </label>
                                        <input
                                            type="email"
                                            id="email"
                                            required
                                            value={email}
                                            onChange={(e) => setEmail(e.target.value)}
                                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent font-lato"
                                            placeholder="your@email.com"
                                        />
                                    </div>

                                    <div>
                                        <label htmlFor="phone" className="block text-sm font-medium text-gray-700 mb-1 font-montserrat">
                                            Phone Number (Optional)
                                        </label>
                                        <input
                                            type="tel"
                                            id="phone"
                                            value={phone}
                                            onChange={(e) => setPhone(e.target.value)}
                                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent font-lato"
                                            placeholder="+1234567890"
                                        />
                                    </div>

                                    <button
                                        type="submit"
                                        disabled={isProcessing}
                                        className="w-full bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-primary-dark transition-colors disabled:opacity-50 disabled:cursor-not-allowed font-montserrat"
                                    >
                                        {isProcessing ? 'Processing...' : `Pay ₦${Number(course.price).toLocaleString()}`}
                                    </button>
                                </form>

                                {/* Payment Methods */}
                                <div className="mt-6 pt-6 border-t border-gray-200">
                                    <p className="text-sm text-gray-600 text-center mb-3 font-lato">
                                        Secure payment powered by Paystack
                                    </p>
                                    <div className="flex justify-center gap-4">
                                        <i className="fab fa-cc-visa text-3xl text-gray-400"></i>
                                        <i className="fab fa-cc-mastercard text-3xl text-gray-400"></i>
                                        <i className="fas fa-university text-3xl text-gray-400"></i>
                                        <i className="fas fa-mobile-alt text-3xl text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Sidebar */}
                        <div className="lg:col-span-1">
                            <div className="bg-white rounded-lg shadow-md p-6 sticky top-4">
                                <h3 className="text-lg font-bold text-gray-900 mb-4 font-montserrat">After Purchase</h3>
                                <ul className="space-y-3">
                                    <li className="flex items-start gap-3">
                                        <i className="fas fa-check-circle text-green-500 mt-1"></i>
                                        <span className="text-sm text-gray-700 font-lato">Instant access to all course content</span>
                                    </li>
                                    <li className="flex items-start gap-3">
                                        <i className="fas fa-check-circle text-green-500 mt-1"></i>
                                        <span className="text-sm text-gray-700 font-lato">Lifetime access to the course</span>
                                    </li>
                                    <li className="flex items-start gap-3">
                                        <i className="fas fa-check-circle text-green-500 mt-1"></i>
                                        <span className="text-sm text-gray-700 font-lato">Certificate of completion</span>
                                    </li>
                                    <li className="flex items-start gap-3">
                                        <i className="fas fa-check-circle text-green-500 mt-1"></i>
                                        <span className="text-sm text-gray-700 font-lato">30-day money-back guarantee</span>
                                    </li>
                                </ul>

                                <div className="mt-6 pt-6 border-t border-gray-200">
                                    <button
                                        onClick={() => router.visit(route('courses.show', course.slug))}
                                        className="w-full text-primary hover:text-primary-dark font-semibold text-sm font-montserrat"
                                    >
                                        ← Back to Course
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}
