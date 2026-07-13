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
    };

    const handleMetadataChange = (event: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        const { name, value, type } = event.target;
        const nextValue = type === 'checkbox' ? (event.target as HTMLInputElement).checked : value;
        form.setData('metadata', { ...form.data.metadata, [name]: nextValue });
    };

    const handleFileChange = (event: ChangeEvent<HTMLInputElement>) => {
        form.setData('program_cover', event.target.files?.[0] ?? null);
    };

    const create = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('admin.events.store'), { preserveScroll: true, forceFormData: true });
    };

    const update = (event: FormEvent, slug: string) => {
        event.preventDefault();
        // Multipart PUT is not parsed consistently by PHP, so Inertia sends a
        // multipart POST with method spoofing when a replacement cover exists.
        form.transform((data) => ({ ...data, _method: 'put' }));
        form.post(route('admin.events.update', slug), { preserveScroll: true, forceFormData: true });
    };

    return { form, handleInputChange, handleMetadataChange, handleFileChange, create, update };
}

function toDateTimeInput(value?: string | null): string {
    return value ? new Date(value).toISOString().slice(0, 16) : '';
}
