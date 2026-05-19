import { Head, usePage, router } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { FormEvent, useState, useEffect } from 'react';
import axios from 'axios';
import { route } from 'ziggy-js';

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

interface AuthUser {
    name: string;
    email: string;
    phone?: string | null;
}

interface CheckoutProps {
    course: Course;
    paystackPublicKey: string;
}

export default function Checkout({ course, paystackPublicKey }: CheckoutProps) {
    const { auth } = usePage().props as { auth?: { user?: AuthUser } };
    const user = auth?.user;

    // Safe access to route params via Ziggy
    const initialEmail = route().params?.email as string || '';

    const [name, setName] = useState('');
    const [email, setEmail] = useState(initialEmail); 
    const [phone, setPhone] = useState('');
    const [isProcessing, setIsProcessing] = useState(false);
    const [useMyDetails, setUseMyDetails] = useState(false);

    // Initial prefill from authenticated user
    useEffect(() => {
        if (user) {
            setName(user.name || '');
            setEmail(user.email || '');
            setPhone(user.phone || '');
            setUseMyDetails(true); // Automatically check "Use my profile details" if logged in
        }
    }, [user]);

    const handleUseMyDetails = (checked: boolean) => {
        setUseMyDetails(checked);
        if (checked && user) {
            setName(user.name || '');
            setEmail(user.email || '');
            setPhone(user.phone || '');
        } else {
            // If unchecked, optionally clear or make editable without clearing
            // For now, let's clear to allow fresh input
            setName('');
            setEmail('');
            setPhone('');
        }
    };

    const handleSubmit = async (e: FormEvent) => {
        e.preventDefault();
        setIsProcessing(true);

        try {
            // Initialize payment on backend
            const response = await axios.post(route('payment.initialize', course.slug), {
                name, // Pass name
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

            <div className="public-section min-h-screen px-4 sm:px-6 lg:px-8">
                <div className="section-shell max-w-5xl">
                    {/* Header */}
                    <div className="text-center mb-8">
                        <p className="enterprise-label mb-3">Checkout</p>
                        <h1 className="text-3xl font-bold text-gray-900">Complete Your Purchase</h1>
                        <p className="mt-2 text-gray-600">You're one step away from accessing this course</p>
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Order Summary */}
                        <div className="lg:col-span-2">
                            <div className="public-card mb-6 p-6">
                                <h2 className="mb-4 text-xl font-bold text-gray-900">Order Summary</h2>

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
                                            <p className="text-sm text-gray-600">{course.subtitle}</p>
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
                            <div className="public-card p-6">
                                <div className="flex justify-between items-center mb-4">
                                    <h2 className="text-xl font-bold text-gray-900">Payment Details</h2>
                                    {user && (
                                        <label className="flex items-center space-x-2 text-sm text-gray-600 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                checked={useMyDetails}
                                                onChange={(e) => handleUseMyDetails(e.target.checked)}
                                                className="rounded text-primary focus:ring-primary border-gray-300"
                                            />
                                            <span>Use my profile details</span>
                                        </label>
                                    )}
                                </div>

                                <form onSubmit={handleSubmit} className="space-y-4">
                                    <div>
                                        <label htmlFor="name" className="mb-1 block text-sm font-medium text-gray-700">
                                            Full Name
                                        </label>
                                        <input
                                            type="text"
                                            id="name"
                                            required
                                            value={name}
                                            onChange={(e) => setName(e.target.value)}
                                            disabled={useMyDetails}
                                            className={`public-input ${useMyDetails ? 'cursor-not-allowed bg-gray-100' : ''}`}
                                            placeholder="John Doe"
                                        />
                                    </div>

                                    <div>
                                        <label htmlFor="email" className="mb-1 block text-sm font-medium text-gray-700">
                                            Email Address
                                        </label>
                                        <input
                                            type="email"
                                            id="email"
                                            required
                                            value={email}
                                            onChange={(e) => setEmail(e.target.value)}
                                            disabled={useMyDetails}
                                            className={`public-input ${useMyDetails ? 'cursor-not-allowed bg-gray-100' : ''}`}
                                            placeholder="your@email.com"
                                        />
                                    </div>

                                    <div>
                                        <label htmlFor="phone" className="mb-1 block text-sm font-medium text-gray-700">
                                            Phone Number (Optional)
                                        </label>
                                        <input
                                            type="tel"
                                            id="phone"
                                            value={phone}
                                            onChange={(e) => setPhone(e.target.value)}
                                            disabled={useMyDetails}
                                            className={`public-input ${useMyDetails ? 'cursor-not-allowed bg-gray-100' : ''}`}
                                            placeholder="+1234567890"
                                        />
                                    </div>

                                    <button
                                        type="submit"
                                        disabled={isProcessing}
                                        className="enterprise-button enterprise-button-primary w-full disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        {isProcessing ? 'Processing...' : `Pay ₦${Number(course.price).toLocaleString()}`}
                                    </button>
                                </form>

                                {/* Payment Methods */}
                                <div className="mt-6 pt-6 border-t border-gray-200">
                                    <p className="mb-3 text-center text-sm text-gray-600">
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
                            <div className="public-card sticky top-4 p-6">
                                <h3 className="mb-4 text-lg font-bold text-gray-900">After Purchase</h3>
                                <ul className="space-y-3">
                                    <li className="flex items-start gap-3">
                                        <i className="fas fa-check-circle text-green-500 mt-1"></i>
                                        <span className="text-sm text-gray-700">Instant access to all course content</span>
                                    </li>
                                    <li className="flex items-start gap-3">
                                        <i className="fas fa-check-circle text-green-500 mt-1"></i>
                                        <span className="text-sm text-gray-700">Lifetime access to the course</span>
                                    </li>
                                    <li className="flex items-start gap-3">
                                        <i className="fas fa-check-circle text-green-500 mt-1"></i>
                                        <span className="text-sm text-gray-700">Certificate of completion</span>
                                    </li>
                                    <li className="flex items-start gap-3">
                                        <i className="fas fa-check-circle text-green-500 mt-1"></i>
                                        <span className="text-sm text-gray-700">30-day money-back guarantee</span>
                                    </li>
                                </ul>

                                <div className="mt-6 pt-6 border-t border-gray-200">
                                    <button
                                        onClick={() => router.visit(route('courses.show', course.slug))}
                                        className="w-full text-sm font-semibold text-primary hover:text-primary-700"
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
