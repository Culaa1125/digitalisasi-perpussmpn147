<!-- Modal Upload Karya -->
<div id="upload-modal-container"
     x-show="{{ $showModal ?? 'showUploadModal' }}" 
     x-cloak
     @click.self="{{ $showModal ?? 'showUploadModal' }} = false"
     @keydown.escape.window="{{ $showModal ?? 'showUploadModal' }} = false"
     x-init="@if($errors->any()) {{ $showModal ?? 'showUploadModal' }} = true @endif"
     class="fixed inset-0 flex items-center justify-center bg-black/60 z-50"
     style="display: none;">
    <div @click.stop 
         class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-5 sm:p-10 relative animate-fadeIn mx-4 max-h-[90vh] overflow-y-auto">
        <button @click="{{ $showModal ?? 'showUploadModal' }} = false" 
                data-close-upload
                class="absolute top-2 right-2 sm:top-4 sm:right-4 text-gray-500 hover:text-red-500 text-xl sm:text-2xl z-10">
            &#10005;
        </button>
        
        <h2 class="text-lg sm:text-2xl font-semibold mb-4 sm:mb-6 text-gray-800">
            <i class="fas fa-cloud-upload-alt mr-2 text-blue-600"></i>Upload Karya
        </h2>
        
        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('siswa.upload.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Drag & Drop Area -->
            <div id="drop-area" 
                 class="border-2 border-dashed border-blue-400 rounded-lg p-6 sm:p-8 mb-4 sm:mb-6 text-center bg-blue-50 hover:bg-blue-100 transition cursor-pointer">
                <input type="file" 
                       id="file-input" 
                       name="file"
                       class="hidden" 
                       accept=".pdf,.doc,.docx,.mp4,.avi,.mov" 
                       required>
                <i class="fas fa-cloud-upload-alt text-blue-500 text-4xl sm:text-5xl mb-2 sm:mb-3"></i>
                <p class="text-gray-700 font-medium mb-1 sm:mb-2 text-sm sm:text-base">Drag & Drop file di sini</p>
                <p class="text-gray-500 text-xs sm:text-sm mb-2 sm:mb-3">atau</p>
                <label for="file-input" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 sm:px-6 rounded cursor-pointer inline-block text-xs sm:text-base">
                    Pilih File
                </label>
                <p class="text-gray-400 text-xs mt-2 sm:mt-3">Format: PDF, DOC, MP4, AVI (Max 50MB)</p>
            </div>
            
            <!-- File List Display -->
            <div id="file-list" class="mb-3 sm:mb-4 hidden">
                <p class="text-gray-700 font-medium mb-2 text-sm sm:text-base">File yang dipilih:</p>
                <div id="file-items" class="space-y-2"></div>
            </div>

            <!-- Thumbnail Upload (Optional) -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                    <i class="fas fa-image mr-2"></i>Thumbnail (Opsional)
                </label>
                <input type="file" 
                       name="thumbnail" 
                       accept=".jpg,.jpeg,.png"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-blue-400 text-sm sm:text-base">
                <p class="text-gray-500 text-xs mt-1">Format: JPG, PNG (Max 5MB)</p>
            </div>

            <!-- Tipe Konten -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                    <i class="fas fa-tag mr-2"></i>Tipe Konten
                </label>
                <select name="tipe" 
                        required
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-blue-400 text-sm sm:text-base">
                    <option value="">Pilih Tipe</option>
                    <option value="buku">Buku</option>
                    <option value="video">Video</option>
                </select>
            </div>

            <!-- Judul -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                    <i class="fas fa-heading mr-2"></i>Judul Karya
                </label>
                <input type="text" 
                       name="judul" 
                       placeholder="Masukkan judul karya Anda" 
                       required
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-blue-400 text-sm sm:text-base">
            </div>

            <!-- Deskripsi -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                    <i class="fas fa-align-left mr-2"></i>Deskripsi
                </label>
                <textarea name="deskripsi" 
                          rows="4"
                          placeholder="Ceritakan tentang karya Anda..."
                          class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-blue-400 resize-none text-sm sm:text-base"></textarea>
            </div>

            <!-- Info Siswa (Auto-filled, hidden) -->
            <input type="hidden" name="nis" value="{{ Auth::guard('siswa')->user()->nis }}">
            
            <!-- Buttons -->
            <div class="flex gap-2 flex-col sm:flex-row">
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded px-3 py-2 sm:px-5 sm:py-2 text-xs sm:text-base transition">
                    <i class="fas fa-upload mr-2"></i>Upload Karya
                </button>
                <button type="button" 
                        @click="{{ $showModal ?? 'showUploadModal' }} = false"
                        data-close-upload
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded px-3 py-2 sm:px-5 sm:py-2 text-xs sm:text-base transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('file-input');
        const fileList = document.getElementById('file-list');
        const fileItems = document.getElementById('file-items');

        // Check if Alpine is not loaded, use vanilla JS fallback
        if (typeof Alpine === 'undefined') {
            console.warn('Alpine.js not loaded, using vanilla JS for modal');
            
            // Add click handlers for all upload buttons
            document.querySelectorAll('[data-open-upload]').forEach(button => {
                button.addEventListener('click', () => {
                    document.getElementById('upload-modal-container').style.display = 'flex';
                });
            });
            
            // Close modal handlers
            document.querySelectorAll('[data-close-upload]').forEach(button => {
                button.addEventListener('click', () => {
                    document.getElementById('upload-modal-container').style.display = 'none';
                });
            });
        }

        if (!dropArea || !fileInput) return;

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Highlight drop area when dragging over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.classList.add('border-blue-600', 'bg-blue-200');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.classList.remove('border-blue-600', 'bg-blue-200');
            }, false);
        });

        // Handle dropped files
        dropArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            displayFile(files[0]);
        }, false);

        // Handle file input change
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                displayFile(e.target.files[0]);
            }
        });

        // Display selected file
        function displayFile(file) {
            if (!file) return;

            fileList.classList.remove('hidden');
            fileItems.innerHTML = '';

            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between bg-gray-100 p-2 sm:p-3 rounded';
            
            const fileIcon = getFileIcon(file.name);
            
            fileItem.innerHTML = `
                <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                    <i class="${fileIcon} text-blue-600 text-sm sm:text-base"></i>
                    <span class="text-gray-700 text-xs sm:text-sm truncate">${file.name}</span>
                    <span class="text-gray-400 text-xs whitespace-nowrap">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                </div>
                <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700 ml-2">
                    <i class="fas fa-times"></i>
                </button>
            `;
            fileItems.appendChild(fileItem);
        }

        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            if (ext === 'pdf') return 'fas fa-file-pdf';
            if (['doc', 'docx'].includes(ext)) return 'fas fa-file-word';
            if (['mp4', 'avi', 'mov'].includes(ext)) return 'fas fa-file-video';
            return 'fas fa-file';
        }

        // Remove file from list
        window.removeFile = function() {
            fileInput.value = '';
            fileList.classList.add('hidden');
            fileItems.innerHTML = '';
        };
    });
</script>
@endpush