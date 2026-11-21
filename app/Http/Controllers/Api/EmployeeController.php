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

        if ($this->hasRole($currentUser, 'super_admin')) {
            // Super admin can view the complete organization.
        } elseif ($this->hasRole($currentUser, 'admin')) {
            $q->where(function ($query) use ($currentUser) {
                $query->where('parent_id', $currentUser->id)
                    ->orWhere('id', $currentUser->id);
            });
        } elseif ($this->hasRole($currentUser, 'manager')) {
            $q->where('parent_id', $currentUser->id);
        } else {
            $q->where('id', $currentUser->id);
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
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'nullable|string|min:8',
                'phone' => 'nullable|string|max:30',
                'designation' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'branch' => 'nullable|string|max:255',
                'role' => ['required', Rule::in(['super_admin', 'admin', 'manager', 'employee', 'admin,employee'])],
                'is_dual_role' => 'nullable|boolean',
                'parent_id' => 'nullable|integer|exists:users,id',
            ]);

            $passwordInput = $request->filled('password')
                ? $request->input('password')
                : str()->password(16);
            $data['password'] = Hash::make($passwordInput);
            
            $data['status'] = 'active';
            $currentUser = $request->user();
            $allowedRoles = $this->creatableRoles($currentUser);
            if (!in_array($data['role'], $allowedRoles, true)) {
                return response()->json([
                    'message' => 'You are not authorized to create this role.'
                ], 403);
            }

            // Agar role 'admin,employee' hai toh automatic is_dual_role true set ho jayega
            if (isset($data['role']) && $data['role'] === 'admin,employee') {
                $data['is_dual_role'] = true;
            } else {
                $data['is_dual_role'] = $request->input('is_dual_role', false);
            }

            $data['parent_id'] = $this->hasRole($currentUser, 'super_admin')
                ? ($data['parent_id'] ?? null)
                : $currentUser->id;

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
            return response()->json(['message' => 'Unable to create staff profile.'], 500);
        }
    }

    public function update(Request $request, User $employee)
    {
        $currentUser = auth()->user();
        if (!$this->canManageUser($currentUser, $employee)) {
            return response()->json(['message' => 'Unauthorized: You can only modify your direct subordinates.'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($employee->id)],
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:30',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'role' => ['sometimes', Rule::in(['super_admin', 'admin', 'manager', 'employee', 'admin,employee'])],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
            'is_dual_role' => 'nullable|boolean'
        ]);

        if (isset($data['role']) && !in_array($data['role'], $this->creatableRoles($currentUser), true)) {
            return response()->json(['message' => 'You are not authorized to assign this role.'], 403);
        }

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

        if (!$this->canManageUser($currentUser, $employee)) {
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
            'branch' => $u->branch ?? null,
            'status' => $u->status ?? 'active',
            'parent_id' => $u->parent_id ?? null,
            // App ke liye is_dual_role bhi payload mein bhej diya gaya hai taaki UI par switch button show ho sake
            'is_dual_role' => (bool) ($u->is_dual_role ?? ($u->role === 'admin,employee'))
        ]; 
    }

    private function hasRole(?User $user, string $role): bool
    {
        if (!$user) {
            return false;
        }

        $roles = array_map('trim', explode(',', (string) $user->role));
        return in_array($role, $roles, true);
    }

    private function creatableRoles(User $currentUser): array
    {
        if ($this->hasRole($currentUser, 'super_admin')) {
            return ['super_admin', 'admin', 'manager', 'employee', 'admin,employee'];
        }

        if ($this->hasRole($currentUser, 'admin')) {
            return ['admin', 'manager', 'employee', 'admin,employee'];
        }

        if ($this->hasRole($currentUser, 'manager')) {
            return ['employee'];
        }

        return [];
    }

    private function canManageUser(User $currentUser, User $employee): bool
    {
        if ($this->hasRole($currentUser, 'super_admin')) {
            return true;
        }

        if ($this->hasRole($currentUser, 'admin')) {
            return $employee->parent_id === $currentUser->id
                || $employee->id === $currentUser->id;
        }

        return $this->hasRole($currentUser, 'manager')
            && $employee->parent_id === $currentUser->id;
    }
}