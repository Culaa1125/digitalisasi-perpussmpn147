<?php
// app/Http/Controllers/Admin/ReviewController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['siswa', 'konten']);

        // Filter berdasarkan rating
        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', $request->rating);
        }

        // Search berdasarkan nama siswa atau judul konten
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', '%' . $search . '%');
            })->orWhereHas('konten', function($q) use ($search) {
                $q->where('judul', 'LIKE', '%' . $search . '%');
            });
        }

        // Filter komentar negatif (rating 1-2)
        if ($request->has('negative') && $request->negative == '1') {
            $query->whereIn('rating', [1, 2]);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Statistik
        $stats = [
            'total' => Review::count(),
            'positive' => Review::whereIn('rating', [4, 5])->count(),
            'neutral' => Review::where('rating', 3)->count(),
            'negative' => Review::whereIn('rating', [1, 2])->count(),
        ];

        return view('admin.reviews', compact('reviews', 'stats'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Review berhasil dihapus!');
    }
}