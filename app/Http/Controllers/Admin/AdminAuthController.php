<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            // Set session timeout warning
            session([
                'last_activity' => now(),
                'session_lifetime' => config('session.lifetime') * 60,
            ]);

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'login' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Dashboard
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Activity
     */
    public function activity()
    {
        return view('admin.activity');
    }

    /**
     * Siswa
     */
    public function siswa()
    {
        return view('admin.siswa');
    }

    /**
     * Karya
     */
    public function karya()
    {
        return view('admin.karya');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        // ✅ Logout
        Auth::guard('admin')->logout();
        
        // ✅ Invalidate session
        // $request->session()->invalidate();
        
        
        // ✅ Regenerate token
        $request->session()->regenerateToken();
        
        // ✅ Clear all session data
        // $request->session()->flush();
        
        // ✅ Redirect dengan flag untuk clear history
        return redirect()->route('admin.login')
            ->with('clear_history', true)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    // Extend session
    public function extendSession(Request $request)
    {
        session(['last_activity' => now()]);
        return response()->json(['success' => true, 'message' => 'Session diperpanjang']);
    }
}