import { useEffect, useRef } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { AlertCircle } from 'lucide-react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Input from '@/Components/Input';
import Textarea from '@/Components/Textarea';
import RichTextEditor from '@/Components/RichTextEditor';
import { useEventForm } from '@/Components/Events/useEventForm';

export default function CreateEvent() {
  const { auth, sideLinks } = usePage().props as any;
  const { form, handleInputChange, handleMetadataChange, handleFileChange, create: handleSubmit } = useEventForm(undefined, auth?.user?.id ?? null);
  const { data: formData, processing: isSubmitting, errors } = form;
  const programMetadata = formData.metadata;
  const formRef = useRef<HTMLFormElement>(null);
  const errorSummaryRef = useRef<HTMLDivElement>(null);
  const errorEntries = Object.entries(errors).filter((entry): entry is [string, string] => Boolean(entry[1]));

  const focusValidationField = (errorKey: string, shouldScroll = true) => {
    const formElement = formRef.current;

    if (!formElement) return;

    const fieldName = errorKey.startsWith('metadata.') ? errorKey.slice('metadata.'.length) : errorKey;
    const field = formElement.querySelector<HTMLElement>(`[name="${fieldName}"]`);
    const fieldGroup = formElement.querySelector<HTMLElement>(`[data-validation-field="${errorKey}"]`);
    const focusTarget = field ?? fieldGroup?.querySelector<HTMLElement>('.ProseMirror, input, textarea, select') ?? fieldGroup;

    if (shouldScroll) {
      focusTarget?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    focusTarget?.focus({ preventScroll: true });
  };

  useEffect(() => {
    const firstError = errorEntries[0]?.[0];

    if (firstError) {
      requestAnimationFrame(() => {
        errorSummaryRef.current?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        focusValidationField(firstError, false);
      });
    }
  }, [errors]);

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
          <form ref={formRef} onSubmit={handleSubmit} noValidate className="space-y-8">
            {errorEntries.length > 0 && (
              <div
                ref={errorSummaryRef}
                role="alert"
                aria-live="polite"
                className="scroll-mt-20 border-l-4 border-red-500 bg-red-50 px-4 py-3 text-red-900"
              >
                <div className="flex items-start gap-3">
                  <AlertCircle className="mt-0.5 h-5 w-5 shrink-0" aria-hidden="true" />
                  <div>
                    <h2 className="text-sm font-semibold">Please correct the highlighted fields</h2>
                    <ul className="mt-2 space-y-1 text-sm">
                      {errorEntries.map(([field, message]) => (
                        <li key={field}>
                          <button
                            type="button"
                            onClick={() => focusValidationField(field)}
                            className="text-left underline decoration-red-300 underline-offset-2 hover:decoration-red-700"
                          >
                            {message}
                          </button>
                        </li>
                      ))}
                    </ul>
                  </div>
                </div>
              </div>
            )}

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
                      aria-invalid={errors?.status ? true : undefined}
                      aria-describedby={errors?.status ? 'status-error' : undefined}
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
                      <p id="status-error" className="text-sm text-red-600 mt-1">{errors.status}</p>
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
                      aria-invalid={errors?.mode ? true : undefined}
                      aria-describedby={errors?.mode ? 'mode-error' : undefined}
                      className={`block w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 focus:outline-none text-sm ${errors?.mode ? 'border-red-500' : 'border-slate-300'}`}
                    >
                      <option value="">Select mode</option>
                      <option value="online">Online</option>
                      <option value="offline">Offline</option>
                      <option value="hybrid">Hybrid</option>
                    </select>
                    {errors?.mode && (
                      <p id="mode-error" className="text-sm text-red-600 mt-1">{errors.mode}</p>
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
                      aria-invalid={errors?.['metadata.program_type'] ? true : undefined}
                      aria-describedby={errors?.['metadata.program_type'] ? 'program_type-error' : undefined}
                      className="block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 focus:outline-none text-sm"
                    >
                      <option value="general_event">General Event</option>
                      <option value="discipleship_track">Discipleship Track</option>
                    </select>
                    {errors?.['metadata.program_type'] && (
                      <p id="program_type-error" className="mt-1 text-sm text-red-600">{errors['metadata.program_type']}</p>
                    )}
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
                      aria-invalid={errors?.['metadata.registration_mode'] ? true : undefined}
                      aria-describedby={errors?.['metadata.registration_mode'] ? 'registration_mode-error' : undefined}
                      className="block w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 focus:outline-none text-sm"
                    >
                      <option value="open">Open Registration</option>
                      <option value="selective">Selective Admission</option>
                    </select>
                    {errors?.['metadata.registration_mode'] && (
                      <p id="registration_mode-error" className="text-sm text-red-600 mt-1">{errors['metadata.registration_mode']}</p>
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
                  aria-invalid={errors?.program_cover ? true : undefined}
                  aria-describedby={errors?.program_cover ? 'program_cover-error' : undefined}
                  className={`block w-full text-sm text-slate-900 bg-slate-50 rounded-lg border focus:ring-2 focus:ring-primary focus:border-primary
                    file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium
                    file:bg-primary file:text-white hover:file:bg-[#003366] transition ${errors?.program_cover ? 'border-red-500' : 'border-slate-300'}`}
                />
                {errors?.program_cover && (
                  <p id="program_cover-error" className="text-sm text-red-600 mt-1">{errors.program_cover}</p>
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
                name="description"
                label="Description"
                value={formData.description}
                onChange={(value) => {
                  form.setData('description', value);
                  form.clearErrors('description');
                }}
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
                    name="require_sign_up"
                    checked={formData.require_sign_up}
                    onChange={handleInputChange}
                    className="rounded border-slate-300 text-primary focus:ring-primary"
                  />
                  <span className="ml-2 text-sm font-medium text-slate-700">Require account sign-up</span>
                </label>

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
                Turn off account sign-up only for free events where email-only attendance and reminders are enough.
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
