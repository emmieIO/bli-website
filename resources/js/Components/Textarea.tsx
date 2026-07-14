import { TextareaHTMLAttributes, forwardRef } from 'react';

interface TextareaProps extends TextareaHTMLAttributes<HTMLTextAreaElement> {
    label?: string;
    error?: string;
}

const Textarea = forwardRef<HTMLTextAreaElement, TextareaProps>(
    ({ label, error, className = '', ...props }, ref) => {
        const textareaId = props.id ?? props.name;
        const errorId = error && textareaId ? `${textareaId}-error` : undefined;

        return (
            <div className="relative">
                {label && (
                    <label htmlFor={textareaId} className="block text-sm font-medium text-gray-700 mb-1 font-lato">
                        {label}
                        {props.required && <span className="text-red-500 ml-1">*</span>}
                    </label>
                )}
                <textarea
                    ref={ref}
                    id={textareaId}
                    aria-invalid={error ? true : undefined}
                    aria-describedby={errorId ?? props['aria-describedby']}
                    className={`
                        block w-full rounded-lg border shadow-sm px-3 py-2
                        focus:border-primary-500 focus:ring-primary-500 focus:outline-none
                        disabled:bg-gray-100 disabled:cursor-not-allowed
                        text-sm font-lato resize-y min-h-[120px]
                        ${error ? 'border-red-500' : 'border-gray-300'}
                        ${className}
                    `}
                    {...props}
                />
                {error && (
                    <p id={errorId} className="text-sm text-red-600 mt-1 font-lato">{error}</p>
                )}
            </div>
        );
    }
);

Textarea.displayName = 'Textarea';

export default Textarea;
