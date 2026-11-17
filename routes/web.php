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

// Browser API List Route
Route::get('/api-list', function () {
    $html = '<h2 style="font-family: Arial; margin-bottom: 5px;">ECL TMS - API Endpoints List</h2>';
    $html .= '<p style="font-family: Arial; color: #555;">Complete list of registered backend routes.</p>';
    $html .= '<table border="1" cellpadding="10" style="border-collapse: collapse; font-family: Arial; width: 100%; margin-top: 10px;">';
    $html .= '<tr style="background-color: #f2f2f2;"><th style="text-align: left; width: 20%;">HTTP Method</th><th style="text-align: left; width: 80%;">API URL Path</th></tr>';

    foreach (Route::getRoutes() as $route) {
        if (str_starts_with($route->uri(), 'api')) {
            $methods = implode(', ', array_diff($route->methods(), ['HEAD']));
            $html .= '<tr>';
            $html .= '<td><code style="color: #0066cc; font-weight: bold;">' . $methods . '</code></td>';
            $html .= '<td>/' . $route->uri() . '</td>';
            $html .= '</tr>';
        }
    }

    $html .= '</table>';
    return $html;
});