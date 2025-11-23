import { Head, Link, router, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState } from 'react';

interface Permission {
    id: number;
    name: string;
}

interface Role {
    id: number;
    name: string;
    permissions: Permission[];
}

interface GroupedPermissions {
    [key: string]: Permission[];
}

interface RolesProps {
    roles: Role[];
    permissions: GroupedPermissions;
}

export default function RolesManagement({ roles, permissions }: RolesProps) {
    const { sideLinks } = usePage().props as any;

    const handleTogglePermission = (roleName: string, permissionName: string, hasPermission: boolean) => {
        const action = hasPermission ? 'remove' : 'add';
        const message = `${action === 'add' ? 'Grant' : 'Remove'} "${permissionName}" permission ${
            action === 'add' ? 'to' : 'from'
        } ${roleName} role?`;

        if (!confirm(message)) return;

        router.post(
            route('admin.roles.toggle-permission'),
            {
                role: roleName,
                permission: permissionName,
                action,
            },
            {
                preserveScroll: true,
            }
        );
    };

    const handleResetToDefaults = () => {
        if (!confirm('Are you sure you want to reset all roles to their default permissions? This action cannot be undone.')) {
            return;
        }

        router.post(route('admin.roles.reset-defaults'), {}, {
            preserveScroll: true,
        });
    };

    const handleViewAuditLog = () => {
        router.get(route('admin.roles.audit-log'), {}, {
            preserveScroll: true,
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Roles & Permissions - Beacon Leadership Institute" />

            {/* Header */}
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 className="text-3xl font-bold text-gray-800 mb-2 font-montserrat">
                        Role & Permission Management
                    </h1>
                    <p className="text-gray-600 font-lato">
                        Configure roles and their associated permissions to control system access.
                    </p>
                </div>
                <div className="flex items-center gap-3">
                    <Link
                        href={route('admin.users.index')}
                        className="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 py-3 px-6 rounded-lg font-medium text-white transition-all duration-200 shadow-sm font-montserrat"
                    >
                        <i className="fas fa-users w-4 h-4"></i>
                        <span>Manage Users</span>
                    </Link>
                </div>
            </div>

            {/* Roles Overview Cards */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {roles.map((role) => (
                    <RoleCard key={role.id} role={role} />
                ))}
            </div>

            {/* Detailed Permission Management */}
            <div className="bg-white rounded-xl border border-gray-200 shadow-lg">
                <div className="p-6 border-b border-gray-200">
                    <h2 className="text-xl font-semibold text-gray-900 font-montserrat">Permission Matrix</h2>
                    <p className="text-gray-600 mt-1 font-lato">Manage detailed permissions for each role</p>
                </div>

                <div className="p-6">
                    {Object.entries(permissions).map(([group, groupPermissions]) => (
                        <div key={group} className="mb-8 last:mb-0">
                            <h3 className="text-lg font-semibold text-gray-800 mb-4 capitalize font-montserrat">
                                {group.replace(/[_-]/g, ' ')} Permissions
                            </h3>

                            <div className="overflow-x-auto">
                                <table className="w-full text-sm">
                                    <thead>
                                        <tr className="border-b border-gray-200">
                                            <th className="text-left py-3 px-4 font-medium text-gray-600 w-1/3 font-montserrat">
                                                Permission
                                            </th>
                                            {roles.map((role) => (
                                                <th
                                                    key={role.id}
                                                    className="text-center py-3 px-4 font-medium text-gray-600 font-montserrat"
                                                >
                                                    {role.name.charAt(0).toUpperCase() + role.name.slice(1)}
                                                </th>
                                            ))}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {groupPermissions.map((permission) => (
                                            <tr key={permission.id} className="border-b border-gray-100 hover:bg-gray-50">
                                                <td className="py-3 px-4">
                                                    <div>
                                                        <span className="font-medium text-gray-900 font-lato">
                                                            {permission.name
                                                                .replace(/[_-]/g, ' ')
                                                                .split(' ')
                                                                .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
                                                                .join(' ')}
                                                        </span>
                                                        <p className="text-xs text-gray-500 mt-1 font-lato">
                                                            {permission.name}
                                                        </p>
                                                    </div>
                                                </td>
                                                {roles.map((role) => {
                                                    const hasPermission = role.permissions.some(
                                                        (p) => p.name === permission.name
                                                    );
                                                    return (
                                                        <td key={role.id} className="py-3 px-4 text-center">
                                                            <button
                                                                onClick={() =>
                                                                    handleTogglePermission(
                                                                        role.name,
                                                                        permission.name,
                                                                        hasPermission
                                                                    )
                                                                }
                                                                className={`w-6 h-6 rounded ${
                                                                    hasPermission
                                                                        ? 'bg-green-500 text-white'
                                                                        : 'bg-gray-200 text-gray-400'
                                                                } hover:scale-110 transition-all duration-200`}
                                                                title={
                                                                    hasPermission
                                                                        ? 'Remove permission'
                                                                        : 'Grant permission'
                                                                }
                                                            >
                                                                <i
                                                                    className={`fas fa-${
                                                                        hasPermission ? 'check' : 'times'
                                                                    } w-4 h-4`}
                                                                ></i>
                                                            </button>
                                                        </td>
                                                    );
                                                })}
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            {/* Quick Actions */}
            <div className="mt-8 bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h3 className="text-lg font-semibold text-gray-800 mb-4 font-montserrat">Quick Actions</h3>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button
                        onClick={handleResetToDefaults}
                        className="flex items-center justify-center gap-2 px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors font-montserrat"
                    >
                        <i className="fas fa-undo w-4 h-4"></i>
                        Reset to Defaults
                    </button>
                    <a
                        href={route('admin.roles.export')}
                        className="flex items-center justify-center gap-2 px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors font-montserrat"
                    >
                        <i className="fas fa-download w-4 h-4"></i>
                        Export Configuration
                    </a>
                    <button
                        onClick={handleViewAuditLog}
                        className="flex items-center justify-center gap-2 px-4 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors font-montserrat"
                    >
                        <i className="fas fa-clock w-4 h-4"></i>
                        View Audit Log
                    </button>
                </div>
            </div>
        </DashboardLayout>
    );
}

function RoleCard({ role }: { role: Role }) {
    const getRoleColors = (roleName: string) => {
        const colors: Record<
            string,
            { bg: string; icon: string; text: string; iconColor: string }
        > = {
            admin: {
                bg: 'bg-red-50',
                icon: 'bg-red-100',
                text: 'text-red-800',
                iconColor: 'text-red-600',
            },
            instructor: {
                bg: 'bg-green-50',
                icon: 'bg-green-100',
                text: 'text-green-800',
                iconColor: 'text-green-600',
            },
            student: {
                bg: 'bg-blue-50',
                icon: 'bg-blue-100',
                text: 'text-blue-800',
                iconColor: 'text-blue-600',
            },
        };
        return (
            colors[roleName] || {
                bg: 'bg-gray-50',
                icon: 'bg-gray-100',
                text: 'text-gray-800',
                iconColor: 'text-gray-600',
            }
        );
    };

    const colors = getRoleColors(role.name);

    return (
        <div className={`bg-white rounded-xl border border-gray-200 shadow-sm ${colors.bg}`}>
            <div className="p-6">
                <div className="flex items-center justify-between mb-4">
                    <div className="flex items-center">
                        <div className={`p-2 ${colors.icon} rounded-lg`}>
                            <i className={`fas fa-shield-alt w-6 h-6 ${colors.iconColor}`}></i>
                        </div>
                        <div className="ml-3">
                            <h3 className={`text-lg font-semibold ${colors.text} font-montserrat`}>
                                {role.name.charAt(0).toUpperCase() + role.name.slice(1)} Role
                            </h3>
                            <p className="text-sm text-gray-600 font-lato">
                                {role.permissions.length} permissions
                            </p>
                        </div>
                    </div>
                </div>
                <div className="space-y-2">
                    <h4 className="text-sm font-medium text-gray-700 font-montserrat">Key Permissions:</h4>
                    <div className="flex flex-wrap gap-1">
                        {role.permissions.slice(0, 4).map((permission) => (
                            <span
                                key={permission.id}
                                className="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-white text-gray-600 font-lato"
                            >
                                {permission.name.replace(/[-_]/g, ' ')}
                            </span>
                        ))}
                        {role.permissions.length > 4 && (
                            <span className="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-200 text-gray-600 font-lato">
                                +{role.permissions.length - 4} more
                            </span>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
