<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // ✅ Check admin routes
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            // ✅ Check siswa routes
            if ($request->is('siswa') || $request->is('siswa/*')) {
                return route('siswa.loginsiswa');
            }

            // ✅ PENTING: Default fallback (ini yang mencegah error)
            return route('admin.login');
        }

        return null;
    }
}