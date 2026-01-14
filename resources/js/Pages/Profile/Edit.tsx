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

            <section className="min-h-screen bg-linear-to-br from-gray-50 to-primary-50/30">
                <div className="max-w-4xl space-y-6">
                    {/* Header */}
                    <div className="mb-8">
                        <h1 className="text-3xl font-bold font-montserrat" style={{ color: '#002147' }}>
                            Profile Settings
                        </h1>
                        <p className="text-gray-600 mt-2 font-lato leading-relaxed">
                            Update your account information and manage your profile.
                        </p>
                    </div>

                    {/* Profile Information */}
                    <div className="bg-white rounded-2xl shadow-lg border border-primary-100 p-8">
                        <UpdateProfileInformationForm
                            mustVerifyEmail={mustVerifyEmail}
                            status={status}
                        />
                    </div>

                    {/* Profile Photo */}
                    <div className="bg-white rounded-2xl shadow-lg border border-primary-100 p-8">
                        <UpdateProfilePhotoForm />
                    </div>

                    {/* Delete Account */}
                    <div className="bg-white rounded-2xl shadow-lg border border-red-100 p-8">
                        <DeleteUserForm />
                    </div>
                </div>
            </section>
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
            <header>
                <h2 className="text-xl font-bold font-montserrat" style={{ color: '#002147' }}>
                    Profile Information
                </h2>
                <p className="mt-1 text-sm text-gray-600 font-lato">
                    Update your account's profile information and email address.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div>
                    <label htmlFor="name" className="block text-sm font-medium text-gray-700 font-lato mb-1">
                        Name
                    </label>
                    <input
                        id="name"
                        type="text"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        required
                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-lato"
                    />
                    <InputError message={errors.name} className="mt-2" />
                </div>

                <div>
                    <label htmlFor="email" className="block text-sm font-medium text-gray-700 font-lato mb-1">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        required
                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-lato"
                    />
                    <InputError message={errors.email} className="mt-2" />
                </div>

                {mustVerifyEmail && user.email_verified_at === null && (
                    <div>
                        <p className="text-sm text-gray-800 font-lato">
                            Your email address is unverified.
                            <a
                                href={route('verification.send')}
                                className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 ml-1"
                            >
                                Click here to re-send the verification email.
                            </a>
                        </p>

                        {status === 'verification-link-sent' && (
                            <div className="mt-2 font-medium text-sm text-green-600 font-lato">
                                A new verification link has been sent to your email address.
                            </div>
                        )}
                    </div>
                )}

                <div>
                    <label htmlFor="phone" className="block text-sm font-medium text-gray-700 font-lato mb-1">
                        Phone Number
                    </label>
                    <input
                        id="phone"
                        type="tel"
                        value={data.phone}
                        onChange={(e) => setData('phone', e.target.value)}
                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-lato"
                    />
                    <InputError message={errors.phone} className="mt-2" />
                </div>

                <div>
                    <label htmlFor="headline" className="block text-sm font-medium text-gray-700 font-lato mb-1">
                        Headline
                    </label>
                    <input
                        id="headline"
                        type="text"
                        value={data.headline}
                        onChange={(e) => setData('headline', e.target.value)}
                        placeholder="e.g., Software Engineer | Tech Enthusiast"
                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-lato"
                    />
                    <InputError message={errors.headline} className="mt-2" />
                </div>

                <div>
                    <label htmlFor="linkedin" className="block text-sm font-medium text-gray-700 font-lato mb-1">
                        LinkedIn URL
                    </label>
                    <input
                        id="linkedin"
                        type="url"
                        value={data.linkedin}
                        onChange={(e) => setData('linkedin', e.target.value)}
                        placeholder="https://linkedin.com/in/yourprofile"
                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-lato"
                    />
                    <InputError message={errors.linkedin} className="mt-2" />
                </div>

                <div>
                    <label htmlFor="website" className="block text-sm font-medium text-gray-700 font-lato mb-1">
                        Website
                    </label>
                    <input
                        id="website"
                        type="url"
                        value={data.website}
                        onChange={(e) => setData('website', e.target.value)}
                        placeholder="https://yourwebsite.com"
                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-lato"
                    />
                    <InputError message={errors.website} className="mt-2" />
                </div>

                <div className="flex items-center gap-4">
                    <button
                        type="submit"
                        disabled={processing}
                        className="px-6 py-2.5 text-white rounded-lg font-medium font-lato transition-all duration-300 disabled:opacity-50"
                        style={{ backgroundColor: '#002147' }}
                    >
                        Save Changes
                    </button>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0"
                    >
                        <p className="text-sm text-gray-600 font-lato">Saved.</p>
                    </Transition>
                </div>
            </form>
        </section>
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

    const selectPhoto = () => {
        photoInputRef.current?.click();
    };

    const updatePhotoPreview = () => {
        const photo = photoInputRef.current?.files?.[0];

        if (!photo) return;

        setData('photo', photo);

        const reader = new FileReader();
        reader.onload = (e) => {
            setPhotoPreview(e.target?.result as string);
        };
        reader.readAsDataURL(photo);
    };

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('profile.photo.update'), {
            onSuccess: () => {
                setPhotoPreview(null);
            },
        });
    };

    return (
        <section>
            <header>
                <h2 className="text-xl font-bold font-montserrat" style={{ color: '#002147' }}>
                    Profile Photo
                </h2>
                <p className="mt-1 text-sm text-gray-600 font-lato">
                    Update your profile photo.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div className="flex items-center gap-6">
                    {/* Current Photo */}
                    <div className="w-20 h-20 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                        {photoPreview ? (
                            <img src={photoPreview} alt="Preview" className="w-full h-full object-cover" />
                        ) : user.photo ? (
                            <img src={`/storage/${user.photo}`} alt={user.name} className="w-full h-full object-cover" />
                        ) : (
                            <i className="fas fa-user text-gray-400 text-2xl"></i>
                        )}
                    </div>

                    {/* Select Photo Button */}
                    <div>
                        <input
                            type="file"
                            ref={photoInputRef}
                            onChange={updatePhotoPreview}
                            accept="image/*"
                            className="hidden"
                        />
                        <button
                            type="button"
                            onClick={selectPhoto}
                            className="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 font-lato transition-colors"
                        >
                            Select New Photo
                        </button>
                        <InputError message={errors.photo} className="mt-2" />
                    </div>
                </div>

                {photoPreview && (
                    <div className="flex items-center gap-4">
                        <button
                            type="submit"
                            disabled={processing}
                            className="px-6 py-2.5 text-white rounded-lg font-medium font-lato transition-all duration-300 disabled:opacity-50"
                            style={{ backgroundColor: '#002147' }}
                        >
                            Upload Photo
                        </button>

                        <button
                            type="button"
                            onClick={() => {
                                setPhotoPreview(null);
                                setData('photo', null);
                                if (photoInputRef.current) {
                                    photoInputRef.current.value = '';
                                }
                            }}
                            className="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 font-lato transition-colors"
                        >
                            Cancel
                        </button>

                        <Transition
                            show={recentlySuccessful}
                            enter="transition ease-in-out"
                            enterFrom="opacity-0"
                            leave="transition ease-in-out"
                            leaveTo="opacity-0"
                        >
                            <p className="text-sm text-gray-600 font-lato">Saved.</p>
                        </Transition>
                    </div>
                )}
            </form>
        </section>
    );
}

