# Inertia.js SSR Migration Guide

## 🎉 Setup Complete!

Your Laravel application is now configured with:
- ✅ **Inertia.js** for seamless SPA experience
- ✅ **React 18** with TypeScript
- ✅ **Server-Side Rendering (SSR)** ready
- ✅ **Vite** for lightning-fast builds
- ✅ **Ziggy** for Laravel route helpers in React
- ✅ **Tailwind CSS** (existing configuration preserved)

---

## 📦 What's Been Installed

### NPM Packages
```json
{
  "dependencies": {
    "@inertiajs/react": "Latest",
    "react": "^18.x",
    "react-dom": "^18.x"
  },
  "devDependencies": {
    "@inertiajs/server": "Latest (SSR)",
    "@vitejs/plugin-react": "Latest",
    "typescript": "Latest",
    "@types/react": "Latest",
    "@types/react-dom": "Latest"
  }
}
```

### Composer Packages
```json
{
  "require": {
    "inertiajs/inertia-laravel": "^2.0",
    "tightenco/ziggy": "^2.6"
  }
}
```

---

## 📁 New File Structure

```
resources/
├── js/
│   ├── types/
│   │   ├── index.d.ts          # App-specific types (User, Course, Event, etc.)
│   │   └── global.d.ts         # Global type declarations
│   ├── Pages/                  # React pages (will replace Blade views)
│   │   ├── Auth/
│   │   ├── Dashboard/
│   │   ├── Courses/
│   │   ├── Events/
│   │   └── ...
│   ├── Components/             # Shared React components
│   ├── Layouts/                # Layout components
│   ├── app.tsx                 # Client-side entry
│   ├── ssr.tsx                 # Server-side entry (SSR)
│   └── bootstrap.ts            # Axios setup
├── views/
│   └── app.blade.php          # Root Inertia template
└── css/
    └── app.css                 # Tailwind styles

app/
└── Http/
    └── Middleware/
        └── HandleInertiaRequests.php  # Inertia middleware

vite.config.ts                  # Vite configuration
tsconfig.json                   # TypeScript configuration
```

---

## 🚀 Next Steps: Converting Blade to Inertia

### Step 1: Update a Controller

**Before (Blade):**
```php
public function login()
{
    return view('auth.login');
}
```

**After (Inertia):**
```php
use Inertia\Inertia;

public function login()
{
    return Inertia::render('Auth/Login');
}
```

### Step 2: Create the React Page

Create `resources/js/Pages/Auth/Login.tsx`:

```typescript
import { FormEventHandler, useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { PageProps } from '@/types';

export default function Login({ auth }: PageProps) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login.store'));
    };

    return (
        <>
            <Head title="Login" />

            <div className="min-h-screen flex items-center justify-center bg-gray-50">
                <div className="max-w-md w-full space-y-8 p-8 bg-white rounded-xl shadow-lg">
                    <div>
                        <h2 className="text-3xl font-bold text-center text-gray-900">
                            Welcome Back
                        </h2>
                        <p className="text-center text-gray-600 mt-2">
                            Sign in to your account to continue
                        </p>
                    </div>

                    <form onSubmit={submit} className="space-y-6">
                        {/* Email Field */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700">
                                Email Address
                            </label>
                            <input
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required
                            />
                            {errors.email && (
                                <p className="text-sm text-red-600 mt-1">{errors.email}</p>
                            )}
                        </div>

                        {/* Password Field */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700">
                                Password
                            </label>
                            <input
                                type="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required
                            />
                            {errors.password && (
                                <p className="text-sm text-red-600 mt-1">{errors.password}</p>
                            )}
                        </div>

                        {/* Remember Me */}
                        <div className="flex items-center justify-between">
                            <label className="flex items-center">
                                <input
                                    type="checkbox"
                                    checked={data.remember}
                                    onChange={(e) => setData('remember', e.target.checked)}
                                    className="rounded border-gray-300 text-blue-600"
                                />
                                <span className="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>

                            <Link
                                href={route('password.request')}
                                className="text-sm text-blue-600 hover:text-blue-500"
                            >
                                Forgot password?
                            </Link>
                        </div>

                        {/* Submit Button */}
                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 disabled:opacity-50"
                        >
                            Sign In
                        </button>
                    </form>

                    <div className="text-center">
                        <p className="text-gray-600">
                            Don't have an account?{' '}
                            <Link href={route('register')} className="text-blue-600 font-medium">
                                Sign up
                            </Link>
                        </p>
                    </div>
                </div>
            </div>
        </>
    );
}
```

---

## 🎨 Creating Shared Layouts

Create `resources/js/Layouts/AppLayout.tsx`:

