<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // 🟢 User ke roles ko split kar lo (agar comma-separated hain jaise 'admin,employee')
        // Jaise 'admin,employee' ban jayega ['admin', 'employee']
        $userRoles = array_map('trim', explode(',', (string) $user->role));

        // Check karo ki user ke paas jo roles hain, unme se koi bhi role 
        // route ke allowed $roles ke sath match karta hai ya nahi
        $hasAccess = false;
        foreach ($roles as $role) {
            if (in_array(trim($role), $userRoles, true)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            return response()->json(['message' => 'You are not authorized for this action.'], 403);
        }

        return $next($request);
    }
}