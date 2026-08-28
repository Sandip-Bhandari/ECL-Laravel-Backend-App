<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

// --- PUBLIC AUTH ROUTE (Direct Login without OTP) ---
Route::post('/login', [AuthController::class, 'login']);

// Admin/System द्वारा User (Employee) create karke Department से link karne ka route (Agar public/protected jo rakhna ho)
Route::post('/admin/create-user', [AuthController::class, 'createUser']);

// Public Department Route
Route::get('/departments', [DepartmentController::class, 'index']);

// Authenticated Routes (Sanctum Protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // --- GENERAL & SHARED TASK ROUTES ---
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);

    // --- NOTIFICATION ROUTES (Sabke liye common) ---
    Route::post('/device-token', [NotificationController::class, 'storeDeviceToken']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead']);

    // --- DASHBOARD (Admin & Managers ke liye) ---
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/departments', [DepartmentController::class, 'store']);

    // --- TASK MANAGEMENT (Admin + Managers jo task dete hain) ---
    Route::post('/admin/tasks', [TaskController::class, 'store']);
    Route::get('/admin/tasks/{task}', [TaskController::class, 'show']);      
    Route::put('/admin/tasks/{task}', [TaskController::class, 'update']);     
    Route::delete('/admin/tasks/{task}', [TaskController::class, 'destroy']); 
    
    // Chat / Comments for Admin/Manager side
    Route::get('/admin/tasks/{task}/comments', [CommentController::class, 'index']);
    Route::post('/admin/tasks/{task}/comments', [CommentController::class, 'store']);

    // --- EMPLOYEE MANAGEMENT (Subordinates ko add/manage karne ke liye) ---
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);

    // --- EMPLOYEE SELF-ROUTES ---
    Route::get('/employee/tasks', [TaskController::class, 'getEmployeeTasks']);
    Route::put('/employee/tasks/{task}', [TaskController::class, 'updateMyTask']);
    
    // Employee Chat / Comments Routes (Self task chat)
    Route::get('/employee/tasks/{task}/comments', [CommentController::class, 'index']);
    Route::post('/employee/tasks/{task}/comments', [CommentController::class, 'store']);

    Route::get('/departments/tree', [DepartmentController::class, 'getDepartmentTree']);
});