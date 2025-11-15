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
        px-6 py-3 rounded-full font-semibold font-lato
        transition-all duration-300
        focus:outline-none focus:ring-2 focus:ring-offset-2
        disabled:opacity-50 disabled:cursor-not-allowed
        ${fullWidth ? 'w-full' : ''}
        ${className}
    `;

    const variants = {
        primary: `
            text-white hover:shadow-lg hover:scale-105
            focus:ring-primary
        `,
        secondary: `
            text-white hover:shadow-lg hover:scale-105
            focus:ring-green-500
        `,
        outline: `
            border-2 hover:shadow-lg
            focus:ring-primary
        `,
        danger: `
            bg-red-600 text-white hover:bg-red-700 hover:shadow-lg
            focus:ring-red-500
        `,
    };

    const getVariantStyles = () => {
        switch (variant) {
            case 'primary':
                return { backgroundColor: '#002147' };
            case 'secondary':
                return { backgroundColor: '#00a651' };
            case 'outline':
                return { borderColor: '#002147', color: '#002147' };
            default:
                return {};
        }
    };

    return (
        <button
            {...props}
            disabled={disabled || isLoading}
            className={`${baseStyles} ${variants[variant]}`}
            style={variant !== 'danger' ? getVariantStyles() : {}}
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
