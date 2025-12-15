import { Head, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { PrinterIcon } from 'lucide-react';
import { useEffect } from 'react';

interface Transaction {
    id: number;
    transaction_id: string;
    payment_ref: string | null;
    amount: number;
    currency: string;
    status: 'pending' | 'successful' | 'failed';
    payment_type: string | null;
    created_at: string;
    paid_at: string | null;
    metadata: any;
    user: {
        name: string;
        email: string;
    };
    course: {
        title: string;
    } | null;
}

interface CompanyDetails {
    name: string;
    address: string;
    email: string;
    phone: string;
}

interface Props extends PageProps {
    transaction: Transaction;
    companyDetails: CompanyDetails;
}

export default function Receipt() {
    const { transaction, companyDetails } = usePage<Props>().props;

    const formatCurrency = (amount: number, currency: string) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency || 'USD',
        }).format(amount);
    };

    const formatDate = (dateString: string | null) => {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true,
        });
    };

    const isCartPurchase = transaction.metadata && transaction.metadata.type === 'cart';
    const items = isCartPurchase ? transaction.metadata.items : (transaction.course ? [{
        course_title: transaction.course.title,
        price: transaction.amount,
    }] : []);

    useEffect(() => {
        // Apply print-specific styles
        const style = document.createElement('style');
        style.innerHTML = `
            @media print {
                body {
                    margin: 0;
                    padding: 0;
                    -webkit-print-color-adjust: exact; /* For Chrome/Safari */
                    print-color-adjust: exact; /* Standard */
                }
                .no-print {
                    display: none !important;
                }
                .receipt-container {
                    box-shadow: none !important;
                    border: none !important;
                    margin: 0;
                    padding: 0;
                }
            }
        `;
        document.head.appendChild(style);

        return () => {
            document.head.removeChild(style);
        };
    }, []);

    const handlePrint = () => {
        window.print();
    };

    return (
        <>
            <Head title={`Receipt #${transaction.transaction_id}`} />
            <div className="min-h-screen bg-gray-100 p-4 print:bg-white">
                <div className="max-w-4xl mx-auto bg-white shadow-lg rounded-lg receipt-container print:shadow-none print:rounded-none">
                    {/* Print Button */}
                    <div className="text-right p-4 no-print">
                        <button
                            onClick={handlePrint}
                            className="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            <PrinterIcon className="w-4 h-4 mr-2" /> Print Receipt
                        </button>
                    </div>

                    <div className="p-8 border-b border-gray-200">
                        <div className="flex justify-between items-center mb-6">
                            <div>
                                <h1 className="text-4xl font-bold text-gray-800 font-montserrat">RECEIPT</h1>
                                <p className="text-sm text-gray-500 font-lato">#{transaction.transaction_id}</p>
                            </div>
                            <div className="text-right">
                                <h2 className="text-xl font-semibold text-gray-800 font-montserrat">{companyDetails.name}</h2>
                                <p className="text-sm text-gray-600 font-lato">{companyDetails.address}</p>
                                <p className="text-sm text-gray-600 font-lato">{companyDetails.email}</p>
                                <p className="text-sm text-gray-600 font-lato">{companyDetails.phone}</p>
                            </div>
                        </div>

                        <div className="flex justify-between mb-8">
                            <div>
                                <h3 className="text-lg font-semibold text-gray-800 mb-2 font-montserrat">Billed To:</h3>
                                <p className="text-md font-medium text-gray-700 font-lato">{transaction.user.name}</p>
                                <p className="text-sm text-gray-600 font-lato">{transaction.user.email}</p>
                            </div>
                            <div className="text-right">
                                <h3 className="text-lg font-semibold text-gray-800 mb-2 font-montserrat">Receipt Date:</h3>
                                <p className="text-md font-medium text-gray-700 font-lato">{formatDate(transaction.created_at)}</p>
                                {transaction.paid_at && (
                                    <>
                                        <h3 className="text-lg font-semibold text-gray-800 mt-4 mb-2 font-montserrat">Paid Date:</h3>
                                        <p className="text-md font-medium text-gray-700 font-lato">{formatDate(transaction.paid_at)}</p>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>

                    <div className="p-8">
                        <div className="overflow-x-auto mb-8">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Item</th>
                                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider font-montserrat">Amount</th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {items.map((item: any, index: number) => (
                                        <tr key={index}>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 font-lato">{item.course_title}</td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 font-lato">{formatCurrency(item.price, transaction.currency)}</td>
                                        </tr>
                                    ))}
                                </tbody>
                                <tfoot>
                                    <tr className="bg-gray-50">
                                        <td className="px-6 py-3 text-right text-base font-semibold text-gray-900 uppercase font-montserrat">Total</td>
                                        <td className="px-6 py-3 text-right text-base font-semibold text-gray-900 font-montserrat">{formatCurrency(transaction.amount, transaction.currency)}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div className="text-sm text-gray-600 space-y-1 font-lato">
                            <p><strong>Payment Status:</strong> <span className={`font-semibold ${transaction.status === 'successful' ? 'text-green-600' : transaction.status === 'failed' ? 'text-red-600' : 'text-yellow-600'}`}>{transaction.status.toUpperCase()}</span></p>
                            {transaction.payment_type && <p><strong>Payment Method:</strong> {transaction.payment_type}</p>}
                            {transaction.payment_ref && <p><strong>Payment Reference:</strong> {transaction.payment_ref}</p>}
                            <p className="pt-4 italic">Thank you for your business!</p>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
