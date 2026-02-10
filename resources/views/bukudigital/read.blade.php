@extends('layouts.app')

@section('title', 'Baca: ' . $konten->judul . ' - SMPN 147 Jakarta')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    
    <!-- Back Button -->
    <a href="{{ route('bukudigital.show', $konten->konten_id) }}" 
       class="inline-flex items-center text-blue-400 hover:text-blue-300 mb-6 transition">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Detail Buku
    </a>

    <!-- Reader Container -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- Reader Area -->
        <div class="lg:col-span-3">
            <!-- Reader Header -->
            <div class="bg-gray-800 rounded-t-xl p-4 flex items-center justify-between shadow-lg">
                <div>
                    <h1 class="text-xl font-bold text-white">{{ $konten->judul }}</h1>
                    <p class="text-gray-400 text-sm">oleh {{ $konten->uploader_name }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span id="pageInfo" class="text-sm text-gray-400">Halaman 1</span>
                </div>
            </div>

            <!-- PDF Viewer -->
            <div class="bg-white rounded-b-xl shadow-2xl">
                <!-- Reading Settings Bar -->
                <div class="bg-gray-100 px-6 py-3 flex items-center justify-between border-b border-gray-300">
                    <div class="flex items-center space-x-4">
                        <button id="zoomOut" class="hover:text-blue-600 transition" title="Zoom Out">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <span id="zoomLevel" class="text-sm font-semibold text-gray-800">100%</span>
                        <button id="zoomIn" class="hover:text-blue-600 transition" title="Zoom In">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button id="fullscreenBtn" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">
                            <i class="fas fa-expand mr-1"></i> Fullscreen
                        </button>
                    </div>
                </div>

                <!-- PDF Canvas Container -->
                <div id="pdfContainer" class="overflow-auto bg-gray-200 relative" style="max-height: 800px;">
                    <div id="loadingIndicator" class="flex items-center justify-center min-h-[600px]">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-4"></i>
                            <p class="text-gray-600">Memuat dokumen...</p>
                        </div>
                    </div>
                    
                    <!-- Fullscreen Navigation Controls -->
                    <div id="fullscreenControls" class="hidden transition-opacity duration-300">
                        <!-- Top Controls Bar -->
                        <div class="fixed top-0 left-0 right-0 bg-gradient-to-b from-black/80 to-transparent p-3 sm:p-4 z-[9999]">
                            <div class="max-w-4xl mx-auto flex items-center justify-between text-white">
                                <span id="pageInfoFullscreen" class="text-xs sm:text-sm font-medium">Halaman 1</span>
                                
                                <button id="exitFullscreenBtn" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-red-600 hover:bg-red-500 rounded-lg transition text-xs sm:text-sm font-medium">
                                    <i class="fas fa-times mr-1"></i> Keluar
                                </button>
                            </div>
                        </div>
                        
                        <!-- Bottom Controls Bar -->
                        <div class="fixed bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 to-transparent p-3 sm:p-6 z-[9999]">
                            <div class="max-w-4xl mx-auto">
                                <!-- Zoom Controls - Large buttons for mobile -->
                                <div class="flex items-center justify-center space-x-4 sm:space-x-6 mb-3">
                                    <button id="zoomOutFullscreen" class="bg-gray-700/80 hover:bg-gray-600 text-white w-12 h-12 sm:w-14 sm:h-14 rounded-full transition flex items-center justify-center backdrop-blur-sm" title="Zoom Out">
                                        <i class="fas fa-search-minus text-lg sm:text-xl"></i>
                                    </button>
                                    <span id="zoomLevelFullscreen" class="text-white text-base sm:text-lg font-bold min-w-[60px] text-center bg-gray-700/80 px-3 py-2 rounded-lg backdrop-blur-sm">100%</span>
                                    <button id="zoomInFullscreen" class="bg-gray-700/80 hover:bg-gray-600 text-white w-12 h-12 sm:w-14 sm:h-14 rounded-full transition flex items-center justify-center backdrop-blur-sm" title="Zoom In">
                                        <i class="fas fa-search-plus text-lg sm:text-xl"></i>
                                    </button>
                                </div>
                                
                                <!-- Page Navigation Buttons -->
                                <div class="flex items-center justify-center space-x-3">
                                    <button id="prevPageFullscreenBottom" 
                                            class="bg-blue-600/90 hover:bg-blue-500 text-white px-4 py-2.5 sm:px-6 sm:py-3 rounded-lg transition disabled:opacity-30 disabled:cursor-not-allowed flex items-center space-x-2 backdrop-blur-sm">
                                        <i class="fas fa-chevron-left"></i>
                                        <span class="text-xs sm:text-sm font-medium">Sebelumnya</span>
                                    </button>
                                    
                                    <button id="nextPageFullscreenBottom" 
                                            class="bg-blue-600/90 hover:bg-blue-500 text-white px-4 py-2.5 sm:px-6 sm:py-3 rounded-lg transition disabled:opacity-30 disabled:cursor-not-allowed flex items-center space-x-2 backdrop-blur-sm">
                                        <span class="text-xs sm:text-sm font-medium">Selanjutnya</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tap indicator (hidden by default, shown briefly on tap) -->
                        <div id="tapIndicator" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white/20 text-white px-4 py-2 rounded-lg backdrop-blur-sm opacity-0 transition-opacity duration-200 pointer-events-none z-[9998]">
                            <i class="fas fa-hand-pointer mr-2"></i>Tap untuk kontrol
                        </div>
                    </div>
                </div>

                <!-- Navigation Controls -->
                <div class="bg-gray-100 px-6 py-4 flex items-center justify-between border-t border-gray-300 rounded-b-xl">
                    <button id="prevPage" 
                            class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </button>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600 text-sm">Halaman</span>
                        <input id="pageNum" type="number" value="1" min="1" 
                               class="w-16 px-3 py-2 border border-gray-300 rounded text-center focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
                        <span class="text-gray-600 text-sm">/ <span id="totalPages">-</span></span>
                    </div>

                    <button id="nextPage" 
                            class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="hidden sm:inline">Selanjutnya</span>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Book Info -->
            <div class="bg-gray-800 rounded-xl p-6 shadow-lg">
                <img src="{{ $konten->thumbnail_url }}" 
                     alt="{{ $konten->judul }}" 
                     class="w-full rounded-lg mb-4 shadow-lg">
                <h3 class="text-xl font-bold mb-2 text-white">{{ $konten->judul }}</h3>
                <p class="text-gray-400 text-sm mb-4">{{ $konten->uploader_name }}</p>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-300">
                        <span class="text-gray-400">Progress:</span>
                        <span class="font-semibold" id="progressPercent">0%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all" style="width: 0%"></div>
                    </div>
                    <div class="flex justify-between text-gray-400 text-xs mt-2">
                        <span><i class="fas fa-eye mr-1"></i>{{ number_format($konten->view_count) }} views</span>
                        <span><i class="fas fa-star mr-1"></i>{{ number_format($konten->average_rating, 1) }}/5</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gray-800 rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-bold mb-4 text-white">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('bukudigital.show', $konten->konten_id) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-500 py-3 rounded-lg transition flex items-center justify-center text-white">
                        <i class="fas fa-info-circle mr-2"></i>Info Buku
                    </a>
                    <button onclick="shareBook()" 
                            class="w-full bg-purple-600 hover:bg-purple-500 py-3 rounded-lg transition flex items-center justify-center text-white">
                        <i class="fas fa-share-alt mr-2"></i>Bagikan
                    </button>
                </div>
            </div>

            <!-- Reading Tips -->
            <div class="bg-gray-800 rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-bold mb-4 text-white flex items-center">
                    <i class="fas fa-lightbulb mr-2 text-yellow-400"></i>Tips Membaca
                </h3>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li><i class="fas fa-check text-green-400 mr-2"></i>Gunakan fullscreen untuk fokus lebih baik</li>
                    <li><i class="fas fa-check text-green-400 mr-2"></i>Atur zoom sesuai kenyamanan mata</li>
                    <li><i class="fas fa-check text-green-400 mr-2"></i>Jangan lupa istirahat setiap 30 menit</li>
                    <li><i class="fas fa-check text-green-400 mr-2"></i>Gunakan tombol panah untuk navigasi cepat</li>
                    <li><i class="fas fa-check text-green-400 mr-2"></i>Swipe kiri/kanan di mobile untuk ganti halaman</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- PDF.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
    // PDF.js setup
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const url = '{{ asset("storage/" . $konten->file_path) }}';
    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;
    let scale = 1.5; // Normal scale
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    const container = document.getElementById('pdfContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    
    // Get fullscreen controls
    const fullscreenControls = document.getElementById('fullscreenControls');
    const prevPageFullscreenBottom = document.getElementById('prevPageFullscreenBottom');
    const nextPageFullscreenBottom = document.getElementById('nextPageFullscreenBottom');
    const exitFullscreenBtn = document.getElementById('exitFullscreenBtn');
    const zoomInFullscreen = document.getElementById('zoomInFullscreen');
    const zoomOutFullscreen = document.getElementById('zoomOutFullscreen');

    // Auto-hide controls variables
    let hideControlsTimeout;
    let isControlsVisible = true;
    const tapIndicator = document.getElementById('tapIndicator');

    /**
     * Show fullscreen controls
     */
    function showFullscreenControls() {
        if (!document.fullscreenElement) return;
        
        fullscreenControls.style.opacity = '1';
        fullscreenControls.style.pointerEvents = 'auto';
        isControlsVisible = true;
        
        // Clear existing timeout
        if (hideControlsTimeout) {
            clearTimeout(hideControlsTimeout);
        }
        
        // Hide after 3 seconds of inactivity
        hideControlsTimeout = setTimeout(hideFullscreenControls, 3000);
    }

    /**
     * Hide fullscreen controls
     */
    function hideFullscreenControls() {
        if (!document.fullscreenElement) return;
        
        fullscreenControls.style.opacity = '0';
        fullscreenControls.style.pointerEvents = 'none';
        isControlsVisible = false;
    }

    /**
     * Toggle fullscreen controls
     */
    function toggleFullscreenControls() {
        if (isControlsVisible) {
            hideFullscreenControls();
        } else {
            showFullscreenControls();
        }
    }

    /**
     * Show tap indicator briefly
     */
    function showTapIndicator() {
        if (!isControlsVisible && document.fullscreenElement) {
            tapIndicator.style.opacity = '1';
            setTimeout(() => {
                tapIndicator.style.opacity = '0';
            }, 1500);
        }
    }

    /**
     * Render the page with high quality (sharp but normal size)
     */
    function renderPage(num) {
        pageRendering = true;
        
        pdfDoc.getPage(num).then(function(page) {
            // Use device pixel ratio for sharper rendering without changing display size
            const devicePixelRatio = window.devicePixelRatio || 1;
            const viewport = page.getViewport({scale: scale});
            
            // Set actual canvas resolution higher for quality
            canvas.height = viewport.height * devicePixelRatio;
            canvas.width = viewport.width * devicePixelRatio;
            
            // But keep display size normal
            canvas.style.width = viewport.width + 'px';
            canvas.style.height = viewport.height + 'px';
            
            // Save context state
            ctx.save();
            
            // Scale the drawing context
            ctx.scale(devicePixelRatio, devicePixelRatio);

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            
            const renderTask = page.render(renderContext);

            renderTask.promise.then(function() {
                // Restore context state
                ctx.restore();
                
                pageRendering = false;
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
                
                // Update progress
                updateProgress();
            });
        });

        // Update page info (both normal and fullscreen)
        document.getElementById('pageNum').value = num;
        document.getElementById('pageInfo').textContent = `Halaman ${num} dari ${pdfDoc.numPages}`;
        document.getElementById('pageInfoFullscreen').textContent = `Halaman ${num} dari ${pdfDoc.numPages}`;
        
        // Update buttons (both normal and fullscreen)
        const isFirstPage = num <= 1;
        const isLastPage = num >= pdfDoc.numPages;
        
        document.getElementById('prevPage').disabled = isFirstPage;
        document.getElementById('nextPage').disabled = isLastPage;
        prevPageFullscreenBottom.disabled = isFirstPage;
        nextPageFullscreenBottom.disabled = isLastPage;
    }

    /**
     * Queue page rendering
     */
    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    /**
     * Update reading progress
     */
    function updateProgress() {
        const progress = (pageNum / pdfDoc.numPages) * 100;
        document.getElementById('progressBar').style.width = progress + '%';
        document.getElementById('progressPercent').textContent = Math.round(progress) + '%';
    }

    /**
     * Go to previous page
     */
    function goToPrevPage() {
        if (pageNum <= 1) return;
        pageNum--;
        queueRenderPage(pageNum);
    }

    /**
     * Go to next page
     */
    function goToNextPage() {
        if (pageNum >= pdfDoc.numPages) return;
        pageNum++;
        queueRenderPage(pageNum);
    }

    /**
     * Zoom in
     */
    function zoomIn() {
        scale += 0.25;
        if (scale > 3) scale = 3;
        const zoomText = Math.round(scale * 100 / 1.5) + '%';
        document.getElementById('zoomLevel').textContent = zoomText;
        document.getElementById('zoomLevelFullscreen').textContent = zoomText;
        queueRenderPage(pageNum);
    }

    /**
     * Zoom out
     */
    function zoomOut() {
        scale -= 0.25;
        if (scale < 0.5) scale = 0.5;
        const zoomText = Math.round(scale * 100 / 1.5) + '%';
        document.getElementById('zoomLevel').textContent = zoomText;
        document.getElementById('zoomLevelFullscreen').textContent = zoomText;
        queueRenderPage(pageNum);
    }

    /**
     * Previous page - Normal mode
     */
    document.getElementById('prevPage').addEventListener('click', goToPrevPage);

    /**
     * Next page - Normal mode
     */
    document.getElementById('nextPage').addEventListener('click', goToNextPage);

    /**
     * Previous page - Fullscreen mode
     */
    prevPageFullscreenBottom.addEventListener('click', goToPrevPage);

    /**
     * Next page - Fullscreen mode
     */
    nextPageFullscreenBottom.addEventListener('click', goToNextPage);

    /**
     * Page number input
     */
    document.getElementById('pageNum').addEventListener('change', function() {
        let num = parseInt(this.value);
        if (num < 1) num = 1;
        if (num > pdfDoc.numPages) num = pdfDoc.numPages;
        pageNum = num;
        queueRenderPage(pageNum);
    });

    /**
     * Zoom controls - Normal mode
     */
    document.getElementById('zoomIn').addEventListener('click', zoomIn);
    document.getElementById('zoomOut').addEventListener('click', zoomOut);

    /**
     * Zoom controls - Fullscreen mode
     */
    zoomInFullscreen.addEventListener('click', zoomIn);
    zoomOutFullscreen.addEventListener('click', zoomOut);

    /**
     * Fullscreen toggle
     */
    document.getElementById('fullscreenBtn').addEventListener('click', function() {
        if (!document.fullscreenElement) {
            container.requestFullscreen().catch(err => {
                console.error('Fullscreen error:', err);
            });
        }
    });

    /**
     * Exit fullscreen
     */
    exitFullscreenBtn.addEventListener('click', function() {
        if (document.fullscreenElement) {
            document.exitFullscreen();
        }
    });

    /**
     * Listen for fullscreen changes
     */
    document.addEventListener('fullscreenchange', function() {
        const btn = document.getElementById('fullscreenBtn');
        if (document.fullscreenElement) {
            // Entering fullscreen
            fullscreenControls.classList.remove('hidden');
            fullscreenControls.style.opacity = '1';
            fullscreenControls.style.pointerEvents = 'auto';
            btn.innerHTML = '<i class="fas fa-compress mr-1"></i> Exit Fullscreen';
            console.log('Fullscreen entered - controls should be visible');
            
            // Start auto-hide timer
            isControlsVisible = true;
            hideControlsTimeout = setTimeout(hideFullscreenControls, 3000);
            
            // Show tap indicator once at the start
            setTimeout(showTapIndicator, 3500);
        } else {
            // Exiting fullscreen
            fullscreenControls.classList.add('hidden');
            fullscreenControls.style.opacity = '1';
            fullscreenControls.style.pointerEvents = 'auto';
            btn.innerHTML = '<i class="fas fa-expand mr-1"></i> Fullscreen';
            console.log('Fullscreen exited - controls hidden');
            
            // Clear timeout
            if (hideControlsTimeout) {
                clearTimeout(hideControlsTimeout);
            }
            isControlsVisible = true;
        }
    });

    /**
     * Detect user interaction in fullscreen to show controls
     */
    document.addEventListener('mousemove', function(e) {
        if (document.fullscreenElement) {
            showFullscreenControls();
        }
    });

    document.addEventListener('touchstart', function(e) {
        if (document.fullscreenElement) {
            // If tapping on canvas (not on buttons), toggle controls
            if (e.target === canvas) {
                toggleFullscreenControls();
            } else {
                showFullscreenControls();
            }
        }
    });

    container.addEventListener('click', function(e) {
        if (document.fullscreenElement && e.target === canvas) {
            toggleFullscreenControls();
        }
    });

    // Keep controls visible when hovering over them
    fullscreenControls.addEventListener('mouseenter', function() {
        if (hideControlsTimeout) {
            clearTimeout(hideControlsTimeout);
        }
    });

    fullscreenControls.addEventListener('mouseleave', function() {
        if (document.fullscreenElement) {
            hideControlsTimeout = setTimeout(hideFullscreenControls, 3000);
        }
    });

    /**
     * Keyboard navigation
     */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            goToPrevPage();
        } else if (e.key === 'ArrowRight') {
            goToNextPage();
        } else if (e.key === 'Escape' && document.fullscreenElement) {
            document.exitFullscreen();
        }
    });

    /**
     * Touch/Swipe navigation for mobile
     */
    let touchStartX = 0;
    let touchEndX = 0;
    let touchStartY = 0;
    let touchEndY = 0;
    let isDragging = false;
    const minSwipeDistance = 80; // Increased minimum distance for swipe to avoid conflict with scrolling

    container.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
        touchStartY = e.changedTouches[0].screenY;
        isDragging = false;
    }, { passive: true });

    container.addEventListener('touchmove', function(e) {
        // Mark as dragging if user is moving their finger
        isDragging = true;
    }, { passive: true });

    container.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        touchEndY = e.changedTouches[0].screenY;
        
        // Only trigger swipe if it was a quick swipe (not a slow drag for scrolling)
        if (isDragging) {
            handleSwipe();
        }
    }, { passive: true });

    function handleSwipe() {
        const swipeDistanceX = touchEndX - touchStartX;
        const swipeDistanceY = Math.abs(touchEndY - touchStartY);
        
        // Only trigger page change if:
        // 1. Horizontal swipe is more than minimum distance
        // 2. Horizontal movement is significantly more than vertical (not scrolling)
        // 3. PDF is not zoomed in (scale is at default)
        const isHorizontalSwipe = Math.abs(swipeDistanceX) > minSwipeDistance;
        const isMoreHorizontalThanVertical = Math.abs(swipeDistanceX) > swipeDistanceY * 2;
        const isNotZoomedIn = scale <= 1.5; // Only allow swipe at default zoom
        
        if (isHorizontalSwipe && isMoreHorizontalThanVertical && isNotZoomedIn) {
            if (swipeDistanceX > 0) {
                // Swiped RIGHT (finger moved left to right) - PREVIOUS page
                console.log('Swipe RIGHT - Previous Page');
                goToPrevPage();
            } else {
                // Swiped LEFT (finger moved right to left) - NEXT page
                console.log('Swipe LEFT - Next Page');
                goToNextPage();
            }
        }
    }

    /**
     * Tap navigation for mobile (tap on sides) - DISABLED when used for showing controls
     */
    canvas.addEventListener('click', function(e) {
        if (!document.fullscreenElement) return;
        
        // First tap shows controls, don't navigate yet
        if (!isControlsVisible) {
            return; // toggleFullscreenControls already called above
        }
        
        const rect = canvas.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        const clickWidth = rect.width;
        
        // Divide canvas into 3 zones: left (30%), center (40%), right (30%)
        if (clickX < clickWidth * 0.3) {
            // Tap on left side - previous page
            goToPrevPage();
            showFullscreenControls(); // Reset hide timer
        } else if (clickX > clickWidth * 0.7) {
            // Tap on right side - next page
            goToNextPage();
            showFullscreenControls(); // Reset hide timer
        }
        // Center area toggles controls
    });

    /**
     * Share function
     */
    function shareBook() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $konten->judul }}',
                text: 'Baca buku "{{ $konten->judul }}" di SMPN 147 Jakarta',
                url: '{{ route("bukudigital.show", $konten->konten_id) }}'
            });
        } else {
            // Fallback - copy link
            const url = '{{ route("bukudigital.show", $konten->konten_id) }}';
            navigator.clipboard.writeText(url).then(() => {
                alert('Link berhasil disalin!');
            });
        }
    }

    /**
     * Load PDF document
     */
    pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
        pdfDoc = pdfDoc_;
        document.getElementById('totalPages').textContent = pdfDoc.numPages;
        
        // Remove loading indicator and add canvas
        loadingIndicator.remove();
        container.appendChild(canvas);
        canvas.className = 'mx-auto shadow-2xl';
        
        // Initial page render
        renderPage(pageNum);
    }).catch(function(error) {
        console.error('Error loading PDF:', error);
        container.innerHTML = `
            <div class="flex items-center justify-center min-h-[600px]">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600 mb-4"></i>
                    <p class="text-gray-600">Gagal memuat dokumen</p>
                    <p class="text-gray-500 text-sm mt-2">Silakan refresh halaman atau hubungi admin</p>
                </div>
            </div>
        `;
    });
</script>
@endpush
@endsection