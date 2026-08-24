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
- Seed data matching the UI examples.
- API and Android Retrofit integration documentation.

## Important integration work remaining in the Android project

The supplied Android UI currently contains local-only login validation, hard-coded departments/employees, hard-coded dashboard numbers, and a Retrofit client with only three task endpoints. The backend is ready for the corresponding API calls, but the Android project must be updated to consume the new endpoints.

Those changes are intentionally documented rather than silently modifying the supplied Android source in this backend ZIP.
