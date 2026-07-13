import { Head, usePage, router } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { FormEvent, useState, useEffect } from 'react';
import axios from 'axios';
import { route } from 'ziggy-js';

interface Event {
    id: number;
    slug: string;
    title: string;
    description: string;
    entry_fee: number;
    program_cover: string | null;
    start_date: string;
    location: string;
    mode: string;
}

interface AuthUser {
    name: string;
    email: string;
    phone?: string | null;
}

interface CheckoutProps {
    event: Event;
    paystackPublicKey: string;
}

export default function Checkout({ event }: CheckoutProps) {
    const { auth } = usePage().props as { auth?: { user?: AuthUser } };
    const user = auth?.user;

    // Safe access to route params via Ziggy
    const initialEmail = route().params?.email as string || '';

    const [name, setName] = useState('');
    const [email, setEmail] = useState(initialEmail); 
    const [phone, setPhone] = useState('');
    const [isProcessing, setIsProcessing] = useState(false);
    const [useMyDetails, setUseMyDetails] = useState(false);
    const modeLabel = event.mode ? event.mode.charAt(0).toUpperCase() + event.mode.slice(1) : 'Hybrid';

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
            const response = await axios.post(route('payment.event.initialize', event.slug), {
                name,
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
            <Head title={`Checkout - ${event.title}`} />

            <section className="border-b border-gray-200 bg-white py-6">
                <div className="section-shell">
                    <nav>
                        <ul className="flex items-center space-x-3 text-sm">
                            <li><button onClick={() => router.visit(route('homepage'))} className="font-medium text-gray-500 transition-colors hover:text-primary">Home</button></li>
                            <li className="text-xs text-gray-400"><i className="fas fa-chevron-right"></i></li>
                            <li><button onClick={() => router.visit(route('events.index'))} className="font-medium text-gray-500 transition-colors hover:text-primary">Events</button></li>
                            <li className="text-xs text-gray-400"><i className="fas fa-chevron-right"></i></li>
                            <li><button onClick={() => router.visit(route('events.show', event.slug))} className="font-medium text-gray-500 transition-colors hover:text-primary">Event</button></li>
                            <li className="text-xs text-gray-400"><i className="fas fa-chevron-right"></i></li>
                            <li className="truncate font-medium text-primary">Checkout</li>
                        </ul>
                    </nav>
                </div>
            </section>

            <section className="public-section bg-gray-50">
                <div className="section-shell">
                    <div className="mb-6 rounded-lg border border-gray-200 bg-white p-5 shadow-sm lg:p-7">
                        <div className="grid gap-5 lg:grid-cols-[minmax(0,1.2fr)_minmax(260px,0.8fr)]">
                            <div className="space-y-4">
                                <div className="flex flex-wrap items-center gap-3">
                                    <span className="inline-flex items-center rounded-full border border-primary/15 bg-primary/5 px-3 py-1.5 text-[11px] font-semibold uppercase text-primary">
                                        Registration Checkout
                                    </span>
                                    <span className="inline-flex items-center rounded-full border border-accent/15 bg-accent/5 px-3 py-1.5 text-[11px] font-semibold uppercase text-accent">
                                        {modeLabel}
                                    </span>
                                </div>
                                <div className="space-y-2">
                                    <h1 className="text-3xl font-bold text-primary md:text-4xl">Complete your registration</h1>
                                    <p className="max-w-2xl text-sm leading-6 text-gray-600 md:text-base">
                                        Confirm your details and proceed to secure payment for <span className="font-semibold text-primary">{event.title}</span>.
                                    </p>
                                </div>
                                <div className="grid gap-4 sm:grid-cols-2">
                                    <div className="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                        <p className="text-[11px] font-semibold uppercase text-gray-500">Event Date</p>
                                        <p className="mt-2 text-base font-semibold text-primary">
                                            {new Date(event.start_date).toLocaleDateString(undefined, {
                                                weekday: 'long',
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric'
                                            })}
                                        </p>
                                    </div>
                                    <div className="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                        <p className="text-[11px] font-semibold uppercase text-gray-500">Registration Fee</p>
                                        <p className="mt-2 text-base font-semibold text-primary">₦{Number(event.entry_fee).toLocaleString()}</p>
                                    </div>
                                </div>
                            </div>
                            {event.program_cover && (
                                <div className="overflow-hidden rounded-lg border border-gray-200 bg-gray-100">
                                    <img
                                        src={`/storage/${event.program_cover}`}
                                        alt={event.title}
                                        className="h-full min-h-[220px] w-full object-cover"
                                    />
                                </div>
                            )}
                        </div>
                    </div>

                    <div className="grid gap-6 lg:grid-cols-[minmax(0,1.45fr)_minmax(300px,0.85fr)] lg:gap-8">
                        <div className="space-y-6">
                            <div className="rounded-lg border border-gray-200 bg-white p-5 shadow-sm lg:p-7">
                                <div className="mb-5">
                                    <h2 className="text-xl font-bold text-primary font-montserrat md:text-2xl">Registration details</h2>
                                    <p className="mt-2 text-sm text-gray-600 font-lato">Review the event information before proceeding to payment.</p>
                                </div>

                                <div className="flex gap-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    {event.program_cover && (
                                        <img
                                            src={`/storage/${event.program_cover}`}
                                            alt={event.title}
                                            className="h-20 w-32 rounded-lg object-cover"
                                        />
                                    )}
                                    <div className="flex-1">
                                        <h3 className="font-semibold text-primary font-montserrat">{event.title}</h3>
                                        <p className="mt-1 text-sm text-gray-600 font-lato">{modeLabel}</p>
                                        <p className="mt-1 text-sm text-gray-600 font-lato">{event.location}</p>
                                    </div>
                                </div>
                            </div>

                            <div className="rounded-lg border border-gray-200 bg-white p-5 shadow-sm lg:p-7">
                                <div className="mb-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <h2 className="text-xl font-bold text-primary font-montserrat md:text-2xl">Payment details</h2>
                                        <p className="mt-2 text-sm text-gray-600 font-lato">These details will be used for your receipt and payment confirmation.</p>
                                    </div>
                                    {user && (
                                        <label className="flex items-center space-x-2 text-sm text-gray-600 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                checked={useMyDetails}
                                                onChange={(e) => handleUseMyDetails(e.target.checked)}
                                                className="rounded border-gray-300 text-primary focus:ring-primary"
                                            />
                                            <span className="font-lato">Use my profile details</span>
                                        </label>
                                    )}
                                </div>

                                <form onSubmit={handleSubmit} className="space-y-5">
                                    <div>
                                        <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-1 font-montserrat">
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
                                        <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-1 font-montserrat">
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
                                        <label htmlFor="phone" className="block text-sm font-medium text-gray-700 mb-1 font-montserrat">
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
                                        className="enterprise-button enterprise-button-primary w-full justify-center py-4 disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        {isProcessing ? 'Processing...' : `Pay ₦${Number(event.entry_fee).toLocaleString()}`}
                                    </button>
                                </form>

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

                        <aside className="space-y-6 lg:sticky lg:top-24 lg:self-start">
                            <div className="rounded-lg border border-gray-200 bg-white p-5 shadow-sm lg:p-6">
                                <p className="text-[11px] font-semibold uppercase text-gray-500">Summary</p>
                                <div className="mt-5 space-y-4">
                                    <div className="flex items-start justify-between gap-4 border-b border-gray-100 pb-4">
                                        <div>
                                            <p className="text-sm font-medium text-gray-500">Event Fee</p>
                                            <p className="mt-1 text-sm text-gray-600">Single attendee registration</p>
                                        </div>
                                        <p className="text-xl font-bold text-primary">₦{Number(event.entry_fee).toLocaleString()}</p>
                                    </div>
                                    <div className="flex items-start justify-between gap-4">
                                        <div>
                                            <p className="text-sm font-medium text-gray-500">Payment Provider</p>
                                            <p className="mt-1 text-sm text-gray-600">Secure online checkout</p>
                                        </div>
                                        <p className="text-base font-semibold text-primary">Paystack</p>
                                    </div>
                                </div>
                            </div>

                            <div className="rounded-lg border border-gray-200 bg-white p-5 shadow-sm lg:p-6">
                                <h3 className="text-lg font-bold text-primary mb-4 font-montserrat">Registration Benefits</h3>
                                <ul className="space-y-3">
                                    <li className="flex items-start gap-3">
                                        <i className="fas fa-check-circle text-accent mt-1"></i>
                                        <span className="text-sm text-gray-700 font-lato">Guaranteed seat reservation</span>
                                    </li>
                                    <li className="flex items-start gap-3">
                                        <i className="fas fa-check-circle text-accent mt-1"></i>
                                        <span className="text-sm text-gray-700 font-lato">Event materials and resources</span>
                                    </li>
                                    <li className="flex items-start gap-3">
                                        <i className="fas fa-check-circle text-accent mt-1"></i>
                                        <span className="text-sm text-gray-700 font-lato">Networking opportunities</span>
                                    </li>
                                </ul>

                                <div className="mt-6 pt-6 border-t border-gray-200">
                                    <button onClick={() => router.visit(route('events.show', event.slug))} className="enterprise-button enterprise-button-outline w-full justify-center">
                                        <i className="fas fa-arrow-left"></i>
                                        Back to event
                                    </button>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>
        </GuestLayout>
    );
}
