# ECL API Quick Reference

Base URL: `http://HOST:8000/api`

All authenticated endpoints use `Authorization: Bearer TOKEN`.

## Login

`POST /login`

```json
{"email":"employee@ecl.gov.in","password":"optional when email-only employee login is enabled"}
```

Returns a Sanctum bearer token. Admin login always requires a password; employee email-only login is controlled by `ALLOW_EMAIL_ONLY_EMPLOYEE_LOGIN`.

### Admin
Use `POST /login` with the admin email and password.

### Employee
Use `POST /login` with the employee email. Include a password when email-only login is disabled.

## Dashboard

`GET /dashboard`

Returns:
- total
- pending
- in_progress
- completed
- overdue
- tasks_by_department
- employee_discussions
- active_workforce

## Task filters

`GET /tasks?status=Pending`

`GET /tasks?department_id=1`

`GET /tasks?assigned_to=4`

`GET /tasks?search=database`

Employees automatically receive only their assigned tasks.

## Create task

`POST /tasks` as multipart or JSON.

Fields:
- title required
- description
- department_id
- assigned_to
- status: Pending | In Progress | Completed
- due_date: YYYY-MM-DD
- attachment: optional file, max 10 MB

## Comments

`GET /tasks/{id}/comments`

`POST /tasks/{id}/comments`

```json
{"comment":"Clarification needed on the safety audit guidelines."}
```

## Employee task status

`PUT /my/tasks/{id}`

```json
{"status":"In Progress"}
```

## Device notifications

`POST /device-token` (authenticated)

```json
{"device_token":"FCM_DEVICE_TOKEN","platform":"android"}
```

The Android app obtains an FCM registration token and the authenticated API stores it for the signed-in user. Task assignment and task status updates send push notifications to registered devices.

To enable delivery from Laravel:

1. In Firebase Console, create/download a Firebase Admin SDK service-account JSON for the same Firebase project used by the Android app.
2. Store that JSON outside the repository and set `FIREBASE_CREDENTIALS` in the backend `.env` to its absolute path.
3. Keep the service-account JSON private; never use the Android `google-services.json` as server credentials.
4. Ensure the API login is authenticated before registering a device token; `/device-token` and `/update-fcm-token` require a Sanctum bearer token.
