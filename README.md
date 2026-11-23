# ECL Task Management API — Laravel + MySQL

Backend for the ECL Android app built with Kotlin + Jetpack Compose.

## Stack

- Laravel 13 REST API
- PHP 8.3+
- MySQL 8+
- Laravel Sanctum bearer tokens
- Private task attachment storage with authenticated downloads

Laravel documents mobile clients as an API-backend use case and recommends Sanctum for mobile API token authentication. See the official Laravel documentation: https://laravel.com/docs/13.x and https://laravel.com/docs/12.x/sanctum.

## 1. Requirements

Install PHP 8.3+, Composer, and MySQL. Laravel's current installation guide also requires Composer for creating and installing a Laravel application.

## 2. Install

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Create a MySQL database named `ecl_task_management`, then set these in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecl_task_management
DB_USERNAME=root
DB_PASSWORD=
```

Run:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve --host=0.0.0.0 --port=8000
```

The API will be available at:

`http://YOUR-PC-IP:8000/api`

For Android Emulator, use `http://10.0.2.2:8000/api/`.
For a physical phone, use the PC's LAN IP, e.g. `http://192.168.1.10:8000/api/`, and allow port 8000 through Windows Firewall.

## 3. Seed accounts

The database seeder is for local development only and is blocked in production. It creates seed accounts with random passwords unless `SEED_ADMIN_PASSWORD` and `SEED_USER_PASSWORD` are set in the local `.env`; seed runs do not erase or overwrite existing users, tasks, or departments. Do not commit or share those values. Rotate any credentials that were previously exposed.

Every login requires a password; OTP login is not used. New and updated passwords must be at least 12 characters. Existing users should rotate any weak or previously exposed passwords.

## 4. Main endpoints

### Public authentication
- `POST /api/login`

### Authenticated
- `POST /api/auth/logout`
- `GET /api/profile`
- `GET /api/departments`
- `GET /api/employees`
- `GET /api/tasks`
- `GET /api/tasks/{id}`
- `GET /api/tasks/{id}/comments`
- `POST /api/tasks/{id}/comments`
- `GET /api/notifications`
- `POST /api/notifications/{id}/read`
- `POST /api/device-token`
- `GET /api/tasks/{task}/attachments/{attachment}` (task participant/admin only)

### Admin only
- `GET /api/dashboard`
- `POST /api/admin/tasks`
- `PUT /api/admin/tasks/{id}`
- `DELETE /api/admin/tasks/{id}`
- `POST /api/employees`
- `PUT /api/employees/{id}`
- `DELETE /api/employees/{id}`

### Employee only
- `PUT /api/employee/tasks/{id}`

## 5. Authentication header

After login, save the returned token in the Android app and send:

```text
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

## 6. Create task example

JSON version:

```json
{
  "title": "Safety Audit Report",
  "description": "Prepare the safety audit report.",
  "department_id": 1,
  "assigned_to": 2,
  "status": "Pending",
  "due_date": "2026-08-20"
}
```

For a file attachment, send `multipart/form-data` with the same fields plus `attachment`.
Attachment URLs require the same `Authorization: Bearer` header and are limited to users who can view the task.

## 7. Android integration

For the deployed API, use this base URL in Android:

`https://eclnet.co.in/test-backend/public/api/`

For local development, set `API_BASE_URL` in the website's `.env` to the same API host used by Android. The website and app then use the same API and data. Leave it empty to use the local Laravel API; set it to the deployed URL only when you intend to use live data.

The Android project uses Firebase Cloud Messaging. Place the Firebase Android config at `ECLAPP/app/google-services.json` and use the same Firebase project for the backend's Firebase Admin SDK credentials. The backend service-account JSON is private and must not be committed; set its path with `FIREBASE_CREDENTIALS` in the backend `.env`.

The app's login calls `POST /api/login` and stores the returned Sanctum token. Password authentication is required for every role. The app registers its FCM token after successful login.

Task creation clients should load departments and employees from the API and submit their IDs. The backend only allows assignments to active users.

## 8. Production checklist

- Use HTTPS.
- Set `APP_ENV=production` and `APP_DEBUG=false`.
- Use a unique password of at least 12 characters for every account.
- Rotate all seeded or previously exposed passwords.
- Purge any previously committed secrets and database exports from all Git branches/tags, then force-push the cleaned history and rotate affected credentials.
- Rotate and clear server logs that may contain previously exposed passwords.
- Put `.env` outside source control.
- Configure the web server document root to the project's `public` directory. When the repository directory itself is web-accessible, the root `.htaccess` denies access except under `public`.
- For Nginx, point `root` to the project's `public` directory and deny dotfiles and `/storage/tasks/`; `.htaccess` rules do not apply to Nginx.
- Back up existing files, then run `php artisan task-attachments:secure` to move existing public task attachments to private storage and remove their public copies. New attachments are private and require an authenticated task-participant download.
- Use a real domain/API hostname instead of a LAN IP.
- Configure Firebase Admin SDK credentials for Laravel push delivery.
- Configure queue worker and scheduler for deadline alerts.
- Restrict CORS origins to trusted web origins; allow localhost only when testing the local website against the deployed API.
