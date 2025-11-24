export interface User {
    id: number;
    name: string;
    email: string;
    photo?: string | null;
    phone?: string | null;
    linkedin?: string | null;
    website?: string | null;
    headline?: string | null;
    email_verified_at?: string | null;
    roles: string[];
    permissions: string[];
}

export interface Course {
    id: number;
    title: string;
    subtitle?: string | null;
    slug: string;
    description: string;
    thumbnail_path?: string | null;
    preview_video_id?: string | null;
    language?: string | null;
    level: 'beginner' | 'intermediate' | 'advanced';
    category_id: number;
    instructor_id: number;
    status: 'draft' | 'under_review' | 'approved' | 'rejected';
    is_free: boolean;
    price?: string | null;
    created_at: string;
    updated_at: string;
}

export interface Event {
    id: number;
    uuid: string;
    slug: string;
    title: string;
    theme: string;
    description: string;
    program_cover?: string | null;
    mode: string;
    location?: string | null;
    attendee_slots?: number | null;
    physical_address?: string | null;
    venue?: string | null;
    contact_email?: string | null;
    start_date: string;
    end_date?: string | null;
    creator_id: number;
    is_active: boolean;
    is_published: boolean;
    is_allowing_application: boolean;
    is_featured: boolean;
    entry_fee: string;
    metadata?: Record<string, any> | null;
    created_at: string;
    updated_at: string;
}

export interface Lesson {
    id: number;
    module_id: number;
    title: string;
    slug: string;
    type?: string | null;
    description?: string | null;
    vimeo_id?: string | null;
    preview_vimeo_id?: string | null;
    is_preview: boolean;
    content_path?: string | null;
    assignment_instructions?: string | null;
    order: number;
    created_at: string;
    updated_at: string;
}

export interface PageProps {
    auth: {
        user: User | null;
    };
    flash: {
        message?: string;
        type?: 'success' | 'error' | 'warning' | 'info';
    };
    ziggy: {
        location: string;
        query: Record<string, any>;
        [key: string]: any;
    };
}

export type FlashMessage = {
    message?: string;
    type?: 'success' | 'error' | 'warning' | 'info';
};
