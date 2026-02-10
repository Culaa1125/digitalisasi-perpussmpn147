@extends('layouts.app')

@section('title', 'Pencarian: ' . $query . ' - SMPN 147 Jakarta')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Hasil Pencarian</h1>
        <p class="text-gray-400">Menampilkan hasil untuk: <span class="text-blue-400 font-semibold">"{{ $query }}"</span></p>
        <p class="text-gray-500 text-sm mt-1">{{ $results->total() }} buku ditemukan</p>
    </div>

    <!-- Search Results -->
    @if($results->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
            @foreach($results as $buku)
                <a href="{{ route('bukudigital.show', $buku->konten_id) }}" class="bg-gray-800 rounded-lg overflow-hidden hover:shadow-2xl transition transform hover:scale-105 backdrop-blur-sm bg-opacity-90">
                    <img src="{{ $buku->thumbnail_url }}" alt="{{ $buku->judul }}" class="w-full h-48 object-cover">
                    <div class="p-3">
                        <h3 class="text-white font-semibold text-xs mb-2 line-clamp-2">
                            {{ $buku->judul }}
                        </h3>
                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <span><i class="fas fa-eye mr-1"></i>{{ $buku->view_count }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $results->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="text-center py-16">
            <div class="bg-gray-800 rounded-lg p-12 backdrop-blur-sm bg-opacity-90 max-w-md mx-auto">
                <i class="fas fa-search text-6xl text-gray-600 mb-4"></i>
                <h3 class="text-2xl font-bold text-white mb-2">Tidak Ada Hasil</h3>
                <p class="text-gray-400 mb-6">Maaf, kami tidak menemukan buku yang cocok dengan pencarian Anda.</p>
                <a href="{{ route('bukudigital.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    @endif

    <!-- Search Tips -->
    <div class="mt-12 bg-gray-800 rounded-lg p-6 backdrop-blur-sm bg-opacity-90">
        <h3 class="text-xl font-bold text-white mb-4">Tips Pencarian</h3>
        <ul class="space-y-2 text-gray-300 text-sm">
            <li><i class="fas fa-check text-green-400 mr-2"></i>Gunakan kata kunci yang spesifik</li>
            <li><i class="fas fa-check text-green-400 mr-2"></i>Coba gunakan sinonim atau kata yang berbeda</li>
            <li><i class="fas fa-check text-green-400 mr-2"></i>Periksa ejaan kata kunci Anda</li>
            <li><i class="fas fa-check text-green-400 mr-2"></i>Gunakan kata kunci yang lebih pendek</li>
        </ul>
    </div>
</div>
@endsection