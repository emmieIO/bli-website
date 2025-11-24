# Shared Hosting Deployment Guide

This guide will help you deploy your Laravel application to a shared hosting environment (e.g., cPanel, Namecheap, Hostinger, etc.).

## 📋 Prerequisites

- Shared hosting account with:
  - PHP 8.2 or higher
  - MySQL/MariaDB database
  - SSH access (recommended) or File Manager
  - Composer support (or you can upload vendor folder)
- Domain name pointed to your hosting

## 🗂️ Folder Structure on Shared Hosting

Most shared hosts have this structure:
```
/home/username/
├── public_html/          ← Your domain's document root
├── tmp/
├── logs/
└── other folders...
```

We'll place your Laravel app outside `public_html` for security:
```
/home/username/
├── laravel-app/          ← Laravel application (outside public_html)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/           ← This will link to public_html
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   └── ...
└── public_html/          ← Symlink or copy from laravel-app/public
```

## 📤 Step 1: Upload Files

### Option A: Using SSH (Recommended)

```bash
# 1. Connect to your server
ssh username@yourdomain.com

# 2. Navigate to home directory
cd ~

# 3. Upload your Laravel project (from your local machine)
# Use SCP or rsync from your local terminal:
scp -r /path/to/bli-website username@yourdomain.com:~/laravel-app

# Or use Git (if available)
git clone https://github.com/yourusername/bli-website.git laravel-app
cd laravel-app
```

### Option B: Using File Manager (cPanel)

1. Compress your project into a ZIP file (exclude `node_modules` and `vendor`)
2. Log into cPanel → File Manager
3. Navigate to your home directory (not public_html)
4. Upload the ZIP file
5. Extract it to create `laravel-app` folder

## 🔧 Step 2: Install Dependencies

### If Composer is Available on Server:

```bash
cd ~/laravel-app
composer install --no-dev --optimize-autoloader
```

### If Composer is NOT Available:

On your local machine:
```bash
composer install --no-dev --optimize-autoloader
zip -r vendor.zip vendor/
```

Then upload `vendor.zip` to server and extract it in `laravel-app/`

## 🔐 Step 3: Environment Configuration

```bash
cd ~/laravel-app

# Copy example env file
cp .env.example .env

# Edit .env file
nano .env  # or use cPanel File Manager editor
```

### Required .env Settings:

```env
APP_NAME="BLI Website"
APP_ENV=production
APP_KEY=                          # Generate this (see next step)
APP_DEBUG=false                   # IMPORTANT: Set to false in production
APP_URL=https://yourdomain.com

# Database (get these from cPanel)
DB_CONNECTION=mysql
DB_HOST=localhost                 # Usually localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database

# Queue (database is perfect for shared hosting)
QUEUE_CONNECTION=database

# Mail (configure with your hosting's SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# File Storage
FILESYSTEM_DISK=local

# Vimeo (if using video uploads)
VIMEO_CLIENT=your-vimeo-client-id
VIMEO_SECRET=your-vimeo-client-secret
VIMEO_ACCESS=your-vimeo-access-token
VIMEO_ALLOWED_DOMAINS=yourdomain.com,www.yourdomain.com
```

## 🔑 Step 4: Generate Application Key

```bash
php artisan key:generate
```

This will automatically update your `.env` file with `APP_KEY`.

## 🗄️ Step 5: Database Setup

### Create Database in cPanel:

1. Log into cPanel
2. Go to **MySQL Databases**
3. Create a new database (e.g., `username_bli`)
4. Create a new MySQL user
5. Add user to database with ALL PRIVILEGES
6. Note down: database name, username, and password

### Run Migrations:

```bash
cd ~/laravel-app
php artisan migrate --force
```

### Seed Database (if needed):

```bash
php artisan db:seed --force
```

## 📁 Step 6: Setup Public Directory

You have two options:

### Option A: Symlink (If Allowed)

```bash
# Remove default public_html
rm -rf ~/public_html

# Create symlink
ln -s ~/laravel-app/public ~/public_html
```

### Option B: Copy Files (If Symlinks Not Allowed)

```bash
# Copy all files from public to public_html
cp -r ~/laravel-app/public/* ~/public_html/

# Create .htaccess in public_html root
nano ~/public_html/index.php
```

Edit `public_html/index.php` to update paths:

