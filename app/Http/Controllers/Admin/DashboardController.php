<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Konten;
use App\Models\Review;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik umum
        $stats = [
            'total_siswa' => Siswa::count(),
            'total_konten' => Konten::where('status', 'approved')->count(),
            'total_reviews' => Review::count(),
            'pending_konten' => Konten::where('status', 'pending')->count(),
        ];

        // Siswa aktif bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $stats['active_students_month'] = ActivityLog::where('activity_type', 'login')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->distinct('nis')
            ->count('nis');

        // Data chart login 12 bulan terakhir
        $monthlyLoginData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $count = ActivityLog::where('activity_type', 'login')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->distinct('nis')
                ->count('nis');
            
            $monthlyLoginData[] = [
                'month' => $date->format('M Y'),
                'label' => $date->format('M Y'),
                'count' => $count,
                'value' => $count
            ];
        }

        // Aktivitas terbaru
        $recentActivities = ActivityLog::with(['siswa', 'konten'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Review terbaru dengan rating rendah
        $negativeReviews = Review::with(['siswa', 'konten'])
            ->whereIn('rating', [1, 2])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Top konten (paling banyak dibaca/ditonton)
        $topKonten = Konten::withCount(['activityLogs' => function($query) {
                $query->whereIn('activity_type', ['read_book', 'watch_video']);
            }])
            ->where('status', 'approved')
            ->orderBy('activity_logs_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'monthlyLoginData', 
            'recentActivities', 
            'negativeReviews',
            'topKonten'
        ));
    }
}