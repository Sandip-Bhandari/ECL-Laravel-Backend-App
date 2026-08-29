<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $q = User::orderBy('name');

        // 🟢 Hierarchy Filter: Agar user Super Admin / Top Management nahi hai, toh sirf apne direct subordinates dekhega
        if ($currentUser && $currentUser->role !== 'super_admin') {
            $q->where('parent_id', $currentUser->id);
        }

        if ($request->filled('search')) {
            $q->where(fn($x) => $x->where('name', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('department')) {
            $q->where('department', $request->department);
        }
        if ($request->filled('role')) {
            $q->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        return response()->json($q->get()->map(fn($u) => $this->payload($u)));
    }

    public function store(Request $request)
    {
        Log::info('Incoming Employee Data:', $request->all());

        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'nullable|string',
                'phone' => 'nullable|string|max:30',
                'designation' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'branch' => 'nullable|string|max:255',
                // 🟢 Updated Rule: 'admin,employee' dual role ko bhi validate karne ke liye allow kiya gaya hai
                'role' => ['required', Rule::in(['admin', 'employee', 'admin,employee'])],
                'is_dual_role' => 'nullable|boolean'
            ]);

            // 🟢 Fix: Agar app se password bheja hai toh woh use ho, warna 'Default@123'
            $passwordInput = $request->filled('password') ? $request->input('password') : 'Default@123';
            $data['password'] = Hash::make($passwordInput);
            
            $data['status'] = 'active';

            // Agar role 'admin,employee' hai toh automatic is_dual_role true set ho jayega
            if (isset($data['role']) && $data['role'] === 'admin,employee') {
                $data['is_dual_role'] = true;
            } else {
                $data['is_dual_role'] = $request->input('is_dual_role', false);
            }

            // 🟢 Naye user ka parent_id current logged-in user ban jayega (Chain System)
            $data['parent_id'] = auth()->id();

            if ($request->filled('department')) {
                $data['department'] = $request->input('department');
            } elseif ($request->filled('department_id')) {
                $deptInput = $request->department_id;
                if (is_numeric($deptInput)) {
                    $dept = Department::find($deptInput);
                    $data['department'] = $dept ? $dept->name : null;
                } else {
                    $data['department'] = $deptInput;
                }
            } else {
                $data['department'] = null;
            }
            
            unset($data['department_id']);

            $u = User::create($data);
            Log::info('Employee Created Successfully ID: ' . $u->id . ' under Parent ID: ' . $data['parent_id']);

            return response()->json([
                'message' => 'Staff profile created successfully under your chain.', 
                'user' => $this->payload($u)
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Database Error:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, User $employee)
    {
        $currentUser = auth()->user();

        // 🟢 Security Check: Ensure manager can only update their own subordinate
        if ($currentUser->role !== 'super_admin' && $employee->parent_id !== $currentUser->id) {
            return response()->json(['message' => 'Unauthorized: You can only modify your direct subordinates.'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($employee->id)],
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:30',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'role' => ['sometimes', Rule::in(['admin', 'employee', 'admin,employee'])],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
            'is_dual_role' => 'nullable|boolean'
        ]);

        if (isset($data['role']) && $data['role'] === 'admin,employee') {
            $data['is_dual_role'] = true;
        }

        if ($request->filled('department')) {
            $data['department'] = $request->input('department');
        } elseif ($request->filled('department_id')) {
            $deptInput = $request->department_id;
            if (is_numeric($deptInput)) {
                $dept = Department::find($deptInput);
                $data['department'] = $dept ? $dept->name : null;
            } else {
                $data['department'] = $deptInput;
            }
        }
        
        unset($data['department_id']);

        if (array_key_exists('password', $data)) { 
            $data['password'] = $data['password'] ? Hash::make($data['password']) : $employee->password; 
        }
        
        $employee->update($data);
        return response()->json([
            'message' => 'Staff profile updated.', 
            'user' => $this->payload($employee->fresh())
        ]);
    }

    public function destroy(User $employee) 
    { 
        $currentUser = auth()->user();

        if ($employee->id === $currentUser->id) {
            return response()->json(['message' => 'You cannot delete your own account.'], 422); 
        }

        // 🟢 Security Check: Manager can only delete their own subordinate
        if ($currentUser->role !== 'super_admin' && $employee->parent_id !== $currentUser->id) {
            return response()->json(['message' => 'Unauthorized: You can only remove your direct subordinates.'], 403);
        }

        $employee->delete(); 
        return response()->json(['message' => 'Staff profile removed.']); 
    }

    private function payload(User $u): array 
    { 
        return [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'role' => $u->role,
            'phone' => $u->phone ?? null,
            'designation' => $u->designation ?? null,
            'department' => $u->department ?? null,
            'status' => $u->status ?? 'active',
            'parent_id' => $u->parent_id ?? null,
            // App ke liye is_dual_role bhi payload mein bhej diya gaya hai taaki UI par switch button show ho sake
            'is_dual_role' => (bool) ($u->is_dual_role ?? ($u->role === 'admin,employee'))
        ]; 
    }
}