import { Head, Link } from '@inertiajs/react';
import { Home, ArrowLeft, AlertTriangle } from 'lucide-react';

interface ErrorProps {
    status: number;
}

const errorMessages: Record<number, { title: string; description: string }> = {
    401: {
        title: 'Unauthorized',
        description: 'Sorry, you are not authorized to access this page. Please log in to continue.',
    },
    403: {
        title: 'Forbidden',
        description: 'Sorry, you don\'t have permission to access this resource.',
    },
    404: {
        title: 'Page Not Found',
        description: 'Sorry, the page you are looking for doesn\'t exist or has been moved.',
    },
    419: {
        title: 'Page Expired',
        description: 'Your session has expired. Please refresh the page and try again.',
    },
    429: {
        title: 'Too Many Requests',
        description: 'You\'ve made too many requests. Please slow down and try again later.',
    },
    500: {
        title: 'Server Error',
        description: 'Whoops! Something went wrong on our end. We\'re working to fix it.',
    },
    503: {
        title: 'Service Unavailable',
        description: 'We\'re temporarily down for maintenance. Please check back soon.',
    },
};

export default function Error({ status }: ErrorProps) {
    const error = errorMessages[status] || {
        title: 'Error',
        description: 'An unexpected error occurred.',
    };

    return (
        <>
            <Head title={`${status} - ${error.title}`} />

            <div className="min-h-screen flex items-center justify-center bg-linear-to-br from-gray-50 via-white to-primary-50/20 px-4 py-12">
                <div className="max-w-2xl w-full text-center">
                    {/* Error Icon */}
                    <div className="mb-8 flex justify-center">
                        <div className="relative">
                            <div className="absolute inset-0 bg-linear-to-br from-primary/20 to-accent/20 blur-3xl rounded-full"></div>
                            <div className="relative bg-white rounded-full p-8 shadow-2xl border border-gray-100">
                                <AlertTriangle
                                    className="w-24 h-24 text-primary"
                                    strokeWidth={1.5}
                                />
                            </div>
                        </div>
                    </div>

                    {/* Error Code */}
                    <div className="mb-4">
                        <span className="inline-block px-4 py-2 bg-linear-to-r from-primary/10 to-accent/10 text-primary font-bold font-montserrat text-sm tracking-wider rounded-full border border-primary/20">
                            ERROR {status}
                        </span>
                    </div>

                    {/* Error Title */}
                    <h1 className="text-5xl md:text-6xl font-bold text-primary mb-4 font-montserrat">
                        {error.title}
                    </h1>

                    {/* Error Description */}
                    <p className="text-xl text-gray-600 mb-8 font-lato leading-relaxed max-w-xl mx-auto">
                        {error.description}
                    </p>

                    {/* Action Buttons */}
                    <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <button
                            onClick={() => window.history.back()}
                            className="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-primary text-primary rounded-lg font-semibold font-montserrat hover:bg-primary hover:text-white transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                        >
                            <ArrowLeft className="w-5 h-5" />
                            Go Back
                        </button>

                        <Link
                            href="/"
                            className="inline-flex items-center gap-2 px-6 py-3 bg-linear-to-r from-primary to-primary-600 text-white rounded-lg font-semibold font-montserrat hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                        >
                            <Home className="w-5 h-5" />
                            Back to Home
                        </Link>
                    </div>

                    {/* Additional Help */}
                    <div className="mt-12 pt-8 border-t border-gray-200">
                        <p className="text-sm text-gray-500 font-lato">
                            Need help? {' '}
                            <Link
                                href="/contact"
                                className="text-primary hover:text-accent underline font-medium"
                            >
                                Contact Support
                            </Link>
                        </p>
                    </div>

                    {/* Decorative Elements */}
                    <div className="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
                        <div className="absolute top-20 left-10 w-72 h-72 bg-primary/5 rounded-full blur-3xl"></div>
                        <div className="absolute bottom-20 right-10 w-96 h-96 bg-accent/5 rounded-full blur-3xl"></div>
                    </div>
                </div>
            </div>
        </>
    );
}
