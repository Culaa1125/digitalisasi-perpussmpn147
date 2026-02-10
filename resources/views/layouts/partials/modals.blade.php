<!-- PROFILE MENU MODAL -->
<div x-cloak x-show="profileModal" 
     x-transition.opacity.duration.200ms
     @keydown.escape.window="profileModal = false"
     @click.self="profileModal = false"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div x-show="profileModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         @click.away="profileModal = false"
         class="bg-white rounded-2xl shadow-2xl w-96 overflow-hidden">
        <!-- Profile Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-linear-to-br from-blue-500 to-red-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-lg">{{ auth()->user()->name ?? 'Admin' }}</h3>
                    <p class="text-gray-500 text-sm">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                </div>
            </div>
        </div>

        <!-- Menu Items -->
        <div class="p-4">
            <!-- My Profile -->
            <button @click="profileModal = false; editProfileModal = true" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors group">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user text-gray-600 text-lg"></i>
                    <span class="text-gray-800 font-medium">My Profile</span>
                </div>
                <i class="fas fa-chevron-right text-gray-400 text-sm group-hover:translate-x-1 transition-transform"></i>
            </button>

            <!-- Settings -->
            <button @click="profileModal = false; settingsModal = true" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors group">
                <div class="flex items-center gap-3">
                    <i class="fas fa-cog text-gray-600 text-lg"></i>
                    <span class="text-gray-800 font-medium">Settings</span>
                </div>
                <i class="fas fa-chevron-right text-gray-400 text-sm group-hover:translate-x-1 transition-transform"></i>
            </button>

            <!-- Notification -->
            <div class="flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center gap-3">
                    <i class="fas fa-bell text-gray-600 text-lg"></i>
                    <span class="text-gray-800 font-medium">Notification</span>
                </div>
                <span class="text-sm text-gray-500">Allow</span>
            </div>

            <!-- Log Out -->
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-sign-out-alt text-gray-600 text-lg"></i>
                    <span class="text-gray-800 font-medium">Log Out</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- EDIT PROFILE MODAL -->
<div x-cloak x-show="editProfileModal"
     x-transition.opacity.duration.200ms
     @keydown.escape.window="editProfileModal = false"
     @click.self="editProfileModal = false"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div x-show="editProfileModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         @click.away="editProfileModal = false"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <!-- Header with Close Button -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Edit Profile</h2>
            <button @click="editProfileModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Profile Picture Section -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&size=80&background=4F46E5&color=fff" 
                         alt="Profile" 
                         class="w-20 h-20 rounded-full object-cover">
                    <button class="absolute bottom-0 right-0 w-8 h-8 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-pencil-alt text-xs"></i>
                    </button>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">{{ auth()->user()->name ?? 'Admin' }}</h3>
                    <p class="text-sm text-gray-500">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                </div>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="p-6 space-y-4">
            <!-- Name Field -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-700 font-medium">Name</span>
                <span class="text-gray-500 text-sm">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>

            <!-- Email Field -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-700 font-medium">Email account</span>
                <span class="text-gray-500 text-sm">{{ auth()->user()->email ?? 'admin@example.com' }}</span>
            </div>

            <!-- Mobile Number Field -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-700 font-medium">Mobile number</span>
                <span class="text-gray-400 text-sm">Add number</span>
            </div>

            <!-- Location Field -->
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-700 font-medium">Location</span>
                <span class="text-gray-500 text-sm">Indonesia</span>
            </div>

            <!-- Save Button -->
            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-medium transition-colors mt-6">
                Save Change
            </button>
        </div>
    </div>
</div>

