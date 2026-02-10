<?php
// app/Http/Controllers/Admin/ActivityController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['siswa', 'konten']);

        // Filter berdasarkan tipe aktivitas
        if ($request->has('type') && $request->type != '') {
            $query->where('activity_type', $request->type);
        }

        // Filter berdasarkan NIS siswa
        if ($request->has('nis') && $request->nis != '') {
            $query->where('nis', $request->nis);
        }

        // Filter berdasarkan tanggal
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search berdasarkan nama siswa atau deskripsi
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('siswa', function($sq) use ($search) {
                    $sq->where('nama_lengkap', 'LIKE', '%' . $search . '%');
                })->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Statistik aktivitas
        $stats = [
            'total_activities' => ActivityLog::count(),
            'total_logins' => ActivityLog::where('activity_type', 'login')->count(),
            'total_reads' => ActivityLog::where('activity_type', 'read_book')->count(),
            'total_watches' => ActivityLog::where('activity_type', 'watch_video')->count(),
        ];

        // Daftar siswa untuk filter
        $siswaList = Siswa::orderBy('nama_lengkap')->get();

        return view('admin.activity', compact('activities', 'stats', 'siswaList'));
    }

   public function getChartData(Request $request)
    {
        $period = $request->get('period', 'month');
        $data = [];

        switch ($period) {
            case 'day':
                // Data 30 hari terakhir
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $count = ActivityLog::whereDate('created_at', $date->format('Y-m-d'))
                        ->where('activity_type', 'login')
                        ->distinct('nis')
                        ->count('nis');
                    
                    $data[] = [
                        'label' => $date->format('d M'),
                        'value' => $count
                    ];
                }
                break;

            case 'week':
                // Data 8 minggu terakhir
                for ($i = 7; $i >= 0; $i--) {
                    $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
                    $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
                    
                    $count = ActivityLog::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                        ->where('activity_type', 'login')
                        ->distinct('nis')
                        ->count('nis');
                    
                    $data[] = [
                        'label' => 'Minggu ' . $startOfWeek->format('d M'),
                        'value' => $count
                    ];
                }
                break;

            case 'month':
            default:
                // Data 12 bulan terakhir
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $count = ActivityLog::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->where('activity_type', 'login')
                        ->distinct('nis')
                        ->count('nis');
                    
                    $data[] = [
                        'label' => $date->format('M Y'),
                        'value' => $count
                    ];
                }
                break;
        }

        return response()->json($data);
    }
}