import { Head, Link, router } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import { ShoppingCart, Trash2, ArrowRight, BookOpen } from 'lucide-react';
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
}

export default function Index({ cart }: Props) {
    const handleRemove = (courseSlug: string) => {
        if (confirm('Are you sure you want to remove this course from your cart?')) {
            router.delete(route('cart.remove', courseSlug));
        }
    };

    const handleClearCart = () => {
        if (confirm('Are you sure you want to clear your entire cart?')) {
            router.delete(route('cart.clear'));
        }
    };

    return (
        <GuestLayout>
            <Head title="My Cart" />

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {/* Header */}
                <div className="mb-8">
                    <div className="flex items-center gap-3 mb-2">
                        <ShoppingCart className="w-8 h-8 text-primary" />
                        <h1 className="text-3xl font-bold text-gray-900 font-montserrat">
                            My Cart
                        </h1>
                    </div>
                    <p className="text-gray-600 font-lato">
                        {cart.itemCount === 0
                            ? 'Your cart is empty'
                            : `${cart.itemCount} course${cart.itemCount > 1 ? 's' : ''} in your cart`}
                    </p>
                </div>

                {cart.itemCount === 0 ? (
                    /* Empty Cart State */
                    <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <div className="max-w-md mx-auto">
                            <div className="mb-6">
                                <ShoppingCart className="w-24 h-24 text-gray-300 mx-auto" strokeWidth={1} />
                            </div>
                            <h2 className="text-2xl font-bold text-gray-900 mb-3 font-montserrat">
                                Your cart is empty
                            </h2>
                            <p className="text-gray-600 mb-6 font-lato">
                                Browse our course catalog and add courses you're interested in.
                            </p>
                            <Link
                                href={route('courses.index')}
                                className="inline-flex items-center gap-2 px-6 py-3 bg-linear-to-r from-primary to-primary-600 text-white rounded-lg font-semibold font-montserrat hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-md hover:shadow-lg"
                            >
                                <BookOpen className="w-5 h-5" />
                                Browse Courses
                            </Link>
                        </div>
                    </div>
                ) : (
                    <div className="grid lg:grid-cols-3 gap-8">
                        {/* Cart Items */}
                        <div className="lg:col-span-2 space-y-4">
                            {cart.items.map((item) => (
                                <div
                                    key={item.id}
                                    className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow"
                                >
                                    <div className="flex gap-4">
                                        {/* Course Thumbnail */}
                                        <div className="shrink-0">
                                            <img
                                                src={item.course.thumbnail_path ? `/storage/${item.course.thumbnail_path}` : '/images/course-placeholder.jpg'}
                                                alt={item.course.title}
                                                className="w-32 h-20 object-cover rounded-lg"
                                            />
                                        </div>

                                        {/* Course Info */}
                                        <div className="grow">
                                            <Link
                                                href={route('courses.show', item.course.slug)}
                                                className="text-lg font-bold text-gray-900 hover:text-primary transition-colors font-montserrat line-clamp-2"
                                            >
                                                {item.course.title}
                                            </Link>
                                            <p className="text-sm text-gray-600 mt-1 font-lato">
                                                By {item.course.instructor?.name || 'Unknown Instructor'}
                                            </p>
                                            {item.course.category && (
                                                <span className="inline-block mt-2 px-3 py-1 bg-primary/10 text-primary text-xs font-medium rounded-full">
                                                    {item.course.category.name}
                                                </span>
                                            )}
                                        </div>

                                        {/* Price & Remove */}
                                        <div className="shrink-0 text-right">
                                            <p className="text-2xl font-bold text-primary font-montserrat">
                                                ₦{Number(item.price).toLocaleString()}
                                            </p>
                                            <button
                                                onClick={() => handleRemove(item.course.slug)}
                                                className="mt-4 inline-flex items-center gap-2 text-red-600 hover:text-red-700 font-medium text-sm transition-colors"
                                            >
                                                <Trash2 className="w-4 h-4" />
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ))}

                            {/* Clear Cart Button */}
                            <button
                                onClick={handleClearCart}
                                className="text-red-600 hover:text-red-700 font-medium text-sm transition-colors"
                            >
                                Clear entire cart
                            </button>
                        </div>

                        {/* Order Summary */}
                        <div className="lg:col-span-1">
                            <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-8">
                                <h2 className="text-xl font-bold text-gray-900 mb-6 font-montserrat">
                                    Order Summary
                                </h2>

                                <div className="space-y-3 mb-6">
                                    <div className="flex justify-between text-gray-600 font-lato">
                                        <span>Subtotal ({cart.itemCount} {cart.itemCount > 1 ? 'courses' : 'course'})</span>
                                        <span>₦{Number(cart.total).toLocaleString()}</span>
                                    </div>
                                    <div className="border-t border-gray-200 pt-3">
                                        <div className="flex justify-between text-lg font-bold text-gray-900 font-montserrat">
                                            <span>Total</span>
                                            <span className="text-primary">₦{Number(cart.total).toLocaleString()}</span>
                                        </div>
                                    </div>
                                </div>

                                <Link
                                    href={route('cart.checkout')}
                                    className="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-linear-to-r from-primary to-primary-600 text-white rounded-lg font-semibold font-montserrat hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-md hover:shadow-lg"
                                >
                                    Proceed to Checkout
                                    <ArrowRight className="w-5 h-5" />
                                </Link>

                                <Link
                                    href={route('courses.index')}
                                    className="block mt-4 text-center text-primary hover:text-primary-600 font-medium text-sm transition-colors"
                                >
                                    Continue Shopping
                                </Link>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </GuestLayout>
    );
}
