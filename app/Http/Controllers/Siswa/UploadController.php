<?php
// app/Http/Controllers/Siswa/UploadController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validasi
            $validated = $request->validate([
                'tipe' => 'required|in:buku,video',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:2000',
                'file' => 'required|file|max:51200|mimes:pdf,doc,docx,mp4,avi,mov',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ], [
                'file.required' => 'File wajib diupload',
                'file.max' => 'Ukuran file maksimal 50MB',
                'file.mimes' => 'Format file tidak didukung',
                'thumbnail.max' => 'Ukuran thumbnail maksimal 5MB',
                'tipe.required' => 'Pilih tipe konten',
                'judul.required' => 'Judul wajib diisi',
            ]);

            $nis = Auth::guard('siswa')->user()->nis;

            // Upload file konten
            $file = $request->file('file');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('uploads/konten', $fileName, 'public');

            // Upload thumbnail (opsional)
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumb = $request->file('thumbnail');
                $thumbName = time() . '_thumb_' . str_replace(' ', '_', $thumb->getClientOriginalName());
                $thumbnailPath = $thumb->storeAs('uploads/thumbnails', $thumbName, 'public');
            }

            // Simpan ke database
            Konten::create([
                'judul' => $validated['judul'],
                'tipe' => $validated['tipe'],
                'deskripsi' => $validated['deskripsi'],
                'file_path' => $filePath,
                'thumbnail_path' => $thumbnailPath,
                'id_siswa_uploader' => $nis,
                'id_admin_uploader' => null,
                'id_approver' => null,
                'status' => 'pending',
                'tanggal_upload' => now(),
                'tanggal_approve' => null,
                'view_count' => 0,
                'average_rating' => 0,
                'total_reviews' => 0,
            ]);

            return redirect()->route('siswa.home')
                           ->with('success', 'Karya berhasil diupload! Menunggu persetujuan admin.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                           ->withErrors($e->errors())
                           ->withInput()
                           ->with('error', 'Validasi gagal. Periksa kembali form Anda.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}