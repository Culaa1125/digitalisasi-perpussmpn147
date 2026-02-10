<?php
// app/Http/Controllers/BukuDigitalController.php

namespace App\Http\Controllers;

use App\Models\Konten;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class BukuDigitalController extends Controller
{
    public function index()
    {
        // Ambil data buku
        $featured = Konten::approved()
                        ->buku()
                        ->orderBy('tanggal_approve', 'desc')
                        ->first();

        $terbaru = Konten::terbaru(6)->get();
        $terpopuler = Konten::terpopuler(6)->get();
        $palingBanyakDibaca = Konten::palingBanyakDibaca(3)->get();

        return view('bukudigital.index', compact(
            'featured',
            'terbaru',
            'terpopuler',
            'palingBanyakDibaca'
        ));
    }

    public function show($id)
    {
        $konten = Konten::with(['reviews.siswa', 'siswaUploader', 'adminUploader'])
                      ->approved()
                      ->buku()
                      ->findOrFail($id);

        // Increment view count
        $konten->incrementView();

        // Get related books
        $relatedBooks = Konten::approved()
                            ->buku()
                            ->where('konten_id', '!=', $konten->konten_id)
                            ->inRandomOrder()
                            ->limit(4)
                            ->get();

        // Get rating breakdown
        $ratingBreakdown = $konten->getRatingBreakdown();

        // Get recent reviews (paginated)
        $reviews = $konten->reviews()
                        ->with('siswa')
                        ->orderBy('created_at', 'desc')
                        ->paginate(6);

        return view('bukudigital.show', compact('konten', 'relatedBooks', 'ratingBreakdown', 'reviews'));
    }

    public function storeReview(Request $request, $id)
    {
        // Check if user is authenticated
        if (!Auth::guard('siswa')->check()) {
            return redirect()->route('siswa.loginsiswa')
                           ->with('error', 'Silakan login untuk memberikan review');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000'
        ]);

        $konten = Konten::findOrFail($id);
        $nis = Auth::guard('siswa')->user()->nis;

        // Check if user already reviewed
        $existingReview = Review::where('konten_id', $id)
                               ->where('nis', $nis)
                               ->first();

        if ($existingReview) {
            // Update existing review
            $existingReview->update($validated);
            $message = 'Review Anda berhasil diperbarui!';
        } else {
            // Create new review
            Review::create([
                'konten_id' => $id,
                'nis' => $nis,
                'rating' => $validated['rating'],
                'comment' => $validated['comment']
            ]);
            $message = 'Terima kasih atas review Anda!';
        }

        // Update konten rating
        $konten->updateRating();

        return redirect()->route('bukudigital.show', $id)
                       ->with('success', $message);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $results = Konten::approved()
                        ->buku()
                        ->where('judul', 'ILIKE', "%{$query}%")
                        ->orWhere('deskripsi', 'ILIKE', "%{$query}%")
                        ->paginate(12);

        return view('bukudigital.search', compact('results', 'query'));
    }

    public function read($id)
    {
        $konten = Konten::approved()
                    ->buku()
                    ->findOrFail($id);

        // Check if user is authenticated
        if (!Auth::guard('siswa')->check()) {
            return redirect()->route('siswa.loginsiswa')
                        ->with('error', 'Silakan login untuk membaca buku');
        }

        // Increment view count (if not already counted in this session)
        if (!session()->has('viewed_' . $id)) {
            $konten->incrementView();
            session()->put('viewed_' . $id, true);
        }

        // Log aktivitas membaca buku
        ActivityLogger::log(
            'read_book', 
            'Membaca buku: ' . $konten->judul,
            $konten->konten_id
        );

        return view('bukudigital.read', compact('konten'));
    }
}