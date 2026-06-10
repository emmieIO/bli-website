import { Head, useForm, usePage } from '@inertiajs/react';
import DashboardLayout from '@/Layouts/DashboardLayout';
import { FormEventHandler, useRef, useState } from 'react';
import InputError from '@/Components/InputError';
import { Transition } from '@headlessui/react';

interface ProfileEditProps {
    mustVerifyEmail: boolean;
    status?: string;
}

export default function Edit({ mustVerifyEmail, status }: ProfileEditProps) {
    const { auth, sideLinks } = usePage().props as any;
    const user = auth?.user;

    return (
        <DashboardLayout sideLinks={sideLinks}>
            <Head title="Edit Profile" />

            <div className="mx-auto max-w-3xl space-y-5">
                <div>
                    <h1 className="text-xl font-semibold tracking-tight text-slate-900">Profile Settings</h1>
                    <p className="mt-1 text-sm text-slate-500">Update your account information and manage your profile.</p>
                </div>

                <div className="rounded-lg border border-slate-200 bg-white p-6">
                    <UpdateProfileInformationForm mustVerifyEmail={mustVerifyEmail} status={status} />
                </div>

                <div className="rounded-lg border border-slate-200 bg-white p-6">
                    <UpdateProfilePhotoForm />
                </div>

                <div className="rounded-lg border border-accent-200 bg-white p-6">
                    <DeleteUserForm />
                </div>
            </div>
        </DashboardLayout>
    );
}

function UpdateProfileInformationForm({ mustVerifyEmail, status }: { mustVerifyEmail: boolean; status?: string }) {
    const { auth } = usePage().props as any;
    const user = auth?.user;

    const { data, setData, patch, errors, processing, recentlySuccessful } = useForm({
        name: user.name || '',
        email: user.email || '',
        phone: user.phone || '',
        linkedin: user.linkedin || '',
        website: user.website || '',
        headline: user.headline || '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        patch(route('profile.update'));
    };

    return (
        <section>
            <h2 className="text-sm font-semibold tracking-tight text-slate-900">Profile Information</h2>
            <p className="mt-1 text-[13px] text-slate-500">Update your account's profile information and email address.</p>

            <form onSubmit={submit} className="mt-5 space-y-4">
                <FormField label="Name" id="name" type="text" value={data.name} onChange={(e) => setData('name', e.target.value)} required error={errors.name} />
                <FormField label="Email" id="email" type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} required error={errors.email} />

                {mustVerifyEmail && user.email_verified_at === null && (
                    <div>
                        <p className="text-[13px] text-slate-600">
                            Your email address is unverified.
                            <a href={route('verification.send')} className="ml-1 underline text-primary hover:text-primary-600">
                                Click here to re-send the verification email.
                            </a>
                        </p>
                        {status === 'verification-link-sent' && (
                            <p className="mt-1 text-[13px] font-medium text-lime-600">A new verification link has been sent to your email address.</p>
                        )}
                    </div>
                )}

                <FormField label="Phone Number" id="phone" type="tel" value={data.phone} onChange={(e) => setData('phone', e.target.value)} error={errors.phone} />
                <FormField label="Headline" id="headline" type="text" value={data.headline} onChange={(e) => setData('headline', e.target.value)} placeholder="e.g., Software Engineer | Tech Enthusiast" error={errors.headline} />
                <FormField label="LinkedIn URL" id="linkedin" type="url" value={data.linkedin} onChange={(e) => setData('linkedin', e.target.value)} placeholder="https://linkedin.com/in/yourprofile" error={errors.linkedin} />
                <FormField label="Website" id="website" type="url" value={data.website} onChange={(e) => setData('website', e.target.value)} placeholder="https://yourwebsite.com" error={errors.website} />

                <div className="flex items-center gap-4">
                    <button type="submit" disabled={processing} className="rounded-md bg-primary px-5 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 disabled:opacity-50 shadow-sm">
                        Save Changes
                    </button>
                    <Transition show={recentlySuccessful} enter="transition ease-in-out" enterFrom="opacity-0" leave="transition ease-in-out" leaveTo="opacity-0">
                        <p className="text-[13px] text-slate-500">Saved.</p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}

function FormField({ label, id, type, value, onChange, required, placeholder, error }: { label: string; id: string; type: string; value: string; onChange: (e: React.ChangeEvent<HTMLInputElement>) => void; required?: boolean; placeholder?: string; error?: string }) {
    return (
        <div>
            <label htmlFor={id} className="block text-[13px] font-medium text-slate-700 mb-1">{label}</label>
            <input id={id} type={type} value={value} onChange={onChange} required={required} placeholder={placeholder}
                className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-primary-300 focus:ring-2 focus:ring-primary-500/10" />
            {error && <InputError message={error} className="mt-1.5" />}
        </div>
    );
}

function UpdateProfilePhotoForm() {
    const { auth } = usePage().props as any;
    const user = auth?.user;
    const [photoPreview, setPhotoPreview] = useState<string | null>(null);
    const photoInputRef = useRef<HTMLInputElement>(null);

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        photo: null as File | null,
    });

    const selectPhoto = () => photoInputRef.current?.click();

    const updatePhotoPreview = () => {
        const photo = photoInputRef.current?.files?.[0];
        if (!photo) return;
        setData('photo', photo);
        const reader = new FileReader();
        reader.onload = (e) => setPhotoPreview(e.target?.result as string);
        reader.readAsDataURL(photo);
    };

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('profile.photo.update'), { onSuccess: () => setPhotoPreview(null) });
    };

    return (
        <section>
            <h2 className="text-sm font-semibold tracking-tight text-slate-900">Profile Photo</h2>
            <p className="mt-1 text-[13px] text-slate-500">Update your profile photo.</p>

            <form onSubmit={submit} className="mt-5 space-y-4">
                <div className="flex items-center gap-5">
                    <div className="h-16 w-16 rounded-lg overflow-hidden bg-slate-100 flex items-center justify-center">
                        {photoPreview ? (
                            <img src={photoPreview} alt="Preview" className="h-full w-full object-cover" />
                        ) : user.photo ? (
                            <img src={`/storage/${user.photo}`} alt={user.name} className="h-full w-full object-cover" />
                        ) : (
                            <i className="fas fa-user text-slate-400"></i>
                        )}
                    </div>
                    <div>
                        <input type="file" ref={photoInputRef} onChange={updatePhotoPreview} accept="image/*" className="hidden" />
                        <button type="button" onClick={selectPhoto}
                            className="rounded-md border border-slate-200 bg-white px-4 py-2 text-[13px] font-medium text-slate-700 transition hover:bg-slate-50">
                            Select New Photo
                        </button>
                        <InputError message={errors.photo} className="mt-1.5" />
                    </div>
                </div>

                {photoPreview && (
                    <div className="flex items-center gap-3">
                        <button type="submit" disabled={processing} className="rounded-md bg-primary px-5 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600 disabled:opacity-50 shadow-sm">
                            Upload Photo
                        </button>
                        <button type="button" onClick={() => { setPhotoPreview(null); setData('photo', null); if (photoInputRef.current) photoInputRef.current.value = ''; }}
                            className="rounded-md border border-slate-200 bg-white px-4 py-2 text-[13px] font-medium text-slate-700 transition hover:bg-slate-50">
                            Cancel
                        </button>
                        <Transition show={recentlySuccessful} enter="transition ease-in-out" enterFrom="opacity-0" leave="transition ease-in-out" leaveTo="opacity-0">
                            <p className="text-[13px] text-slate-500">Saved.</p>
                        </Transition>
                    </div>
                )}
            </form>
        </section>
    );
}

