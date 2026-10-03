<?php 

use Illuminate\Support\Facades\Route;

// Redirection to Login
Route::get('/', function () {
    return redirect('/login');
});

// Auth Login Route (Point to auth/login.blade.php)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Admin Dashboard Route
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

// Employee Dashboard Route
Route::get('/employee/dashboard', function () {
    return view('employee.dashboard');
});

// Browser par API endpoints ki list dikhane ke liye route
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