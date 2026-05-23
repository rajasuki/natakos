# AGENTS.md

## Stack
- Laravel 13 / PHP 8.3 backend with Blade views, Vite 8, and Tailwind 4.
- `resources/js/app.js` is effectively empty; most frontend work happens in Blade templates with large inline `<style>` blocks in `resources/views/public/layout.blade.php`, `resources/views/admin/layout.blade.php`, and `resources/views/tenant/layout.blade.php`.

## Commands
- Use `composer setup` for first-time bootstrap. It installs Composer deps, creates `.env`, generates the app key, runs `php artisan migrate --force`, then runs `npm install --ignore-scripts` and `npm run build`.
- Root `.npmrc` sets `ignore-scripts=true`; keep using `npm install --ignore-scripts` instead of a plain `npm install`.
- Use `composer dev` for the real local loop. It starts `php artisan serve`, `php artisan queue:listen --tries=1 --timeout=0`, `php artisan pail --timeout=0`, and `npm run dev` together.
- Use `composer test` for the standard PHP check; it clears config first, then runs `php artisan test`.
- Focused PHP runs: `php artisan test tests/Unit/PaymentWorkflowTest.php` or `php artisan test --filter PaymentWorkflow`.
- There is no repo-local JS lint or typecheck script. For frontend-only verification, `npm run build` is the only packaged check. For PHP formatting, use `vendor/bin/pint`.

## App Shape
- Routing is split by role. Public pages are `/` and `/rooms*`; authenticated users hit `/dashboard`, then `AuthenticatedSessionController` redirects by `users.role` to `/admin/*` or `/tenant/*`.
- Invalid user roles are treated as a real failure path: both the auth controller and the `admin` / `tenant` middleware log the user out and send them back to login.
- Public room detail uses slug binding at `/rooms/{room:slug}`. Keep admin room slug generation intact if you touch room creation or updates.
- Room occupancy rules live in `App\Support\RoomOccupancy`; admin tenant flows call `syncStatuses()` after assignments/checkouts, and room edits call `ensureStatusIsConsistent()`.
- Payment status side effects live in `App\Support\PaymentWorkflow`; use it when changing payment flows so `paid_at`, `verified_at`, `verified_by`, and `rejection_reason` stay consistent.

## Data Gotchas
- Do not assume this repo can rebuild a full working app database from migrations alone. `database/migrations/` only contains Laravel defaults plus additive changes for `kos_profiles` and `payments`.
- Core app tables used by the codebase, including `rooms`, `facilities`, `room_images`, `tenants`, `payments`, `kos_profiles`, and pivot tables, are not created anywhere in this repo.
- Admin and tenant dashboards query `payment_deadline_view` and `tenant_end_date_view`; those database views are referenced in controllers but are not defined in this repo.
- `database/seeders/DatabaseSeeder.php` is still the default stub and only creates a generic test user. It does not seed admin accounts, tenants, rooms, facilities, or the missing views.
- Local runtime config uses MySQL in `.env`, while `phpunit.xml` forces in-memory SQLite for tests.
- `App\Http\Controllers\Public\HomeController` deliberately skips most DB reads when `app()->runningUnitTests()` is true. Do not remove those guards casually; they are what let homepage tests run without the missing app schema.
- `KosProfile::nearby_places` should go through `KosProfile::serializeNearbyPlaces()` / `parseNearbyPlaces()`. The model still supports legacy newline-based values in addition to structured JSON.

## File Storage
- Room main images, room gallery images, and kos logos use the `public` disk (`storage/app/public`). Run `php artisan storage:link` if you need them accessible from `/storage`.
- Payment proof uploads intentionally use the default filesystem disk (`local` by default), and the admin/tenant proof handlers check both `local` and `public` when deleting or serving old paths. Do not simplify that storage behavior without tracing both controllers.

## Conventions
- User-facing copy, validation messages, labels, and WhatsApp text are in Indonesian. Preserve that language unless the task explicitly changes product copy.
- Status values are hard-coded across controllers, views, and tests. Keep these exact sets synchronized when changing workflow logic: room `available|occupied|maintenance`, tenant `active|inactive|moved_out`, payment `unpaid|pending_verification|paid|rejected`.
