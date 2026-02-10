<?php
// app/Models/Konten.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konten extends Model
{
    use HasFactory;

    protected $table = 'konten';
    protected $primaryKey = 'konten_id';
    public $timestamps = false;

    protected $fillable = [
        'judul',
        'deskripsi', 
        'tipe',
        'status',
        'id_siswa_uploader',
        'id_admin_uploader',
        'id_approver',
        'tanggal_upload',
        'tanggal_approve',
        'file_path',
        'thumbnail_path',
        'view_count',
        'average_rating',    // ← Tambahkan ini
        'total_reviews'      // ← Tambahkan ini
    ];

    protected $casts = [
        'tanggal_upload' => 'datetime',
        'tanggal_approve' => 'datetime',
        'view_count' => 'integer',
        'average_rating' => 'decimal:2',  // ← Tambahkan ini
        'total_reviews' => 'integer'       // ← Tambahkan ini
    ];

    // Relasi
    public function siswaUploader()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa_uploader', 'nis');
    }

    public function adminUploader()
    {
        return $this->belongsTo(Admin::class, 'id_admin_uploader', 'admin_id');
    }

    public function approver()
    {
        return $this->belongsTo(Admin::class, 'id_approver', 'admin_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'konten_id', 'konten_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'konten_id', 'konten_id');
    }

    // ✅ SCOPES - Tambahkan yang kurang
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)  // ← TAMBAHKAN INI
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)  // ← TAMBAHKAN INI
    {
        return $query->where('status', 'rejected');
    }

    public function scopeBuku($query)
    {
        return $query->where('tipe', 'buku');
    }

    public function scopeVideo($query)  // ← TAMBAHKAN INI (bonus)
    {
        return $query->where('tipe', 'video');
    }

    public function scopeTerbaru($query, $limit = 6)
    {
        return $query->approved()
                    ->buku()
                    ->orderBy('tanggal_approve', 'desc')
                    ->limit($limit);
    }

    public function scopeTerpopuler($query, $limit = 6)
    {
        return $query->approved()
                    ->buku()
                    ->orderBy('view_count', 'desc')
                    ->limit($limit);
    }

    public function scopePalingBanyakDibaca($query, $limit = 3)
    {
        return $query->approved()
                    ->buku()
                    ->orderBy('view_count', 'desc')
                    ->limit($limit);
    }

    // Methods
    public function incrementView()
    {
        $this->increment('view_count');
    }

    public function updateRating()
    {
        $this->average_rating = $this->reviews()->avg('rating') ?? 0;
        $this->total_reviews = $this->reviews()->count();
        $this->save();
    }

    public function getRatingBreakdown()
    {
        $breakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $breakdown[$i] = $this->reviews()->where('rating', $i)->count();
        }
        return $breakdown;
    }

    // Accessors
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return asset('storage/' . $this->thumbnail_path);
        }
        return 'https://via.placeholder.com/200x280?text=No+Image';
    }

    public function getUploaderNameAttribute()
    {
        if ($this->siswaUploader) {
            return $this->siswaUploader->nama_lengkap;
        }
        if ($this->adminUploader) {
            return $this->adminUploader->nama ?? 'Admin';
        }
        return 'Unknown';
    }

    public function getStarsAttribute()
    {
        $rating = $this->average_rating ?? 0;
        $fullStars = floor($rating);
        $hasHalfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
        
        return [
            'full' => (int)$fullStars,
            'half' => $hasHalfStar ? 1 : 0,
            'empty' => (int)$emptyStars
        ];
    }
}