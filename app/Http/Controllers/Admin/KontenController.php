<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KontenController extends Controller
{
    // Menampilkan dashboard dengan daftar konten
    public function index(Request $request)
    {
        $query = Konten::with(['siswaUploader', 'adminUploader', 'approver']); // ← Update relationship

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tipe
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe', $request->tipe);
        }

        // Search berdasarkan judul
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'LIKE', '%' . $request->search . '%');
        }

        $konten = $query->orderBy('tanggal_upload', 'desc')->paginate(10)->withQueryString();

        // Hitung statistik
        $stats = [
            'total' => Konten::count(),
            'pending' => Konten::pending()->count(),
            'approved' => Konten::approved()->count(),
            'rejected' => Konten::rejected()->count(),
        ];

        return view('admin.konten', compact('konten', 'stats'));
    }

    /**
     * Store new konten dari admin upload
     */
    public function store(Request $request)
    {
        try {
            // VALIDASI
            $validated = $request->validate([
                'tipe' => 'required|in:buku,video',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'file' => 'required|file|max:51200|mimes:pdf,mp4,avi,mov',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            // ADMIN UPLOAD → pakai id_admin_uploader
            $adminId = Auth::guard('admin')->id();

            // UPLOAD FILE KONTEN
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/konten', $fileName, 'public');

            // UPLOAD THUMBNAIL (opsional)
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumb = $request->file('thumbnail');
                $thumbName = time() . '_thumb_' . $thumb->getClientOriginalName();
                $thumbnailPath = $thumb->storeAs('uploads/thumbnails', $thumbName, 'public');
            }

            // SIMPAN DATABASE (table baru)
            $konten = Konten::create([
                'judul' => $validated['judul'],
                'tipe' => $validated['tipe'],
                'deskripsi' => $validated['deskripsi'],
                'file_path' => $filePath,
                'thumbnail_path' => $thumbnailPath,
                'id_siswa_uploader' => null,           // Siswa → null
                'id_admin_uploader' => $adminId,       // Admin → ID admin
                'id_approver' => $adminId,             // Auto approve
                'status' => 'approved',
                'tanggal_upload' => now(),
                'tanggal_approve' => now(),
            ]);

            return redirect()->back()->with('success', 'Konten "' . $validated['judul'] . '" berhasil diupload oleh admin!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('danger', 'Error: ' . $e->getMessage());
        }
    }

    // Approve konten
    public function approve($id)
    {
        $konten = Konten::findOrFail($id);
        
        $konten->update([
            'status' => 'approved',
            'id_approver' => Auth::guard('admin')->id(),
            'tanggal_approve' => now(),
        ]);

        return redirect()->back()->with('success', 'Konten berhasil disetujui!');
    }

    // Reject konten
    public function reject($id)
    {
        $konten = Konten::findOrFail($id);
        
        $konten->update([
            'status' => 'rejected',
            'id_approver' => Auth::guard('admin')->id(),
            'tanggal_approve' => now(),
        ]);

        return redirect()->back()->with('success', 'Konten ditolak!');
    }

    // Detail konten
    public function show($id)
    {
        $konten = Konten::with(['siswaUploader', 'adminUploader', 'approver'])->findOrFail($id);
        return view('admin.kontenshow', compact('konten'));
    }

    // Hapus konten (+ hapus file)
    public function destroy($id)
    {
        $konten = Konten::findOrFail($id);
        
        if ($konten->file_path) Storage::disk('public')->delete($konten->file_path);
        if ($konten->thumbnail_path) Storage::disk('public')->delete($konten->thumbnail_path);
        
        $konten->delete();

        // ✅ GUNAKAN karya.index (route ADA)
        return redirect()->route('admin.karya.index')->with('success', 'Konten dihapus!');
    }
}