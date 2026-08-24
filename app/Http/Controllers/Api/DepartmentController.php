<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    // 1. Departments fetch karne ke liye
    public function index()
    {
        try {
            $departments = Department::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'code']);

            return response()->json($departments, 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch departments: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch departments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // 2. Naya Department store karne ke liye
    public function store(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'name' => 'required|string|max:255|unique:departments,name',
                'code' => 'nullable|string|max:50'
            ]);

            // Department Create
            $department = Department::create([
                'name' => $request->name,
                'code' => $request->code ?? strtoupper(substr($request->name, 0, 3)),
                'is_active' => true,
            ]);

            return response()->json([
                'message' => 'Department created successfully',
                'data' => $department
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Agar validation fail hoti hai
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Koi aur unexpected error aane par
            Log::error('Failed to create department: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server error while creating department',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}