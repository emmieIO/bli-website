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
        return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    };

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Profile" />

            <div className="mx-auto max-w-4xl space-y-6">
                <ProfileHeader user={user} formatMemberSince={formatMemberSince} />

                <div className="border-b border-slate-200">
                    <nav className="-mb-px flex space-x-6">
                        <TabButton active={activeTab === 'profile'} onClick={() => handleTabChange('profile')}>Profile</TabButton>
                        <TabButton active={activeTab === 'security'} onClick={() => handleTabChange('security')}>Security</TabButton>
                        {hasInstructorProfile && (
                            <TabButton active={activeTab === 'instructor'} onClick={() => handleTabChange('instructor')}>Instructor</TabButton>
                        )}
                        {hasSpeakerProfile && (
                            <TabButton active={activeTab === 'speaker'} onClick={() => handleTabChange('speaker')}>Speaker</TabButton>
                        )}
                    </nav>
                </div>

                {activeTab === 'profile' && <ProfileTab user={user} />}
                {activeTab === 'security' && <SecurityTab />}
                {activeTab === 'instructor' && hasInstructorProfile && <InstructorTab user={user} />}
                {activeTab === 'speaker' && hasSpeakerProfile && <SpeakerTab user={user} />}

                <DangerZone showModal={showDeleteModal} setShowModal={setShowDeleteModal} />
            </div>
        </DashboardLayout>
    );
}

function ProfileHeader({ user, formatMemberSince }: { user: User; formatMemberSince: (date: string) => string }) {
    const fileInputRef = useRef<HTMLInputElement>(null);

    const getAvatarUrl = () => {
        if (user.photo) return `/storage/${user.photo}`;
        return `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=002147&color=fff`;
    };

    const handlePhotoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            router.post(route('profile.photo.update'), { photo: file }, { preserveScroll: true });
        }
    };

    return (
        <div className="rounded-lg bg-primary p-6 text-white">
            <div className="flex items-center gap-5">
                <div className="relative group">
                    <img
                        className="h-16 w-16 rounded-lg object-cover ring-2 ring-white/20"
                        src={getAvatarUrl()}
                        alt={`${user.name}'s profile photo`}
                    />
                    <input ref={fileInputRef} type="file" className="hidden" accept="image/png, image/jpeg, image/gif" onChange={handlePhotoChange} />
                    <button
                        onClick={() => fileInputRef.current?.click()}
                        className="absolute -bottom-1 -right-1 rounded-md bg-white p-1 text-primary shadow-sm transition hover:bg-slate-100"
                    >
                        <i className="fas fa-camera text-xs"></i>
                    </button>
                </div>
                <div>
                    <h2 className="text-lg font-semibold">{user.name}</h2>
                    <p className="text-sm text-primary-200">{user.email}</p>
                    <p className="mt-0.5 text-xs text-primary-300">Member since {formatMemberSince(user.created_at)}</p>
                </div>
            </div>
        </div>
    );
}

