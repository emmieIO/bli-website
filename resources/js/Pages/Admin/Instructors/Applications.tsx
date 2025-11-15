import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';

interface User {
    id: number;
    name: string;
    email: string;
}

interface InstructorProfile {
    id: number;
    application_id: string;
    user: User;
    headline?: string;
    phone?: string;
    status: string;
    is_approved: boolean;
    deleted_at?: string | null;
}

interface Props {
    instructorProfiles: InstructorProfile[];
}

export default function Applications({ instructorProfiles }: Props) {
    const [selectedProfiles, setSelectedProfiles] = useState<number[]>([]);
    const [showApproveModal, setShowApproveModal] = useState(false);
    const [selectedProfile, setSelectedProfile] = useState<InstructorProfile | null>(null);
    const [isApproving, setIsApproving] = useState(false);
    const [searchQuery, setSearchQuery] = useState('');

    const handleSelectAll = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.checked) {
            setSelectedProfiles(instructorProfiles.map(profile => profile.id));
        } else {
            setSelectedProfiles([]);
        }
    };

    const handleSelectProfile = (profileId: number) => {
        setSelectedProfiles(prev =>
            prev.includes(profileId)
                ? prev.filter(id => id !== profileId)
                : [...prev, profileId]
        );
    };

    const openApproveModal = (profile: InstructorProfile) => {
        setSelectedProfile(profile);
        setShowApproveModal(true);
    };

    const closeApproveModal = () => {
        setShowApproveModal(false);
        setSelectedProfile(null);
        setIsApproving(false);
    };

    const handleApprove = () => {
        if (!selectedProfile) return;

        setIsApproving(true);
        router.patch(
            route('admin.instructors.applications.approve', selectedProfile.id),
            {},
            {
                preserveScroll: true,
                onFinish: () => {
                    closeApproveModal();
                },
            }
        );
    };

    const filteredProfiles = instructorProfiles.filter(profile => {
        if (!searchQuery) return true;
        const query = searchQuery.toLowerCase();
        return (
            profile.user.name.toLowerCase().includes(query) ||
            profile.user.email.toLowerCase().includes(query) ||
            profile.application_id.toLowerCase().includes(query) ||
            profile.headline?.toLowerCase().includes(query)
        );
    });

    const getStatusBadge = (status: string) => {
        const statusColors: Record<string, string> = {
            draft: 'bg-yellow-100 text-yellow-800',
            submitted: 'bg-blue-100 text-blue-800',
            pending: 'bg-orange-100 text-orange-800',
            approved: 'bg-green-100 text-green-800',
            denied: 'bg-red-100 text-red-800',
        };

        return (
            <span className={`px-2 py-1 text-xs font-medium rounded-full ${statusColors[status] || 'bg-gray-100 text-gray-800'}`}>
                {status.charAt(0).toUpperCase() + status.slice(1)}
            </span>
        );
    };

    return (
        <DashboardLayout>
            <Head title="Instructor Applications" />

            <div className="p-6">
                {/* Header */}
                <div className="mb-6">
                    <h1 className="text-2xl font-bold text-gray-900 font-montserrat">
                        Instructor Applications
                    </h1>
                    <p className="mt-1 text-sm text-gray-600">
                        Review and approve instructor applications
                    </p>
                </div>

                {/* Tabs */}
                <div className="mb-6 border-b border-gray-200">
                    <nav className="-mb-px flex space-x-8">
                        <Link
                            href={route('admin.instructors.index')}
                            className="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 font-medium text-sm transition-colors"
                        >
                            Active Instructors
                        </Link>
                        <Link
                            href={route('admin.instructors.applications')}
                            className="border-b-2 border-primary text-primary whitespace-nowrap py-4 px-1 font-medium text-sm"
                        >
                            Applications
                        </Link>
                    </nav>
                </div>

                {/* Search */}
                <div className="mb-4">
                    <div className="relative">
                        <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i className="fas fa-search text-gray-400"></i>
                        </div>
                        <input
                            type="text"
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            className="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                            placeholder="Search by name, email, profile ID, or headline..."
                        />
                    </div>
                </div>

                {/* Mass Actions */}
                {selectedProfiles.length > 0 && (
                    <div className="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-between">
                        <span className="text-sm text-blue-700">
                            {selectedProfiles.length} profile(s) selected
                        </span>
                        <button className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                            Mass Action
                        </button>
                    </div>
                )}

                {/* Table */}
                <div className="bg-white rounded-lg shadow overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th scope="col" className="w-4 p-4">
                                        <input
                                            type="checkbox"
                                            checked={selectedProfiles.length === instructorProfiles.length}
                                            onChange={handleSelectAll}
                                            className="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary"
                                        />
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fullname
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Profile ID
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Headline
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Phone
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {filteredProfiles.length === 0 ? (
                                    <tr>
                                        <td colSpan={8} className="px-6 py-12 text-center text-gray-500">
                                            <i className="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                            <p>No applications found</p>
                                        </td>
                                    </tr>
                                ) : (
                                    filteredProfiles.map((profile) => (
                                        <tr key={profile.id} className="hover:bg-gray-50">
                                            <td className="w-4 p-4">
                                                <input
                                                    type="checkbox"
                                                    checked={selectedProfiles.includes(profile.id)}
                                                    onChange={() => handleSelectProfile(profile.id)}
                                                    className="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary"
                                                />
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm font-medium text-gray-900">
                                                    {profile.user.name}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-gray-900">
                                                    {profile.application_id}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4">
                                                <div className="text-sm text-gray-900 max-w-xs truncate">
                                                    {profile.headline || '-'}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-gray-900">
                                                    {profile.user.email}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-gray-900">
                                                    {profile.phone || '-'}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                {getStatusBadge(profile.status)}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <button
                                                    onClick={() => openApproveModal(profile)}
                                                    disabled={profile.is_approved || profile.status === 'draft'}
                                                    className={`inline-flex items-center px-3 py-1.5 rounded-lg text-white text-xs font-medium transition-colors ${
                                                        profile.is_approved || profile.status === 'draft'
                                                            ? 'bg-gray-300 cursor-not-allowed'
                                                            : 'bg-green-600 hover:bg-green-700'
                                                    }`}
                                                >
                                                    <i className="fas fa-check mr-1"></i>
                                                    Approve
                                                </button>
                                                <Link
                                                    href={route('admin.instructors.applications.view', profile.id)}
                                                    className="inline-flex items-center px-3 py-1.5 bg-primary text-white rounded-lg hover:bg-[#003366] text-xs font-medium transition-colors"
                                                >
                                                    <i className="fas fa-eye mr-1"></i>
                                                    View
                                                </Link>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {/* Approve Confirmation Modal */}
            {showApproveModal && selectedProfile && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                        <div className="p-6">
                            <div className="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-green-100 rounded-full">
                                <i className="fas fa-check text-green-600 text-xl"></i>
                            </div>
                            <h3 className="mb-2 text-lg font-medium text-gray-900 text-center">
                                Approve Application
                            </h3>
                            <p className="mb-4 text-sm text-gray-500 text-center">
                                Are you sure you want to approve the application for {selectedProfile.application_id}?
                            </p>
                            <div className="flex items-center justify-center gap-3">
                                <button
                                    onClick={handleApprove}
                                    disabled={isApproving}
                                    className="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {isApproving ? 'Approving...' : "Yes, I'm sure"}
                                </button>
                                <button
                                    onClick={closeApproveModal}
                                    disabled={isApproving}
                                    className="px-5 py-2.5 bg-white text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 text-sm font-medium"
                                >
                                    No, cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}
