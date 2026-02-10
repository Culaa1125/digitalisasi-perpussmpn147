<?php
// app/Http/Controllers/FilmController.php

namespace App\Http\Controllers;

use App\Models\Konten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class FilmController extends Controller
{
    public function index()
    {
        // Film featured (terbaru)
        $featured = Konten::approved()
                        ->where('tipe', 'video')
                        ->orderBy('tanggal_approve', 'desc')
                        ->first();

        // Film terbaru
        $terbaru = Konten::approved()
                       ->where('tipe', 'video')
                       ->orderBy('tanggal_approve', 'desc')
                       ->limit(6)
                       ->get();

        // Film terpopuler
        $terpopuler = Konten::approved()
                          ->where('tipe', 'video')
                          ->orderBy('view_count', 'desc')
                          ->limit(6)
                          ->get();

        // Film paling banyak ditonton
        $palingBanyakDitonton = Konten::approved()
                                     ->where('tipe', 'video')
                                     ->orderBy('view_count', 'desc')
                                     ->limit(3)
                                     ->get();

        return view('film.index', compact(
            'featured',
            'terbaru',
            'terpopuler',
            'palingBanyakDitonton'
        ));
    }

    public function show($id)
    {
        $konten = Konten::with(['reviews.siswa', 'siswaUploader', 'adminUploader'])
                      ->approved()
                      ->where('tipe', 'video')
                      ->findOrFail($id);

        // Increment view count
        $konten->incrementView();

        // Get related films
        $relatedFilms = Konten::approved()
                            ->where('tipe', 'video')
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

        return view('film.show', compact('konten', 'relatedFilms', 'ratingBreakdown', 'reviews'));
    }

    // ✅ NEW: Method untuk halaman watch
    public function watch($id)
    {
        // Check if user is authenticated
        if (!Auth::guard('siswa')->check()) {
            return redirect()->route('siswa.loginsiswa')
                           ->with('error', 'Silakan login untuk menonton film');
        }

        $konten = Konten::with(['siswaUploader', 'adminUploader'])
                      ->approved()
                      ->where('tipe', 'video')
                      ->findOrFail($id);

        // Increment view count
        $konten->incrementView();

        // Log aktivitas menonton film
        ActivityLogger::log(
            'watch_video', 
            'Menonton video: ' . $konten->judul,
            $konten->konten_id
        );

        return view('film.watch', compact('konten'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $results = Konten::approved()
                        ->where('tipe', 'video')
                        ->where(function($q) use ($query) {
                            $q->where('judul', 'ILIKE', "%{$query}%")
                              ->orWhere('deskripsi', 'ILIKE', "%{$query}%");
                        })
                        ->paginate(12);

        return view('film.search', compact('results', 'query'));
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
        $existingReview = \App\Models\Review::where('konten_id', $id)
                               ->where('nis', $nis)
                               ->first();

        if ($existingReview) {
            $existingReview->update($validated);
            $message = 'Review Anda berhasil diperbarui!';
        } else {
            \App\Models\Review::create([
                'konten_id' => $id,
                'nis' => $nis,
                'rating' => $validated['rating'],
                'comment' => $validated['comment']
            ]);
            $message = 'Terima kasih atas review Anda!';
        }

        // Update konten rating
        $konten->updateRating();

        return redirect()->route('film.show', $id)
                       ->with('success', $message);
    }
}