function TabButton({ active, onClick, children }: { active: boolean; onClick: () => void; children: React.ReactNode }) {
    return (
        <button
            onClick={onClick}
            className={`whitespace-nowrap border-b-2 px-1 pb-3 pt-1 text-sm font-medium transition ${
                active ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700'
            }`}
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
        patch(route('profile.update'), { preserveScroll: true, onSuccess: () => setData('current_password', '') });
    };

    return (
        <div className="rounded-lg border border-slate-200 bg-white p-6">
            <h3 className="text-sm font-semibold tracking-tight text-slate-900 mb-5">Personal Information</h3>
            <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-2 gap-5">
                <Input label="Full Name" type="text" name="name" value={data.name} onChange={(e) => setData('name', e.target.value)} icon="user" required autoFocus error={errors.name} />
                <Input label="Headline" type="text" name="headline" value={data.headline} onChange={(e) => setData('headline', e.target.value)} icon="user" placeholder="Your professional headline" error={errors.headline} />
                <Input label="Phone" type="text" name="phone" value={data.phone} onChange={(e) => setData('phone', e.target.value)} icon="phone" error={errors.phone} />
                <Input label="Email" type="email" name="email" value={data.email} onChange={(e) => setData('email', e.target.value)} icon="envelope" readOnly={!!(user.roles?.includes('student') || user.roles?.includes('user') || user.roles?.includes('instructor'))} required error={errors.email} />
                <Input label="Current Password" type="password" name="current_password" value={data.current_password} onChange={(e) => setData('current_password', e.target.value)} icon="lock" required error={errors.current_password} />
                <Input label="LinkedIn Profile" type="text" name="linkedin" value={data.linkedin} onChange={(e) => setData('linkedin', e.target.value)} icon="linkedin" error={errors.linkedin} />
                <Input label="Website" type="text" name="website" value={data.website} onChange={(e) => setData('website', e.target.value)} icon="globe" error={errors.website} />
                <div className="md:col-span-2">
                    <Button type="submit" icon="save" loading={processing}>Save Changes</Button>
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
        patch(route('profile.update_password'), { preserveScroll: true, onSuccess: () => reset() });
    };

    return (
        <div className="rounded-lg border border-slate-200 bg-white p-6">
            <h3 className="text-sm font-semibold tracking-tight text-slate-900 mb-5">Account Security</h3>
            <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-2 gap-5">
                <Input label="Current Password" type="password" name="current_password" value={data.current_password} onChange={(e) => setData('current_password', e.target.value)} icon="lock" autoComplete="current-password" error={errors.current_password} />
                <Input label="New Password" type="password" name="password" value={data.password} onChange={(e) => setData('password', e.target.value)} icon="lock" autoComplete="new-password" error={errors.password} />
                <Input label="Confirm Password" type="password" name="password_confirmation" value={data.password_confirmation} onChange={(e) => setData('password_confirmation', e.target.value)} icon="lock" autoComplete="new-password" error={errors.password_confirmation} />
                <div className="md:col-span-2">
                    <Button type="submit" icon="lock" loading={processing}>Update Password</Button>
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
        patch(route('profile.update'), { preserveScroll: true });
    };

    return (
        <div className="rounded-lg border border-slate-200 bg-white p-6">
            <h3 className="text-sm font-semibold tracking-tight text-slate-900 mb-5">Instructor Information</h3>
            <form onSubmit={handleSubmit} className="space-y-5">
                <Textarea label="Biography" name="bio" rows={10} value={data.bio} onChange={(e) => setData('bio', e.target.value)} placeholder="Write a short professional bio..." required error={errors.bio} />
                <Textarea label="Areas of Expertise" name="area_of_expertise" rows={3} value={data.area_of_expertise} onChange={(e) => setData('area_of_expertise', e.target.value)} placeholder="List your areas of specialization..." error={errors.area_of_expertise} />
                <Input label="LinkedIn Profile" type="url" name="linkedin" value={data.linkedin} onChange={(e) => setData('linkedin', e.target.value)} placeholder="https://linkedin.com/in/username" error={errors.linkedin} />
                <Button type="submit" icon="save" loading={processing}>Save Changes</Button>
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
        patch(route('profile.update'), { preserveScroll: true, onSuccess: () => setData('current_password', '') });
    };

    return (
        <div className="rounded-lg border border-slate-200 bg-white p-6">
            <h3 className="text-sm font-semibold tracking-tight text-slate-900 mb-5">Speaker Information</h3>
            <form onSubmit={handleSubmit} className="grid grid-cols-1 md:grid-cols-2 gap-5">
                <Input label="Full Name" type="text" name="name" value={data.name} onChange={(e) => setData('name', e.target.value)} icon="user" required autoFocus error={errors.name} />
                <Input label="Phone" type="text" name="phone" value={data.phone} onChange={(e) => setData('phone', e.target.value)} icon="phone" error={errors.phone} />
                <Input label="Email" type="email" name="email" value={data.email} onChange={(e) => setData('email', e.target.value)} icon="envelope" readOnly={!!(user.roles?.includes('student') || user.roles?.includes('user') || user.roles?.includes('instructor'))} required error={errors.email} />
                <Input label="Current Password" type="password" name="current_password" value={data.current_password} onChange={(e) => setData('current_password', e.target.value)} icon="lock" required error={errors.current_password} />
                <div className="md:col-span-2">
                    <Button type="submit" icon="save" loading={processing}>Save Changes</Button>
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
        destroy(route('account.destroy'), { preserveScroll: true, onSuccess: () => setShowModal(false) });
    };

    return (
        <>
            <div className="rounded-lg border border-accent-200 bg-white p-6">
                <h3 className="text-sm font-semibold tracking-tight text-accent mb-2">Danger Zone</h3>
                <p className="text-sm text-slate-500 mb-4">Permanently delete your account. This action cannot be undone.</p>
                <button
                    onClick={() => setShowModal(true)}
                    className="rounded-md border border-accent bg-white px-4 py-2 text-sm font-medium text-accent transition hover:bg-accent hover:text-white"
                >
                    Deactivate Account
                </button>
            </div>

            {showModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/30 px-4 backdrop-blur-sm">
                    <div className="w-full max-w-md rounded-lg border border-slate-200 bg-white p-6 shadow-lg">
                        <h2 className="text-base font-semibold text-accent">Confirm Account Deactivation</h2>
                        <p className="mt-2 text-sm text-slate-500">Are you sure you want to deactivate your account? This action cannot be undone.</p>
                        <form onSubmit={handleDelete} className="mt-5 space-y-4">
                            <Input label="Current Password" type="password" name="current_password_destroy" value={data.current_password_destroy} onChange={(e) => setData('current_password_destroy', e.target.value)} icon="lock" required error={errors.current_password_destroy} />
                            <div className="flex justify-end gap-3">
                                <Button type="button" variant="secondary" onClick={() => setShowModal(false)}>Cancel</Button>
                                <Button type="submit" variant="danger" loading={processing}>Deactivate</Button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </>
    );
}
