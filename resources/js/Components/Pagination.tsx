import { Link } from '@inertiajs/react';
import React from 'react';

interface PaginationProps {
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

export default function Pagination({ links }: PaginationProps) {
    return (
        <nav className="text-center mt-4">
            {links.map((link, key) => (
                <Link
                    key={key}
                    href={link.url || '#'}
                    className={`inline-block py-2 px-3 rounded-lg text-sm leading-none ${link.active ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'} ${!link.url ? 'opacity-50 cursor-not-allowed' : ''} mx-1`}
                    dangerouslySetInnerHTML={{ __html: link.label }}
                />
            ))}
        </nav>
    );
}