<?php
// app/Http/Controllers/Admin/SiswaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Siswa::query();
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $siswaList = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->created_at = $item->created_at 
                    ? Carbon::parse($item->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') 
                    : null;
                $item->updated_at = $item->updated_at 
                    ? Carbon::parse($item->updated_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') 
                    : null;
                $item->approved_at = $item->approved_at 
                    ? Carbon::parse($item->approved_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') 
                    : null;
                return $item;
            });
        
        $pendingCount = Siswa::pending()->count();
        $approvedCount = Siswa::approved()->count();
        $rejectedCount = Siswa::rejected()->count();
        
        return view('admin.siswa', compact('siswaList', 'pendingCount', 'approvedCount', 'rejectedCount', 'status'));
    }

    public function approve($nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();
        
        $siswa->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::guard('admin')->id(),
            'rejection_reason' => null,
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('success', "Akun {$siswa->nama_lengkap} berhasil disetujui");
    }

    public function reject(Request $request, $nis)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $siswa = Siswa::where('nis', $nis)->firstOrFail();
        
        $siswa->update([
            'status' => 'rejected',
            'approved_at' => null,
            'approved_by' => Auth::guard('admin')->id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('warning', "Akun {$siswa->nama_lengkap} ditolak");
    }

    public function update(Request $request, $nis)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:50',
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:siswa,email,' . $nis . ',nis',
            'password' => 'nullable|string|min:6',
            'no_hp' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'jk' => 'nullable|in:L,P',
            'kelas' => 'nullable|string|max:10',
        ]);

        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        $siswa->nis = $validated['nis'];
        $siswa->nama_lengkap = $validated['nama_lengkap'];
        $siswa->email = $validated['email'];
        $siswa->no_hp = $validated['no_hp'] ?? null;
        $siswa->tanggal_lahir = $validated['tanggal_lahir'] ?? null;
        $siswa->jk = $validated['jk'] ?? null;
        $siswa->kelas = $validated['kelas'] ?? null;

        if ($request->filled('password')) {
            $siswa->password = Hash::make($validated['password']);
        }

        $siswa->save();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    public function destroy($nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();
        $namaLengkap = $siswa->nama_lengkap;
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('danger', "Data siswa {$namaLengkap} berhasil dihapus");
    }
}