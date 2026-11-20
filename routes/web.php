<?php

use Illuminate\Support\Facades\Route;

// Default Root Redirect
Route::get('/', function () {
    return redirect('/login');
});

// routes/web.php
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::view('/security-policy', 'security-policy')->name('security-policy');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/employee/dashboard', function () {
    return view('employee.dashboard');
})->name('employee.dashboard');

// Admin Task Assignment Page Route
Route::get('/admin/assign-task', function () {
    return view('admin.assign-task');
})->name('admin.assign-task');
