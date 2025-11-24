import { ButtonHTMLAttributes } from 'react';

interface ButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
    variant?: 'primary' | 'secondary' | 'danger' | 'ghost';
    icon?: string;
    loading?: boolean;
}

export default function Button({
    children,
    variant = 'primary',
    icon,
    loading = false,
    className = '',
    disabled,
    ...props
}: ButtonProps) {
    const variants = {
        primary: 'bg-primary hover:bg-primary-600 text-white',
        secondary: 'bg-gray-200 hover:bg-gray-300 text-gray-700',
        danger: 'bg-red-600 hover:bg-red-700 text-white',
        ghost: 'border border-gray-300 hover:bg-gray-50 text-gray-700',
    };

    return (
        <button
            className={`
                inline-flex items-center gap-2 font-medium px-6 py-3 rounded-lg
                transition-all duration-200 font-montserrat
                disabled:opacity-50 disabled:cursor-not-allowed
                ${variants[variant]}
                ${className}
            `}
            disabled={disabled || loading}
            {...props}
        >
            {loading ? (
                <i className="fas fa-spinner fa-spin w-4 h-4"></i>
            ) : icon ? (
                <i className={`fas fa-${icon} w-4 h-4`}></i>
            ) : null}
            {children}
        </button>
    );
}
