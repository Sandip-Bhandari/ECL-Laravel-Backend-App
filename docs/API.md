# ECL API Quick Reference

Base URL: `http://HOST:8000/api`

All authenticated endpoints use `Authorization: Bearer TOKEN`.

## Login

### Admin
`POST /auth/admin-login`

```json
{"identifier":"admin@ecl.gov.in","password":"Admin@123"}
```

### Employee
`POST /auth/employee-login`

```json
{"email":"sandip@ecl.gov.in"}
```

If production password authentication is enabled:

```json
{"email":"sandip@ecl.gov.in","password":"..."}
```

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

`POST /device-token`

```json
{"device_token":"FCM_DEVICE_TOKEN","platform":"android"}
```

The backend stores tokens and creates in-app notification records. Actual FCM push delivery requires Firebase server credentials and should be added before production deployment.
