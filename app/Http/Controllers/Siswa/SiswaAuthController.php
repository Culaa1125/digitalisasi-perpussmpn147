<?php
// app/Http/Controllers/Siswa/SiswaAuthController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;
use App\Helpers\ActivityLogger;

class SiswaAuthController extends Controller
{
    public function showLogin()
    {
        return view('siswa.loginsiswa');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nis' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cek apakah siswa ada
        $siswa = Siswa::where('nis', $credentials['nis'])->first();

        if (!$siswa) {
            return back()->withErrors([
                'login' => 'NIS tidak ditemukan.',
            ])->withInput($request->only('nis'));
        }

        // Cek status approval
        if ($siswa->status === 'pending') {
            return back()->withErrors([
                'login' => 'Akun Anda masih menunggu persetujuan admin.',
            ])->withInput($request->only('nis'));
        }

        if ($siswa->status === 'rejected') {
            $reason = $siswa->rejection_reason ? " Alasan: {$siswa->rejection_reason}" : '';
            return back()->withErrors([
                'login' => "Akun Anda ditolak oleh admin.{$reason}",
            ])->withInput($request->only('nis'));
        }

        // Coba login jika approved
        if (Auth::guard('siswa')->attempt($credentials)) {
            $request->session()->regenerate();
            
            // Set session timeout warning
            session([
                'last_activity' => now(),
                'session_lifetime' => config('session.lifetime') * 60, // Convert to seconds
            ]);
            
            // Log aktivitas login
            ActivityLogger::log('login', 'Siswa berhasil login');
            
            return redirect()->intended(route('siswa.home'));
        }

        return back()->withErrors([
            'login' => 'Password salah.',
        ])->withInput($request->only('nis'));
    }

    public function showRegister()
    {
        return view('siswa.daftarsiswa');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:50|unique:siswa,nis',
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:siswa,email',
            'password' => 'required|string|min:6|confirmed',
            'no_hp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'jk' => 'nullable|in:L,P',
            'kelas' => 'nullable|string|max:10',
        ]);

        Siswa::create([
            'nis' => $validated['nis'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'no_hp' => $validated['no_hp'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jk' => $validated['jk'] ?? null,
            'kelas' => $validated['kelas'] ?? null,
            'status' => 'pending', // Set status pending
        ]);

        return redirect()->route('siswa.loginsiswa')
            ->with('success', 'Pendaftaran berhasil! Akun Anda menunggu persetujuan admin.');
    }

    public function logout(Request $request)
    {
        // Log aktivitas logout sebelum logout
        ActivityLogger::log('logout', 'Siswa melakukan logout');
        
        Auth::guard('siswa')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('bukudigital.index')
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