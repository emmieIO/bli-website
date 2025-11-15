import { Head, useForm, usePage, router } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { useState, useRef, FormEvent } from 'react';
import Input from '@/Components/Input';
import Textarea from '@/Components/Textarea';
import Button from '@/Components/Button';

interface User {
    id: number;
    name: string;
    email: string;
    phone?: string;
    headline?: string;
    linkedin?: string;
    website?: string;
    photo?: string;
    created_at: string;
    roles?: string[];
    instructorProfile?: {
        bio?: string;
        area_of_expertise?: string;
        linkedin?: string;
    };
}

interface ProfileProps {
    user: User;
}

export default function Profile({ user }: ProfileProps) {
    const { sideLinks } = usePage().props as any;
    const [activeTab, setActiveTab] = useState(() => {
        return localStorage.getItem('userTab') || 'profile';
    });
    const [showDeleteModal, setShowDeleteModal] = useState(false);

    const hasInstructorProfile = user.instructorProfile !== undefined && user.instructorProfile !== null;
    const hasSpeakerProfile = user.roles?.includes('speaker') || false;

    const handleTabChange = (tab: string) => {
        setActiveTab(tab);
        localStorage.setItem('userTab', tab);
    };

    const formatMemberSince = (date: string) => {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Profile - Beacon Leadership Institute" />

            <section className="max-w-5xl mx-auto">
                {/* Profile Header */}
                <ProfileHeader user={user} formatMemberSince={formatMemberSince} />

                {/* Tab Navigation */}
                <div className="mb-6 border-b border-gray-200">
                    <nav className="-mb-px flex space-x-6">
                        <TabButton
                            active={activeTab === 'profile'}
                            onClick={() => handleTabChange('profile')}
                        >
                            Profile
                        </TabButton>
                        <TabButton
                            active={activeTab === 'security'}
                            onClick={() => handleTabChange('security')}
                        >
                            Security
                        </TabButton>
                        {hasInstructorProfile && (
                            <TabButton
                                active={activeTab === 'instructor'}
                                onClick={() => handleTabChange('instructor')}
                            >
                                Instructor Information
                            </TabButton>
                        )}
                        {hasSpeakerProfile && (
                            <TabButton
                                active={activeTab === 'speaker'}
                                onClick={() => handleTabChange('speaker')}
                            >
                                Speaker Information
                            </TabButton>
                        )}
                    </nav>
                </div>

                {/* Tab Content */}
                {activeTab === 'profile' && <ProfileTab user={user} />}
                {activeTab === 'security' && <SecurityTab />}
                {activeTab === 'instructor' && hasInstructorProfile && <InstructorTab user={user} />}
                {activeTab === 'speaker' && hasSpeakerProfile && <SpeakerTab user={user} />}

                {/* Danger Zone */}
                <DangerZone
                    showModal={showDeleteModal}
                    setShowModal={setShowDeleteModal}
                />
            </section>
        </DashboardLayout>
    );
}

function ProfileHeader({ user, formatMemberSince }: { user: User; formatMemberSince: (date: string) => string }) {
    const fileInputRef = useRef<HTMLInputElement>(null);

    const getAvatarUrl = () => {
        if (user.photo) {
            return `/storage/${user.photo}`;
        }
        return `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=002147&color=fff`;
    };

    const handlePhotoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            router.post(route('profile.photo.update'), {
                photo: file,
            }, {
                preserveScroll: true,
            });
        }
    };

    return (
        <div className="bg-gradient-to-r from-primary to-primary-700 rounded-xl shadow-lg p-6 text-white mb-10">
            <div className="flex items-center gap-6">
                <div className="relative group">
                    <img
                        className="w-20 h-20 rounded-full object-cover border-2 border-white/50 group-hover:opacity-80 transition-opacity"
                        src={getAvatarUrl()}
                        alt={`${user.name}'s profile photo`}
                    />
                    <input
                        ref={fileInputRef}
                        type="file"
                        className="hidden"
                        accept="image/png, image/jpeg, image/gif"
                        onChange={handlePhotoChange}
                    />
                    <label
                        onClick={() => fileInputRef.current?.click()}
                        className="absolute bottom-0 right-0 bg-white rounded-full p-1.5 cursor-pointer shadow-md transform transition-transform group-hover:scale-110"
                    >
                        <i className="fas fa-camera w-4 h-4 text-primary"></i>
                    </label>
                </div>
                <div>
                    <h2 className="text-2xl font-bold font-montserrat">{user.name}</h2>
                    <p className="text-sm opacity-90 font-lato">{user.email}</p>
                    <p className="text-xs text-white/70 mt-1 font-lato">
                        Member since {formatMemberSince(user.created_at)}
                    </p>
                </div>
            </div>
        </div>
    );
}

function TabButton({ active, onClick, children }: { active: boolean; onClick: () => void; children: React.ReactNode }) {
    return (
        <button
            onClick={onClick}
            className={`
                whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm font-montserrat transition-colors
                ${active
                    ? 'border-primary text-primary'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                }
            `}
        >
            {children}
        </button>
    );
}

