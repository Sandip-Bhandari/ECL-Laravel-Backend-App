<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;
use App\Http\Middleware\RefreshSanctumToken;


// --- PUBLIC AUTH ROUTE (Direct Login without OTP) ---
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login')
    ->name('login');

// Public Department Route
Route::get('/departments', [DepartmentController::class, 'index']);

// Authenticated Routes (Sanctum Protected)
Route::middleware(['auth:sanctum', RefreshSanctumToken::class])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // --- GENERAL & SHARED TASK ROUTES ---
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);

    // --- NOTIFICATION ROUTES ---
    Route::post('/device-token', [NotificationController::class, 'storeDeviceToken']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);

    // --- DASHBOARD & DEPARTMENTS ---
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/departments', [DepartmentController::class, 'store'])
        ->middleware('role:super_admin,admin');
    Route::get('/departments/tree', [DepartmentController::class, 'getDepartmentTree']);

    // --- CHAT / MESSAGES ROUTES ---
    Route::post('/tasks/{task}/messages', [ChatController::class, 'store']);
    Route::get('/tasks/{task}/messages', [ChatController::class, 'index']);

    // --- TASK MANAGEMENT (Admin + Managers) ---
    Route::post('/admin/tasks', [TaskController::class, 'store'])
        ->middleware('role:super_admin,admin,manager');
    Route::get('/admin/tasks/{task}', [TaskController::class, 'show']);      
    Route::put('/admin/tasks/{task}', [TaskController::class, 'update'])
        ->middleware('role:super_admin,admin,manager');
    Route::delete('/admin/tasks/{task}', [TaskController::class, 'destroy'])
        ->middleware('role:super_admin,admin,manager');
    
    // Chat / Comments for Admin/Manager side
    Route::get('/admin/tasks/{task}/comments', [ChatController::class, 'index']);
    Route::post('/admin/tasks/{task}/comments', [ChatController::class, 'store']);

    // --- EMPLOYEE MANAGEMENT ---
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store'])
        ->middleware('role:super_admin,admin,manager');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])
        ->middleware('role:super_admin,admin,manager');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])
        ->middleware('role:super_admin,admin,manager');

    // --- EMPLOYEE SELF-ROUTES ---
    Route::get('/employee/tasks', [TaskController::class, 'getEmployeeTasks']);
    Route::put('/employee/tasks/{task}', [TaskController::class, 'updateMyTask']);
    
    Route::get('/employee/tasks/{task}/comments', [ChatController::class, 'index']);
    Route::post('/employee/tasks/{task}/comments', [ChatController::class, 'store']);

    // --- SHARED TASK COMMENTS / CHAT ROUTES ---
    Route::get('/tasks/{task}/comments', [ChatController::class, 'index']);
    Route::post('/tasks/{task}/comments', [ChatController::class, 'store']);

    // --- FCM TOKEN UPDATE ---
    Route::post('/update-fcm-token', [AuthController::class, 'updateFcmToken']);
});