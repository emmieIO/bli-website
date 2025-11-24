import { forwardRef, InputHTMLAttributes } from 'react';

interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
    icon?: string;
    error?: string;
    label?: string;
}

const Input = forwardRef<HTMLInputElement, InputProps>(
    ({ icon, error, label, className = '', ...props }, ref) => {
        return (
            <div className="space-y-2">
                {label && (
                    <label className="block text-sm font-medium text-gray-700 font-lato">
                        {label}
                    </label>
                )}

                <div className="relative">
                    {icon && (
                        <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i className={`${icon} h-5 w-5 text-gray-400`} />
                        </div>
                    )}

                    <input
                        {...props}
                        ref={ref}
                        className={`
                            block w-full
                            ${icon ? 'pl-10' : 'pl-3'}
                            pr-3 py-3
                            border border-gray-300 rounded-xl
                            text-gray-900 placeholder-gray-500
                            focus:outline-none focus:ring-2 focus:border-primary
                            transition-all duration-200
                            bg-gray-50 focus:bg-white
                            ${error ? 'border-red-500 focus:ring-red-500' : 'focus:ring-primary'}
                            ${className}
                        `}
                        style={error ? {} : { '--tw-ring-color': '#002147' } as any}
                    />
                </div>

                {error && (
                    <p className="text-sm text-red-600 font-lato">{error}</p>
                )}
            </div>
        );
    }
);

Input.displayName = 'Input';

export default Input;
