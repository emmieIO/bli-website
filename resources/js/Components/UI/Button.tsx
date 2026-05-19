import { ButtonHTMLAttributes, PropsWithChildren } from 'react';

interface ButtonProps extends ButtonHTMLAttributes<HTMLButtonElement>, PropsWithChildren {
    variant?: 'primary' | 'secondary' | 'outline' | 'danger';
    isLoading?: boolean;
    icon?: string;
    fullWidth?: boolean;
}

export default function Button({
    variant = 'primary',
    isLoading = false,
    icon,
    fullWidth = false,
    children,
    className = '',
    disabled,
    ...props
}: ButtonProps) {
    const baseStyles = `
        inline-flex items-center justify-center gap-2
        min-h-11 px-5 py-3 rounded-lg font-semibold font-sans
        transition-all duration-200
        focus:outline-none focus:ring-2 focus:ring-offset-2
        disabled:opacity-50 disabled:cursor-not-allowed
        ${fullWidth ? 'w-full' : ''}
        ${className}
    `;

    const variants = {
        primary: `
            bg-primary text-white hover:bg-primary-700 hover:shadow-md
            focus:ring-primary
        `,
        secondary: `
            bg-accent text-white hover:bg-accent-700 hover:shadow-md
            focus:ring-accent
        `,
        outline: `
            border border-gray-300 text-primary hover:bg-gray-50
            focus:ring-primary
        `,
        danger: `
            bg-red-600 text-white hover:bg-red-700 hover:shadow-md
            focus:ring-red-500
        `,
    };

    return (
        <button
            {...props}
            disabled={disabled || isLoading}
            className={`${baseStyles} ${variants[variant]}`}
        >
            {isLoading ? (
                <>
                    <i className="fas fa-spinner fa-spin" />
                    <span>Processing...</span>
                </>
            ) : (
                <>
                    {icon && <i className={icon} />}
                    {children}
                </>
            )}
        </button>
    );
}
