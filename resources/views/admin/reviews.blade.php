@extends('layouts.admin')

@section('title', 'Reviews & Komentar')
@section('page-title', 'Reviews & Komentar')

@section('content')
<section class="p-4 md:p-10">
    <!-- TOP BAR WITH FILTERS -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 md:p-6 mb-6">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Review</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input 
                            type="text" 
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nama siswa atau judul konten" 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        >
                    </div>
                </div>

                <!-- Rating Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <select name="rating" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Semua Rating</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Bintang)</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 Bintang)</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3 Bintang)</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐ (2 Bintang)</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐ (1 Bintang)</option>
                    </select>
                </div>

                <!-- Negative Filter -->
                <div class="flex items-end gap-2">
                    <label class="flex items-center gap-2 cursor-pointer flex-1">
                        <input 
                            type="checkbox" 
                            name="negative" 
                            value="1"
                            {{ request('negative') == '1' ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <span class="text-sm text-gray-700">Hanya Negatif (1-2⭐)</span>
                    </label>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.reviews.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- STATISTICS CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Total Review</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Positif (4-5⭐)</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['positive'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Netral (3⭐)</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['neutral'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Negatif (1-2⭐)</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['negative'] }}</p>
        </div>
    </div>

    <!-- REVIEWS LIST -->
    <div class="space-y-4">
        @forelse($reviews as $review)
        <div class="bg-white rounded-lg border border-gray-200 p-4 md:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col md:flex-row md:items-start gap-4">
                <!-- User Info -->
                <div class="flex items-center gap-3 md:w-64 flex-shrink-0">
                    <img 
                        src="https://ui-avatars.com/api/?name={{ urlencode($review->siswa->nama_lengkap ?? 'Unknown') }}&background=4F46E5&color=fff" 
                        alt="" 
                        class="w-12 h-12 rounded-full"
                    >
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-sm text-gray-800 truncate">
                            {{ $review->siswa->nama_lengkap ?? 'Unknown' }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $review->siswa->kelas ?? '-' }}</p>
                    </div>
                </div>

                <!-- Review Content -->
                <div class="flex-1 min-w-0">
                    <!-- Konten Title & Rating -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                        <div class="min-w-0">
                            <h3 class="font-semibold text-gray-800 truncate">
                                {{ $review->konten->judul ?? 'Unknown' }}
                            </h3>
                            <p class="text-xs text-gray-500">
                                {{ $review->konten->tipe == 'buku' ? '📚 Buku' : '🎬 Video' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                            <span class="ml-2 text-sm font-semibold text-gray-700">{{ $review->rating }}/5</span>
                        </div>
                    </div>

                    <!-- Comment -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-3">
                        <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $review->comment ?? 'Tidak ada komentar' }}
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $review->created_at->format('d M Y, H:i') }} WIB
                        </p>
                        
                        <!-- Delete Button -->
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review->review_id) }}" 
                              onsubmit="return confirm('Yakin ingin menghapus review ini?')"
                              class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:text-red-700 hover:bg-red-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                <i class="fas fa-trash"></i>
                                <span>Hapus Review</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Warning Badge for Negative Reviews -->
            @if($review->rating <= 2)
            <div class="mt-3 pt-3 border-t border-gray-200">
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                    <i class="fas fa-exclamation-triangle"></i>
                    Review Negatif - Perlu Perhatian
                </span>
            </div>
            @endif
        </div>
        @empty
        <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
            <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Tidak ada review ditemukan</p>
        </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if($reviews->hasPages())
    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
    @endif
</section>
@endsection