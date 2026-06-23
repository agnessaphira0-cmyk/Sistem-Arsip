<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session('admin_role') !== 'superadmin') {
            return redirect()->route('user.index')->with('error', 'Anda tidak memiliki akses untuk fitur ini!');
        }
        return $next($request);
    }
}