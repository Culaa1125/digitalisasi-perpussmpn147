<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\KontenController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Siswa\SiswaAuthController;
use App\Http\Controllers\Siswa\ChatbotController;
use App\Http\Controllers\BukuDigitalController;
use App\Http\Controllers\Siswa\SiswaHomeController;
use App\Http\Controllers\Siswa\UploadController;
use App\Http\Controllers\FilmController;

// Root redirect
Route::get('/', function () {
    return redirect()->route('bukudigital.index');
});

// Buku Digital Routes (Public)
Route::prefix('bukudigital')->name('bukudigital.')->group(function () {
    Route::get('/', [BukuDigitalController::class, 'index'])->name('index');
    Route::get('/search', [BukuDigitalController::class, 'search'])->name('search');
    Route::get('/{id}', [BukuDigitalController::class, 'show'])->name('show');
    
    // Read route (requires authentication)
    Route::get('/{id}/read', [BukuDigitalController::class, 'read'])
         ->name('read')
         ->middleware('auth:siswa');
    
    // Review route (requires authentication)
    Route::post('/{id}/review', [BukuDigitalController::class, 'storeReview'])
         ->name('review')
         ->middleware('auth:siswa');
});

// Film Routes (Public)
Route::prefix('film')->name('film.')->group(function () {
    Route::get('/', [FilmController::class, 'index'])->name('index');
    Route::get('/search', [FilmController::class, 'search'])->name('search');
    Route::get('/{id}', [FilmController::class, 'show'])->name('show');
    
    // NEW: Watch route (requires authentication)
    Route::get('/{id}/watch', [FilmController::class, 'watch'])
         ->name('watch')
         ->middleware('auth:siswa');
    
    // Review route (requires authentication)
    Route::post('/{id}/review', [FilmController::class, 'storeReview'])
         ->name('review')
         ->middleware('auth:siswa');
});

// Admin Login Routes (guest only)
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login']);
});

// Admin Protected Routes dengan prevent-back middleware
Route::middleware(['auth:admin', 'prevent-back'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/activity', [ActivityController::class, 'index'])->name('activity');
    Route::get('/activity/chart-data', [ActivityController::class, 'getChartData'])->name('activity.chart');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Siswa
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::put('/siswa/{nis}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{nis}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::post('/siswa/{nis}/approve', [SiswaController::class, 'approve'])->name('siswa.approve');
    Route::post('/siswa/{nis}/reject', [SiswaController::class, 'reject'])->name('siswa.reject');
    
    // Karya
    Route::get('/karya', [KontenController::class, 'index'])->name('karya.index');
    Route::post('/konten/store', [KontenController::class, 'store'])->name('konten.store');
    Route::get('/konten/{id}', [KontenController::class, 'show'])->name('konten.show');
    Route::post('/konten/{id}/approve', [KontenController::class, 'approve'])->name('konten.approve');
    Route::post('/konten/{id}/reject', [KontenController::class, 'reject'])->name('konten.reject');
    Route::delete('/konten/{id}', [KontenController::class, 'destroy'])->name('konten.destroy');
    
    // Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::post('/extend-session', [AdminAuthController::class, 'extendSession'])->name('extend-session');
});

// Siswa Routes dengan prevent-back middleware
Route::prefix('siswa')->name('siswa.')->group(function () {
    // Guest only (login/register)
    Route::middleware('guest:siswa')->group(function () {
        Route::get('/login', [SiswaAuthController::class, 'showLogin'])->name('loginsiswa');
        Route::post('/login', [SiswaAuthController::class, 'login']);
        Route::get('/register', [SiswaAuthController::class, 'showRegister'])->name('daftarsiswa');
        Route::post('/register', [SiswaAuthController::class, 'register']);
    });

    // Authenticated only dengan prevent-back middleware
    Route::middleware(['auth:siswa', 'prevent-back'])->group(function () {
        Route::get('/home', [SiswaHomeController::class, 'index'])->name('home');
        Route::post('/logout', [SiswaAuthController::class, 'logout'])->name('logout');
        Route::post('/extend-session', [SiswaAuthController::class, 'extendSession'])->name('extend-session');
        Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');

        // Chatbot routes
        Route::post('/chatbot', [ChatbotController::class, 'chat'])->name('chatbot.chat');
        Route::get('/chatbot/history', [ChatbotController::class, 'getHistory'])->name('chatbot.history');
        Route::delete('/chatbot/clear', [ChatbotController::class, 'clearHistory'])->name('chatbot.clear');
    });
});