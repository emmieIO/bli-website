import { Head, Link } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { usePage } from '@inertiajs/react';
import type { StudentMentorshipIndexProps } from '@/types/mentorship';

export default function Index({ requests, statistics, expiringWarnings }: StudentMentorshipIndexProps) {
  const { sideLinks } = usePage().props as any;

  const getStatusBadgeClass = (color: string) => {
    const colors: { [key: string]: string } = {
      yellow: 'bg-yellow-100 text-yellow-800',
      blue: 'bg-primary-50 text-primary-700',
      green: 'bg-lime-100 text-lime-700',
      red: 'bg-accent-50 text-accent',
      gray: 'bg-slate-100 text-slate-700',
    };
    return colors[color] || colors.gray;
  };

  const getExpirationBadgeClass = (color: string) => {
    const colors: { [key: string]: string } = {
      orange: 'bg-orange-100 text-orange-800 border-orange-200',
      red: 'bg-accent-50 text-accent border-accent-200',
    };
    return colors[color] || 'bg-slate-100 text-slate-700 border-slate-200';
  };

  return (
    <DashboardLayout sideLinks={sideLinks}>
      <Head title="My Mentorship Requests" />

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          {/* Header */}
          <div className="mb-8 flex justify-between items-center">
            <div>
              <h1 className="text-3xl font-bold text-slate-900">My Mentorship Requests</h1>
              <p className="mt-2 text-sm text-slate-500">
                Request and manage direct mentorship with instructors
              </p>
            </div>
            <Link
              href={route('student.mentorship.create')}
              className="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition"
            >
              <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Request Mentorship
            </Link>
          </div>

          {/* Expiration Warnings */}
          {expiringWarnings.length > 0 && (
            <div className="mb-6">
              {expiringWarnings.map((warning) => (
                <div
                  key={warning.id}
                  className={`mb-3 p-4 rounded-lg border-2 ${getExpirationBadgeClass(warning.expiration_status.color)}`}
                >
                  <div className="flex items-start justify-between">
                    <div className="flex items-start space-x-3">
                      <svg className="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <div>
                        <p className="font-semibold">Mentorship Expiring Soon</p>
                        <p className="text-sm mt-1">
                          Your mentorship with <strong>{warning.instructor}</strong> {warning.expiration_status.message.toLowerCase()}
                        </p>
                        <p className="text-xs mt-1 opacity-75">
                          Expiration Date: {new Date(warning.expiration_date).toLocaleDateString()}
                        </p>
                      </div>
                    </div>
                    <Link
                      href={route('student.mentorship.show', warning.id)}
                      className="text-sm font-medium hover:underline"
                    >
                      View Details
                    </Link>
                  </div>
                </div>
              ))}
            </div>
          )}

          {/* Statistics Cards */}
          <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <div className="bg-white rounded-lg border border-slate-200 p-4">
              <p className="text-sm text-slate-500">Total</p>
              <p className="text-2xl font-bold text-slate-900">{statistics.total}</p>
            </div>
            <div className="bg-white rounded-lg border border-slate-200 p-4">
              <p className="text-sm text-slate-500">Pending</p>
              <p className="text-2xl font-bold text-yellow-600">{statistics.pending}</p>
            </div>
            <div className="bg-white rounded-lg border border-slate-200 p-4">
              <p className="text-sm text-slate-500">Instructor Approved</p>
              <p className="text-2xl font-bold text-primary">{statistics.instructor_approved}</p>
            </div>
            <div className="bg-white rounded-lg border border-slate-200 p-4">
              <p className="text-sm text-slate-500">Admin Approved</p>
              <p className="text-2xl font-bold text-lime-600">{statistics.admin_approved}</p>
            </div>
            <div className="bg-white rounded-lg border border-slate-200 p-4">
              <p className="text-sm text-slate-500">Active</p>
              <p className="text-2xl font-bold text-lime-700">{statistics.active}</p>
            </div>
            <div className="bg-white rounded-lg border border-slate-200 p-4">
              <p className="text-sm text-slate-500">Rejected</p>
              <p className="text-2xl font-bold text-accent">{statistics.rejected}</p>
            </div>
          </div>

          {/* Requests List */}
          <div className="bg-white border border-slate-200 rounded-lg overflow-hidden">
            {requests.length === 0 ? (
              <div className="text-center py-12">
                <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 className="mt-2 text-sm font-medium text-slate-900">No mentorship requests</h3>
                <p className="mt-1 text-sm text-slate-500">Get started by requesting mentorship from an instructor.</p>
                <div className="mt-6">
                  <Link
                    href={route('student.mentorship.create')}
                    className="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/90"
                  >
                    Request Mentorship
                  </Link>
                </div>
              </div>
            ) : (
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-slate-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Instructor</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Duration</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Requested</th>
                    <th className="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {requests.map((request) => (
                    <tr key={request.id} className="hover:bg-slate-50">
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm font-medium text-slate-900">{request.instructor.name}</div>
                        <div className="text-sm text-slate-500">{request.instructor.email}</div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                        {request.formatted_duration}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusBadgeClass(request.status_color)}`}>
                          {request.status_label}
                        </span>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        {new Date(request.created_at).toLocaleDateString()}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <Link
                          href={route('student.mentorship.show', request.id)}
                          className="text-primary hover:text-primary/90"
                        >
                          View Details
                        </Link>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            )}
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
