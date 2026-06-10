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
    return colors[roleName] || 'bg-slate-100 text-slate-700';
  };

  return (
    <DashboardLayout sideLinks={sideLinks}>
      <Head title="User Management" />

      <div className="workspace-stack">
        <section className="workspace-header-card px-6 py-6 lg:px-8">
          <div className="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div className="max-w-3xl">
              <p className="workspace-muted-label">System Administration</p>
              <h1 className="mt-3 text-3xl font-semibold tracking-tight text-slate-900">User Management</h1>
              <p className="mt-3 text-sm leading-7 text-slate-600">
                Review platform users, assign roles, and keep access management straightforward.
              </p>
            </div>
            <div className="flex items-center gap-3">
              <Button
                onClick={fetchStats}
                variant="secondary"
                icon="chart-bar"
                loading={loading}
                className="bg-slate-700 hover:bg-slate-800 text-white"
              >
                User Statistics
              </Button>
              <Link
                href={route('admin.roles.index')}
                className="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800"
              >
                <i className="fas fa-shield-alt w-4 h-4"></i>
                <span>Manage Roles</span>
              </Link>
            </div>
          </div>
        </section>

        {showStats && stats && (
          <div className="grid grid-cols-1 gap-4 md:grid-cols-5">
            <StatsCard icon="users" label="Total Users" value={stats.total_users} color="blue" />
            <StatsCard icon="shield-alt" label="Admins" value={stats.admins} color="red" />
            <StatsCard icon="graduation-cap" label="Instructors" value={stats.instructors} color="green" />
            <StatsCard icon="book-open" label="Students" value={stats.students} color="purple" />
            <StatsCard icon="user-slash" label="No Role" value={stats.users_without_roles} color="yellow" />
          </div>
        )}

        <section className="workspace-card relative overflow-x-auto">
          <table className="w-full text-sm text-left text-slate-500">
          <thead className="text-xs text-slate-500 uppercase bg-slate-50/90 border-b border-slate-200">
            <tr>
              <th scope="col" className="px-6 py-4 font-semibold">
                User Details
              </th>
              <th scope="col" className="px-6 py-4 font-semibold">
                Current Role
              </th>
              <th scope="col" className="px-6 py-4 font-semibold">
                Registration Date
              </th>
              <th scope="col" className="px-6 py-4 font-semibold">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            {users.data.length > 0 ? (
              users.data.map((user) => (
                <tr
                  key={user.id}
                  className="bg-white border-b border-slate-200 hover:bg-slate-50 transition-colors duration-150"
                >
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-3">
                      <div className="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                        <span className="text-sm font-semibold text-primary-700">
                          {user.name.charAt(0).toUpperCase()}
                        </span>
                      </div>
                      <div>
                        <h3 className="font-semibold text-slate-900">
                          {user.name}
                        </h3>
                        <p className="text-sm text-slate-500">{user.email}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-6 py-4">
                    {user.roles.length > 0 ? (
                      user.roles.map((role) => (
                        <span
                          key={role.id}
                          className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getRoleColor(
                            role.name
                          )}`}
                        >
                          {role.name.charAt(0).toUpperCase() + role.name.slice(1)}
                        </span>
                      ))
                    ) : (
                      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        No Role
                      </span>
                    )}
                  </td>
                  <td className="px-6 py-4">
                    <span className="text-slate-700">
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
                <td colSpan={4} className="px-6 py-12 text-center text-slate-500">
                  <div className="flex flex-col items-center">
                    <i className="fas fa-users w-12 h-12 text-gray-300 mb-4"></i>
                    <p className="">No users found</p>
                  </div>
                </td>
              </tr>
            )}
          </tbody>
          </table>
        </section>

        {users.last_page > 1 && (
          <div className="flex justify-center">
            <Pagination links={users.links} />
          </div>
        )}
      </div>

      {/* Role Assignment Modal */}
      {showRoleModal && selectedUser && (
        <div className="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
          <div className="relative p-5 border w-96 border border-slate-200 rounded-md bg-white">
            <div className="mt-3">
              <h3 className="text-lg font-semibold text-slate-900 mb-4">
                Assign Role
              </h3>
              <form onSubmit={handleAssignRole}>
                <div className="mb-4">
                  <label className="block text-sm font-medium text-slate-700 mb-2">
                    User
                  </label>
                  <p className="text-slate-900 font-semibold">
                    {selectedUser.name}
                  </p>
                </div>
                <div className="mb-4">
                  <label
                    htmlFor="role"
                    className="block text-sm font-medium text-slate-700 mb-2"
                  >
                    Role
                  </label>
                  <select
                    name="role"
                    id="role"
                    value={selectedRole}
                    onChange={(e) => setSelectedRole(e.target.value)}
                    required
                    className="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
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
    <div className="workspace-card p-6">
      <div className="flex items-center">
        <div className={`p-2 ${bg} rounded-lg`}>
          <i className={`fas fa-${icon} w-6 h-6 ${text}`}></i>
        </div>
        <div className="ml-4">
          <p className="text-sm font-medium text-slate-500">{label}</p>
          <p className="text-2xl font-semibold text-slate-900">{value}</p>
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
              className="px-3 py-2 text-sm text-gray-400 cursor-not-allowed"
            >
              {label}
            </span>
          );
        }

        return (
          <Link
            key={index}
            href={link.url}
            className={`px-3 py-2 text-sm rounded-lg transition-colors ${
              link.active
                ? 'bg-primary text-white'
                : 'text-slate-700 hover:bg-slate-100'
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
