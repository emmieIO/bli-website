import { CalendarPlus, MapPin, Trash2 } from 'lucide-react';
import type { EventDayForm, EventMode } from '@/types/events';

interface EventDaysEditorProps {
    days: EventDayForm[];
    errors: Record<string, string | undefined>;
    defaults: Pick<EventDayForm, 'theme' | 'mode' | 'venue_name' | 'physical_address' | 'meeting_link'>;
    onChange: (days: EventDayForm[]) => void;
}

export default function EventDaysEditor({ days, errors, defaults, onChange }: EventDaysEditorProps) {
    const addDay = () => {
        const previous = days[days.length - 1];
        onChange([...days, {
            title: '',
            theme: defaults.theme,
            start_at: previous?.end_at ?? '',
            end_at: '',
            mode: defaults.mode,
            venue_name: defaults.venue_name,
            physical_address: defaults.physical_address,
            meeting_link: defaults.meeting_link,
            notes: '',
        }]);
    };

    const updateDay = (index: number, field: keyof EventDayForm, value: string | null) => {
        onChange(days.map((day, dayIndex) => dayIndex === index ? { ...day, [field]: value } : day));
    };

    const removeDay = (index: number) => onChange(days.filter((_, dayIndex) => dayIndex !== index));

    return (
        <section className="space-y-4 border-t border-slate-200 pt-7" aria-labelledby="event-days-title">
            <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3 id="event-days-title" className="text-lg font-semibold text-slate-900">Day-by-day schedule</h3>
                    <p className="mt-1 text-sm text-slate-500">Add days when the program changes theme, time, venue, or joining link.</p>
                </div>
                <button type="button" onClick={addDay} className="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-3.5 py-2 text-sm font-semibold text-white hover:bg-primary-600">
                    <CalendarPlus size={17} />
                    Add day
                </button>
            </div>

            {days.length === 0 ? (
                <div className="border border-dashed border-slate-300 bg-slate-50 px-4 py-5 text-sm text-slate-600">
                    No detailed schedule. The program-wide date and location above will be used.
                </div>
            ) : (
                <div className="space-y-4">
                    {days.map((day, index) => (
                        <div key={day.id ?? index} className="rounded-md border border-slate-200 bg-slate-50/60 p-4 sm:p-5">
                            <div className="mb-4 flex items-center justify-between gap-3">
                                <div className="flex items-center gap-2">
                                    <span className="flex h-7 w-7 items-center justify-center rounded-md bg-primary text-xs font-bold text-white">{index + 1}</span>
                                    <h4 className="text-sm font-semibold text-slate-900">Program day {index + 1}</h4>
                                </div>
                                <button type="button" onClick={() => removeDay(index)} title={`Remove day ${index + 1}`} className="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-600">
                                    <Trash2 size={16} />
                                </button>
                            </div>

                            <div className="grid gap-4 md:grid-cols-2">
                                <DayField label="Day title" value={day.title} onChange={(value) => updateDay(index, 'title', value)} error={errors[`days.${index}.title`]} placeholder="Opening session" />
                                <DayField label="Day theme" value={day.theme} onChange={(value) => updateDay(index, 'theme', value)} error={errors[`days.${index}.theme`]} placeholder="Leading with clarity" />
                                <DayField label="Starts" type="datetime-local" value={day.start_at} onChange={(value) => updateDay(index, 'start_at', value)} error={errors[`days.${index}.start_at`]} required />
                                <DayField label="Ends (optional)" type="datetime-local" value={day.end_at ?? ''} onChange={(value) => updateDay(index, 'end_at', value || null)} error={errors[`days.${index}.end_at`]} />
                                <div>
                                    <label className="mb-1 block text-sm font-medium text-slate-700">Delivery mode</label>
                                    <select value={day.mode} onChange={(event) => updateDay(index, 'mode', event.target.value as EventMode)} className="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm">
                                        <option value="online">Online</option>
                                        <option value="offline">In person</option>
                                        <option value="hybrid">Hybrid</option>
                                    </select>
                                </div>
                                <DayField label="Venue name" value={day.venue_name} onChange={(value) => updateDay(index, 'venue_name', value)} error={errors[`days.${index}.venue_name`]} placeholder="Main auditorium" />
                                {day.mode !== 'online' && (
                                    <div className="md:col-span-2">
                                        <DayField icon={<MapPin size={15} />} label="Physical address" value={day.physical_address} onChange={(value) => updateDay(index, 'physical_address', value)} error={errors[`days.${index}.physical_address`]} placeholder="Street, city, state" required />
                                    </div>
                                )}
                                {day.mode !== 'offline' && (
                                    <div className="md:col-span-2">
                                        <DayField label="Meeting link" type="url" value={day.meeting_link} onChange={(value) => updateDay(index, 'meeting_link', value)} error={errors[`days.${index}.meeting_link`]} placeholder="https://meet.google.com/..." />
                                    </div>
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </section>
    );
}

function DayField({ label, value, onChange, error, type = 'text', placeholder, required, icon }: {
    label: string;
    value: string;
    onChange: (value: string) => void;
    error?: string;
    type?: string;
    placeholder?: string;
    required?: boolean;
    icon?: React.ReactNode;
}) {
    return (
        <div>
            <label className="mb-1 flex items-center gap-1.5 text-sm font-medium text-slate-700">{icon}{label}{required && <span className="text-red-500">*</span>}</label>
            <input type={type} value={value} onChange={(event) => onChange(event.target.value)} placeholder={placeholder} required={required} className={`block w-full rounded-md border bg-white px-3 py-2 text-sm focus:border-primary focus:ring-primary ${error ? 'border-red-500' : 'border-slate-300'}`} />
            {error && <p className="mt-1 text-xs text-red-600">{error}</p>}
        </div>
    );
}
