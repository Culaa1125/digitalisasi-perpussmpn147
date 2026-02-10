@extends('layouts.app')

@section('title', $konten->judul . ' - SMPN 147 Jakarta')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8 relative z-10">
    
    <!-- Main Content: TAB Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Film Thumbnail & Details -->
        <div class="lg:col-span-1">
            <div class="rounded-lg overflow-hidden shadow-2xl relative">
                <img src="{{ $konten->thumbnail_url }}" 
                     alt="{{ $konten->judul }}" 
                     class="w-full h-auto object-cover">
                <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                    <div class="bg-red-600 rounded-full p-6">
                        <i class="fas fa-play text-white text-4xl ml-1"></i>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 bg-gray-800 rounded-lg p-4 backdrop-blur-sm bg-opacity-90">
                <h3 class="text-white font-bold text-lg mb-3 text-center">Detail Film</h3>
                <div class="text-left space-y-3">
                    <div>
                        <span class="text-gray-400 text-sm">Judul</span>
                        <p class="text-white font-semibold">{{ $konten->judul }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-sm">Uploader</span>
                        <p class="text-white font-semibold">{{ $konten->uploader_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-sm">Tanggal Upload</span>
                        <p class="text-white font-semibold">{{ $konten->tanggal_approve->format('d F Y') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-sm">Views</span>
                        <p class="text-white font-semibold">
                            <i class="fas fa-eye mr-1"></i>{{ number_format($konten->view_count) }}
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-sm">Rating</span>
                        <div class="flex mt-2">
                            @for($i = 0; $i < $konten->stars['full']; $i++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                            @if($konten->stars['half'])
                                <i class="fas fa-star-half-alt text-yellow-400"></i>
                            @endif
                            @for($i = 0; $i < $konten->stars['empty']; $i++)
                                <i class="fas fa-star text-gray-600"></i>
                            @endfor
                        </div>
                        <p class="text-white font-semibold text-sm mt-1">
                            {{ number_format($konten->average_rating, 1) }}/5 
                            ({{ number_format($konten->total_reviews) }})
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Content Area -->
        <div class="lg:col-span-2" x-data="{ activeTab: 'detail' }">
            <!-- Tab Buttons -->
            <div class="flex mb-4">
                <button 
                    @click="activeTab = 'detail'"
                    data-tab-button="detail"
                    :class="activeTab === 'detail' ? 'border-purple-500' : 'border-transparent opacity-60'"
                    class="flex-1 py-3 font-semibold text-lg border-b-2 transition-colors text-white bg-gray-800 rounded-tl-lg"
                >
                    Detail Film
                </button>
                <button 
                    @click="activeTab = 'rating'"
                    data-tab-button="rating"
                    :class="activeTab === 'rating' ? 'border-purple-500' : 'border-transparent opacity-60'"
                    class="flex-1 py-3 font-semibold text-lg border-b-2 transition-colors text-white bg-gray-800 rounded-tr-lg"
                >
                    Rating & Reviews
                </button>
            </div>

            <!-- Detail Film Tab -->
            <div x-show="activeTab === 'detail'" 
                 data-tab-content="detail"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                <div class="bg-gray-800 rounded-lg p-6 sm:p-8 backdrop-blur-sm bg-opacity-90">
                    <h1 class="text-2xl sm:text-4xl font-bold text-white mb-4">{{ $konten->judul }}</h1>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex">
                            @for($i = 0; $i < $konten->stars['full']; $i++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                            @if($konten->stars['half'])
                                <i class="fas fa-star-half-alt text-yellow-400"></i>
                            @endif
                            @for($i = 0; $i < $konten->stars['empty']; $i++)
                                <i class="fas fa-star text-gray-600"></i>
                            @endfor
                        </div>
                        <span class="text-white font-semibold">{{ number_format($konten->average_rating, 1) }}/5</span>
                        <span class="text-gray-400">({{ number_format($konten->total_reviews) }} Reviews)</span>
                    </div>
                    
                    @auth('siswa')
                        {{-- ✅ UBAH: Link ke halaman watch --}}
                        <a href="{{ route('film.watch', $konten->konten_id) }}" 
                           class="block w-full bg-gradient-to-r from-purple-600 to-pink-700 hover:from-purple-700 hover:to-pink-800 text-white font-bold py-3 px-6 rounded-lg transition mb-8 text-center">
                            <i class="fas fa-play mr-2"></i>Tonton Sekarang
                        </a>
                    @else
                        <a href="{{ route('siswa.loginsiswa') }}" 
                           class="block w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition mb-8 text-center">
                            <i class="fas fa-lock mr-2"></i>Login untuk Menonton
                        </a>
                    @endauth
                    
                    <h3 class="text-xl font-bold text-white mb-3">Deskripsi</h3>
                    <div class="text-gray-300 leading-relaxed mb-8 whitespace-pre-line">
                        {{ $konten->deskripsi ?? 'Tidak ada deskripsi untuk film ini.' }}
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-700 rounded-lg p-4">
                            <p class="text-gray-400 text-sm mb-2">Uploader</p>
                            <p class="text-white font-semibold">{{ $konten->uploader_name }}</p>
                        </div>
                        <div class="bg-gray-700 rounded-lg p-4">
                            <p class="text-gray-400 text-sm mb-2">Tanggal Upload</p>
                            <p class="text-white font-semibold">{{ $konten->tanggal_approve->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rating & Reviews Tab -->
            <div x-show="activeTab === 'rating'" 
                 data-tab-content="rating"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;">
                <div class="bg-gray-800 rounded-lg p-6 sm:p-8 backdrop-blur-sm bg-opacity-90">
                    
                    <!-- Header with Write Review Button -->
                    <div class="mb-6 pb-4 border-b-2 border-purple-500">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-lg sm:text-2xl font-bold text-white">
                                All Reviews 
                                <span class="text-gray-400 text-base sm:text-xl">({{ number_format($konten->total_reviews) }})</span>
                            </h2>
                            @auth('siswa')
                                <button 
                                    id="openReviewModal"
                                    class="bg-purple-500 hover:bg-purple-600 text-white rounded-full px-3 sm:px-6 py-2 font-semibold text-xs sm:text-base whitespace-nowrap flex-shrink-0">
                                    <i class="fas fa-pen sm:mr-2"></i>
                                    <span class="hidden sm:inline">Tulis Review</span>
                                </button>
                            @else
                                <a href="{{ route('siswa.loginsiswa') }}" 
                                   class="bg-gray-600 hover:bg-gray-700 text-white rounded-full px-3 sm:px-6 py-2 font-semibold text-xs sm:text-base whitespace-nowrap flex-shrink-0">
                                    <i class="fas fa-sign-in-alt sm:mr-2"></i>
                                    <span class="hidden sm:inline">Login</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                    
                    <!-- Rating Overview -->
                    <div class="mb-8 flex flex-col md:flex-row gap-8">
                        <!-- Average Rating -->
                        <div class="flex-1 text-center">
                            <div class="text-4xl sm:text-5xl font-bold text-white mb-2">
                                {{ number_format($konten->average_rating, 1) }}
                            </div>
                            <div class="flex justify-center mb-4">
                                @for($i = 0; $i < $konten->stars['full']; $i++)
                                    <i class="fas fa-star text-yellow-400"></i>
                                @endfor
                                @if($konten->stars['half'])
                                    <i class="fas fa-star-half-alt text-yellow-400"></i>
                                @endif
                                @for($i = 0; $i < $konten->stars['empty']; $i++)
                                    <i class="fas fa-star text-gray-600"></i>
                                @endfor
                            </div>
                            <p class="text-gray-400">Berdasarkan {{ number_format($konten->total_reviews) }} ulasan</p>
                        </div>
                        
                        <!-- Rating Breakdown -->
                        <div class="flex-1 flex flex-col justify-center">
                            <div class="space-y-3">
                                @foreach([5, 4, 3, 2, 1] as $star)
                                    @php
                                        $count = $ratingBreakdown[$star] ?? 0;
                                        $percentage = $konten->total_reviews > 0 
                                            ? ($count / $konten->total_reviews) * 100 
                                            : 0;
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <span class="text-white text-sm w-8">
                                            {{ $star }}<i class="fas fa-star text-yellow-400 text-xs ml-1"></i>
                                        </span>
                                        <div class="flex-1 bg-gray-700 rounded-full h-2">
                                            <div class="bg-yellow-400 h-2 rounded-full" 
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-gray-400 text-sm w-12 text-right">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reviews List -->
                    @if($reviews->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            @foreach($reviews as $review)
                                <div class="bg-gray-700 rounded-lg p-4 sm:p-6 shadow backdrop-blur-sm bg-opacity-90">
                                    <div class="flex items-center mb-2 flex-wrap gap-2">
                                        <div class="flex mr-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }} text-sm"></i>
                                            @endfor
                                        </div>
                                        <span class="font-semibold text-white">
                                            {{ $review->siswa->nama_lengkap ?? 'Anonymous' }}
                                            <i class="fas fa-check-circle text-green-400 ml-1"></i>
                                        </span>
                                    </div>
                                    <p class="mb-2 text-gray-300 text-sm">{{ $review->comment }}</p>
                                    <p class="text-gray-400 text-xs">
                                        Posted on {{ $review->created_at->format('F d, Y') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="flex justify-center">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-comments text-5xl text-gray-600 mb-4"></i>
                            <p class="text-gray-400">Belum ada review untuk film ini</p>
                            <p class="text-gray-500 text-sm">Jadilah yang pertama memberikan review!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Section Film Lainnya -->
    @if($relatedFilms->count() > 0)
    <div class="mb-12 mt-12">
        <div class="flex items-center gap-4 mb-6 pb-4 border-b-2 border-purple-500">
            <h2 class="text-xl sm:text-2xl font-bold text-white">
                <i class="fas fa-film mr-2"></i>Film Lainnya
            </h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($relatedFilms as $related)
                <a href="{{ route('film.show', $related->konten_id) }}" 
                   class="bg-gray-800 rounded-lg overflow-hidden hover:shadow-2xl transition transform hover:scale-105 cursor-pointer backdrop-blur-sm bg-opacity-90">
                    <div class="relative">
                        <img src="{{ $related->thumbnail_url }}" 
                             alt="{{ $related->judul }}" 
                             class="w-full h-48 sm:h-64 object-cover">
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition bg-black/50">
                            <div class="bg-red-600 rounded-full p-3 sm:p-4">
                                <i class="fas fa-play text-white text-lg sm:text-2xl ml-1"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="text-white font-semibold text-xs sm:text-sm mb-2 line-clamp-2">
                            {{ $related->judul }}
                        </h3>
                        <div class="flex justify-between items-center">
                            <div class="flex">
                                @for($i = 0; $i < $related->stars['full']; $i++)
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                @endfor
                                @if($related->stars['half'])
                                    <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                                @endif
                                @for($i = 0; $i < $related->stars['empty']; $i++)
                                    <i class="fas fa-star text-gray-600 text-xs"></i>
                                @endfor
                            </div>
                            <span class="text-gray-400 text-xs">
                                {{ $related->view_count > 999 ? number_format($related->view_count / 1000, 1) . 'K' : $related->view_count }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Review Modal -->
@auth('siswa')
<div id="reviewModal" class="fixed inset-0 flex items-center justify-center bg-black/60 z-50 hidden">
    <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-lg p-6 sm:p-8 relative animate-fadeIn mx-4">
        <button data-close-modal
                class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-2xl">
            &times;
        </button>
        
        <h2 class="text-xl sm:text-2xl font-bold text-white mb-6">Tulis Review</h2>
        
        <form action="{{ route('film.review', $konten->konten_id) }}" method="POST">
            @csrf
            
            <!-- Rating Stars -->
            <div class="mb-6">
                <label class="block text-white font-semibold mb-3">Rating Film</label>
                <div class="flex gap-2">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" 
                                class="rating-star text-3xl transition"
                                data-rating="{{ $i }}">
                            <i class="{{ $i <= 5 ? 'fas fa-star text-yellow-400' : 'far fa-star text-gray-600' }}"></i>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="5">
            </div>
            
            <!-- Comment -->
            <div class="mb-6">
                <label class="block text-white font-semibold mb-3">Review Anda</label>
                <textarea name="comment" 
                          rows="5" 
                          required
                          class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-purple-500 resize-none"
                          placeholder="Bagikan pendapat Anda tentang film ini..."></textarea>
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded-lg transition">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Review
                </button>
                <button type="button" 
                        data-close-modal
                        class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 rounded-lg transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endauth

<!-- Success/Error Messages -->
@if(session('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 4000)"
         x-transition
         class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 4000)"
         x-transition
         class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab functionality
        const tabButtons = document.querySelectorAll('[data-tab-button]');
        const tabContents = document.querySelectorAll('[data-tab-content]');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab-button');
                
                tabButtons.forEach(btn => {
                    if (btn.getAttribute('data-tab-button') === targetTab) {
                        btn.classList.remove('border-transparent', 'opacity-60');
                        btn.classList.add('border-purple-500');
                    } else {
                        btn.classList.remove('border-purple-500');
                        btn.classList.add('border-transparent', 'opacity-60');
                    }
                });
                
                tabContents.forEach(content => {
                    if (content.getAttribute('data-tab-content') === targetTab) {
                        content.style.display = 'block';
                        content.classList.add('animate-fadeIn');
                    } else {
                        content.style.display = 'none';
                    }
                });
            });
        });

        // Review modal functionality
        const reviewModal = document.getElementById('reviewModal');
        const openReviewBtn = document.getElementById('openReviewModal');
        const closeReviewBtns = document.querySelectorAll('[data-close-modal]');
        
        if (openReviewBtn && reviewModal) {
            openReviewBtn.addEventListener('click', function(e) {
                e.preventDefault();
                reviewModal.classList.remove('hidden');
            });
        }
        
        if (closeReviewBtns.length > 0) {
            closeReviewBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    reviewModal.classList.add('hidden');
                });
            });
        }
        
        // Close modal when clicking outside
        if (reviewModal) {
            reviewModal.addEventListener('click', function(e) {
                if (e.target === reviewModal) {
                    reviewModal.classList.add('hidden');
                }
            });
        }

        // Star rating functionality
        const ratingStars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('ratingInput');
        let currentRating = 5;
        
        ratingStars.forEach(star => {
            star.addEventListener('click', function() {
                currentRating = parseInt(this.getAttribute('data-rating'));
                ratingInput.value = currentRating;
                updateStars(currentRating);
            });
            
            star.addEventListener('mouseenter', function() {
                const hoverRating = parseInt(this.getAttribute('data-rating'));
                updateStars(hoverRating);
            });
        });
        
        if (ratingStars.length > 0) {
            const starsContainer = ratingStars[0].parentElement;
            starsContainer.addEventListener('mouseleave', function() {
                updateStars(currentRating);
            });
        }
        
        function updateStars(rating) {
            ratingStars.forEach(star => {
                const starValue = parseInt(star.getAttribute('data-rating'));
                const icon = star.querySelector('i');
                
                if (starValue <= rating) {
                    icon.classList.remove('far', 'text-gray-600');
                    icon.classList.add('fas', 'text-yellow-400');
                } else {
                    icon.classList.remove('fas', 'text-yellow-400');
                    icon.classList.add('far', 'text-gray-600');
                }
            });
        }
    });
</script>
@endpush
@endsection