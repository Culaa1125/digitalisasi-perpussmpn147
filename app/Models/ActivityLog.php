<?php
// app/Models/ActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'activity_type',
        'description',
        'konten_id',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    public function konten()
    {
        return $this->belongsTo(Konten::class, 'konten_id', 'konten_id');
    }

    // Scope untuk filter berdasarkan tipe aktivitas
    public function scopeOfType($query, $type)
    {
        return $query->where('activity_type', $type);
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}