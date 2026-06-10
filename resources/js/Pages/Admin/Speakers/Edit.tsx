import { useState, FormEvent, ChangeEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import Input from '@/Components/Input';

interface User {
  id: number;
  name: string;
  email: string;
  phone: string;
  headline: string;
  linkedin: string;
  website: string;
  photo: string;
}

interface Speaker {
  id: number;
  user: User;
  bio: string;
  organization: string;
  status: string;
  updated_at: string;
}

interface EditSpeakerProps {
  speaker: Speaker;
}

interface FormData {
  name: string;
  headline: string;
  organization: string;
  email: string;
  phone: string;
  linkedin: string;
  website: string;
  bio: string;
  status: string;
}

export default function EditSpeaker({ speaker }: EditSpeakerProps) {
  const { sideLinks, errors } = usePage().props as any;
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [formData, setFormData] = useState<FormData>({
    name: speaker.user.name || '',
    headline: speaker.user.headline || '',
    organization: speaker.organization || '',
    email: speaker.user.email || '',
    phone: speaker.user.phone || '',
    linkedin: speaker.user.linkedin || '',
    website: speaker.user.website || '',
    bio: speaker.bio || '',
    status: speaker.status || 'active',
  });
  const [photoFile, setPhotoFile] = useState<File | null>(null);

  const handleInputChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handlePhotoChange = (e: ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      setPhotoFile(e.target.files[0]);
    }
  };

  const handleSubmit = (e: FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);

    const data = new FormData();

    // Append all form fields
    Object.entries(formData).forEach(([key, value]) => {
      if (value !== '') {
        data.append(key, value);
      }
    });

    // Append photo file if selected
    if (photoFile) {
      data.append('photo', photoFile);
    }

    // Add _method for Laravel PUT request
    data.append('_method', 'PUT');

    router.post(route('admin.speakers.update', speaker.id), data, {
      preserveScroll: true,
      onFinish: () => {
        setIsSubmitting(false);
      },
    });
  };

  const updatedDate = new Date(speaker.updated_at).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });

  return (
    <DashboardLayout sideLinks={sideLinks}>
      <Head title="Edit Speaker Profile" />

      <div className="py-10 px-4 sm:px-6 mx-auto">
        {/* Page Header */}
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
          <div className="flex items-center gap-3">
            <div className="p-2.5 rounded-lg bg-primary/10">
              <i className="fas fa-microphone w-6 h-6 text-[#002147]"></i>
            </div>
            <div>
              <h2 className="text-2xl font-extrabold text-[#002147]">Update {speaker.user.name}</h2>
              <p className="text-sm text-slate-500 mt-1">Edit speaker details and update their public profile.</p>
            </div>
          </div>

          <Link
            href={route('admin.speakers.index')}
            className="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-[#002147] bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#002147] transition"
          >
            <i className="fas fa-arrow-left w-4 h-4 mr-2"></i>
            Back
          </Link>
        </div>

        {/* Form Card */}
        <div className="bg-white border border-slate-200 rounded-lg shadow-sm p-6 md:p-8">
          <form onSubmit={handleSubmit} className="space-y-8">
            {/* Section: Personal & Contact Info */}
            <div>
              <h3 className="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-200">
                👤 Personal & Contact Info
              </h3>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <Input
                  label="Full Name"
                  name="name"
                  value={formData.name}
                  onChange={handleInputChange}
                  error={errors?.name}
                  icon="user"
                  required
                />
                <Input
                  label="Professional Title"
                  name="headline"
                  value={formData.headline}
                  onChange={handleInputChange}
                  error={errors?.headline}
                  icon="briefcase"
                />
                <Input
                  label="Organization"
                  name="organization"
                  value={formData.organization}
                  onChange={handleInputChange}
                  error={errors?.organization}
                  icon="building"
                />
                <Input
                  label="Email Address"
                  name="email"
                  type="email"
                  value={formData.email}
                  onChange={handleInputChange}
                  error={errors?.email}
                  icon="envelope"
                  required
                />
                <Input
                  label="Phone Number"
                  name="phone"
                  type="tel"
                  value={formData.phone}
                  onChange={handleInputChange}
                  error={errors?.phone}
                  icon="phone"
                />
                <Input
                  label="LinkedIn Profile"
                  name="linkedin"
                  type="url"
                  value={formData.linkedin}
                  onChange={handleInputChange}
                  error={errors?.linkedin}
                  placeholder="https://linkedin.com/in/..."
                />
                <Input
                  label="Personal Website"
                  name="website"
                  type="url"
                  value={formData.website}
                  onChange={handleInputChange}
                  error={errors?.website}
                  placeholder="https://yoursite.com"
                />
              </div>
            </div>

            {/* Section: Speaker Bio */}
            <div>
              <h3 className="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-200">
                📝 Speaker Bio
              </h3>
              <div>
                <label htmlFor="bio" className="block text-sm font-medium text-slate-700 mb-2">
                  Bio (Public-facing description)
                </label>
                <textarea
                  id="bio"
                  name="bio"
                  rows={6}
                  value={formData.bio}
                  onChange={handleInputChange}
                  className="block w-full p-3 text-sm text-slate-900 bg-slate-50 rounded-lg border border-slate-300 focus:ring-2 focus:ring-[#002147] focus:border-[#002147] resize-none"
                  placeholder="Write a compelling bio that highlights expertise, achievements, and speaking style..."
                />
                {errors?.bio && (
                  <p className="text-sm text-red-500 mt-1">{errors.bio}</p>
                )}
                <p className="mt-2 text-xs text-slate-500">
                  Ideal length: 100–300 words. This will be shown on event pages.
                </p>
              </div>
            </div>

            {/* Section: Photo Upload */}
            <div>
              <h3 className="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-200">
                🖼️ Profile Photo
              </h3>
              <div>
                <label htmlFor="photo" className="block text-sm font-medium text-slate-700 mb-2">
                  Upload Photo (JPG/PNG)
                </label>
                <input
                  type="file"
                  name="photo"
                  id="photo"
                  accept=".jpg,.jpeg,.png,.JPG,.PNG"
                  onChange={handlePhotoChange}
                  className="block w-full text-sm text-slate-900 bg-slate-50 rounded-lg border border-slate-300 focus:ring-2 focus:ring-[#002147] focus:border-[#002147]
                    file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium
                    file:bg-primary file:text-white hover:file:bg-[#FF0000] transition"
                />
                {errors?.photo && (
                  <p className="text-sm text-red-500 mt-1">{errors.photo}</p>
                )}

                {speaker.user.photo && (
                  <div className="mt-3 flex items-center gap-2">
                    <img
                      src={`/storage/${speaker.user.photo}`}
                      alt="Current speaker photo"
                      className="w-16 h-16 object-cover rounded-lg border"
                    />
                    <span className="text-sm text-slate-500">
                      Current photo uploaded on {updatedDate}
                    </span>
                  </div>
                )}
              </div>
            </div>

            {/* Section: Speaker Status */}
            <div>
              <h3 className="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b border-slate-200">
                📋 Speaker Status
              </h3>
              <div>
                <label htmlFor="status" className="block text-sm font-medium text-slate-700 mb-2">
                  Status
                </label>
                <div className="relative">
                  <i className="fas fa-check-circle absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                  <select
                    id="status"
                    name="status"
                    value={formData.status}
                    onChange={handleInputChange}
                    className="block w-full pl-10 pr-3 py-2.5 text-sm text-slate-900 bg-slate-50 rounded-lg border border-slate-300 focus:ring-2 focus:ring-[#002147] focus:border-[#002147]"
                  >
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="pending">Pending</option>
                  </select>
                </div>
                {errors?.status && (
                  <p className="text-sm text-red-500 mt-1">{errors.status}</p>
                )}
              </div>
            </div>

            {/* Action Buttons */}
            <div className="pt-6 border-t border-slate-200 flex flex-col sm:flex-row justify-end gap-3">
              <Link
                href={route('admin.speakers.index')}
                className="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition text-center"
              >
                Cancel
              </Link>
              <button
                type="submit"
                disabled={isSubmitting}
                className="px-6 py-2.5 inline-flex items-center text-sm font-medium text-white bg-primary rounded-lg hover:bg-[#FF0000] focus:ring-4 focus:ring-blue-300 focus:outline-none transition disabled:opacity-50 disabled:cursor-not-allowed justify-center"
              >
                <i className="fas fa-save w-4 h-4 mr-2"></i>
                {isSubmitting ? 'Updating...' : 'Update Speaker Profile'}
              </button>
            </div>
          </form>
        </div>
      </div>
    </DashboardLayout>
  );
}
