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
        $q = User::orderBy('name');
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
                'password' => 'nullable',
                'phone' => 'nullable|string|max:30',
                'designation' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255', // 🟢 Direct department name string
                'branch' => 'nullable|string|max:255',
                'role' => ['required', Rule::in(['admin', 'employee'])]
            ]);

            // Default password set kar rahe hain
            $data['password']  = Hash::make('Default@123'); 
            $data['status']    = 'active';

            // 🟢 department_id ki jagah seedha department string/name save kar rahe hain taaki column missing error na aaye
            // Agar request me department_id aa raha ho toh use bhi handle kar lete hain safety ke liye
            if ($request->filled('department_id') && empty($data['department'])) {
                $deptInput = $request->department_id;
                if (is_numeric($deptInput)) {
                    $dept = Department::find($deptInput);
                    $data['department'] = $dept ? $dept->name : null;
                } else {
                    $data['department'] = $deptInput;
                }
            }
            
            // Unset extra/non-database keys if any
            unset($data['department_id']);

            // User database mein create kar rahe hain
            $u = User::create($data);
            Log::info('Employee Created Successfully ID: ' . $u->id);

            return response()->json([
                'message' => 'Staff profile created successfully.', 
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
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($employee->id)],
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:30',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'role' => ['sometimes', Rule::in(['admin', 'employee'])],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])]
        ]);

        if ($request->filled('department_id') && !isset($data['department'])) {
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
        if ($employee->id === auth()->id()) {
            return response()->json(['message' => 'You cannot delete your own account.'], 422); 
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
            'phone' => $u->phone ?? null, // 🟢 Phone enabled
            'designation' => $u->designation ?? null,
            'department' => $u->department ?? null, // 🟢 Department string returned
            'status' => $u->status ?? 'active'
        ]; 
    }
}