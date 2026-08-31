<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Direct Login (Android App Compatible with Dual Roles)
     */
    public function login(Request $request)
    {
        // Sirf email aur password validate karein
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);
        
        // Database se user dhoondhein
        $user = User::where('email', $data['email'])->first();
        
        // Password check karein
        if (!$user || !Hash::check($data['password'], (string) $user->password)) {
            throw ValidationException::withMessages(['email' => 'Invalid email or password.']);
        }

        // Roles ko array mein convert karein (e.g., "admin,employee" -> ['admin', 'employee'])
        $roles = array_map('trim', explode(',', $user->role));
        $isAdmin = in_array('admin', $roles);
        $isEmployee = in_array('employee', $roles);
        $isDualRole = count($roles) > 1 || ($user->is_dual_role ?? false);

        // Tokens generate karein
        $adminToken = null;
        $employeeToken = null;

        if ($isAdmin) {
            $adminToken = $user->createToken('android-admin')->plainTextToken;
        }

        if ($isEmployee || !$isAdmin) {
            $employeeToken = $user->createToken('android-employee')->plainTextToken;
        }

        // Primary default token (agar dual role hai toh admin token pehle preference rakhega, warna employee)
        $primaryToken = $adminToken ?? $employeeToken;
        
        // JSON Response return karein
        return response()->json([
            'message'          => 'Login successful.',
            'token'            => $primaryToken,          // Fallback / Primary token
            'admin_token'      => $adminToken,            // Dual-role admin token
            'employee_token'   => $employeeToken,         // Dual-role employee token
            'role'             => $user->role,           
            'has_admin_access' => $isAdmin || str_contains($user->role, 'manager'),
            'is_dual_role'     => $isDualRole,           
            'user'             => $this->userPayload($user)
        ], 200);
    }

    public function logout(Request $request) 
    { 
        $request->user()->currentAccessToken()?->delete(); 
        return response()->json(['message' => 'Logged out successfully.'], 200); 
    }

    public function profile(Request $request) 
    { 
        return response()->json(['user' => $this->userPayload($request->user())], 200); 
    }

    private function userPayload(User $u): array 
    { 
        $roles = array_map('trim', explode(',', $u->role));
        $isDualRole = count($roles) > 1 || ($u->is_dual_role ?? ($u->role === 'admin,employee'));

        return [
            'id'           => $u->id,
            'name'         => $u->name,
            'email'        => $u->email,
            'role'         => $u->role,          
            'phone'        => $u->phone ?? null,
            'designation'  => $u->designation ?? null,
            'department'   => is_object($u->department) ? $u->department?->name : ($u->department ?? null),
            'is_dual_role' => (bool) $isDualRole
        ]; 
    }
}