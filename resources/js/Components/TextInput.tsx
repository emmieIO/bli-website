
import { InputHTMLAttributes, forwardRef } from 'react';

export default forwardRef(function TextInput(
    { className = '', ...props }: InputHTMLAttributes<HTMLInputElement>,
    ref: React.Ref<HTMLInputElement>
) {
    return (
        <input
            {...props}
            className={
                'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm ' +
                className
            }
            ref={ref}
        />
    );
});
