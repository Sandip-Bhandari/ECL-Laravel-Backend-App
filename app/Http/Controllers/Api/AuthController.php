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
     * Direct Login (Android App Compatible)
     */
    public function login(Request $request)
    {
        // Sirf email aur password validate karein, role ko optional ya flexible rakhein
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

        // Token name generate karein
        $tokenName = str_contains($user->role, 'admin') ? 'android-admin' : 'android-employee';
        $token = $user->createToken($tokenName)->plainTextToken;
        
        // Android app ke code ke mutabiq keys return karein ('token', 'role', 'has_admin_access')
        return response()->json([
            'message'          => 'Login successful.',
            'token'            => $token,
            'role'             => $user->role,
            'has_admin_access' => str_contains($user->role, 'admin') || str_contains($user->role, 'manager'),
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