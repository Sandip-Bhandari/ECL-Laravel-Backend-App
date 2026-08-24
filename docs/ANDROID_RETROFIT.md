# Android Retrofit integration notes

Your existing Android app currently has a `RetrofitClient` pointing to:

`http://172.18.221.130:8000/api/`

Use the LAN IP of the computer running Laravel. For the Android Emulator use `10.0.2.2` instead of the host's LAN IP.

## Required Android changes

1. Add a login request/response model.
2. Add `POST auth/employee-login` and `POST auth/admin-login` to `ApiService`.
3. Add an OkHttp interceptor that sends `Authorization: Bearer <token>` on authenticated requests.
4. Store the token in DataStore.
5. Replace local-only login navigation with API success handling.
6. Change `TaskRequest` to include `department_id` and `assigned_to`.
7. Use `MultipartBody.Part` for optional task attachments.
8. Add APIs for employee list and department list so dropdowns are populated from MySQL.
9. Fetch `/dashboard` for live admin statistics instead of hardcoded values.
10. Fetch `/profile` for the employee profile screen.

## Example endpoints for Retrofit

```kotlin
@POST("auth/employee-login")
suspend fun employeeLogin(@Body request: EmployeeLoginRequest): Response<LoginResponse>

@POST("auth/admin-login")
suspend fun adminLogin(@Body request: AdminLoginRequest): Response<LoginResponse>

@GET("tasks")
suspend fun getTasks(): Response<List<Task>>

@GET("dashboard")
suspend fun getDashboard(): Response<DashboardResponse>
```

Do not hard-code credentials in the Android application.
