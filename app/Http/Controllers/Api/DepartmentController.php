<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    // 1. Simple active departments fetch karne ke liye (Flat list)
    public function index()
    {
        try {
            $departments = Department::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'parent_id', 'name', 'code']);

            return response()->json($departments, 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch departments: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch departments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // 2. Hierarchical Tree format mein departments fetch karne ke liye (Chain System)
    public function getDepartmentTree()
    {
        try {
            // Sirf root departments (jaise CCMD jinka parent_id null hai) aur unke recursive children
            $departments = Department::whereNull('parent_id')
                ->where('is_active', true)
                ->with('recursiveChildren')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $departments
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch department tree: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch department tree',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // 3. Naya Department ya Sub-Department store karne ke liye
    public function store(Request $request)
    {
        try {
            // Validation (parent_id optional hai, agar root hoga toh null rahega)
            $request->validate([
                'name' => 'required|string|max:255|unique:departments,name',
                'code' => 'nullable|string|max:50',
                'parent_id' => 'nullable|exists:departments,id' // Yeh check karega ki parent database me exist karta hai ya nahi
            ]);

            // Department Create
            $department = Department::create([
                'name' => $request->name,
                'code' => $request->code ?? strtoupper(substr($request->name, 0, 3)),
                'parent_id' => $request->parent_id ?? null, // Parent ID save hogi
                'is_active' => true,
            ]);

            return response()->json([
                'message' => 'Department created successfully',
                'data' => $department
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Failed to create department: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server error while creating department',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}