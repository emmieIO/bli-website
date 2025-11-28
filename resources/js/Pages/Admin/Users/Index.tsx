import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState, FormEvent } from 'react';
import Button from '@/Components/Button';

interface Role {
    id: number;
    name: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    created_at: string;
    roles: Role[];
}

interface PaginatedUsers {
    data: User[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

interface Stats {
    total_users: number;
    admins: number;
    instructors: number;
    students: number;
    users_without_roles: number;
}

interface UserManagementProps {
    users: PaginatedUsers;
    roles: Role[];
}

export default function UserManagement({ users, roles }: UserManagementProps) {
    const { sideLinks } = usePage().props as any;
    const [showStats, setShowStats] = useState(false);
    const [stats, setStats] = useState<Stats | null>(null);
    const [loading, setLoading] = useState(false);
    const [showRoleModal, setShowRoleModal] = useState(false);
    const [selectedUser, setSelectedUser] = useState<User | null>(null);
    const [selectedRole, setSelectedRole] = useState('');
    const [isProcessing, setIsProcessing] = useState(false);

    const fetchStats = async () => {
        if (showStats) {
            setShowStats(false);
            return;
        }

        setLoading(true);
        try {
            const response = await fetch(route('admin.users.statistics'));
            const data = await response.json();
            setStats(data);
            setShowStats(true);
        } catch (error) {
            // Failed to load statistics - silently fail
            setShowStats(false);
        } finally {
            setLoading(false);
        }
    };

    const openRoleModal = (user: User) => {
        setSelectedUser(user);
        setSelectedRole(user.roles[0]?.name || '');
        setShowRoleModal(true);
    };

    const handleAssignRole = (e: FormEvent) => {
        e.preventDefault();
        if (!selectedUser || !selectedRole) return;

        setIsProcessing(true);
        router.post(
            route('admin.users.assign-role', selectedUser.id),
            { role: selectedRole },
            {
                preserveScroll: true,
                onFinish: () => {
                    setIsProcessing(false);
                    setShowRoleModal(false);
                    setSelectedUser(null);
                    setSelectedRole('');
                },
            }
        );
    };

    const handleRemoveRole = (user: User, roleName: string) => {
        if (!confirm(`Remove ${roleName} role from ${user.name}?`)) return;

        router.delete(route('admin.users.remove-role', [user.id, roleName]), {
            preserveScroll: true,
        });
    };

    const getRoleColor = (roleName: string) => {
        const colors: Record<string, string> = {
            admin: 'bg-red-100 text-red-800',
            instructor: 'bg-green-100 text-green-800',
            student: 'bg-blue-100 text-blue-800',
        };
        return colors[roleName] || 'bg-gray-100 text-gray-800';
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="User Management - Beacon Leadership Institute" />

            {/* Header */}
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 className="text-3xl font-bold text-gray-800 mb-2 font-montserrat">User Management</h1>
                    <p className="text-gray-600 font-lato">
                        Manage user accounts and assign roles to control access permissions.
                    </p>
                </div>
                <div className="flex items-center gap-3">
                    <Button
                        onClick={fetchStats}
                        variant="secondary"
                        icon="chart-bar"
                        loading={loading}
                        className="bg-gray-600 hover:bg-gray-700 text-white"
                    >
                        User Statistics
                    </Button>
                    <Link
                        href={route('admin.roles.index')}
                        className="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 py-3 px-6 rounded-lg font-medium text-white transition-all duration-200 shadow-sm font-montserrat"
                    >
                        <i className="fas fa-shield-alt w-4 h-4"></i>
                        <span>Manage Roles</span>
                    </Link>
                </div>
            </div>

            {/* Stats Cards */}
            {showStats && stats && (
                <div className="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                    <StatsCard icon="users" label="Total Users" value={stats.total_users} color="blue" />
                    <StatsCard icon="shield-alt" label="Admins" value={stats.admins} color="red" />
                    <StatsCard icon="graduation-cap" label="Instructors" value={stats.instructors} color="green" />
                    <StatsCard icon="book-open" label="Students" value={stats.students} color="purple" />
                    <StatsCard icon="user-slash" label="No Role" value={stats.users_without_roles} color="yellow" />
                </div>
            )}

            {/* Users Table */}
            <div className="relative overflow-x-auto shadow-lg rounded-xl bg-white border border-gray-200">
                <table className="w-full text-sm text-left text-gray-600">
                    <thead className="text-xs text-gray-600 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" className="px-6 py-4 font-semibold font-montserrat">
                                User Details
                            </th>
                            <th scope="col" className="px-6 py-4 font-semibold font-montserrat">
                                Current Role
                            </th>
                            <th scope="col" className="px-6 py-4 font-semibold font-montserrat">
                                Registration Date
                            </th>
                            <th scope="col" className="px-6 py-4 font-semibold font-montserrat">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {users.data.length > 0 ? (
                            users.data.map((user) => (
                                <tr
                                    key={user.id}
                                    className="bg-white border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150"
                                >
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            <div className="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                                <span className="text-sm font-semibold text-primary-700 font-montserrat">
                                                    {user.name.charAt(0).toUpperCase()}
                                                </span>
                                            </div>
                                            <div>
                                                <h3 className="font-semibold text-gray-900 font-montserrat">
                                                    {user.name}
                                                </h3>
                                                <p className="text-sm text-gray-500 font-lato">{user.email}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        {user.roles.length > 0 ? (
                                            user.roles.map((role) => (
                                                <span
                                                    key={role.id}
                                                    className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium font-montserrat ${getRoleColor(
                                                        role.name
                                                    )}`}
                                                >
                                                    {role.name.charAt(0).toUpperCase() + role.name.slice(1)}
                                                </span>
                                            ))
                                        ) : (
                                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 font-montserrat">
                                                No Role
                                            </span>
                                        )}
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className="text-gray-700 font-lato">
                                            {new Date(user.created_at).toLocaleDateString('en-US', {
                                                year: 'numeric',
                                                month: 'short',
                                                day: 'numeric',
                                            })}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-2">
                                            <button
                                                onClick={() => openRoleModal(user)}
                                                className="inline-flex items-center justify-center w-8 h-8 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors duration-200"
                                                title="Assign Role"
                                            >
                                                <i className="fas fa-user-cog w-4 h-4"></i>
                                            </button>
                                            {user.roles.length > 0 && (
                                                <button
                                                    onClick={() => handleRemoveRole(user, user.roles[0].name)}
                                                    className="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                                    title="Remove Role"
                                                >
                                                    <i className="fas fa-user-minus w-4 h-4"></i>
                                                </button>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                            ))
                        ) : (
                            <tr>
                                <td colSpan={4} className="px-6 py-12 text-center text-gray-500">
                                    <div className="flex flex-col items-center">
                                        <i className="fas fa-users w-12 h-12 text-gray-300 mb-4"></i>
                                        <p className="font-lato">No users found</p>
                                    </div>
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>

            {/* Pagination */}
            {users.last_page > 1 && (
                <div className="mt-6 flex justify-center">
                    <Pagination links={users.links} />
                </div>
            )}

            {/* Role Assignment Modal */}
            {showRoleModal && selectedUser && (
                <div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
                    <div className="relative p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div className="mt-3">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4 font-montserrat">
                                Assign Role
                            </h3>
                            <form onSubmit={handleAssignRole}>
                                <div className="mb-4">
                                    <label className="block text-sm font-medium text-gray-700 mb-2 font-lato">
                                        User
                                    </label>
                                    <p className="text-gray-900 font-semibold font-montserrat">
                                        {selectedUser.name}
                                    </p>
                                </div>
                                <div className="mb-4">
                                    <label
                                        htmlFor="role"
                                        className="block text-sm font-medium text-gray-700 mb-2 font-lato"
                                    >
                                        Role
                                    </label>
                                    <select
                                        name="role"
                                        id="role"
                                        value={selectedRole}
                                        onChange={(e) => setSelectedRole(e.target.value)}
                                        required
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 font-lato"
                                    >
                                        <option value="">Select a role</option>
                                        {roles.map((role) => (
                                            <option key={role.id} value={role.name}>
                                                {role.name.charAt(0).toUpperCase() + role.name.slice(1)}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <div className="flex justify-end gap-3">
                                    <Button
                                        type="button"
                                        variant="secondary"
                                        onClick={() => setShowRoleModal(false)}
                                    >
                                        Cancel
                                    </Button>
                                    <Button type="submit" loading={isProcessing}>
                                        Assign Role
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}
        </DashboardLayout>
    );
}

function StatsCard({
    icon,
    label,
    value,
    color,
}: {
    icon: string;
    label: string;
    value: number;
    color: string;
}) {
    const colorClasses: Record<string, { bg: string; text: string }> = {
        blue: { bg: 'bg-blue-100', text: 'text-blue-600' },
        red: { bg: 'bg-red-100', text: 'text-red-600' },
        green: { bg: 'bg-green-100', text: 'text-green-600' },
        purple: { bg: 'bg-purple-100', text: 'text-purple-600' },
        yellow: { bg: 'bg-yellow-100', text: 'text-yellow-600' },
    };

    const { bg, text } = colorClasses[color] || colorClasses.blue;

    return (
        <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div className="flex items-center">
                <div className={`p-2 ${bg} rounded-lg`}>
                    <i className={`fas fa-${icon} w-6 h-6 ${text}`}></i>
                </div>
                <div className="ml-4">
                    <p className="text-sm font-medium text-gray-600 font-lato">{label}</p>
                    <p className="text-2xl font-semibold text-gray-900 font-montserrat">{value}</p>
                </div>
            </div>
        </div>
    );
}

function Pagination({ links }: { links: Array<{ url: string | null; label: string; active: boolean }> }) {
    return (
        <nav className="flex gap-1">
            {links.map((link, index) => {
                const label = link.label
                    .replace('&laquo; Previous', '«')
                    .replace('Next &raquo;', '»');

                if (!link.url) {
                    return (
                        <span
                            key={index}
                            className="px-3 py-2 text-sm text-gray-400 cursor-not-allowed font-lato"
                        >
                            {label}
                        </span>
                    );
                }

                return (
                    <Link
                        key={index}
                        href={link.url}
                        className={`px-3 py-2 text-sm rounded-lg transition-colors font-lato ${
                            link.active
                                ? 'bg-primary text-white'
                                : 'text-gray-700 hover:bg-gray-100'
                        }`}
                        preserveScroll
                    >
                        {label}
                    </Link>
                );
            })}
        </nav>
    );
}