```typescript
import { PropsWithChildren } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';

export default function AppLayout({ children }: PropsWithChildren) {
    const { auth, flash } = usePage<PageProps>().props;

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Navigation */}
            <nav className="bg-white shadow">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex items-center">
                            <Link href={route('homepage')} className="text-xl font-bold">
                                BLI
                            </Link>
                        </div>

                        <div className="flex items-center space-x-4">
                            {auth.user ? (
                                <>
                                    <span className="text-gray-700">{auth.user.name}</span>
                                    <Link
                                        href={route('logout')}
                                        method="post"
                                        as="button"
                                        className="text-gray-700 hover:text-gray-900"
                                    >
                                        Logout
                                    </Link>
                                </>
                            ) : (
                                <>
                                    <Link href={route('login')} className="text-gray-700">
                                        Login
                                    </Link>
                                    <Link href={route('register')} className="bg-blue-600 text-white px-4 py-2 rounded">
                                        Register
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </nav>

            {/* Flash Messages */}
            {flash.message && (
                <div className={`max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4`}>
                    <div
                        className={`p-4 rounded-lg ${
                            flash.type === 'success'
                                ? 'bg-green-50 text-green-800'
                                : flash.type === 'error'
                                ? 'bg-red-50 text-red-800'
                                : 'bg-blue-50 text-blue-800'
                        }`}
                    >
                        {flash.message}
                    </div>
                </div>
            )}

            {/* Main Content */}
            <main>{children}</main>
        </div>
    );
}
```

---

## 🔄 Using the Layout

```typescript
import AppLayout from '@/Layouts/AppLayout';

export default function Dashboard() {
    return (
        <AppLayout>
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <h1 className="text-3xl font-bold">Dashboard</h1>
                {/* Your content */}
            </div>
        </AppLayout>
    );
}
```

---

## 🏗️ Building & Running

### Development
```bash
# Terminal 1: Run Laravel
php artisan serve

# Terminal 2: Run Vite dev server
npm run dev
```

### Build for Production
```bash
# Build both client and SSR
npm run build

# If SSR is enabled, also run:
php artisan inertia:start-ssr
```

---

## 🔍 Useful Inertia Patterns

### 1. Using Route Helper
```typescript
import { router } from '@inertiajs/react';

// Navigate programmatically
router.visit(route('courses.show', { course: course.slug }));

// With Link component
<Link href={route('courses.index')}>View Courses</Link>
```

### 2. Form Handling
```typescript
const { data, setData, post, processing, errors, reset } = useForm({
    title: '',
    description: '',
});

const submit = (e) => {
    e.preventDefault();
    post(route('courses.store'), {
        onSuccess: () => reset(),
    });
};
```

### 3. Accessing Shared Data
```typescript
const { auth, flash, ziggy } = usePage<PageProps>().props;

// Check if user is logged in
if (auth.user) {
    // User is authenticated
}

// Check user permissions
if (auth.user?.permissions.includes('course-create')) {
    // Has permission
}
```

### 4. File Uploads
```typescript
const { data, setData, post } = useForm({
    thumbnail: null as File | null,
});

post(route('courses.store'), {
    forceFormData: true,
});
```

---

## 📊 Migration Checklist

- [x] Inertia.js installed and configured
- [x] React + TypeScript setup
- [x] SSR configured
- [x] Type definitions created
- [x] Middleware registered
- [ ] Convert authentication pages
- [ ] Convert dashboard pages
- [ ] Convert course pages
- [ ] Convert event pages
- [ ] Convert speaker pages
- [ ] Convert admin pages
- [ ] Create shared components library
- [ ] Test SSR rendering
- [ ] Deploy and test in production

---

## 🎯 Recommended Conversion Order

1. **Auth Pages** (Login, Register, Forgot Password, Reset Password)
2. **Dashboard** (User dashboard, profile)
3. **Public Pages** (Homepage, About)
4. **Course Pages** (List, Detail, Enroll)
5. **Event Pages** (List, Detail, Register)
6. **Speaker Pages** (Application, Profile)
7. **Admin Pages** (All admin functionality)
8. **Instructor Pages** (Course builder, etc.)

---

## 🛠️ Troubleshooting

### Issue: "Module not found"
**Solution:** Make sure paths in `tsconfig.json` match your file structure.

### Issue: SSR not working
**Solution:** Run `npm run build` then `php artisan inertia:start-ssr`

### Issue: Types not recognized
**Solution:** Restart TypeScript server in your IDE.

### Issue: Ziggy route() not found
**Solution:** Make sure `@routes` directive is in `app.blade.php`

---

## 📚 Resources

- [Inertia.js Docs](https://inertiajs.com)
- [React TypeScript Cheatsheet](https://react-typescript-cheatsheet.netlify.app)
- [Tailwind CSS](https://tailwindcss.com)
- [Ziggy (Laravel Routes in JS)](https://github.com/tighten/ziggy)

---

## ✨ Next: Install shadcn/ui

For beautiful, accessible components:

```bash
npx shadcn-ui@latest init
```

Then add components as needed:
```bash
npx shadcn-ui@latest add button
npx shadcn-ui@latest add form
npx shadcn-ui@latest add input
npx shadcn-ui@latest add dialog
```

---

**Your Inertia.js setup is complete and ready to use!** 🎉

Start by converting one page at a time, testing as you go. The gradual migration approach allows both Blade and Inertia pages to coexist.
