import { useForm } from '@inertiajs/react';
import type { ChangeEvent, FormEvent } from 'react';
import type { EditableEvent, EventFormData, EventProgramMetadataForm } from '@/types/events';

const emptyMetadata: EventProgramMetadataForm = {
    program_type: 'general_event',
    program_code: '',
    registration_mode: 'open',
    requires_screening: false,
    screening_note: '',
    cohort_duration_weeks: '',
    group_model: '',
    central_teaching_schedule: '',
    group_meeting_schedule: '',
    weekly_prayer_target_minutes: '',
    weekly_evangelism_target_min: '',
    weekly_evangelism_target_max: '',
    weekly_discipleship_target_min: '',
    weekly_discipleship_target_max: '',
    meeting_link: '',
    access_notes: '',
};

export function useEventForm(event?: EditableEvent, creatorId: number | null = null) {
    const form = useForm<EventFormData>({
        title: event?.title ?? '',
        status: event?.status ?? 'draft',
        mode: event?.mode ?? '',
        attendee_slots: event?.attendee_slots?.toString() ?? '',
        theme: event?.theme ?? '',
        start_date: toDateTimeInput(event?.start_date),
        end_date: toDateTimeInput(event?.end_date),
        location: event?.location ?? '',
        physical_address: event?.physical_address ?? '',
        contact_email: event?.contact_email ?? '',
        entry_fee: event?.entry_fee?.toString() ?? '0',
        description: event?.description ?? '',
        is_active: event?.is_active ?? false,
        is_published: event?.is_published ?? false,
        is_allowing_application: event?.is_allowing_application ?? false,
        is_featured: event?.is_featured ?? false,
        require_sign_up: event?.require_sign_up ?? true,
        creator_id: event?.creator_id ?? creatorId,
        program_cover: null,
        metadata: {
            ...emptyMetadata,
            ...event?.metadata,
            program_type: event?.metadata?.program_type ?? 'general_event',
            program_code: event?.metadata?.program_code ?? '',
            registration_mode: event?.metadata?.registration_mode ?? 'open',
            screening_note: event?.metadata?.screening_note ?? '',
            cohort_duration_weeks: event?.metadata?.cohort_duration_weeks?.toString() ?? '',
            group_model: event?.metadata?.group_model ?? '',
            central_teaching_schedule: event?.metadata?.central_teaching_schedule ?? '',
            group_meeting_schedule: event?.metadata?.group_meeting_schedule ?? '',
            weekly_prayer_target_minutes: event?.metadata?.weekly_prayer_target_minutes?.toString() ?? '',
            weekly_evangelism_target_min: event?.metadata?.weekly_evangelism_target_min?.toString() ?? '',
            weekly_evangelism_target_max: event?.metadata?.weekly_evangelism_target_max?.toString() ?? '',
            weekly_discipleship_target_min: event?.metadata?.weekly_discipleship_target_min?.toString() ?? '',
            weekly_discipleship_target_max: event?.metadata?.weekly_discipleship_target_max?.toString() ?? '',
            meeting_link: event?.metadata?.meeting_link ?? '',
            access_notes: event?.metadata?.access_notes ?? '',
        },
    });

    const handleInputChange = (event: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        const { name, value, type } = event.target;
        const nextValue = type === 'checkbox' ? (event.target as HTMLInputElement).checked : value;
        form.setData(name as keyof EventFormData, nextValue as never);
        form.clearErrors(name as keyof EventFormData);
    };

    const handleMetadataChange = (event: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        const { name, value, type } = event.target;
        const nextValue = type === 'checkbox' ? (event.target as HTMLInputElement).checked : value;
        form.setData('metadata', { ...form.data.metadata, [name]: nextValue });
        form.clearErrors(`metadata.${name}` as keyof EventFormData);
    };

    const handleFileChange = (event: ChangeEvent<HTMLInputElement>) => {
        form.setData('program_cover', event.target.files?.[0] ?? null);
        form.clearErrors('program_cover');
    };

    const create = (event: FormEvent) => {
        event.preventDefault();
        form.transform(normalizeEventPayload);
        form.post(route('admin.events.store'), { preserveScroll: true, forceFormData: true });
    };

    const update = (event: FormEvent, slug: string) => {
        event.preventDefault();
        // Multipart PUT is not parsed consistently by PHP, so Inertia sends a
        // multipart POST with method spoofing when a replacement cover exists.
        form.transform((data) => ({ ...normalizeEventPayload(data), _method: 'put' }));
        form.post(route('admin.events.update', slug), { preserveScroll: true, forceFormData: true });
    };

    return { form, handleInputChange, handleMetadataChange, handleFileChange, create, update };
}

function normalizeEventPayload(data: EventFormData): EventFormData {
    const isDiscipleshipTrack = data.metadata.program_type === 'discipleship_track';
    const keepsScreeningNote = data.metadata.requires_screening || data.metadata.registration_mode === 'selective';

    return {
        ...data,
        title: data.title.trim(),
        theme: data.theme.trim(),
        location: data.mode === 'offline' ? '' : data.location.trim(),
        physical_address: data.mode === 'online' ? '' : data.physical_address.trim(),
        contact_email: data.contact_email.trim(),
        attendee_slots: data.attendee_slots.trim(),
        entry_fee: data.entry_fee.trim() || '0',
        metadata: {
            ...data.metadata,
            program_code: data.metadata.program_code.trim(),
            screening_note: keepsScreeningNote ? data.metadata.screening_note.trim() : '',
            cohort_duration_weeks: isDiscipleshipTrack ? data.metadata.cohort_duration_weeks.trim() : '',
            group_model: isDiscipleshipTrack ? data.metadata.group_model.trim() : '',
            central_teaching_schedule: isDiscipleshipTrack ? data.metadata.central_teaching_schedule.trim() : '',
            group_meeting_schedule: isDiscipleshipTrack ? data.metadata.group_meeting_schedule.trim() : '',
            weekly_prayer_target_minutes: isDiscipleshipTrack ? data.metadata.weekly_prayer_target_minutes.trim() : '',
            weekly_evangelism_target_min: isDiscipleshipTrack ? data.metadata.weekly_evangelism_target_min.trim() : '',
            weekly_evangelism_target_max: isDiscipleshipTrack ? data.metadata.weekly_evangelism_target_max.trim() : '',
            weekly_discipleship_target_min: isDiscipleshipTrack ? data.metadata.weekly_discipleship_target_min.trim() : '',
            weekly_discipleship_target_max: isDiscipleshipTrack ? data.metadata.weekly_discipleship_target_max.trim() : '',
            meeting_link: data.metadata.meeting_link.trim(),
            access_notes: data.metadata.access_notes.trim(),
        },
    };
}

function toDateTimeInput(value?: string | null): string {
    return value ? new Date(value).toISOString().slice(0, 16) : '';
}