function DeleteUserForm() {
    const [confirmingUserDeletion, setConfirmingUserDeletion] = useState(false);
    const { data, setData, delete: destroy, processing, reset, errors } = useForm({ password: '' });

    const deleteUser: FormEventHandler = (e) => {
        e.preventDefault();
        destroy(route('profile.destroy'), { preserveScroll: true, onSuccess: () => closeModal(), onError: () => reset('password'), onFinish: () => reset('password') });
    };

    const closeModal = () => { setConfirmingUserDeletion(false); reset('password'); };

    return (
        <section>
            <h2 className="text-sm font-semibold tracking-tight text-accent">Delete Account</h2>
            <p className="mt-1 text-[13px] text-slate-500">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            <button type="button" onClick={() => setConfirmingUserDeletion(true)} className="mt-4 rounded-md bg-accent px-5 py-2.5 text-sm font-medium text-white transition hover:bg-accent-600">
                Delete Account
            </button>

            {confirmingUserDeletion && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/30 px-4 backdrop-blur-sm">
                    <div className="w-full max-w-md rounded-lg border border-slate-200 bg-white p-6 shadow-lg">
                        <h3 className="text-base font-semibold text-slate-900">Are you sure you want to delete your account?</h3>
                        <p className="mt-2 text-sm text-slate-500">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.</p>
                        <form onSubmit={deleteUser} className="mt-5 space-y-4">
                            <div>
                                <label htmlFor="password" className="block text-[13px] font-medium text-slate-700 mb-1">Password</label>
                                <input id="password" type="password" value={data.password} onChange={(e) => setData('password', e.target.value)} placeholder="Enter your password"
                                    className="w-full rounded-md border border-slate-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-accent-300 focus:ring-2 focus:ring-accent-500/10" />
                                <InputError message={errors.password} className="mt-1.5" />
                            </div>
                            <div className="flex justify-end gap-3">
                                <button type="button" onClick={closeModal} className="rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cancel</button>
                                <button type="submit" disabled={processing} className="rounded-md bg-accent px-4 py-2 text-sm font-medium text-white transition hover:bg-accent-600 disabled:opacity-50">Delete Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </section>
    );
}
