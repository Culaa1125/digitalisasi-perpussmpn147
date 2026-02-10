<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use Illuminate\Support\Facades\Auth;

class SiswaHomeController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        
        // Karya milik siswa ini
        $myKonten = Konten::where('id_siswa_uploader', $siswa->nis)
                         ->orderBy('tanggal_upload', 'desc')
                         ->get();
        
        // Statistik karya siswa
        $stats = [
            'total_karya' => $myKonten->count(),
            'approved' => $myKonten->where('status', 'approved')->count(),
            'pending' => $myKonten->where('status', 'pending')->count(),
            'rejected' => $myKonten->where('status', 'rejected')->count(),
            'total_views' => $myKonten->where('status', 'approved')->sum('view_count'),
            'total_reviews' => $myKonten->where('status', 'approved')->sum('total_reviews'),
        ];
        
        // Karya terpopuler milik siswa
        $popularKarya = $myKonten->where('status', 'approved')
                                 ->sortByDesc('view_count')
                                 ->take(3);
        
        // ✅ FIX: Buku terbaru dari perpustakaan (bukan milik siswa)
        // Gunakan closure untuk grouping OR condition
        $recentBooks = Konten::approved()
                            ->buku()
                            ->where(function($query) use ($siswa) {
                                $query->where('id_siswa_uploader', '!=', $siswa->nis)
                                      ->orWhereNull('id_siswa_uploader');
                            })
                            ->orderBy('tanggal_approve', 'desc')
                            ->limit(6)
                            ->get();
        
        return view('siswa.home', compact('siswa', 'myKonten', 'stats', 'popularKarya', 'recentBooks'));
    }
}