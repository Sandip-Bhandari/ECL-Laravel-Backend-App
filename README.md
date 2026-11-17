# ECL Task Management API — Laravel + MySQL

Backend for the ECL Android app built with Kotlin + Jetpack Compose.

## Stack

- Laravel 13 REST API
- PHP 8.3+
- MySQL 8+
- Laravel Sanctum bearer tokens
- Public task attachment storage

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

### Admin
- Email / identifier: `admin@ecl.gov.in`
- Password: `Admin@123`

### Employee accounts
- `rahul@ecl.com`
- `amit@ecl.com`
- `priya@ecl.com`
- `sandip@ecl.gov.in`

If `ALLOW_EMAIL_ONLY_EMPLOYEE_LOGIN=true`, employee login accepts the official email without a password to match the current Android UI. **For production, set it to false and add a password field or an OTP flow.**

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

### Admin only
- `GET /api/dashboard`
- `POST /api/tasks`
- `PUT /api/tasks/{id}`
- `DELETE /api/tasks/{id}`
- `POST /api/employees`
- `PUT /api/employees/{id}`
- `DELETE /api/employees/{id}`

### Employee only
- `PUT /api/my/tasks/{id}`

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

## 7. Android integration

Your current Retrofit base URL is:

`http://172.18.221.130:8000/api/`

Replace that with the actual PC/server IP where this Laravel project is running.

The Android project uses Firebase Cloud Messaging. Place the Firebase Android config at `ECLAPP/app/google-services.json` and use the same Firebase project for the backend's Firebase Admin SDK credentials. The backend service-account JSON is private and must not be committed; set its path with `FIREBASE_CREDENTIALS` in the backend `.env`.

The app's login calls `POST /api/login` and stores the returned Sanctum token. Employee email-only login is controlled by `ALLOW_EMAIL_ONLY_EMPLOYEE_LOGIN`; keep it disabled in production and use password authentication. The app registers its FCM token after successful login.

The task assignment UI currently uses hard-coded department and employee labels. Replace these with values returned by the API so the backend can resolve valid department and assignee records; the request now sends the selected labels and a default priority.

## 8. Production checklist

- Use HTTPS.
- Disable email-only employee login.
- Change all seeded passwords.
- Put `.env` outside source control.
- Use a real domain/API hostname instead of a LAN IP.
- Configure Firebase Admin SDK credentials for Laravel push delivery.
- Configure queue worker and scheduler for deadline alerts.
- Restrict CORS origins instead of `*` if a browser client is later added.
