<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $authResult = $this->authenticate($request, $guards);

        if ($authResult === 'authentication_failed') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($authResult === 'account_blocked') {
            return response()->json(['error' => 'Your account has been blocked'], 403);
        }

        return $next($request);
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     */
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                $user = $this->auth->guard($guard)->user();
                
                // التحقق من حالة المستخدم
                if ($user->status === 'blocked') {
                    return 'account_blocked';
                }

                return $this->auth->shouldUse($guard);
            }
        }

        return 'authentication_failed';
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return null; // لا تقم بالتوجيه إلى login
    }
}