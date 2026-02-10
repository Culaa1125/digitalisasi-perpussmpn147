<?php
// app/Helpers/ActivityLogger.php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log($type, $description = null, $kontenId = null)
    {
        if (Auth::guard('siswa')->check()) {
            ActivityLog::create([
                'nis' => Auth::guard('siswa')->user()->nis,
                'activity_type' => $type,
                'description' => $description,
                'konten_id' => $kontenId,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent()
            ]);
        }
    }
}