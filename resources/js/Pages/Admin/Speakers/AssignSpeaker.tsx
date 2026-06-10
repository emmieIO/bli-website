import { useState, FormEvent, ChangeEvent } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface User {
  id: number;
  name: string;
  photo: string;
}

interface Speaker {
  id: number;
  user: User;
  organization: string;
  name: string;
  photo: string;
  title: string;
}

interface Event {
  id: number;
  title: string;
}

interface AssignSpeakerProps {
  event: Event;
  speakers: Speaker[];
  assignedSpeakerIds: number[];
}

export default function AssignSpeaker({ event, speakers, assignedSpeakerIds }: AssignSpeakerProps) {
  const { sideLinks } = usePage().props as any;
  const [selectedSpeakerIds, setSelectedSpeakerIds] = useState<number[]>(assignedSpeakerIds || []);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleCheckboxChange = (speakerId: number) => {
    setSelectedSpeakerIds(prev => {
      if (prev.includes(speakerId)) {
        return prev.filter(id => id !== speakerId);
      } else {
        return [...prev, speakerId];
      }
    });
  };

  const handleSubmit = (e: FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);

    router.post(route('admin.events.assign-speaker', event.id), {
      speaker_ids: selectedSpeakerIds,
    }, {
      preserveScroll: true,
      onFinish: () => {
        setIsSubmitting(false);
      },
    });
  };

  const getSpeakerPhoto = (speaker: Speaker) => {
    const photo = speaker.photo || speaker.user?.photo;
    if (photo) {
      return `/storage/${photo}`;
    }
    const name = speaker.name || speaker.user?.name || 'Speaker';
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=00275E&color=fff`;
  };

  const getSpeakerName = (speaker: Speaker) => {
    return speaker.name || speaker.user?.name || 'Unknown Speaker';
  };

  return (
    <DashboardLayout sideLinks={sideLinks}>
      <Head title={`Assign Speakers - ${event.title}`} />

      <div className="py-8">
        <div className="">
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-2xl font-bold text-slate-700 flex items-center gap-2">
              <i className="fas fa-user-plus w-6 h-6 text-teal-600"></i>
              Assign Speakers to {event.title}
            </h2>
            <Link
              href={route('admin.events.show', event.id)}
              className="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 text-sm rounded hover:bg-slate-200"
            >
              <i className="fas fa-arrow-left w-4 h-4 mr-2"></i> Back to Event
            </Link>
          </div>

          <div className="bg-white shadow rounded-lg p-6">
            <form onSubmit={handleSubmit}>
              <div className="mb-6">
                <label className="block text-sm font-medium text-slate-700 mb-2">
                  Select Speakers
                </label>
                <div className="border border-slate-200 rounded-lg max-h-60 overflow-y-auto divide-y">
                  {speakers.length > 0 ? (
                    speakers.map((speaker) => (
                      <label
                        key={speaker.id}
                        className="flex items-center gap-3 p-4 hover:bg-slate-50 cursor-pointer"
                      >
                        <input
                          type="checkbox"
                          value={speaker.id}
                          checked={selectedSpeakerIds.includes(speaker.id)}
                          onChange={() => handleCheckboxChange(speaker.id)}
                          className="text-teal-600 rounded focus:ring-teal-500"
                        />
                        <img
                          src={getSpeakerPhoto(speaker)}
                          className="w-10 h-10 rounded-full object-cover"
                          alt={getSpeakerName(speaker)}
                        />
                        <div className="flex-1">
                          <div className="font-medium text-slate-700">
                            {getSpeakerName(speaker)}
                          </div>
                          <div className="text-sm text-slate-500">
                            {speaker.organization || 'Independent'}
                            {speaker.title && ` · ${speaker.title}`}
                          </div>
                        </div>
                      </label>
                    ))
                  ) : (
                    <div className="p-4 text-center text-slate-500 text-sm">
                      No available speakers found.
                    </div>
                  )}
                </div>
                <p className="mt-2 text-xs text-slate-500">
                  {selectedSpeakerIds.length} speaker(s) selected
                </p>
              </div>

              <div className="flex justify-end">
                <button
                  type="submit"
                  disabled={isSubmitting}
                  className="bg-teal-600 flex items-center gap-3 hover:bg-teal-700 text-white px-6 py-2 rounded-md text-sm font-medium shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <i className="fas fa-link w-4 h-4"></i>
                  <span>{isSubmitting ? 'Assigning...' : 'Assign Selected'}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