function ProfileTab({ user }: { user: User }) {
    const { data, setData, patch, processing, errors } = useForm({
        name: user.name || '',
        headline: user.headline || '',
        phone: user.phone || '',
        email: user.email || '',
        linkedin: user.linkedin || '',
        website: user.website || '',
        current_password: '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        patch(route('profile.update'), {
            preserveScroll: true,
            onSuccess: () => {
                setData('current_password', '');
            },
        });
    };

    const isStudent = user.roles?.includes('student') || user.roles?.includes('user') || user.roles?.includes('instructor');

    return (
        <div className="bg-white border border-primary-100 rounded-xl shadow-sm p-6">
            <h3 className="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 font-montserrat">
                <i className="fas fa-user w-5 h-5 text-primary-600"></i>
                Personal Information
            </h3>

            <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Input
                    label="Full Name"
                    type="text"
                    name="name"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    icon="user"
                    required
                    autoFocus
                    error={errors.name}
                />

                <Input
                    label="Headline"
                    type="text"
                    name="headline"
                    value={data.headline}
                    onChange={(e) => setData('headline', e.target.value)}
                    icon="user"
                    placeholder="Your professional headline"
                    error={errors.headline}
                />

                <Input
                    label="Phone"
                    type="text"
                    name="phone"
                    value={data.phone}
                    onChange={(e) => setData('phone', e.target.value)}
                    icon="phone"
                    error={errors.phone}
                />

                <Input
                    label="Email"
                    type="email"
                    name="email"
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                    icon="envelope"
                    readOnly={isStudent}
                    required
                    error={errors.email}
                />

                <Input
                    label="Current Password"
                    type="password"
                    name="current_password"
                    value={data.current_password}
                    onChange={(e) => setData('current_password', e.target.value)}
                    icon="lock"
                    required
                    error={errors.current_password}
                />

                <Input
                    label="LinkedIn Profile"
                    type="text"
                    name="linkedin"
                    value={data.linkedin}
                    onChange={(e) => setData('linkedin', e.target.value)}
                    icon="linkedin"
                    error={errors.linkedin}
                />

                <Input
                    label="Website"
                    type="text"
                    name="website"
                    value={data.website}
                    onChange={(e) => setData('website', e.target.value)}
                    icon="globe"
                    error={errors.website}
                />

                <div className="md:col-span-2 mt-4">
                    <Button type="submit" icon="save" loading={processing}>
                        Save Changes
                    </Button>
                </div>
            </form>
        </div>
    );
}

function SecurityTab() {
    const { data, setData, patch, processing, errors, reset } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        patch(route('profile.update_password'), {
            preserveScroll: true,
            onSuccess: () => reset(),
        });
    };

    return (
        <div className="bg-white border border-primary-100 rounded-xl shadow-sm p-6">
            <h3 className="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 font-montserrat">
                <i className="fas fa-shield-alt w-5 h-5 text-primary-600"></i>
                Account Security
            </h3>

            <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Input
                    label="Current Password"
                    type="password"
                    name="current_password"
                    value={data.current_password}
                    onChange={(e) => setData('current_password', e.target.value)}
                    icon="lock"
                    autoComplete="current-password"
                    error={errors.current_password}
                />

                <Input
                    label="New Password"
                    type="password"
                    name="password"
                    value={data.password}
                    onChange={(e) => setData('password', e.target.value)}
                    icon="lock"
                    autoComplete="new-password"
                    error={errors.password}
                />

                <Input
                    label="Confirm Password"
                    type="password"
                    name="password_confirmation"
                    value={data.password_confirmation}
                    onChange={(e) => setData('password_confirmation', e.target.value)}
                    icon="lock"
                    autoComplete="new-password"
                    error={errors.password_confirmation}
                />

                <div className="md:col-span-2 mt-4">
                    <Button type="submit" icon="lock" loading={processing}>
                        Update Password
                    </Button>
                </div>
            </form>
        </div>
    );
}

function InstructorTab({ user }: { user: User }) {
    const { data, setData, patch, processing, errors } = useForm({
        bio: user.instructorProfile?.bio || '',
        area_of_expertise: user.instructorProfile?.area_of_expertise || '',
        linkedin: user.instructorProfile?.linkedin || '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        patch(route('profile.update'), {
            preserveScroll: true,
        });
    };

    return (
        <div className="bg-white border border-primary-100 rounded-xl shadow-sm p-6">
            <h3 className="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 font-montserrat">
                <i className="fas fa-book-open w-5 h-5 text-primary-600"></i>
                Instructor Information
            </h3>

            <form onSubmit={handleSubmit} className="grid grid-cols-1 gap-6">
                <Textarea
                    label="Biography"
                    name="bio"
                    rows={10}
                    value={data.bio}
                    onChange={(e) => setData('bio', e.target.value)}
                    placeholder="Write a short professional bio..."
                    required
                    error={errors.bio}
                />

                <Textarea
                    label="Areas of Expertise"
                    name="area_of_expertise"
                    rows={3}
                    value={data.area_of_expertise}
                    onChange={(e) => setData('area_of_expertise', e.target.value)}
                    placeholder="List your areas of specialization (e.g. Leadership, Communication, Management)..."
                    error={errors.area_of_expertise}
                />

                <Input
                    label="LinkedIn Profile"
                    type="url"
                    name="linkedin"
                    value={data.linkedin}
                    onChange={(e) => setData('linkedin', e.target.value)}
                    placeholder="https://linkedin.com/in/username"
                    error={errors.linkedin}
                />

                <div className="mt-4">
                    <Button type="submit" icon="save" loading={processing}>
                        Save Changes
                    </Button>
                </div>
            </form>
        </div>
    );
}

