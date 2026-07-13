import type { Event } from '@/types';

export type EventStatus = Event['status'];
export type EventMode = 'online' | 'offline' | 'hybrid';

export interface EventProgramMetadataForm {
    program_type: 'general_event' | 'discipleship_track';
    program_code: string;
    registration_mode: 'open' | 'selective';
    requires_screening: boolean;
    screening_note: string;
    cohort_duration_weeks: string;
    group_model: string;
    central_teaching_schedule: string;
    group_meeting_schedule: string;
    weekly_prayer_target_minutes: string;
    weekly_evangelism_target_min: string;
    weekly_evangelism_target_max: string;
    weekly_discipleship_target_min: string;
    weekly_discipleship_target_max: string;
    meeting_link: string;
    access_notes: string;
}

export interface EventFormData {
    title: string;
    status: EventStatus | '';
    mode: EventMode | '';
    attendee_slots: string;
    theme: string;
    start_date: string;
    end_date: string;
    location: string;
    physical_address: string;
    contact_email: string;
    entry_fee: string;
    description: string;
    is_active: boolean;
    is_published: boolean;
    is_allowing_application: boolean;
    is_featured: boolean;
    require_sign_up: boolean;
    creator_id: number | null;
    program_cover: File | null;
    metadata: EventProgramMetadataForm;
    _method?: 'put';
}

export interface EditableEventMetadata {
    program_type?: 'general_event' | 'discipleship_track';
    program_code?: string | null;
    registration_mode?: 'open' | 'selective';
    requires_screening?: boolean;
    screening_note?: string | null;
    cohort_duration_weeks?: number | null;
    group_model?: string | null;
    central_teaching_schedule?: string | null;
    group_meeting_schedule?: string | null;
    weekly_prayer_target_minutes?: number | null;
    weekly_evangelism_target_min?: number | null;
    weekly_evangelism_target_max?: number | null;
    weekly_discipleship_target_min?: number | null;
    weekly_discipleship_target_max?: number | null;
    meeting_link?: string | null;
    access_notes?: string | null;
}

export interface EditableEvent extends Omit<Event, 'entry_fee' | 'attendee_slots' | 'metadata' | 'mode'> {
    entry_fee: number;
    attendee_slots: number;
    mode: EventMode;
    metadata?: EditableEventMetadata | null;
}

export interface EditEventProps {
    event: EditableEvent;
}

export interface EventResourceFormData {
    title: string;
    type: 'file' | 'link' | '';
    external_link: string;
    description: string;
    is_downloadable: boolean;
    file_path: File | null;
}

export interface AddEventResourceProps {
    event: Pick<Event, 'id' | 'title' | 'slug'>;
}
