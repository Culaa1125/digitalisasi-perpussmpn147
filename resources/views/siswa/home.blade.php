@extends('layouts.app')

@section('title', 'Home - SMPN 147 Jakarta')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 sm:p-8 mb-8 text-white">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Selamat Datang, {{ $siswa->nama_lengkap }}! 👋</h1>
                <p class="text-blue-100">{{ $siswa->kelas }} • {{ $siswa->nis }}</p>
            </div>
            <div class="text-right">
                <p class="text-blue-100 text-sm">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
                <p class="text-xl sm:text-2xl font-bold">{{ now()->format('H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Personal Stats - Karya Saya -->
    <div class="mb-8">
        <h2 class="text-xl sm:text-2xl font-bold text-white mb-4">
            <i class="fas fa-chart-line mr-2"></i>Statistik Karya Saya
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 sm:p-6 backdrop-blur-sm bg-opacity-90">
                <div class="text-center">
                    <i class="fas fa-folder text-3xl sm:text-4xl text-white mb-2"></i>
                    <p class="text-blue-100 text-xs sm:text-sm">Total Karya</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $stats['total_karya'] }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 sm:p-6 backdrop-blur-sm bg-opacity-90">
                <div class="text-center">
                    <i class="fas fa-check-circle text-3xl sm:text-4xl text-white mb-2"></i>
                    <p class="text-green-100 text-xs sm:text-sm">Disetujui</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $stats['approved'] }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-4 sm:p-6 backdrop-blur-sm bg-opacity-90">
                <div class="text-center">
                    <i class="fas fa-clock text-3xl sm:text-4xl text-white mb-2"></i>
                    <p class="text-yellow-100 text-xs sm:text-sm">Pending</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $stats['pending'] }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg p-4 sm:p-6 backdrop-blur-sm bg-opacity-90">
                <div class="text-center">
                    <i class="fas fa-times-circle text-3xl sm:text-4xl text-white mb-2"></i>
                    <p class="text-red-100 text-xs sm:text-sm">Ditolak</p>
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Engagement Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-gray-800 rounded-lg p-6 backdrop-blur-sm bg-opacity-90">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Views</p>
                    <p class="text-3xl font-bold text-white">{{ number_format($stats['total_views']) }}</p>
                    <p class="text-gray-500 text-xs mt-1">Dari karya yang disetujui</p>
                </div>
                <div class="bg-purple-500 p-4 rounded-full">
                    <i class="fas fa-eye text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6 backdrop-blur-sm bg-opacity-90">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total Reviews</p>
                    <p class="text-3xl font-bold text-white">{{ number_format($stats['total_reviews']) }}</p>
                    <p class="text-gray-500 text-xs mt-1">Review dari pembaca</p>
                </div>
                <div class="bg-pink-500 p-4 rounded-full">
                    <i class="fas fa-star text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ FIX: Tambahkan Alpine data dengan filter di section Karya Saya -->

    <div class="mb-8" x-data="{ showUploadModal: false, filter: 'all' }">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-white">
                <i class="fas fa-book mr-2"></i>Karya Saya
            </h2>
            <button @click="showUploadModal = true" 
                    data-open-upload
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition text-sm">
                <i class="fas fa-plus mr-2"></i>Upload Karya
            </button>
        </div>

        @if($myKonten->count() > 0)
            <!-- Tab Filter - PINDAHKAN x-data ke parent -->
            <div class="flex gap-2 mb-4 flex-wrap">
                <button @click="filter = 'all'" 
                        :class="filter === 'all' ? 'bg-blue-600' : 'bg-gray-700'"
                        class="px-4 py-2 rounded-lg text-white text-sm font-semibold transition">
                    Semua ({{ $myKonten->count() }})
                </button>
                <button @click="filter = 'approved'" 
                        :class="filter === 'approved' ? 'bg-green-600' : 'bg-gray-700'"
                        class="px-4 py-2 rounded-lg text-white text-sm font-semibold transition">
                    Disetujui ({{ $stats['approved'] }})
                </button>
                <button @click="filter = 'pending'" 
                        :class="filter === 'pending' ? 'bg-yellow-600' : 'bg-gray-700'"
                        class="px-4 py-2 rounded-lg text-white text-sm font-semibold transition">
                    Pending ({{ $stats['pending'] }})
                </button>
                <button @click="filter = 'rejected'" 
                        :class="filter === 'rejected' ? 'bg-red-600' : 'bg-gray-700'"
                        class="px-4 py-2 rounded-lg text-white text-sm font-semibold transition">
                    Ditolak ({{ $stats['rejected'] }})
                </button>
            </div>

            <!-- Karya List -->
            <div class="bg-gray-800 rounded-lg p-4 sm:p-6 backdrop-blur-sm bg-opacity-90">
                <div class="space-y-4">
                    @foreach($myKonten as $karya)
                        <div x-data="{ showDetails: false }" 
                            x-show="filter === 'all' || filter === '{{ $karya->status }}'"
                            class="bg-gray-700 rounded-lg p-4 hover:bg-gray-600 transition">
                            <div class="flex items-start gap-4">
                                <!-- Thumbnail -->
                                <div class="flex-shrink-0">
                                    <img src="{{ $karya->thumbnail_url }}" 
                                        alt="{{ $karya->judul }}" 
                                        class="w-16 h-20 sm:w-20 sm:h-28 object-cover rounded">
                                </div>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <h3 class="text-white font-semibold text-sm sm:text-base line-clamp-2">
                                            {{ $karya->judul }}
                                        </h3>
                                        <!-- Status Badge -->
                                        @if($karya->status === 'approved')
                                            <span class="bg-green-500 text-white px-2 py-1 rounded text-xs font-semibold whitespace-nowrap">
                                                <i class="fas fa-check mr-1"></i>Approved
                                            </span>
                                        @elseif($karya->status === 'pending')
                                            <span class="bg-yellow-500 text-white px-2 py-1 rounded text-xs font-semibold whitespace-nowrap">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @else
                                            <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold whitespace-nowrap">
                                                <i class="fas fa-times mr-1"></i>Rejected
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-4 text-xs sm:text-sm text-gray-400 mb-2">
                                        <span><i class="fas fa-calendar mr-1"></i>{{ $karya->tanggal_upload->format('d/m/Y') }}</span>
                                        <span><i class="fas fa-tag mr-1"></i>{{ ucfirst($karya->tipe) }}</span>
                                    </div>

                                    @if($karya->status === 'approved')
                                        <div class="flex items-center gap-4 text-xs sm:text-sm text-gray-300">
                                            <span><i class="fas fa-eye text-blue-400 mr-1"></i>{{ number_format($karya->view_count) }} views</span>
                                            <span><i class="fas fa-star text-yellow-400 mr-1"></i>{{ number_format($karya->average_rating, 1) }} ({{ $karya->total_reviews }})</span>
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="flex gap-2 mt-3">
                                        @if($karya->status === 'approved')
                                            <a href="{{ route('bukudigital.show', $karya->konten_id) }}" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                                <i class="fas fa-eye mr-1"></i>Lihat
                                            </a>
                                        @endif
                                        <button @click="showDetails = !showDetails" 
                                                class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                            <i class="fas fa-info-circle mr-1"></i>Detail
                                        </button>
                                    </div>

                                    <!-- Expanded Details -->
                                    <div x-show="showDetails" 
                                        x-transition
                                        class="mt-3 pt-3 border-t border-gray-600">
                                        <p class="text-gray-300 text-sm mb-2">
                                            <span class="font-semibold text-gray-400">Deskripsi:</span><br>
                                            {{ $karya->deskripsi ?? 'Tidak ada deskripsi' }}
                                        </p>
                                        @if($karya->status === 'approved')
                                            <p class="text-gray-400 text-xs">
                                                Disetujui pada: {{ $karya->tanggal_approve->format('d F Y, H:i') }}
                                            </p>
                                        @elseif($karya->status === 'rejected')
                                            <p class="text-red-400 text-xs">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Karya Anda ditolak. Silakan perbaiki dan upload ulang.
                                            </p>
                                        @else
                                            <p class="text-yellow-400 text-xs">
                                                <i class="fas fa-hourglass-half mr-1"></i>
                                                Karya Anda sedang dalam proses review oleh admin.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-gray-800 rounded-lg p-12 backdrop-blur-sm bg-opacity-90 text-center">
                <i class="fas fa-folder-open text-6xl text-gray-600 mb-4"></i>
                <h3 class="text-xl font-bold text-white mb-2">Belum Ada Karya</h3>
                <p class="text-gray-400 mb-6">Anda belum mengupload karya apapun. Mulai bagikan karya Anda sekarang!</p>
                <button @click="showUploadModal = true" 
                        data-open-upload
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-plus mr-2"></i>Upload Karya Pertama
                </button>
            </div>
        @endif
        
        <!-- Include Upload Modal -->
        @include('components.upload-modal', ['showModal' => 'showUploadModal'])
    </div>

    <!-- Karya Terpopuler Saya -->
    @if($popularKarya->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl sm:text-2xl font-bold text-white mb-6">
            <i class="fas fa-fire mr-2"></i>Karya Terpopuler Saya
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($popularKarya as $index => $karya)
                <a href="{{ route('bukudigital.show', $karya->konten_id) }}" 
                   class="bg-gray-800 rounded-lg overflow-hidden hover:shadow-2xl transition transform hover:scale-105 backdrop-blur-sm bg-opacity-90">
                    <div class="relative">
                        <img src="{{ $karya->thumbnail_url }}" 
                             alt="{{ $karya->judul }}" 
                             class="w-full h-48 object-cover">
                        <div class="absolute top-2 left-2 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                            #{{ $index + 1 }}
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-white font-semibold text-sm mb-2 line-clamp-2">{{ $karya->judul }}</h3>
                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <span><i class="fas fa-eye mr-1"></i>{{ number_format($karya->view_count) }}</span>
                            <span><i class="fas fa-star mr-1 text-yellow-400"></i>{{ number_format($karya->average_rating, 1) }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Rekomendasi dari Perpustakaan -->
    @if($recentBooks->count() > 0)
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-white">
                <i class="fas fa-book-open mr-2"></i>Rekomendasi untuk Anda
            </h2>
            <a href="{{ route('bukudigital.index') }}" class="text-blue-400 hover:text-blue-300 text-sm">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($recentBooks as $book)
                <a href="{{ route('bukudigital.show', $book->konten_id) }}" 
                   class="bg-gray-800 rounded-lg overflow-hidden hover:shadow-xl transition transform hover:scale-105 backdrop-blur-sm bg-opacity-90">
                    <img src="{{ $book->thumbnail_url }}" 
                         alt="{{ $book->judul }}" 
                         class="w-full h-40 object-cover">
                    <div class="p-3">
                        <h3 class="text-white font-semibold text-xs mb-1 line-clamp-2">{{ $book->judul }}</h3>
                        <p class="text-gray-400 text-xs">
                            <i class="fas fa-eye mr-1"></i>{{ number_format($book->view_count) }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Quick Access -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-6 text-white">
            <i class="fas fa-lightbulb text-4xl mb-4"></i>
            <h3 class="text-xl font-bold mb-2">Tips Upload Karya</h3>
            <ul class="space-y-2 text-indigo-100 text-sm">
                <li><i class="fas fa-check mr-2"></i>Pastikan karya original dan berkualitas</li>
                <li><i class="fas fa-check mr-2"></i>Gunakan thumbnail yang menarik</li>
                <li><i class="fas fa-check mr-2"></i>Tulis deskripsi yang jelas</li>
                <li><i class="fas fa-check mr-2"></i>Periksa format file sebelum upload</li>
            </ul>
        </div>

        <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg p-6 text-white">
            <i class="fas fa-trophy text-4xl mb-4"></i>
            <h3 class="text-xl font-bold mb-2">Pencapaian Bulan Ini</h3>
            <div class="space-y-3">
                <div class="bg-white/20 rounded-lg p-3">
                    <p class="text-sm mb-1">Total Views</p>
                    <div class="flex items-center">
                        <div class="flex-1 bg-white/20 rounded-full h-2 mr-3">
                            @php
                                $viewPercentage = $stats['total_views'] > 0 ? min(($stats['total_views'] / 1000) * 100, 100) : 0;
                            @endphp
                            <div class="bg-white h-2 rounded-full" style="width: {{ $viewPercentage }}%"></div>
                        </div>
                        <span class="font-bold">{{ number_format($stats['total_views']) }}</span>
                    </div>
                </div>
                <p class="text-pink-100 text-sm">
                    @if($stats['approved'] > 0)
                        Hebat! Karyamu sudah dilihat {{ number_format($stats['total_views']) }} kali! 🎉
                    @else
                        Upload karya pertamamu dan raih views! 🚀
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Success Messages -->
@if(session('logout_success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)"
         x-transition
         class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('logout_success') }}
    </div>
@endif

@if(session('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)"
         x-transition
         class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif
@endsection