<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class RedirectIfNoProfile
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Only check for user role
            if ($user->role === 'user' && !$user->profileData) {
                return redirect()->route('profile.create');
            }
        }

        return $next($request);
    }
}