function SpeakerTab({ user }: { user: User }) {
    const { data, setData, patch, processing, errors } = useForm({
        name: user.name || '',
        phone: user.phone || '',
        email: user.email || '',
        current_password: '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        patch(route('profile.update'), {
            preserveScroll: true,
            onSuccess: () => {
                setData('current_password', '');
            },
        });
    };

    const isStudent = user.roles?.includes('student') || user.roles?.includes('user') || user.roles?.includes('instructor');

    return (
        <div className="bg-white border border-primary-100 rounded-xl shadow-sm p-6">
            <h3 className="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 font-montserrat">
                <i className="fas fa-microphone w-5 h-5 text-primary-600"></i>
                Speaker Information
            </h3>

            <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Input
                    label="Full Name"
                    type="text"
                    name="name"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    icon="user"
                    required
                    autoFocus
                    error={errors.name}
                />

                <Input
                    label="Phone"
                    type="text"
                    name="phone"
                    value={data.phone}
                    onChange={(e) => setData('phone', e.target.value)}
                    icon="phone"
                    error={errors.phone}
                />

                <Input
                    label="Email"
                    type="email"
                    name="email"
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                    icon="envelope"
                    readOnly={isStudent}
                    required
                    error={errors.email}
                />

                <Input
                    label="Current Password"
                    type="password"
                    name="current_password"
                    value={data.current_password}
                    onChange={(e) => setData('current_password', e.target.value)}
                    icon="lock"
                    required
                    error={errors.current_password}
                />

                <div className="md:col-span-2 mt-4">
                    <Button type="submit" icon="save" loading={processing}>
                        Save Changes
                    </Button>
                </div>
            </form>
        </div>
    );
}

function DangerZone({ showModal, setShowModal }: { showModal: boolean; setShowModal: (show: boolean) => void }) {
    const { data, setData, delete: destroy, processing, errors } = useForm({
        current_password_destroy: '',
    });

    const handleDelete = (e: FormEvent) => {
        e.preventDefault();
        destroy(route('account.destroy'), {
            preserveScroll: true,
            onSuccess: () => setShowModal(false),
        });
    };

    return (
        <>
            <div className="mt-10">
                <div className="bg-white border border-red-200 rounded-xl shadow-sm p-6">
                    <h3 className="text-lg font-semibold text-red-700 mb-4 flex items-center gap-2 font-montserrat">
                        <i className="fas fa-exclamation-triangle w-5 h-5"></i>
                        Danger Zone
                    </h3>
                    <p className="text-sm text-gray-600 mb-4 font-lato">
                        Permanently delete your account. This action cannot be undone.
                    </p>
                    <button
                        onClick={() => setShowModal(true)}
                        className="text-red-700 border border-red-500 hover:bg-red-600 hover:text-white font-semibold px-5 py-2 rounded-lg transition-all duration-200 font-montserrat"
                    >
                        <i className="fas fa-trash w-4 h-4 inline-block mr-2"></i>
                        Deactivate Account
                    </button>
                </div>
            </div>

            {/* Delete Modal */}
            {showModal && (
                <div className="fixed inset-0 flex items-center justify-center z-50 bg-black/30">
                    <div className="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4">
                        <h2 className="text-lg font-bold mb-4 text-red-700 font-montserrat">
                            Confirm Account Deactivation
                        </h2>
                        <p className="mb-6 text-gray-700 font-lato">
                            Are you sure you want to deactivate your account? This action cannot be undone.
                        </p>
                        <form onSubmit={handleDelete} className="space-y-4">
                            <Input
                                label="Current Password"
                                type="password"
                                name="current_password_destroy"
                                value={data.current_password_destroy}
                                onChange={(e) => setData('current_password_destroy', e.target.value)}
                                icon="lock"
                                required
                                error={errors.current_password_destroy}
                            />
                            <div className="flex justify-end gap-3">
                                <Button
                                    type="button"
                                    variant="secondary"
                                    onClick={() => setShowModal(false)}
                                >
                                    Cancel
                                </Button>
                                <Button
                                    type="submit"
                                    variant="danger"
                                    loading={processing}
                                >
                                    Deactivate
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </>
    );
}
