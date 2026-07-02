<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    // public function handle(Request $request, Closure $next, $moduleKey)
    // {
    //     if (Session::get('user_type') === 'Super User') {
    //         return $next($request);
    //     }

    //     $permissions = Session::get('permissions', []);

    //     if (!in_array($moduleKey, $permissions)) {
    //         abort(403, 'Unauthorized');
    //     }

    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('all-user-login');
        }

        // Super admin bypass
        if ($user->role->name === 'super-user') {
            return $next($request);
        }

        if (!$user->hasPermission($permission)) {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}