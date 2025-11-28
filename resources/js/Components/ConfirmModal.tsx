import { ReactNode } from 'react';

interface ConfirmModalProps {
    show: boolean;
    onClose: () => void;
    onConfirm: () => void;
    title: string;
    message: string | ReactNode;
    confirmText?: string;
    cancelText?: string;
    confirmButtonClass?: string;
    icon?: ReactNode;
    isProcessing?: boolean;
    processingText?: string;
}

export default function ConfirmModal({
    show,
    onClose,
    onConfirm,
    title,
    message,
    confirmText = 'Confirm',
    cancelText = 'Cancel',
    confirmButtonClass = 'bg-primary-600 hover:bg-primary-700 text-white',
    icon,
    isProcessing = false,
    processingText = 'Processing...',
}: ConfirmModalProps) {
    if (!show) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div className="relative bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4 border border-gray-200">
                {/* Modal header */}
                <div className="flex items-center justify-between pb-4 mb-4 border-b border-gray-200">
                    <h3 className="text-lg font-semibold text-gray-900 font-montserrat">
                        {title}
                    </h3>
                    <button
                        type="button"
                        onClick={onClose}
                        disabled={isProcessing}
                        className="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors disabled:opacity-50"
                    >
                        <i className="fas fa-times w-4 h-4"></i>
                    </button>
                </div>

                {/* Modal body */}
                <div className="space-y-4">
                    <div className="flex flex-col items-center text-center">
                        {icon && (
                            <div className="mb-4">
                                {icon}
                            </div>
                        )}
                        <div className="text-sm text-gray-500 font-lato">
                            {message}
                        </div>
                    </div>
                </div>

                {/* Modal footer */}
                <div className="flex items-center justify-end pt-4 mt-4 border-t border-gray-200 gap-3">
                    <button
                        onClick={onClose}
                        type="button"
                        disabled={isProcessing}
                        className="py-2.5 px-5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors font-lato disabled:opacity-50"
                    >
                        {cancelText}
                    </button>
                    <button
                        onClick={onConfirm}
                        disabled={isProcessing}
                        className={`px-5 py-2.5 text-sm font-medium rounded-lg transition-colors inline-flex items-center font-montserrat disabled:opacity-50 disabled:cursor-not-allowed ${confirmButtonClass}`}
                    >
                        {isProcessing ? (
                            <>
                                <i className="fas fa-spinner fa-spin w-4 h-4 mr-2"></i>
                                {processingText}
                            </>
                        ) : (
                            confirmText
                        )}
                    </button>
                </div>
            </div>
        </div>
    );
}
