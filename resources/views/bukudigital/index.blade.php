@extends('layouts.app')

@section('title', 'Buku Digital - SMPN 147 Jakarta')

@section('content')
<div class="max-w-7xl mx-auto px-2 sm:px-4 py-8 relative z-10">
    
    <!-- Featured Section -->
    @if($featured)
    <div class="mb-8 lg:mb-12">
        <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg p-4 sm:p-6 text-white mb-4 sm:mb-6">
            <h2 class="text-lg sm:text-2xl font-bold mb-2 sm:mb-4">
                <i class="fas fa-fire mr-2"></i>Sedang Hangat
            </h2>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-8">
            <!-- Featured Content -->
            <div class="lg:col-span-2">
                <a href="{{ route('bukudigital.show', $featured->konten_id) }}" class="block">
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-xl backdrop-blur-sm bg-opacity-90 hover:shadow-2xl transition transform hover:scale-[1.02]">
                        <img src="{{ $featured->thumbnail_url }}" alt="{{ $featured->judul }}" class="w-full h-48 sm:h-80 object-cover">
                        <div class="p-3 sm:p-6">
                            <h3 class="text-lg sm:text-2xl font-bold text-white mb-2">
                                {{ $featured->judul }}
                            </h3>
                            <p class="text-gray-300 mb-1 sm:mb-2">{{ $featured->uploader_name }}</p>
                            <p class="text-gray-400 text-sm sm:text-base">
                                <i class="fas fa-eye text-yellow-400 mr-1"></i>
                                <span class="text-yellow-400">{{ number_format($featured->view_count) }} views</span>
                                <span class="text-gray-500 ml-2">• {{ $featured->tanggal_approve->diffForHumans() }}</span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Sidebar Info -->
            <div class="space-y-4 sm:space-y-6">
                <div class="bg-gray-800 rounded-lg p-3 sm:p-6 text-center backdrop-blur-sm bg-opacity-90">
                    <div class="bg-blue-600 rounded-lg p-6 mb-4">
                        <i class="fas fa-book-open text-white text-5xl"></i>
                    </div>
                    <h4 class="text-white font-semibold mb-2">Koleksi Digital</h4>
                    <p class="text-gray-300 text-xs sm:text-sm">
                        Akses ribuan buku digital berkualitas untuk mendukung pembelajaran Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Terbaru dan Terpopuler dengan Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main Content Area -->
        <div class="lg:col-span-3 space-y-8 lg:space-y-12">
            
            <!-- Terbaru Section -->
            <div>
                <div class="flex items-center gap-2 sm:gap-4 mb-4 sm:mb-6 pb-2 sm:pb-4 border-b-2 border-blue-500">
                    <h2 class="text-lg sm:text-2xl font-bold text-white">
                        <i class="fas fa-clock mr-2"></i>Terbaru
                    </h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-6">
                    @forelse($terbaru as $buku)
                        <a href="{{ route('bukudigital.show', $buku->konten_id) }}" class="bg-gray-800 rounded-lg overflow-hidden hover:shadow-2xl transition transform hover:scale-105 cursor-pointer backdrop-blur-sm bg-opacity-90">
                            <img src="{{ $buku->thumbnail_url }}" alt="{{ $buku->judul }}" class="w-full h-32 sm:h-64 object-cover">
                            <div class="p-2 sm:p-4">
                                <h3 class="text-white font-semibold text-xs sm:text-sm mb-1 sm:mb-2 line-clamp-2">
                                    {{ $buku->judul }}
                                </h3>
                                <div class="flex items-center justify-between text-xs text-gray-400">
                                    <span><i class="fas fa-eye mr-1"></i>{{ $buku->view_count }}</span>
                                    <span>{{ $buku->tanggal_approve->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-3 text-center text-gray-400 py-8">
                            <i class="fas fa-book text-5xl mb-4"></i>
                            <p>Belum ada buku tersedia</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Terpopuler Section -->
            <div>
                <div class="flex items-center gap-2 sm:gap-4 mb-4 sm:mb-6 pb-2 sm:pb-4 border-b-2 border-blue-500">
                    <h2 class="text-lg sm:text-2xl font-bold text-white">
                        <i class="fas fa-fire mr-2"></i>Terpopuler
                    </h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-6">
                    @forelse($terpopuler as $buku)
                        <a href="{{ route('bukudigital.show', $buku->konten_id) }}" class="bg-gray-800 rounded-lg overflow-hidden hover:shadow-2xl transition transform hover:scale-105 cursor-pointer backdrop-blur-sm bg-opacity-90">
                            <img src="{{ $buku->thumbnail_url }}" alt="{{ $buku->judul }}" class="w-full h-32 sm:h-64 object-cover">
                            <div class="p-2 sm:p-4">
                                <h3 class="text-white font-semibold text-xs sm:text-sm mb-1 sm:mb-2 line-clamp-2">
                                    {{ $buku->judul }}
                                </h3>
                                <div class="flex items-center justify-center text-yellow-400 text-xs sm:text-base">
                                    <i class="fas fa-fire mr-2"></i>
                                    <span>{{ number_format($buku->view_count) }} views</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-3 text-center text-gray-400 py-8">
                            <i class="fas fa-chart-line text-5xl mb-4"></i>
                            <p>Belum ada data popularitas</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar - Paling Banyak Dibaca -->
        <div class="bg-gray-800 rounded-lg p-3 sm:p-6 backdrop-blur-sm bg-opacity-90 h-fit">
            <div class="flex items-center gap-2 sm:gap-4 mb-4 sm:mb-6 pb-2 sm:pb-4 border-b-2 border-blue-500">
                <h3 class="text-base sm:text-xl font-bold text-white">Paling Banyak Dibaca</h3>
            </div>
            <div class="space-y-3 sm:space-y-6">
                @forelse($palingBanyakDibaca as $index => $buku)
                    <a href="{{ route('bukudigital.show', $buku->konten_id) }}" class="block">
                        <div class="bg-gray-700 rounded-lg p-2 sm:p-4 backdrop-blur-sm bg-opacity-80 hover:bg-opacity-100 transition">
                            <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full mb-2 sm:mb-3 mx-auto">
                                <span class="text-white font-bold text-base sm:text-lg">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <h4 class="text-white font-semibold text-center mb-1 sm:mb-2 line-clamp-2 text-sm">
                                {{ $buku->judul }}
                            </h4>
                            <div class="flex justify-center items-center text-yellow-400 text-xs sm:text-sm">
                                <i class="fas fa-eye mr-2"></i>
                                <span>{{ number_format($buku->view_count) }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center text-gray-400 py-4">
                        <p class="text-sm">Belum ada data</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 3000)"
         class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif
@endsection