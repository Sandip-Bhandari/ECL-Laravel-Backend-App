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
     * 1. Send OTP (Validates Email & Password, then sends OTP)
     */
    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
            'role'     => 'required|in:employee,admin'
        ]);
        
        $user = User::where('email', $data['email'])
                    ->where('role', $data['role'])
                    ->first();
        
        // Check if user exists and password is correct
        if (!$user || !Hash::check($data['password'], (string) $user->password)) {
            throw ValidationException::withMessages(['email' => 'Invalid email, password, or role.']);
        }

        if (empty($user->phone)) {
            throw ValidationException::withMessages(['email' => 'Phone number not registered in backend for this account.']);
        }

        // Generate 6 digit OTP
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10); // 10 minutes expiry
        $user->save();

        // TODO: Integrate actual SMS gateway service here (e.g., Twilio, MSG91, Firebase)
        // $this->sendSms($user->phone, $otp);

        $response = [
            'message' => 'OTP sent successfully to registered phone.',
        ];

        // Include debug OTP only in local/staging environments
        if (app()->environment(['local', 'staging', 'development'])) {
            $response['debug_otp'] = $otp;
        }

        return response()->json($response, 200);
    }

    /**
     * 2. Verify OTP & Login
     */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string',
            'role'  => 'required|in:employee,admin'
        ]);

        $user = User::where('email', $data['email'])
                    ->where('role', $data['role'])
                    ->first();

        // Check user, OTP match, and expiration
        if (!$user || (string) $user->otp !== (string) $data['otp'] || !$user->otp_expires_at || now()->gt($user->otp_expires_at)) {
            throw ValidationException::withMessages(['otp' => 'Invalid or expired OTP.']);
        }

        // Clear OTP after successful login to prevent reuse
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        $tokenName = $data['role'] === 'admin' ? 'android-admin' : 'android-employee';
        $token = $user->createToken($tokenName)->plainTextToken;
        
        return response()->json([
            'message' => 'Login successful.',
            'token'   => $token,
            'user'    => $this->userPayload($user)
        ], 200);
    }

    /**
     * Legacy / Direct Admin Login (Optional)
     */
    public function adminLogin(Request $request)
    {
        $data = $request->validate([
            'identifier' => 'required|string',
            'password'   => 'required|string'
        ]);
        
        $user = User::where('role', 'admin')
            ->where(function($q) use ($data) { 
                $q->where('email', $data['identifier'])
                  ->orWhere('name', $data['identifier']); 
            })->first();

        if (!$user || !Hash::check($data['password'], (string) $user->password)) {
            throw ValidationException::withMessages(['identifier' => 'Invalid admin credentials.']);
        }

        $token = $user->createToken('android-admin')->plainTextToken;
        
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
            'id'          => $u->id,
            'name'        => $u->name,
            'email'       => $u->email,
            'role'        => $u->role,
            'phone'       => $u->phone ?? null,
            'designation' => $u->designation ?? null,
            'department'  => is_object($u->department) ? $u->department?->name : ($u->department ?? null),
        ]; 
    }
}