function DeleteUserForm() {
    const [confirmingUserDeletion, setConfirmingUserDeletion] = useState(false);

    const { data, setData, delete: destroy, processing, reset, errors } = useForm({
        password: '',
    });

    const confirmUserDeletion = () => {
        setConfirmingUserDeletion(true);
    };

    const deleteUser: FormEventHandler = (e) => {
        e.preventDefault();

        destroy(route('profile.destroy'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => reset('password'),
            onFinish: () => reset('password'),
        });
    };

    const closeModal = () => {
        setConfirmingUserDeletion(false);
        reset('password');
    };

    return (
        <section>
            <header>
                <h2 className="text-xl font-bold font-montserrat text-red-600">
                    Delete Account
                </h2>
                <p className="mt-1 text-sm text-gray-600 font-lato">
                    Once your account is deleted, all of its resources and data will be permanently deleted.
                </p>
            </header>

            <div className="mt-6">
                <button
                    type="button"
                    onClick={confirmUserDeletion}
                    className="px-6 py-2.5 bg-red-600 text-white rounded-lg font-medium font-lato hover:bg-red-700 transition-colors"
                >
                    Delete Account
                </button>
            </div>

            {/* Confirmation Modal */}
            {confirmingUserDeletion && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
                        <h3 className="text-xl font-bold font-montserrat" style={{ color: '#002147' }}>
                            Are you sure you want to delete your account?
                        </h3>
                        <p className="mt-4 text-sm text-gray-600 font-lato">
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                            Please enter your password to confirm.
                        </p>

                        <form onSubmit={deleteUser} className="mt-6 space-y-6">
                            <div>
                                <label htmlFor="password" className="block text-sm font-medium text-gray-700 font-lato mb-1">
                                    Password
                                </label>
                                <input
                                    id="password"
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    placeholder="Enter your password"
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent font-lato"
                                />
                                <InputError message={errors.password} className="mt-2" />
                            </div>

                            <div className="flex items-center gap-4">
                                <button
                                    type="button"
                                    onClick={closeModal}
                                    className="px-6 py-2.5 border border-gray-300 rounded-lg font-medium font-lato hover:bg-gray-50 transition-colors"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-6 py-2.5 bg-red-600 text-white rounded-lg font-medium font-lato hover:bg-red-700 transition-colors disabled:opacity-50"
                                >
                                    Delete Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </section>
    );
}
