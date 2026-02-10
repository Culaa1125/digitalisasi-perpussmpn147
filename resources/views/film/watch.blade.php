@extends('layouts.app')

@section('title', 'Tonton - ' . $konten->judul . ' | SMPN 147 Jakarta')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8 relative z-10">
    
    <!-- Back Button -->
    <a href="{{ route('film.show', $konten->konten_id) }}" 
       class="inline-flex items-center text-blue-400 hover:text-blue-300 mb-6 transition">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Detail Film
    </a>

    <!-- Video Player Section -->
    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-2xl mb-8">
        <div class="relative group" id="videoContainer">
            <!-- Video Element -->
            <video id="videoPlayer" 
                   class="w-full aspect-video bg-black" 
                   poster="{{ $konten->thumbnail_url }}"
                   controlsList="nodownload"
                   oncontextmenu="return false;">
                <source src="{{ asset('storage/' . $konten->file_path) }}" type="video/mp4">
                Browser Anda tidak mendukung video player.
            </video>

            <!-- Custom Video Controls -->
            <div id="videoControls" 
                 class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 to-transparent p-2 sm:p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                
                <!-- Progress Bar -->
                <div class="mb-2 sm:mb-4">
                    <div class="relative w-full h-1 sm:h-1.5 bg-gray-600 rounded-full cursor-pointer touch-none" id="progressContainer">
                        <div id="progressBar" class="absolute h-full bg-blue-500 rounded-full pointer-events-none" style="width: 0%"></div>
                        <div id="progressHandle" class="absolute w-3 h-3 sm:w-4 sm:h-4 bg-blue-500 rounded-full -top-1 sm:-top-1.5 -ml-1.5 sm:-ml-2 pointer-events-none" style="left: 0%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] sm:text-xs text-gray-300 mt-1">
                        <span id="currentTime">00:00</span>
                        <span id="duration">00:00</span>
                    </div>
                </div>

                <!-- Control Buttons -->
                <div class="flex items-center justify-between gap-2 sm:gap-0">
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <!-- Play/Pause Button -->
                        <button id="playPauseBtn" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center text-white justify-center bg-blue-600 hover:bg-blue-500 rounded-full transition touch-manipulation">
                            <i class="fas fa-play text-sm sm:text-base" id="playPauseIcon"></i>
                        </button>

                        <!-- Rewind Button -->
                        <button id="rewindBtn" class="hover:text-blue-400 text-blue-500 transition touch-manipulation p-1 sm:p-0">
                            <i class="fas fa-backward text-sm sm:text-base"></i>
                        </button>

                        <!-- Forward Button -->
                        <button id="forwardBtn" class="hover:text-blue-400 text-blue-500 transition touch-manipulation p-1 sm:p-0">
                            <i class="fas fa-forward text-sm sm:text-base"></i>
                        </button>

                        <!-- Volume Control - Hidden on Mobile -->
                        <div class="hidden sm:flex items-center space-x-2">
                            <button id="muteBtn" class="hover:text-blue-400 text-blue-500 transition">
                                <i class="fas fa-volume-up" id="volumeIcon"></i>
                            </button>
                            <div class="relative w-16 lg:w-20 h-1 bg-gray-600 rounded-full cursor-pointer touch-none" id="volumeContainer">
                                <div id="volumeBar" class="absolute h-full bg-blue-500 rounded-full pointer-events-none" style="width: 100%"></div>
                                <div id="volumeHandle" class="absolute w-3 h-3 bg-blue-500 rounded-full -top-1 -ml-1.5 pointer-events-none" style="left: 100%"></div>
                            </div>
                        </div>

                        <!-- Mobile Volume Button Only -->
                        <button id="muteBtnMobile" class="sm:hidden hover:text-blue-400 text-blue-500 transition touch-manipulation p-1">
                            <i class="fas fa-volume-up text-sm" id="volumeIconMobile"></i>
                        </button>
                    </div>

                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <!-- Speed Control - Smaller on Mobile -->
                        <select id="speedControl" class="bg-gray-700 text-white px-2 sm:px-3 py-1 rounded hover:bg-gray-600 transition text-[10px] sm:text-sm touch-manipulation">
                            <option value="0.5">0.5x</option>
                            <option value="0.75">0.75x</option>
                            <option value="1" selected>1x</option>
                            <option value="1.25">1.25x</option>
                            <option value="1.5">1.5x</option>
                            <option value="2">2x</option>
                        </select>

                        <!-- Fullscreen Button -->
                        <button id="fullscreenBtn" class="hover:text-blue-400 transition text-blue-500 touch-manipulation p-1 sm:p-0">
                            <i class="fas fa-expand text-sm sm:text-base" id="fullscreenIcon"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="absolute inset-0 flex items-center justify-center bg-black/50 hidden">
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500"></div>
            </div>

            <!-- Play Button Overlay -->
            <div id="playOverlay" class="absolute inset-0 flex items-center justify-center bg-black/30 cursor-pointer touch-manipulation">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-blue-600/80 hover:bg-blue-600 rounded-full flex items-center justify-center transition transform hover:scale-110 active:scale-95">
                    <i class="fas fa-play text-2xl sm:text-3xl ml-1 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Film Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800 rounded-xl p-6 shadow-lg backdrop-blur-sm bg-opacity-90">
                <h1 class="text-3xl font-bold mb-2 text-white">{{ $konten->judul }}</h1>
                
                <div class="flex items-center space-x-4 mb-6">
                    <div class="flex items-center text-yellow-400">
                        @for($i = 0; $i < $konten->stars['full']; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                        @if($konten->stars['half'])
                            <i class="fas fa-star-half-alt"></i>
                        @endif
                        @for($i = 0; $i < $konten->stars['empty']; $i++)
                            <i class="fas fa-star text-gray-600"></i>
                        @endfor
                        <span class="ml-2 text-white">{{ number_format($konten->average_rating, 1) }}/5</span>
                    </div>
                    <span class="text-gray-400">({{ number_format($konten->total_reviews) }} Reviews)</span>
                </div>

                <h3 class="text-xl font-semibold mb-3 text-white">Deskripsi</h3>
                <p class="text-gray-300 leading-relaxed mb-6 whitespace-pre-line">
                    {{ $konten->deskripsi ?? 'Tidak ada deskripsi untuk film ini.' }}
                </p>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-400 text-sm">Uploader</p>
                        <p class="font-semibold text-white">{{ $konten->uploader_name }}</p>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-400 text-sm">Tanggal Upload</p>
                        <p class="font-semibold text-white">{{ $konten->tanggal_approve->format('d M Y') }}</p>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-400 text-sm">Views</p>
                        <p class="font-semibold text-white">
                            <i class="fas fa-eye mr-1"></i>{{ number_format($konten->view_count) }}
                        </p>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-400 text-sm">Tipe</p>
                        <p class="font-semibold text-white">Video</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Share Options -->
            <div class="bg-gray-800 rounded-xl p-6 shadow-lg backdrop-blur-sm bg-opacity-90">
                <h3 class="text-xl font-semibold mb-4 text-white">Bagikan Film</h3>
                <div class="flex space-x-3">
                    <button onclick="shareToFacebook()" class="flex-1 bg-blue-500 hover:bg-blue-600 py-2 rounded-lg transition">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button onclick="shareToTwitter()" class="flex-1 bg-sky-500 hover:bg-sky-600 py-2 rounded-lg transition">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button onclick="shareToWhatsapp()" class="flex-1 bg-green-500 hover:bg-green-600 py-2 rounded-lg transition">
                        <i class="fab fa-whatsapp"></i>
                    </button>
                    <button onclick="copyLink()" class="flex-1 bg-gray-600 hover:bg-gray-700 py-2 rounded-lg transition">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>

            <!-- Info Notice -->
            <div class="bg-yellow-900/30 border border-yellow-600 rounded-xl p-6 backdrop-blur-sm">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-400 text-xl mr-3 mt-1"></i>
                    <div>
                        <h4 class="text-yellow-400 font-semibold mb-2">Pemberitahuan</h4>
                        <p class="text-yellow-200 text-sm">
                            Film ini hanya dapat ditonton secara streaming. Download tidak tersedia untuk melindungi hak cipta konten.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const video = document.getElementById('videoPlayer');
const playPauseBtn = document.getElementById('playPauseBtn');
const playPauseIcon = document.getElementById('playPauseIcon');
const playOverlay = document.getElementById('playOverlay');
const progressBar = document.getElementById('progressBar');
const progressHandle = document.getElementById('progressHandle');
const progressContainer = document.getElementById('progressContainer');
const currentTimeEl = document.getElementById('currentTime');
const durationEl = document.getElementById('duration');
const volumeBar = document.getElementById('volumeBar');
const volumeHandle = document.getElementById('volumeHandle');
const volumeContainer = document.getElementById('volumeContainer');
const muteBtn = document.getElementById('muteBtn');
const muteBtnMobile = document.getElementById('muteBtnMobile');
const volumeIcon = document.getElementById('volumeIcon');
const volumeIconMobile = document.getElementById('volumeIconMobile');
const speedControl = document.getElementById('speedControl');
const fullscreenBtn = document.getElementById('fullscreenBtn');
const fullscreenIcon = document.getElementById('fullscreenIcon');
const rewindBtn = document.getElementById('rewindBtn');
const forwardBtn = document.getElementById('forwardBtn');
const loadingSpinner = document.getElementById('loadingSpinner');
const videoContainer = document.getElementById('videoContainer');
const videoControls = document.getElementById('videoControls');

// Detect touch device
const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

// Show controls on touch devices or on hover
if (isTouchDevice) {
    let controlsTimeout;
    
    function showControls() {
        videoControls.style.opacity = '1';
        clearTimeout(controlsTimeout);
        controlsTimeout = setTimeout(() => {
            if (!video.paused) {
                videoControls.style.opacity = '0';
            }
        }, 3000);
    }
    
    video.addEventListener('touchstart', showControls);
    videoContainer.addEventListener('touchstart', showControls);
    video.addEventListener('play', showControls);
    video.addEventListener('pause', () => {
        videoControls.style.opacity = '1';
    });
}

// Play/Pause
function togglePlay() {
    if (video.paused) {
        video.play();
        playPauseIcon.classList.remove('fa-play');
        playPauseIcon.classList.add('fa-pause');
        playOverlay.style.display = 'none';
    } else {
        video.pause();
        playPauseIcon.classList.remove('fa-pause');
        playPauseIcon.classList.add('fa-play');
    }
}

playPauseBtn.addEventListener('click', togglePlay);
video.addEventListener('click', togglePlay);
playOverlay.addEventListener('click', togglePlay);

// Update progress bar
video.addEventListener('timeupdate', () => {
    if (!progressDragging) {
        const progress = (video.currentTime / video.duration) * 100;
        progressBar.style.width = progress + '%';
        progressHandle.style.left = progress + '%';
    }
    currentTimeEl.textContent = formatTime(video.currentTime);
});

// Load duration
video.addEventListener('loadedmetadata', () => {
    durationEl.textContent = formatTime(video.duration);
});

// ✅ Progress Seek - Mouse & Touch Support
let progressDragging = false;

function getPositionX(e) {
    return e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
}

progressContainer.addEventListener('mousedown', startProgressDrag);
progressContainer.addEventListener('touchstart', startProgressDrag, { passive: false });

function startProgressDrag(e) {
    e.preventDefault();
    progressDragging = true;
    seekToPosition(e);
}

document.addEventListener('mousemove', (e) => {
    if (progressDragging) seekToPosition(e);
});

document.addEventListener('touchmove', (e) => {
    if (progressDragging) {
        e.preventDefault();
        seekToPosition(e);
    }
}, { passive: false });

document.addEventListener('mouseup', stopProgressDrag);
document.addEventListener('touchend', stopProgressDrag);

function stopProgressDrag() {
    progressDragging = false;
}

function seekToPosition(e) {
    const rect = progressContainer.getBoundingClientRect();
    const clientX = getPositionX(e);
    let pos = (clientX - rect.left) / rect.width;
    pos = Math.max(0, Math.min(1, pos));
    video.currentTime = pos * video.duration;
    
    const progress = pos * 100;
    progressBar.style.width = progress + '%';
    progressHandle.style.left = progress + '%';
}

// ✅ Volume - Mouse & Touch Support (Desktop only)
let volumeDragging = false;

if (volumeContainer) {
    volumeContainer.addEventListener('mousedown', startVolumeDrag);
    volumeContainer.addEventListener('touchstart', startVolumeDrag, { passive: false });
}

function startVolumeDrag(e) {
    e.preventDefault();
    volumeDragging = true;
    setVolumeFromPosition(e);
}

document.addEventListener('mousemove', (e) => {
    if (volumeDragging) setVolumeFromPosition(e);
});

document.addEventListener('touchmove', (e) => {
    if (volumeDragging) {
        e.preventDefault();
        setVolumeFromPosition(e);
    }
}, { passive: false });

document.addEventListener('mouseup', stopVolumeDrag);
document.addEventListener('touchend', stopVolumeDrag);

function stopVolumeDrag() {
    volumeDragging = false;
}

function setVolumeFromPosition(e) {
    const rect = volumeContainer.getBoundingClientRect();
    const clientX = getPositionX(e);
    let pos = (clientX - rect.left) / rect.width;
    pos = Math.max(0, Math.min(1, pos));
    
    video.volume = pos;
    video.muted = false;
    updateVolumeUI(pos * 100);
    updateVolumeIcon();
}

function updateVolumeUI(percentage) {
    if (volumeBar && volumeHandle) {
        volumeBar.style.width = percentage + '%';
        volumeHandle.style.left = percentage + '%';
    }
}

// Mute buttons (desktop and mobile)
muteBtn.addEventListener('click', toggleMute);
muteBtnMobile.addEventListener('click', toggleMute);

function toggleMute() {
    video.muted = !video.muted;
    if (video.muted) {
        updateVolumeUI(0);
    } else {
        updateVolumeUI(video.volume * 100);
    }
    updateVolumeIcon();
}

function updateVolumeIcon() {
    const icons = [volumeIcon, volumeIconMobile];
    icons.forEach(icon => {
        if (!icon) return;
        if (video.muted || video.volume === 0) {
            icon.classList.remove('fa-volume-up', 'fa-volume-down');
            icon.classList.add('fa-volume-mute');
        } else if (video.volume < 0.5) {
            icon.classList.remove('fa-volume-up', 'fa-volume-mute');
            icon.classList.add('fa-volume-down');
        } else {
            icon.classList.remove('fa-volume-down', 'fa-volume-mute');
            icon.classList.add('fa-volume-up');
        }
    });
}

// Speed
speedControl.addEventListener('change', (e) => {
    video.playbackRate = parseFloat(e.target.value);
});

// Fullscreen
fullscreenBtn.addEventListener('click', () => {
    if (!document.fullscreenElement) {
        videoContainer.requestFullscreen();
        fullscreenIcon.classList.remove('fa-expand');
        fullscreenIcon.classList.add('fa-compress');
    } else {
        document.exitFullscreen();
        fullscreenIcon.classList.remove('fa-compress');
        fullscreenIcon.classList.add('fa-expand');
    }
});

document.addEventListener('fullscreenchange', () => {
    if (!document.fullscreenElement) {
        fullscreenIcon.classList.remove('fa-compress');
        fullscreenIcon.classList.add('fa-expand');
    }
});

// Rewind/Forward
rewindBtn.addEventListener('click', () => {
    video.currentTime = Math.max(0, video.currentTime - 10);
});

forwardBtn.addEventListener('click', () => {
    video.currentTime = Math.min(video.duration, video.currentTime + 10);
});

// Loading spinner
video.addEventListener('waiting', () => {
    loadingSpinner.classList.remove('hidden');
});

video.addEventListener('canplay', () => {
    loadingSpinner.classList.add('hidden');
});

// Keyboard shortcuts (Desktop only)
if (!isTouchDevice) {
    document.addEventListener('keydown', (e) => {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        
        switch(e.key) {
            case ' ':
                e.preventDefault();
                togglePlay();
                break;
            case 'ArrowLeft':
                video.currentTime -= 5;
                break;
            case 'ArrowRight':
                video.currentTime += 5;
                break;
            case 'ArrowUp':
                e.preventDefault();
                video.volume = Math.min(1, video.volume + 0.1);
                video.muted = false;
                updateVolumeUI(video.volume * 100);
                updateVolumeIcon();
                break;
            case 'ArrowDown':
                e.preventDefault();
                video.volume = Math.max(0, video.volume - 0.1);
                updateVolumeUI(video.volume * 100);
                updateVolumeIcon();
                break;
            case 'f':
                fullscreenBtn.click();
                break;
            case 'm':
                muteBtn.click();
                break;
        }
    });
}

// Format time
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}

// Share functions
function shareToFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareToTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('Tonton {{ $konten->judul }} di SMPN 147 Jakarta');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
}

function shareToWhatsapp() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('Tonton {{ $konten->judul }} di SMPN 147 Jakarta');
    window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link berhasil disalin!');
    });
}

// Disable right-click and prevent download
video.addEventListener('contextmenu', (e) => e.preventDefault());
</script>
@endpush
@endsection