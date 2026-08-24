<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

// Public Auth Routes (OTP Login System for Employees & Password Login for Admin)
Route::prefix('auth')->group(function () {
    // Naye OTP routes employee ke liye
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    
    // Admin login waisa hi rahega
    Route::post('/admin-login', [AuthController::class, 'adminLogin']);
});

// Public Department Route
Route::get('/departments', [DepartmentController::class, 'index']);

// Authenticated Routes (Sanctum Protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // --- GENERAL TASK ROUTES ---
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);

    // --- NOTIFICATION ROUTES ---
    Route::post('/device-token', [NotificationController::class, 'storeDeviceToken']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead']);

    // --- ADMIN ONLY ROUTES ---
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        
        Route::post('/departments', [DepartmentController::class, 'store']);

        // Task Management
        Route::post('/admin/tasks', [TaskController::class, 'store']);
        Route::get('/admin/tasks/{task}', [TaskController::class, 'show']);      
        Route::put('/admin/tasks/{task}', [TaskController::class, 'update']);     
        Route::delete('/admin/tasks/{task}', [TaskController::class, 'destroy']); 
        
        // Admin Chat / Comments Routes
        Route::get('/admin/tasks/{task}/comments', [CommentController::class, 'index']);
        Route::post('/admin/tasks/{task}/comments', [CommentController::class, 'store']);

        // Employee Management
        Route::get('/employees', [EmployeeController::class, 'index']);
        Route::post('/employees', [EmployeeController::class, 'store']);
        Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);
    });

    // --- EMPLOYEE ONLY ROUTES ---
    Route::middleware('role:employee')->group(function () {
        // Employee tasks
        Route::get('/employee/tasks', [TaskController::class, 'getEmployeeTasks']);
        Route::put('/employee/tasks/{task}', [TaskController::class, 'updateMyTask']);
        
        // Employee Chat / Comments Routes
        Route::get('/employee/tasks/{task}/comments', [CommentController::class, 'index']);
        Route::post('/employee/tasks/{task}/comments', [CommentController::class, 'store']);
    });
});