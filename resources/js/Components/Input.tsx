import { InputHTMLAttributes, forwardRef } from 'react';

interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
    label?: string;
    icon?: string;
    error?: string;
}

const Input = forwardRef<HTMLInputElement, InputProps>(
    ({ label, icon, error, className = '', ...props }, ref) => {
        const inputId = props.id ?? props.name;
        const errorId = error && inputId ? `${inputId}-error` : undefined;

        return (
            <div className="relative">
                {label && (
                    <label htmlFor={inputId} className="block text-sm font-medium text-gray-700 mb-1 font-lato">
                        {label}
                        {props.required && <span className="text-red-500 ml-1">*</span>}
                    </label>
                )}
                <div className="relative">
                    {icon && (
                        <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i className={`fas fa-${icon} text-gray-400 w-4 h-4`}></i>
                        </div>
                    )}
                    <input
                        ref={ref}
                        id={inputId}
                        aria-invalid={error ? true : undefined}
                        aria-describedby={errorId ?? props['aria-describedby']}
                        className={`
                            block w-full rounded-lg border shadow-sm px-3 py-2
                            focus:border-primary-500 focus:ring-primary-500 focus:outline-none
                            disabled:bg-gray-100 disabled:cursor-not-allowed
                            text-sm font-lato
                            ${icon ? 'pl-10' : ''}
                            ${error ? 'border-red-500' : 'border-gray-300'}
                            ${className}
                        `}
                        {...props}
                    />
                </div>
                {error && (
                    <p id={errorId} className="text-sm text-red-600 mt-1 font-lato">{error}</p>
                )}
            </div>
        );
    }
);

Input.displayName = 'Input';

export default Input;