<!-- SETTINGS MODAL -->
<div x-cloak x-show="settingsModal"
     x-transition.opacity.duration.200ms
     @keydown.escape.window="settingsModal = false"
     @click.self="settingsModal = false"
     class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div x-show="settingsModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         @click.away="settingsModal = false"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
        <!-- Header with Close Button -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Settings</h2>
            <button @click="settingsModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Settings Options -->
        <div class="p-6 space-y-4">
            <!-- Theme -->
            <div class="flex justify-between items-center py-3">
                <span class="text-gray-700 font-medium">Theme</span>
                <div class="relative">
                    <select class="appearance-none bg-transparent text-gray-600 pr-8 font-medium focus:outline-none cursor-pointer">
                        <option>Light</option>
                        <option>Dark</option>
                        <option>Auto</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-0 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            <!-- Language -->
            <div class="flex justify-between items-center py-3">
                <span class="text-gray-700 font-medium">Language</span>
                <div class="relative">
                    <select class="appearance-none bg-transparent text-gray-600 pr-8 font-medium focus:outline-none cursor-pointer">
                        <option>Eng</option>
                        <option>Ind</option>
                        <option>中文</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-0 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL MENAMBAHKAN KARYA -->
<div x-cloak x-show="modalAddKarya"
    class="fixed inset-0 flex items-center justify-center z-50"
    x-transition.opacity>
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden" @click.away="modalAddKarya = false">
        <!-- MODAL HEADER -->
        <div class="bg-linear-to-r from-blue-500 to-blue-600 px-8 py-6 text-white">
            <h2 class="text-2xl font-bold">Menambahkan Karya</h2>
            <p class="text-blue-100 text-sm mt-1">Tambahkan File</p>
        </div>
        <!-- MODAL BODY -->
        <div class="px-8 py-6">
            <!-- Name Field -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" placeholder="Masukkan nama" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
            </div>
            <!-- Email Field -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" placeholder="Masukkan email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
            </div>
            <!-- Password Field -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" placeholder="Masukkan password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
            </div>
            <!-- File Upload Section -->
            <div class="mb-8 p-4 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer" @click="document.getElementById('fileInput').click()">
                <div class="text-center">
                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                    <p class="text-sm text-gray-600">Klik untuk mengunggah file atau seret ke sini</p>
                    <p class="text-xs text-gray-500 mt-1">Maksimal 10MB</p>
                </div>
                <input type="file" id="fileInput" class="hidden" @change="fileName = $event.target.files[0] ? '✓ File dipilih: '+$event.target.files[0].name : ''">
            </div>
            <div class="mb-6 text-sm text-gray-600" x-text="fileName"></div>
            <!-- Button Actions -->
            <div class="flex gap-3">
                <button @click="modalAddKarya = false" class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Close
                </button>
                <button class="flex-1 px-4 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors">
                    <i class="fas fa-check mr-2"></i>
                    Tambahkan karya
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update -->
<div
  x-show="showUpdateModal"
  x-transition.opacity
  @click.away="closeUpdateModal()"
  class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50"
  style="display:none"
>
  <div @click.stop class="bg-white rounded p-6 max-w-md w-full">
    <h2 class="mb-4 text-lg font-bold">Update Data Siswa</h2>
    <!-- Isian form dan tombol simpan/batal -->
    <button @click="closeUpdateModal()" class="mr-2 px-4 py-2 border rounded">Batal</button>
    <button @click="submitUpdate()" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
  </div>
</div>

<!-- Modal Delete -->
<div
  x-show="showDeleteModal"
  x-transition.opacity
  @click.away="closeDeleteModal()"
  class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50"
  style="display:none"
>
  <div @click.stop class="bg-white rounded p-6 max-w-sm w-full text-center">
    <h2 class="mb-4 text-lg font-bold">Konfirmasi Hapus Data</h2>
    <p>Apakah Anda yakin akan menghapus data siswa <strong x-text="selectedSiswa?.nama_lengkap || 'Unknown'"></strong>?</p>
    <div class="mt-4">
      <button @click="closeDeleteModal()" class="mr-2 px-4 py-2 border rounded">Batal</button>
      <button @click="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded">Hapus</button>
    </div>
  </div>
</div>