```php
<?php

define('LARAVEL_START', microtime(true));

// Update these paths to point to laravel-app folder
require __DIR__.'/../laravel-app/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel-app/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
// ... rest remains the same
```

## 🔒 Step 7: Set Permissions

```bash
cd ~/laravel-app

# Storage and cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# If using Option B (copy method), also set permissions on public_html
chmod -R 755 ~/public_html
```

## 🏗️ Step 8: Build Assets

### On Your Local Machine (Before Upload):

```bash
npm run build
```

This creates optimized files in `public/build/`. Make sure to upload this folder.

### Or on Server (If Node.js Available):

```bash
cd ~/laravel-app
npm install
npm run build
```

## ⚙️ Step 9: Optimize Application

```bash
cd ~/laravel-app

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

## 📧 Step 10: Setup Queue Worker (For Notifications)

Since shared hosting doesn't support `queue:work` daemon, use cron jobs:

### Add Cron Job in cPanel:

1. Go to cPanel → **Cron Jobs**
2. Add new cron job:

**Run Every Minute:**
```
* * * * * cd /home/username/laravel-app && php artisan queue:work --stop-when-empty
```

**Or Every 5 Minutes (lighter load):**
```
*/5 * * * * cd /home/username/laravel-app && php artisan queue:work --stop-when-empty
```

### Alternative: Laravel Scheduler Cron

```
* * * * * cd /home/username/laravel-app && php artisan schedule:run >> /dev/null 2>&1
```

Then in `app/Console/Kernel.php`, add:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('queue:work --stop-when-empty')->everyMinute();
}
```

## 🔍 Step 11: Test Your Application

1. Visit `https://yourdomain.com`
2. Test registration/login
3. Test course creation and submission
4. Check if notifications are working
5. Verify email sending

## 🐛 Troubleshooting

### 500 Internal Server Error

1. Check `.env` file exists and `APP_KEY` is set
2. Check file permissions (775 for storage, 755 for public)
3. Check PHP version (should be 8.2+)
4. Enable error reporting temporarily:
   ```env
   APP_DEBUG=true
   ```
5. Check error logs in cPanel or `storage/logs/laravel.log`

### "The stream or file could not be opened"

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Assets Not Loading (404 for CSS/JS)

1. Make sure `public/build/` folder is uploaded
2. Check `.env` has correct `APP_URL`
3. Verify `ASSET_URL=/build` in `.env`

### Database Connection Error

1. Verify database credentials in `.env`
2. Ensure database user has proper permissions
3. Use `localhost` for `DB_HOST` (not 127.0.0.1)

### Notifications Not Sending

1. Check `jobs` table exists:
   ```bash
   php artisan migrate
   ```
2. Verify cron job is running:
   ```bash
   php artisan queue:work --stop-when-empty
   ```
3. Check `storage/logs/laravel.log` for errors

### "Route not found" Errors

```bash
php artisan route:clear
php artisan route:cache
```

## 🔄 Updating Your Application

```bash
# 1. Upload new files (via FTP/SSH)
# 2. Run migrations
php artisan migrate --force

# 3. Clear and rebuild cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Rebuild frontend assets (if changed)
npm run build  # On local machine, then upload public/build/
```

## 📊 Performance Optimization for Shared Hosting

### 1. Enable OpCache (via php.ini or .user.ini)

Create `.user.ini` in `laravel-app/`:

```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
```

### 2. Use Database for Sessions and Cache

Already configured in `.env`:
```env
SESSION_DRIVER=database
CACHE_STORE=database
```

### 3. Minimize Queue Processing

Process queues less frequently if server resources are limited:
```
*/15 * * * * cd /home/username/laravel-app && php artisan queue:work --stop-when-empty
```

## 🔐 Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` generated
- [ ] `.env` file not accessible via web (should be outside public_html)
- [ ] `storage/` and `bootstrap/cache/` writable but not publicly accessible
- [ ] Database credentials are strong
- [ ] HTTPS/SSL certificate installed
- [ ] Firewall rules configured (if available)

## 📞 Support

If you encounter issues:

1. Check `storage/logs/laravel.log`
2. Enable debug mode temporarily: `APP_DEBUG=true`
3. Contact your hosting provider for PHP/server configuration
4. Check Laravel documentation: https://laravel.com/docs

---

**Note:** Some shared hosts have specific requirements or control panels. Adjust paths and commands according to your hosting provider's documentation.
