import { Head, usePage, router } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Lock, CreditCard, ShoppingCart } from 'lucide-react';
import { useState, FormEvent } from 'react';
import { Course } from '@/types';

interface CartItem {
    id: number;
    course: Course;
    price: number;
}

interface Cart {
    items: CartItem[];
    total: number;
    itemCount: number;
}

interface Props {
    cart: Cart;
    paystackPublicKey: string;
}

export default function Checkout({ cart, paystackPublicKey }: Props) {
    const { auth } = usePage().props as any;
    const [email, setEmail] = useState(auth.user.email || '');
    const [phone, setPhone] = useState(auth.user.phone || '');
    const [processing, setProcessing] = useState(false);

    const handlePayment = async (e: FormEvent) => {
        e.preventDefault();
        setProcessing(true);

        try {
            const response = await fetch(route('payment.cart.initialize'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({ email, phone }),
            });

            const data = await response.json();

            if (!response.ok) {
                console.error('Payment initialization failed:', data);
                alert(data.message || 'Failed to initialize payment');
                setProcessing(false);
                return;
            }

            if (data.success && data.payment_data.data.authorization_url) {
                window.location.href = data.payment_data.data.authorization_url;
            } else {
                console.error('Payment data invalid:', data);
                alert(data.message || 'Failed to initialize payment');
                setProcessing(false);
            }
        } catch (error) {
            console.error('Payment error:', error);
            alert('An error occurred. Please try again. Check console for details.');
            setProcessing(false);
        }
    };

    return (
        <GuestLayout>
            <Head title="Checkout" />

            <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {/* Header */}
                <div className="mb-8">
                    <h1 className="text-3xl font-bold text-gray-900 font-montserrat mb-2">
                        Checkout
                    </h1>
                    <p className="text-gray-600 font-lato">
                        Complete your purchase to start learning
                    </p>
                </div>

                <div className="grid lg:grid-cols-3 gap-8">
                    {/* Payment Form */}
                    <div className="lg:col-span-2">
                        <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <div className="flex items-center gap-2 mb-6">
                                <Lock className="w-5 h-5 text-primary" />
                                <h2 className="text-xl font-bold text-gray-900 font-montserrat">
                                    Payment Information
                                </h2>
                            </div>

                            <form onSubmit={handlePayment} className="space-y-4">
                                <div>
                                    <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address
                                    </label>
                                    <input
                                        id="email"
                                        type="email"
                                        value={email}
                                        onChange={(e) => setEmail(e.target.value)}
                                        required
                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="your@email.com"
                                    />
                                </div>

                                <div>
                                    <label htmlFor="phone" className="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number (Optional)
                                    </label>
                                    <input
                                        id="phone"
                                        type="tel"
                                        value={phone}
                                        onChange={(e) => setPhone(e.target.value)}
                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="+1234567890"
                                    />
                                </div>

                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-linear-to-r from-primary to-primary-600 text-white rounded-lg font-semibold font-montserrat hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <CreditCard className="w-5 h-5" />
                                    {processing ? 'Processing...' : `Pay ₦${Number(cart.total).toLocaleString()}`}
                                </button>
                            </form>

                            <div className="mt-6 pt-6 border-t border-gray-200">
                                <div className="flex items-center gap-2 text-sm text-gray-600">
                                    <Lock className="w-4 h-4" />
                                    <span>Secure payment powered by Paystack</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Order Summary */}
                    <div className="lg:col-span-1">
                        <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-8">
                            <div className="flex items-center gap-2 mb-6">
                                <ShoppingCart className="w-5 h-5 text-primary" />
                                <h2 className="text-xl font-bold text-gray-900 font-montserrat">
                                    Order Summary
                                </h2>
                            </div>

                            <div className="space-y-3 mb-6">
                                {cart.items.map((item) => (
                                    <div key={item.id} className="flex justify-between text-sm">
                                        <span className="text-gray-700 font-lato line-clamp-1">
                                            {item.course.title}
                                        </span>
                                        <span className="text-gray-900 font-medium shrink-0 ml-2">
                                            ₦{Number(item.price).toLocaleString()}
                                        </span>
                                    </div>
                                ))}
                            </div>

                            <div className="border-t border-gray-200 pt-4">
                                <div className="flex justify-between text-sm text-gray-600 mb-2 font-lato">
                                    <span>Subtotal ({cart.itemCount} {cart.itemCount > 1 ? 'courses' : 'course'})</span>
                                    <span>₦{Number(cart.total).toLocaleString()}</span>
                                </div>
                                <div className="flex justify-between text-lg font-bold text-gray-900 font-montserrat">
                                    <span>Total</span>
                                    <span className="text-primary">₦{Number(cart.total).toLocaleString()}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}
