import { Fragment, PropsWithChildren } from 'react';
import { Dialog, Transition } from '@headlessui/react';
import { X } from 'lucide-react';

interface Props extends PropsWithChildren {
    show: boolean;
    onClose: () => void;
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '4xl';
}

export default function Modal({
    show = false,
    onClose,
    maxWidth = '2xl',
    children,
}: Props) {
    const maxWidthClass = {
        sm: 'max-w-sm',
        md: 'max-w-md',
        lg: 'max-w-lg',
        xl: 'max-w-xl',
        '2xl': 'max-w-2xl',
        '4xl': 'max-w-4xl',
    }[maxWidth];

    return (
        <Transition show={show} as={Fragment} appear>
            <Dialog as="div" className="relative z-50" open={show} onClose={onClose}>
                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-220"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in duration-180"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-slate-950/55" />
                </Transition.Child>

                <div className="fixed inset-0 overflow-y-auto">
                    <div className="flex min-h-full items-center justify-center px-4 py-6 sm:px-6 sm:py-10">
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-220"
                            enterFrom="opacity-0 translate-y-2 sm:translate-y-0 sm:scale-95"
                            enterTo="opacity-100 translate-y-0 sm:scale-100"
                            leave="ease-in duration-180"
                            leaveFrom="opacity-100 translate-y-0 sm:scale-100"
                            leaveTo="opacity-0 translate-y-1 sm:translate-y-0 sm:scale-[0.985]"
                        >
                            <Dialog.Panel
                                className={`modal-panel relative mx-auto w-full overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl ring-1 ring-slate-950/5 ${maxWidthClass}`}
                            >
                                <button
                                    type="button"
                                    className="absolute right-3 top-3 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                    onClick={onClose}
                                >
                                    <span className="sr-only">Close</span>
                                    <X className="h-5 w-5" aria-hidden="true" />
                                </button>
                                <div className="max-h-[calc(100vh-3rem)] overflow-y-auto sm:max-h-[calc(100vh-5rem)]">
                                    {children}
                                </div>
                            </Dialog.Panel>
                        </Transition.Child>
                    </div>
                </div>
            </Dialog>
        </Transition>
    );
}
