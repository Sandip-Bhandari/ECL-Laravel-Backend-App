# ECL API Quick Reference

Base URL: `http://HOST:8000/api`

All authenticated endpoints use `Authorization: Bearer TOKEN`.

## Login

`POST /login`

```json
{"email":"employee@ecl.gov.in","password":"YOUR_PASSWORD"}
```

Returns a Sanctum bearer token. A password is required for every role. New and updated passwords must be at least 12 characters; OTP login is not used.

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

Employees automatically receive only their assigned tasks. Dashboard access is restricted to administrators and managers.

## Create task

`POST /admin/tasks` as multipart or JSON, with an administrator or manager token.

Fields:
- title required
- description
- department_id
- assigned_to
- status: Pending | In Progress | Approval for Completed | Completed
- due_date: YYYY-MM-DD
- attachment: optional file, max 10 MB

Attachments are stored privately. Download with `GET /tasks/{task}/attachments/{attachment}` and the same bearer token; the requester must be able to view the task.

## Comments

`GET /tasks/{id}/comments`

`POST /tasks/{id}/comments`

```json
{"comment":"Clarification needed on the safety audit guidelines."}
```

## Employee task status

`PUT /employee/tasks/{id}`

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
