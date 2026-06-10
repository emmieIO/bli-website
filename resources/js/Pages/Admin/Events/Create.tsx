import { useState, FormEvent, ChangeEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Input from '@/Components/Input';
import Textarea from '@/Components/Textarea';
import RichTextEditor from '@/Components/RichTextEditor';

interface FormData {
  title: string;
  status: 'draft' | 'review' | 'published' | 'registration_open' | 'registration_closed' | 'live' | 'completed' | 'cancelled' | 'archived' | '';
  mode: 'online' | 'offline' | 'hybrid' | '';
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
}

interface ProgramMetadataFormData {
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

export default function CreateEvent() {
  const { auth, sideLinks, errors } = usePage().props as any;
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [formData, setFormData] = useState<FormData>({
    title: '',
    status: 'draft',
    mode: '',
    attendee_slots: '',
    theme: '',
    start_date: '',
    end_date: '',
    location: '',
    physical_address: '',
    contact_email: '',
    entry_fee: '0',
    description: '',
    is_active: false,
    is_published: false,
    is_allowing_application: false,
    is_featured: false,
  });
  const [programMetadata, setProgramMetadata] = useState<ProgramMetadataFormData>({
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
  });
  const [programCover, setProgramCover] = useState<File | null>(null);

  const handleInputChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    const { name, value, type } = e.target;

    if (type === 'checkbox') {
      const checked = (e.target as HTMLInputElement).checked;
      setFormData(prev => ({ ...prev, [name]: checked }));
    } else {
      setFormData(prev => ({ ...prev, [name]: value }));
    }
  };

  const handleFileChange = (e: ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      setProgramCover(e.target.files[0]);
    }
  };

  const handleMetadataChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    const { name, value, type } = e.target;

    if (type === 'checkbox') {
      const checked = (e.target as HTMLInputElement).checked;
      setProgramMetadata((prev) => ({ ...prev, [name]: checked }));
      return;
    }

    setProgramMetadata((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e: FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);

    const data = new FormData();

    // Append all form fields
    Object.entries(formData).forEach(([key, value]) => {
      if (typeof value === 'boolean') {
        if (value) data.append(key, '1');
      } else {
        data.append(key, value);
      }
    });

    // Add creator_id (current authenticated user)
    if (auth?.user?.id) {
      data.append('creator_id', auth.user.id.toString());
    }

    // Append file if selected
    if (programCover) {
      data.append('program_cover', programCover);
    }

    Object.entries(programMetadata).forEach(([key, value]) => {
      if (typeof value === 'boolean') {
        data.append(`metadata[${key}]`, value ? '1' : '0');
        return;
      }

      if (value !== '') {
        data.append(`metadata[${key}]`, value);
      }
    });

    router.post(route('admin.events.store'), data, {
      preserveScroll: true,
      onFinish: () => {
        setIsSubmitting(false);
      },
    });
  };

  const shouldShowLocation = formData.mode === 'online' || formData.mode === 'hybrid';
  const shouldShowPhysicalAddress = formData.mode === 'offline' || formData.mode === 'hybrid';
  const isDiscipleshipTrack = programMetadata.program_type === 'discipleship_track';

  return (
    <DashboardLayout sideLinks={sideLinks}>
      <Head title="Create New Event" />

      <div className="p-6">
        {/* Page Header */}
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
          <div className="flex items-center gap-3">
            <div>
              <h1 className="text-2xl font-extrabold text-slate-900">Create New Event</h1>
              <p className="text-sm text-slate-500 mt-1">Fill in the details to create a new event.</p>
            </div>
          </div>

          <Link
            href={route('admin.events.index')}
            className="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition shadow-sm"
          >
            <i className="fas fa-arrow-left w-4 h-4 mr-2"></i>
            Back to Events
          </Link>
        </div>

        {/* Form Card */}
        <div className="bg-white border border-slate-200 rounded-lg shadow-sm p-6 md:p-8">
          <form onSubmit={handleSubmit} className="space-y-8">
            {/* Section: Basic Event Info */}
            <div>
              <h3 className="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-200">
                Event Basics
              </h3>
              <div className="space-y-6">
                <Input
                  label="Event Title"
                  name="title"
                  value={formData.title}
                  onChange={handleInputChange}
                  error={errors?.title}
                  placeholder="Enter the full name of your event"
                  required
                />

                <Input
                  label="Event Theme"
                  name="theme"
                  value={formData.theme}
                  onChange={handleInputChange}
                  error={errors?.theme}
                  placeholder="e.g., Leadership Development, Digital Transformation"
                  required
                />

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label htmlFor="status" className="block text-sm font-medium text-slate-700 mb-1">
                      Operational Status <span className="text-red-500 ml-1">*</span>
                    </label>
                    <select
                      id="status"
                      name="status"
                      value={formData.status}
                      onChange={handleInputChange}
                      required
                      className={`block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 focus:outline-none text-sm ${errors?.status ? 'border-red-500' : 'border-slate-300'}`}
                    >
                      <option value="draft">Draft</option>
                      <option value="review">Review</option>
                      <option value="published">Published</option>
                      <option value="registration_open">Registration Open</option>
                      <option value="registration_closed">Registration Closed</option>
                      <option value="live">Live</option>
                      <option value="completed">Completed</option>
                      <option value="cancelled">Cancelled</option>
                      <option value="archived">Archived</option>
                    </select>
                    {errors?.status && (
                      <p className="text-sm text-red-500 mt-1">{errors.status}</p>
                    )}
                  </div>

                  <div>
                    <label htmlFor="mode" className="block text-sm font-medium text-slate-700 mb-1">
                      Event Mode <span className="text-red-500 ml-1">*</span>
                    </label>
                    <select
                      id="mode"
                      name="mode"
                      value={formData.mode}
                      onChange={handleInputChange}
                      required
                      className={`block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 focus:outline-none text-sm ${errors?.mode ? 'border-red-500' : 'border-slate-300'}`}
                    >
                      <option value="">Select mode</option>
                      <option value="online">Online</option>
                      <option value="offline">Offline</option>
                      <option value="hybrid">Hybrid</option>
                    </select>
                    {errors?.mode && (
                      <p className="text-sm text-red-500 mt-1">{errors.mode}</p>
                    )}
                  </div>

                  <Input
                    label="Attendee Slots"
                    name="attendee_slots"
                    type="number"
                    min="1"
                    value={formData.attendee_slots}
                    onChange={handleInputChange}
                    error={errors?.attendee_slots}
                    placeholder="Enter no. of available slots"
                  />
                </div>
              </div>
            </div>

            {/* Section: Date & Location */}
            <div>
              <h3 className="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-200">
                Date & Location
              </h3>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <Input
                  label="Start Date & Time"
                  name="start_date"
                  type="datetime-local"
                  value={formData.start_date}
                  onChange={handleInputChange}
                  error={errors?.start_date}
                  required
                />

                <Input
                  label="End Date & Time"
                  name="end_date"
                  type="datetime-local"
                  value={formData.end_date}
                  onChange={handleInputChange}
                  error={errors?.end_date}
                  required
                />

                {shouldShowLocation && (
                  <Input
                    label="Location (Meeting Link)"
                    name="location"
                    value={formData.location}
                    onChange={handleInputChange}
                    error={errors?.location}
                    placeholder="Paste meeting link"
                  />
                )}

                {shouldShowPhysicalAddress && (
                  <Input
                    label="Full Physical Address (if offline/hybrid)"
                    name="physical_address"
                    value={formData.physical_address}
                    onChange={handleInputChange}
                    error={errors?.physical_address}
                    placeholder="Street, City, State, Country"
                  />
                )}
              </div>
            </div>

            {/* Section: Contact & Pricing */}
            <div>
              <h3 className="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-200">
                Contact & Pricing
              </h3>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <Input
                  label="Contact Email"
                  name="contact_email"
                  type="email"
                  value={formData.contact_email}
                  onChange={handleInputChange}
                  error={errors?.contact_email}
                  placeholder="organizer@event.com"
                />

                <Input
                  label="Entry Fee (₦)"
                  name="entry_fee"
                  type="number"
                  min="0"
                  step="0.01"
                  value={formData.entry_fee}
                  onChange={handleInputChange}
                  error={errors?.entry_fee}
                  placeholder="0.00"
                />
              </div>
            </div>

            <div>
              <h3 className="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-200">
                Program Setup
              </h3>
              <div className="space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label htmlFor="program_type" className="block text-sm font-medium text-slate-700 mb-1">
                      Program Type
                    </label>
                    <select
                      id="program_type"
                      name="program_type"
                      value={programMetadata.program_type}
                      onChange={handleMetadataChange}
                      className="block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 focus:outline-none text-sm"
                    >
                      <option value="general_event">General Event</option>
                      <option value="discipleship_track">Discipleship Track</option>
                    </select>
                  </div>

                  <Input
                    label="Program Code"
                    name="program_code"
                    value={programMetadata.program_code}
                    onChange={handleMetadataChange}
                    error={errors?.['metadata.program_code']}
                    placeholder="e.g. BDT"
                  />
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label htmlFor="registration_mode" className="block text-sm font-medium text-slate-700 mb-1">
                      Intake Mode
                    </label>
                    <select
                      id="registration_mode"
                      name="registration_mode"
                      value={programMetadata.registration_mode}
                      onChange={handleMetadataChange}
                      className="block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 focus:outline-none text-sm"
                    >
                      <option value="open">Open Registration</option>
                      <option value="selective">Selective Admission</option>
                    </select>
                    {errors?.['metadata.registration_mode'] && (
                      <p className="text-sm text-red-500 mt-1">{errors['metadata.registration_mode']}</p>
                    )}
                  </div>

                  <Input
                    label="Cohort Duration (Weeks)"
                    name="cohort_duration_weeks"
                    type="number"
                    min="1"
                    max="52"
                    value={programMetadata.cohort_duration_weeks}
                    onChange={handleMetadataChange}
                    error={errors?.['metadata.cohort_duration_weeks']}
                    placeholder="6"
                  />
                </div>

                <div className="rounded-lg border border-slate-200 bg-slate-50 p-4">
                  <label className="inline-flex items-center">
                    <input
                      type="checkbox"
                      name="requires_screening"
                      checked={programMetadata.requires_screening}
                      onChange={handleMetadataChange}
                      className="rounded border-slate-300 text-primary focus:ring-primary"
                    />
                    <span className="ml-2 text-sm font-medium text-slate-700">Require screening before admission</span>
                  </label>
                  <p className="mt-2 text-xs text-slate-500">
                    Use this for application review, interviews, pastoral approval, or other selective intake steps.
                  </p>
                </div>

                {(programMetadata.requires_screening || programMetadata.registration_mode === 'selective') && (
                  <Textarea
                    label="Screening Note"
                    name="screening_note"
                    value={programMetadata.screening_note}
                    onChange={handleMetadataChange}
                    error={errors?.['metadata.screening_note']}
                    placeholder="Explain that payment does not guarantee admission and summarize the screening process."
                  />
                )}

                {isDiscipleshipTrack && (
                  <>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <Input
                        label="Group Model"
                        name="group_model"
                        value={programMetadata.group_model}
                        onChange={handleMetadataChange}
                        error={errors?.['metadata.group_model']}
                        placeholder="e.g. Cluster-based accountability groups"
                      />
                      <Input
                        label="Central Teaching Schedule"
                        name="central_teaching_schedule"
                        value={programMetadata.central_teaching_schedule}
                        onChange={handleMetadataChange}
                        error={errors?.['metadata.central_teaching_schedule']}
                        placeholder="e.g. Weekly, 60-90 mins"
                      />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <Input
                        label="Group Meeting Schedule"
                        name="group_meeting_schedule"
                        value={programMetadata.group_meeting_schedule}
                        onChange={handleMetadataChange}
                        error={errors?.['metadata.group_meeting_schedule']}
                        placeholder="e.g. Weekly reflection and accountability"
                      />
                      <Input
                        label="Weekly Prayer Target (Minutes)"
                        name="weekly_prayer_target_minutes"
                        type="number"
                        min="0"
                        value={programMetadata.weekly_prayer_target_minutes}
                        onChange={handleMetadataChange}
                        error={errors?.['metadata.weekly_prayer_target_minutes']}
                        placeholder="420"
                      />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div className="grid grid-cols-2 gap-4">
                        <Input
                          label="Evangelism Min"
                          name="weekly_evangelism_target_min"
                          type="number"
                          min="0"
                          value={programMetadata.weekly_evangelism_target_min}
                          onChange={handleMetadataChange}
                          error={errors?.['metadata.weekly_evangelism_target_min']}
                          placeholder="3"
                        />
                        <Input
                          label="Evangelism Max"
                          name="weekly_evangelism_target_max"
                          type="number"
                          min="0"
                          value={programMetadata.weekly_evangelism_target_max}
                          onChange={handleMetadataChange}
                          error={errors?.['metadata.weekly_evangelism_target_max']}
                          placeholder="5"
                        />
                      </div>

                      <div className="grid grid-cols-2 gap-4">
                        <Input
                          label="Discipleship Min"
                          name="weekly_discipleship_target_min"
                          type="number"
                          min="0"
                          value={programMetadata.weekly_discipleship_target_min}
                          onChange={handleMetadataChange}
                          error={errors?.['metadata.weekly_discipleship_target_min']}
                          placeholder="1"
                        />
                        <Input
                          label="Discipleship Max"
                          name="weekly_discipleship_target_max"
                          type="number"
                          min="0"
                          value={programMetadata.weekly_discipleship_target_max}
                          onChange={handleMetadataChange}
                          error={errors?.['metadata.weekly_discipleship_target_max']}
                          placeholder="3"
                        />
                      </div>
                    </div>
                  </>
                )}

                <div className="grid grid-cols-1 gap-6">
                  <Input
                    label="Attendee Meeting Link"
                    name="meeting_link"
                    value={programMetadata.meeting_link}
                    onChange={handleMetadataChange}
                    error={errors?.['metadata.meeting_link']}
                    placeholder="https://..."
                  />

                  <Textarea
                    label="Access Notes"
                    name="access_notes"
                    value={programMetadata.access_notes}
                    onChange={handleMetadataChange}
                    error={errors?.['metadata.access_notes']}
                    placeholder="Orientation notes, onboarding expectations, reporting process, or joining instructions."
                  />
                </div>
              </div>
            </div>

            {/* Section: Media */}
            <div>
              <h3 className="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-200">
                Cover Image
              </h3>
              <div>
                <label htmlFor="program_cover" className="block text-sm font-medium text-slate-700 mb-2">
                  Upload Cover Image (JPG/PNG)
                </label>
                <input
                  type="file"
                  name="program_cover"
                  id="program_cover"
                  accept=".jpg,.jpeg,.png,.JPG,.PNG"
                  onChange={handleFileChange}
                  className={`block w-full text-sm text-slate-900 bg-slate-50 rounded-lg border focus:ring-2 focus:ring-primary focus:border-primary
                    file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium
                    file:bg-primary file:text-white hover:file:bg-[#003366] transition ${errors?.program_cover ? 'border-red-500' : 'border-slate-300'}`}
                />
                {errors?.program_cover && (
                  <p className="text-sm text-red-500 mt-1">{errors.program_cover}</p>
                )}
                <p className="mt-2 text-xs text-slate-500">Recommended size: 1200x600px. Max file size: 5MB.</p>
              </div>
            </div>

            {/* Section: Description */}
            <div>
              <h3 className="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-200">
                Event Description
              </h3>
              <RichTextEditor
                label="Description"
                value={formData.description}
                onChange={(value) => setFormData(prev => ({ ...prev, description: value }))}
                error={errors?.description}
                placeholder="Describe your event, audience, highlights, and what attendees can expect..."
                required
              />
            </div>

            {/* Section: Options */}
            <div>
              <h3 className="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-200">Visibility & Intake</h3>
              <div className="flex flex-wrap items-center gap-6 p-4 bg-slate-50 rounded-lg">
                <label className="inline-flex items-center">
                  <input
                    type="checkbox"
                    name="is_allowing_application"
                    checked={formData.is_allowing_application}
                    onChange={handleInputChange}
                    className="rounded border-slate-300 text-primary focus:ring-primary"
                  />
                  <span className="ml-2 text-sm font-medium text-slate-700">Allow Speaker Applications</span>
                </label>

                <label className="inline-flex items-center">
                  <input
                    type="checkbox"
                    name="is_featured"
                    checked={formData.is_featured}
                    onChange={handleInputChange}
                    className="rounded border-slate-300 text-primary focus:ring-primary"
                  />
                  <span className="ml-2 text-sm font-medium text-slate-700">Is featured</span>
                </label>
              </div>
              <p className="mt-2 text-xs text-slate-500">
                Status controls the public stage of the event. Use the switches here for speaker intake and homepage highlighting.
              </p>
            </div>

            {/* Submit Button */}
            <div className="pt-6 border-t border-slate-200 flex flex-col sm:flex-row justify-end gap-3">
              <Link
                href={route('admin.events.index')}
                className="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition text-center"
              >
                Cancel
              </Link>
              <button
                type="submit"
                disabled={isSubmitting}
                className="px-6 py-2.5 inline-flex items-center justify-center text-sm font-medium text-white bg-primary hover:bg-primary-600 focus:ring-4 focus:ring-primary-500/20 focus:outline-none transition disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
              >
                <i className="fas fa-save w-4 h-4 mr-2"></i>
                {isSubmitting ? 'Saving...' : 'Save Event'}
              </button>
            </div>
          </form>
        </div>
      </div>
    </DashboardLayout>
  );
}
