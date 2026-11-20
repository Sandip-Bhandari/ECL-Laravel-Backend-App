<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class RefreshSanctumToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || $user->status !== 'active') {
            return response()->json(['message' => 'Account is inactive.'], 403);
        }

        $token = $user->currentAccessToken();
        if ($token instanceof PersonalAccessToken) {
            $token->forceFill(['expires_at' => now()->addHours(48)])->save();
        }

        return $next($request);
    }
}
