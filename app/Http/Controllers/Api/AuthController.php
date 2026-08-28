<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Direct Login (Email, Password & Role ke sath)
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
            'role'     => ['required', Rule::in(['employee', 'admin', 'admin,employee'])]
        ]);
        
        // Query to find user based on email and matching role (including dual-role support)
        $user = User::where('email', $data['email'])
                    ->where(function($q) use ($data) {
                        $q->where('role', $data['role'])
                          ->orWhere('role', 'admin,employee'); // Dual-role users can login via any role selection
                    })
                    ->first();
        
        // Check if user exists and password is correct
        if (!$user || !Hash::check($data['password'], (string) $user->password)) {
            throw ValidationException::withMessages(['email' => 'Invalid email, password, or role.']);
        }

        // Generate token based on selected role
        $tokenName = $data['role'] === 'admin' ? 'android-admin' : 'android-employee';
        $token = $user->createToken($tokenName)->plainTextToken;
        
        return response()->json([
            'message' => 'Login successful.',
            'token'   => $token,
            'user'    => $this->userPayload($user)
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
        return [
            'id'           => $u->id,
            'name'         => $u->name,
            'email'        => $u->email,
            'role'         => $u->role,
            'phone'        => $u->phone ?? null,
            'designation'  => $u->designation ?? null,
            'department'   => is_object($u->department) ? $u->department?->name : ($u->department ?? null),
            'is_dual_role' => (bool) ($u->is_dual_role ?? ($u->role === 'admin,employee'))
        ]; 
    }
}