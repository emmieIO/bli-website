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
    status: 'draft' | 'review' | 'published' | 'registration_open' | 'registration_closed' | 'live' | 'completed' | 'cancelled' | 'archived';
    is_active: boolean;
    is_published: boolean;
    is_allowing_application: boolean;
    is_featured: boolean;
    require_sign_up: boolean;
    entry_fee: string;
    metadata?: Record<string, any> | null;
    created_at: string;
    updated_at: string;
}

export interface SideLink {
    section?: string;
    title: string;
    icon: string;
    route?: string;
    permission?: string | string[];
    exclude_permission?: string | string[];
    variant?: string;
    children?: Array<{
        title: string;
        route: string;
        permission?: string | string[];
    }>;
}

export interface PlatformModule {
    key: string;
    title: string;
    section: string;
    icon: string;
    status: 'active' | 'planned' | string;
    stage: string;
    audience: string;
    purpose: string;
    actions: string[];
    entry_route?: string | null;
    entry_url?: string | null;
    lms_bridge?: string | null;
    planned_permissions?: string[];
    planned_routes?: string[];
    can_access: boolean;
}

export interface PageProps {
    // Inertia pages may add controller-specific props beyond this shared core.
    [key: string]: unknown;
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
    sideLinks: SideLink[];
    platformModules: PlatformModule[];
}

export type FlashMessage = {
    message?: string;
    type?: 'success' | 'error' | 'warning' | 'info';
};
