import { ReactNode } from 'react';
import Modal from './Modal';

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
    return (
        <Modal show={show} onClose={onClose} maxWidth="md">
            <div className="p-6">
                {/* Modal header */}
                <div className="mb-4 flex items-center justify-between border-b border-gray-200 pb-3">
                    <h3 className="pr-3 text-base font-semibold text-gray-900 font-montserrat">
                        {title}
                    </h3>
                </div>

                {/* Modal body */}
                <div className="space-y-4">
                    <div className="flex flex-col items-center text-center">
                        {icon && (
                            <div className="mb-3">
                                {icon}
                            </div>
                        )}
                        <div className="mx-auto max-w-[26rem] text-sm leading-6 text-gray-500 font-lato">
                            {message}
                        </div>
                    </div>
                </div>

                {/* Modal footer */}
                <div className="mt-5 flex items-center justify-end gap-3 border-t border-gray-200 pt-4">
                    <button
                        onClick={onClose}
                        type="button"
                        disabled={isProcessing}
                        className="rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 font-lato disabled:opacity-50"
                    >
                        {cancelText}
                    </button>
                    <button
                        onClick={onConfirm}
                        disabled={isProcessing}
                        className={`inline-flex items-center rounded-md px-4 py-2.5 text-sm font-medium transition-colors font-montserrat disabled:cursor-not-allowed disabled:opacity-50 ${confirmButtonClass}`}
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
        </Modal>
    );
}
