import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState } from 'react';
import Textarea from '@/Components/Textarea';
import Button from '@/Components/Button';

interface User {
  id: number;
  name: string;
  email: string;
}

interface Speaker {
  id: number;
  name: string;
  user: User;
}

interface Event {
  id: number;
  title: string;
  start_date: string;
  end_date: string;
}

interface Application {
  id: number;
  topic_title: string;
  topic_description?: string;
  session_format: {
    value: string;
  };
  approved_at?: string;
  speaker: Speaker;
  event: Event;
}

interface ApprovedApplicationsProps {
  applications: Application[];
}

export default function ApprovedApplications({ applications }: ApprovedApplicationsProps) {
  const { sideLinks } = usePage().props as any;
  const [showRevokeModal, setShowRevokeModal] = useState(false);
  const [selectedApplication, setSelectedApplication] = useState<Application | null>(null);
  const [revokeFeedback, setRevokeFeedback] = useState('');
  const [isProcessing, setIsProcessing] = useState(false);

  const formatDate = (dateString?: string) => {
    if (!dateString) return '—';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    });
  };

  const formatSessionFormat = (format: string) => {
    return format
      .replace(/_/g, ' ')
      .split(' ')
      .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
      .join(' ');
  };

  const openRevokeModal = (application: Application) => {
    setSelectedApplication(application);
    setShowRevokeModal(true);
  };

  const handleRevoke = (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedApplication || !revokeFeedback.trim()) return;

    setIsProcessing(true);
    router.post(
      route('admin.speakers.application.revoke', selectedApplication.id),
      { feedback: revokeFeedback },
      {
        preserveScroll: true,
        onFinish: () => {
          setIsProcessing(false);
          setShowRevokeModal(false);
          setSelectedApplication(null);
          setRevokeFeedback('');
        },
      }
    );
  };

  return (
    <DashboardLayout sideLinks={sideLinks}>
      <Head title="Approved Speaker Applications" />

      <div className="px-4 mx-auto max-w-7xl py-8">
        {/* Header Section */}
        <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
          <div className="flex items-center gap-3">
            <Link
              href={window.history.length > 1 ? window.location.href : route('admin.speakers.index')}
              onClick={(e) => {
                e.preventDefault();
                window.history.back();
              }}
              className="inline-flex items-center justify-center overflow-hidden w-10 h-10 bg-white border rounded-full border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition shadow-sm"
              title="Back"
            >
              <i className="fas fa-arrow-left w-5 h-5"></i>
            </Link>
            <div className="p-2.5 rounded-lg bg-primary/10">
              <i className="fas fa-check-circle w-6 h-6 text-primary"></i>
            </div>
            <div>
              <h1 className="text-2xl font-extrabold text-primary">
                Approved Speaker Applications
              </h1>
              <p className="text-sm text-slate-500">
                Review and manage approved speaker applications
              </p>
            </div>
          </div>
        </div>

        {/* Status Tabs */}
        <div className="mb-8 flex gap-2 border-b border-slate-200">
          <Link
            href={route('admin.speakers.index')}
            className="px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent"
          >
            Active Speakers
          </Link>
          <Link
            href={route('admin.speakers.pending')}
            className="px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent"
          >
            Pending Speakers
          </Link>
          <Link
            href={route('admin.speakers.applications.pending')}
            className="px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 border-b-2 border-transparent"
          >
            Applications (Pending)
          </Link>
          <Link
            href={route('admin.speakers.applications.approved')}
            className="px-4 py-2 text-sm font-medium border-b-2 border-primary text-primary"
          >
            Applications (Approved)
          </Link>
        </div>

        {/* Applications Table */}
        <div className="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
          <div className="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h2 className="text-lg font-medium text-slate-900">Approved Applications</h2>
            <p className="text-sm text-slate-500 mt-1">
              You can view details or revoke approval if needed.
            </p>
          </div>

          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-slate-50 whitespace-nowrap">
                <tr>
                  <th
                    scope="col"
                    className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider"
                  >
                    Topic Title
                  </th>
                  <th
                    scope="col"
                    className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider"
                  >
                    Speaker
                  </th>
                  <th
                    scope="col"
                    className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider"
                  >
                    Event
                  </th>
                  <th
                    scope="col"
                    className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider"
                  >
                    Session Format
                  </th>
                  <th
                    scope="col"
                    className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider"
                  >
                    Approved At
                  </th>
                  <th
                    scope="col"
                    className="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider"
                  >
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200 whitespace-nowrap">
                {applications.length > 0 ? (
                  applications.map((application) => (
                    <tr key={application.id} className="hover:bg-slate-50 transition-colors">
                      <td className="px-6 py-4">
                        <div className="font-medium text-slate-900">
                          {application.topic_title}
                        </div>
                        {application.topic_description && (
                          <div className="text-xs text-slate-500 mt-1 line-clamp-1">
                            {application.topic_description.substring(0, 60)}
                            {application.topic_description.length > 60 ? '...' : ''}
                          </div>
                        )}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="flex items-center gap-2">
                          <div className="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-medium text-slate-700">
                            {application.speaker.user.name.charAt(0).toUpperCase()}
                          </div>
                          <span className="text-sm text-slate-900">
                            {application.speaker.user.name}
                          </span>
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="flex items-center gap-2">
                          <span className="text-sm text-slate-900 font-bold">
                            {application.event.title}
                          </span>
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                          {formatSessionFormat(application.session_format.value)}
                        </span>
                      </td>
                      <td className="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">
                        {formatDate(application.approved_at)}
                      </td>
                      <td className="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                        <Link
                          href={route('admin.speakers.application.review', application.id)}
                          className="inline-flex items-center px-3 py-1.5 bg-primary text-white text-xs font-medium rounded-lg hover:bg-primary-600 focus:ring-2 focus:ring-offset-2 focus:ring-primary transition shadow-sm"
                        >
                          <i className="fas fa-eye w-4 h-4 mr-1.5"></i>
                          View Details
                        </Link>

                        <button
                          type="button"
                          onClick={() => openRevokeModal(application)}
                          className="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition shadow-sm"
                        >
                          <i className="fas fa-undo w-4 h-4 mr-1.5"></i>
                          Revoke
                        </button>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={6} className="px-6 py-16 text-center">
                      <div className="flex flex-col items-center space-y-4 text-gray-400">
                        <i className="fas fa-award w-12 h-12 text-gray-300"></i>
                        <h3 className="text-lg font-medium text-slate-900">
                          No approved applications yet
                        </h3>
                        <p className="max-w-md text-center text-slate-500">
                          Speaker applications will appear here once approved. Start by reviewing
                          pending applications.
                        </p>
                        <Link
                          href={route('admin.speakers.applications.pending')}
                          className="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-600 focus:ring-4 focus:ring-primary-300 transition shadow-sm"
                        >
                          <i className="fas fa-list-check w-4 h-4 mr-2"></i>
                          Review Pending Applications
                        </Link>
                      </div>
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {/* Revoke Approval Modal */}
      {showRevokeModal && selectedApplication && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
          <div className="relative bg-white rounded-lg border border-slate-200 p-6 w-full max-w-md mx-4 border border-slate-200">
            {/* Modal header */}
            <div className="flex items-center justify-between pb-4 mb-4 border-b border-slate-200">
              <h3 className="text-lg font-semibold text-slate-900">
                Revoke Speaker Approval
              </h3>
              <button
                type="button"
                onClick={() => setShowRevokeModal(false)}
                className="text-gray-400 bg-transparent hover:bg-slate-200 hover:text-slate-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors"
              >
                <i className="fas fa-times w-4 h-4"></i>
              </button>
            </div>

            {/* Modal body */}
            <form onSubmit={handleRevoke} className="space-y-4">
              <p className="text-slate-700">
                Please provide a reason for revoking approval for{' '}
                <span className="font-semibold">{selectedApplication.speaker.user.name}</span>'s
                application:
              </p>

              <Textarea
                rows={4}
                value={revokeFeedback}
                onChange={(e) => setRevokeFeedback(e.target.value)}
                placeholder="Enter reason for revoking approval..."
                required
              />

              {/* Modal footer */}
              <div className="flex items-center justify-end gap-3 pt-4">
                <Button
                  type="button"
                  variant="secondary"
                  onClick={() => setShowRevokeModal(false)}
                >
                  Cancel
                </Button>
                <Button
                  type="submit"
                  variant="danger"
                  loading={isProcessing}
                  icon="undo"
                >
                  Revoke Approval
                </Button>
              </div>
            </form>
          </div>
        </div>
      )}
    </DashboardLayout>
  );
}
