# Quick Server Update Instructions

After pulling the latest changes to your shared hosting server, run these commands:

```bash
# 1. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Optimize application (this should work now)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. If you haven't run migrations yet
php artisan migrate --force

# 4. Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## What Was Fixed

### Route Conflicts Fixed
- **Issue 1:** Duplicate route name `profile.update` was defined in both `routes/auth.php` and `routes/user.php`
  - **Solution:** Renamed in `auth.php`: `profile.update` → `profile.update_information`

- **Issue 2:** Duplicate route name `profile.photo.update` in both route files
  - **Solution:** Renamed in `auth.php`: `profile.photo.update` → `profile.photo.update_legacy`

- **Result:** Route optimization now works without errors

### Latest Features Deployed
✅ Complete notification system (bell icon in dashboard)
✅ Course approval workflow (submit → review → approve/reject)
✅ Profile dropdown now works on click instead of hover
✅ Course authorization (only approved courses visible to public)
✅ Email and database notifications for course workflow

## Verify Everything Works

1. **Homepage:** Visit your domain - should load normally
2. **Login:** Test user login
3. **Notifications:** Check bell icon in dashboard (top right)
4. **Profile:** Click profile dropdown (should work on click)
5. **Courses:** Browse courses (only approved ones should show)

## If You Still Get Errors

Check the Laravel log:
```bash
tail -50 storage/logs/laravel.log
```

Clear everything and try again:
```bash
php artisan optimize:clear
composer dump-autoload
php artisan config:cache
php artisan route:cache
```

## Queue Setup (If Not Done)

Add this cron job in cPanel for notifications to work:
```
* * * * * cd /home/username/path-to-laravel && php artisan queue:work --stop-when-empty
```

Replace:
- `username` with your cPanel username
- `path-to-laravel` with actual path (e.g., `/home/wizbrnsq/beaconinst.org`)
