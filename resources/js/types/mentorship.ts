export interface MentorshipPerson {
    id?: number;
    name: string;
    email: string;
}

export interface MentorshipRequest {
    id: number;
    student_id?: number;
    instructor_id?: number;
    student: MentorshipPerson;
    instructor: MentorshipPerson;
    message: string;
    goals: string | null;
    status: string;
    duration_type: string;
    duration_value: number;
    instructor_response: string | null;
    instructor_approved_at?: string | null;
    admin_response: string | null;
    admin_approved_at?: string | null;
    rejection_reason?: string | null;
    meeting_link?: string | null;
    meeting_schedule?: unknown | null;
    next_session_at?: string | null;
    created_at: string;
    status_label: string;
    status_color: string;
    formatted_duration: string;
}

export interface MentorshipSession {
    id: number;
    session_date: string;
    duration: number;
    notes: string | null;
    topics_covered: string | null;
    recording_link: string | null;
    completed_at: string | null;
    created_at: string;
}

export interface MentorshipResource {
    id: number;
    file_name: string;
    file_type: string | null;
    file_size: number | null;
    description: string | null;
    category: string;
    created_at: string;
    uploader: Pick<MentorshipPerson, 'name'>;
}

export interface MentorshipMilestone {
    id: number;
    title: string;
    description: string | null;
    due_date: string | null;
    order: number;
    completed_at: string | null;
    completed_by: Pick<MentorshipPerson, 'name'> | null;
    created_at: string;
}

export interface MentorshipStatistics {
    total: number;
    pending: number;
    instructor_approved: number;
    admin_approved: number;
    active: number;
    rejected: number;
}

export interface MentorshipExpiringWarning {
    id: number;
    student: string;
    instructor: string;
    days_remaining: number;
    expiration_date: string;
    expiration_status: {
        status: string;
        message: string;
        color: string;
    };
}

export type MentorshipTab = 'overview' | 'sessions' | 'resources' | 'milestones';

export interface MentorshipWorkspaceProps {
    mentorshipRequest: MentorshipRequest;
    sessions: MentorshipSession[];
    resources: MentorshipResource[];
    milestones: MentorshipMilestone[];
}

export interface InstructorMentorshipIndexProps {
    requests: MentorshipRequest[];
    pending: MentorshipRequest[];
    statistics: MentorshipStatistics;
    expiringWarnings: MentorshipExpiringWarning[];
}

export interface StudentMentorshipIndexProps {
    requests: MentorshipRequest[];
    statistics: MentorshipStatistics;
    expiringWarnings: MentorshipExpiringWarning[];
}

export interface MentorshipSessionsProps {
    mentorshipRequest: MentorshipRequest;
    sessions: MentorshipSession[];
}

export interface MentorshipResourcesProps {
    mentorshipRequest: MentorshipRequest;
    resources: MentorshipResource[];
}

export interface MentorshipMilestonesProps {
    mentorshipRequest: MentorshipRequest;
    milestones: MentorshipMilestone[];
}
