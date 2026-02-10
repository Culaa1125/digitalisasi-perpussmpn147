<?php
// app/Models/Siswa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Siswa extends Authenticatable
{
    use Notifiable;

    protected $table = 'siswa';
    protected $primaryKey = 'nis'; 
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'kelas', 'nama_lengkap', 'nis', 'email', 
        'no_hp', 'tanggal_lahir', 'jk', 'password', 
        'status', 'approved_at', 'approved_by', 'rejection_reason'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'approved_at' => 'datetime',
    ];

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'nis', 'nis');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'nis', 'nis');
    }

    public function approver()
    {
        return $this->belongsTo(Admin::class, 'approved_by', 'admin_id');
    }

    // Scope untuk filter berdasarkan status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}