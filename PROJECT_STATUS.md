# What is included

This ZIP contains a Laravel 13 backend codebase prepared around the supplied ECL Android UI and the Retrofit models currently present in the Android project.

## Implemented

- MySQL migrations for users, departments, tasks, attachments, comments, notifications, device tokens, sessions/cache/jobs, and Sanctum tokens.
- Admin and employee authentication.
- Sanctum bearer-token authentication.
- Role authorization middleware.
- Admin dashboard statistics.
- Task listing, creation, update, deletion, assignment, filtering and overdue detection.
- Employee-only task status updates.
- Comments/discussions.
- Workforce CRUD.
- Department listing.
- Task file attachments.
- In-app notification records and device-token registration.
- Hourly deadline/overdue notification generation through Laravel scheduler.
- Firebase Cloud Messaging push delivery for task assignment and task-status events (requires service-account credentials in backend `.env`).
- Seed data matching the UI examples.
- API and Android Retrofit integration documentation.

## Android integration status

The Android login now calls the API, persists the Sanctum token, requests notification permission, and registers its Firebase Cloud Messaging token. The task assignment UI still uses hard-coded employee/department labels; replace them with API-backed records so assignments target real users.

Set `FIREBASE_CREDENTIALS` in the backend `.env` to a Firebase Admin SDK service-account JSON path before expecting Laravel to deliver push notifications.
