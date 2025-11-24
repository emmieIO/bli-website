# 📋 Shared Hosting Deployment Checklist

Quick reference checklist for deploying to shared hosting.

## Before Upload

- [ ] Run `npm run build` to build frontend assets
- [ ] Ensure `.env.example` is updated with all required variables
- [ ] Remove sensitive data from repository
- [ ] Test locally one final time
- [ ] Create backup of current live site (if updating)

## On Shared Hosting

### 1. File Upload
- [ ] Upload project to `~/laravel-app/` (outside public_html)
- [ ] Upload `vendor/` folder (or run composer install)
- [ ] Upload `public/build/` folder with compiled assets

### 2. Environment Setup
- [ ] Copy `.env.example` to `.env`
- [ ] Run `php artisan key:generate`
- [ ] Update `.env` with production values:
  - [ ] `APP_ENV=production`
  - [ ] `APP_DEBUG=false`
  - [ ] `APP_URL=https://yourdomain.com`
  - [ ] Database credentials
  - [ ] Mail SMTP settings
  - [ ] Vimeo API credentials (if using)

### 3. Database
- [ ] Create MySQL database in cPanel
- [ ] Create database user and assign to database
- [ ] Update `.env` with database credentials
- [ ] Run `php artisan migrate --force`
- [ ] (Optional) Run `php artisan db:seed --force`

### 4. Public Directory
- [ ] Link/copy `laravel-app/public/*` to `public_html/`
- [ ] Update `public_html/index.php` paths if using copy method
- [ ] Verify `.htaccess` exists in `public_html/`

### 5. Permissions
- [ ] `chmod -R 775 storage`
- [ ] `chmod -R 775 bootstrap/cache`
- [ ] `chmod -R 755 public_html`

### 6. Optimization
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `php artisan storage:link`

### 7. Queue Setup (For Notifications)
- [ ] Add cron job in cPanel:
  ```
  * * * * * cd /home/username/laravel-app && php artisan queue:work --stop-when-empty
  ```
- [ ] Test queue is processing: `php artisan queue:work --once`

### 8. Testing
- [ ] Visit homepage - should load without errors
- [ ] Test user registration
- [ ] Test user login
- [ ] Test course creation (instructor)
- [ ] Test course submission for review
- [ ] Check notification bell works
- [ ] Verify email sending works
- [ ] Test file uploads

### 9. Security
- [ ] Verify `APP_DEBUG=false`
- [ ] Verify `.env` is NOT accessible via browser
- [ ] Verify `storage/` is NOT accessible via browser
- [ ] Install SSL certificate (HTTPS)
- [ ] Test all forms for CSRF protection

### 10. Performance
- [ ] Enable OpCache (if available)
- [ ] Test page load speeds
- [ ] Verify images are optimized
- [ ] Check database query performance

## Quick Commands

### Clear Everything
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### Rebuild Cache
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Check Queue
```bash
php artisan queue:work --once
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

## Common Issues

| Issue | Solution |
|-------|----------|
| 500 Error | Check permissions on `storage/` and `bootstrap/cache/` |
| Assets 404 | Verify `public/build/` folder exists and `ASSET_URL` in `.env` |
| DB Connection Error | Check credentials, use `localhost` not `127.0.0.1` |
| Queue not processing | Verify cron job is set up and running |
| Notifications not sending | Check `jobs` table exists and queue cron is running |

## Post-Deployment

- [ ] Monitor `storage/logs/laravel.log` for errors
- [ ] Test all major features
- [ ] Inform users of any changes
- [ ] Document any custom configurations
- [ ] Schedule regular backups

## Useful cPanel Paths

```
Project Root:    /home/username/laravel-app/
Public Dir:      /home/username/public_html/
Logs:           /home/username/laravel-app/storage/logs/
```

## Support Contacts

- **Hosting Support:** [Your hosting provider]
- **Laravel Docs:** https://laravel.com/docs
- **Project Issues:** [Your repository issues page]

---

💡 **Tip:** Use the `deploy.sh` script to automate most of these steps!

```bash
./deploy.sh
```
