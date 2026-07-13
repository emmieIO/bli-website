# Production Readiness

## Verified locally

- `php artisan test` passes.
- `npx tsc --noEmit` passes.
- `npm run build` produces the production bundle.
- All migrations apply successfully to MySQL.
- Operational HTTP endpoints fail closed without `QUEUE_TOKEN`.
- Public events, authenticated dashboard, event creation, and mobile navigation pass browser smoke tests.

## Required production configuration

- Set `APP_ENV=production`, `APP_DEBUG=false`, a valid `APP_KEY`, and the HTTPS `APP_URL`.
- Set a long random `QUEUE_TOKEN`. Prefer `Authorization: Bearer <token>` for external cron calls.
- Configure a real mail transport and verified `MAIL_FROM_ADDRESS`; do not deploy with `MAIL_MAILER=log`.
- Configure production database, cache, session, queue, and filesystem credentials.
- Configure Paystack live keys and confirm the webhook URL and signature verification.
- Configure the scheduler to run `php artisan schedule:run` every minute.
- Run a persistent queue worker under a process supervisor. Use `/process-queue` only when the host cannot run workers.
- Confirm `public/storage` points to `storage/app/public` and uploaded files are durable across releases.
- Confirm HTTPS, secure cookies, backups, log retention, and error monitoring.

## Deployment sequence

1. Back up the production database and uploaded files.
2. Put the application in maintenance mode.
3. Install production PHP and Node dependencies from lock files.
4. Build frontend assets.
5. Run `php artisan migrate --force`.
6. Run `php artisan optimize` and restart queue workers.
7. Bring the application online and repeat the public, login, event, payment, mail, and mobile smoke tests.
8. Keep the database backup until the release has been observed successfully.
