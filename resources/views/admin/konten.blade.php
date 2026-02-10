@extends('layouts.admin')

@section('title', 'Karya')
@section('page-title', 'Data Karya')

@section('content')
<div class="flex min-h-screen relative" x-data="karyaListData()">
    <div class="flex-1 p-10">
        <!-- Upload Button -->
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Data Karya</h1>
            <button @click="openUploadModal()" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold transition shadow-md">
                <i class="fas fa-upload mr-2"></i>Upload Konten Baru
            </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-8">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input
                    type="text"
                    x-model="searchTerm"
                    @input="selectedKontenId = null"
                    placeholder="Cari karya berdasarkan judul, uploader, atau tipe"
                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                >
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-blue-500 text-white rounded-lg p-6 shadow-md">
                <h5 class="text-sm font-medium opacity-90 mb-1">Total Konten</h5>
                <h2 class="text-3xl font-bold" x-text="stats.total"></h2>
            </div>
            <div class="bg-yellow-500 text-white rounded-lg p-6 shadow-md">
                <h5 class="text-sm font-medium opacity-90 mb-1">Pending</h5>
                <h2 class="text-3xl font-bold" x-text="stats.pending"></h2>
            </div>
            <div class="bg-green-500 text-white rounded-lg p-6 shadow-md">
                <h5 class="text-sm font-medium opacity-90 mb-1">Approved</h5>
                <h2 class="text-3xl font-bold" x-text="stats.approved"></h2>
            </div>
            <div class="bg-red-500 text-white rounded-lg p-6 shadow-md">
                <h5 class="text-sm font-medium opacity-90 mb-1">Rejected</h5>
                <h2 class="text-3xl font-bold" x-text="stats.rejected"></h2>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <select x-model="filterStatus" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                <select x-model="filterTipe" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">Semua Tipe</option>
                    <option value="buku">Buku</option>
                    <option value="video">Video</option>
                </select>
                <button @click="resetFilters()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg px-4 py-2 text-sm transition">
                    Reset Filter
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg border border-gray-200">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Judul</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tipe</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Uploader</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal Upload</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="item in karyaListFiltered" :key="item.konten_id">
                        <tr 
                            @click="selectKarya(item.konten_id)" 
                            :class="selectedKontenId === item.konten_id ? 'bg-blue-100' : ''"
                            class="border-b border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer"
                        >
                            <td class="px-6 py-4 text-sm text-gray-700" x-text="item.konten_id"></td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                        <i :class="item.tipe === 'buku' ? 'fas fa-book' : 'fas fa-video'"></i>
                                    </div>
                                    <span class="font-medium" x-text="item.judul"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span 
                                    class="inline-block px-3 py-1 rounded-full text-xs font-medium"
                                    :class="item.tipe === 'buku' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'"
                                    x-text="item.tipe.charAt(0).toUpperCase() + item.tipe.slice(1)"
                                ></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700" x-text="getUploaderDisplay(item)"></td>
                            <td class="px-6 py-4 text-sm">
                                <span 
                                    class="inline-block px-3 py-1 rounded-full text-xs font-medium"
                                    :class="{
                                        'bg-yellow-100 text-yellow-800': item.status === 'pending',
                                        'bg-green-100 text-green-800': item.status === 'approved',
                                        'bg-red-100 text-red-800': item.status === 'rejected'
                                    }"
                                    x-text="item.status.charAt(0).toUpperCase() + item.status.slice(1)"
                                ></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700" x-text="formatDateTime(item.tanggal_upload)"></td>
                        </tr>
                    </template>
                    <template x-if="karyaListFiltered.length === 0">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-400 font-medium">Tidak ada data konten ditemukan.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <!-- PAGINATION -->
            @if($konten->hasPages())
            <div class="px-4 md:px-6 py-4 border-t border-gray-200">
                {{ $konten->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- SIDEBAR SLIDE-RIGHT -->
    <aside
        x-show="selectedKontenId"
        @click.away="selectedKontenId = null"
        x-transition:enter="transform transition ease-in-out duration-300"
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transform transition ease-in-out duration-200"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0"
        class="fixed top-0 right-0 z-50 h-full w-full sm:w-96 max-w-full bg-white border-l border-gray-200 p-8 shadow-xl overflow-y-auto"
        style="display: none"
    >
        <template x-if="selectedKarya && selectedKontenId">
            <div class="flex flex-col items-center text-center gap-4">
                <div class="w-full text-right mb-1">
                    <span class="text-xs text-gray-500 font-medium">Konten ID:</span>
                    <span class="block text-lg font-semibold text-gray-900" x-text="selectedKarya.konten_id"></span>
                </div>
                
                <div class="w-28 h-28 rounded-full bg-indigo-600 flex items-center justify-center text-white shadow mb-2">
                    <i :class="selectedKarya.tipe === 'buku' ? 'fas fa-book' : 'fas fa-video'" class="text-5xl"></i>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-800 mb-1" x-text="selectedKarya.judul"></h2>
                
                <div class="w-full text-left text-gray-700 text-[15px] leading-loose space-y-1 mb-2">
                    <div><span class="font-semibold">Tipe:</span> <span x-text="selectedKarya.tipe.charAt(0).toUpperCase() + selectedKarya.tipe.slice(1)"></span></div>
                    <div><span class="font-semibold">Uploader:</span> <span x-text="selectedKarya.uploader_nama || 'N/A'"></span></div>
                    <div><span class="font-semibold">Status:</span> 
                        <span 
                            class="inline-block px-2 py-1 rounded text-xs font-medium ml-1"
                            :class="{
                                'bg-yellow-100 text-yellow-800': selectedKarya.status === 'pending',
                                'bg-green-100 text-green-800': selectedKarya.status === 'approved',
                                'bg-red-100 text-red-800': selectedKarya.status === 'rejected'
                            }"
                            x-text="selectedKarya.status.charAt(0).toUpperCase() + selectedKarya.status.slice(1)"
                        ></span>
                    </div>
                    <div><span class="font-semibold">Tanggal Upload:</span> <span x-text="formatDateTime(selectedKarya.tanggal_upload)"></span></div>
                    <div x-show="selectedKarya.deskripsi">
                        <span class="font-semibold">Deskripsi:</span> 
                        <p class="text-sm text-gray-600 mt-1" x-text="selectedKarya.deskripsi || '-'"></p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h3 class="text-sm font-semibold mb-1">Detail Konten</h3>
                    <p class="text-sm text-gray-500">Informasi lengkap tentang konten yang diunggah.</p>
                </div>
                
                <!-- FIX: Update form actions di sidebar -->
                <div class="flex flex-col gap-3 mt-8 w-full">
                    <template x-if="selectedKarya.status === 'pending'">
                        <div class="flex gap-3">
                            <!-- ✅ FIX: Ubah dari /approve/{id} ke /{id}/approve -->
                            <form :action="`/admin/konten/${selectedKarya.konten_id}/approve`" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-6 py-2 rounded bg-green-600 text-white hover:bg-green-700 font-semibold transition">
                                    <i class="fas fa-check mr-2"></i>Approve
                                </button>
                            </form>
                            
                            <!-- ✅ FIX: Ubah dari /reject/{id} ke /{id}/reject -->
                            <form :action="`/admin/konten/${selectedKarya.konten_id}/reject`" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-6 py-2 rounded bg-orange-600 text-white hover:bg-orange-700 font-semibold transition">
                                    <i class="fas fa-times mr-2"></i>Reject
                                </button>
                            </form>
                        </div>
                    </template>
                    
                    <a :href="`/admin/konten/${selectedKarya.konten_id}`" class="block w-full px-6 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 font-semibold transition text-center">
                        <i class="fas fa-eye mr-2"></i>Lihat Detail
                    </a>
                    
                    <button @click="openDeleteModal()" class="w-full px-6 py-2 rounded bg-red-600 text-white hover:bg-red-700 font-semibold transition">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </div>
            </div>
        </template>
    </aside>

    <!-- MODAL DELETE - PERFECT -->
    <div x-show="showDeleteModal" 
        @click.away="closeDeleteModal()"
        class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 p-4"
        style="display: none">
        <div @click.stop class="bg-white rounded-lg p-6 w-full max-w-sm text-center shadow-2xl">
            <h2 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h2>
            <p class="mb-6">Yakin hapus <strong x-text="selectedKarya.judul"></strong>?</p>
            
            <form :action="`/admin/konten/${selectedKarya.konten_id}`" method="POST">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <div class="flex gap-3 justify-end">
                    <button type="button" @click="closeDeleteModal()" 
                            class="px-4 py-2 border rounded hover:bg-gray-100">Batal</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 font-semibold">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL UPLOAD -->
    <div
        x-show="showUploadModal"
        x-transition.opacity
        @click.away="closeUploadModal()"
        class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 p-4"
        style="display: none"
    >
        <div @click.stop class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Upload Konten Baru</h2>
                <button @click="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="/admin/konten/store" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Tipe Konten -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Konten <span class="text-red-500">*</span></label>
                    <select name="tipe" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="">Pilih Tipe</option>
                        <option value="buku">Buku</option>
                        <option value="video">Video</option>
                    </select>
                </div>

                <!-- Judul -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" required maxlength="255" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Masukkan judul konten">
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="Masukkan deskripsi konten (opsional)"></textarea>
                </div>

                <!-- File Upload -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">File Konten <span class="text-red-500">*</span></label>
                    <input type="file" name="file" required class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-2">Format: PDF untuk buku, MP4/AVI untuk video. Maksimal 50MB.</p>
                </div>

                <!-- Thumbnail (Optional) -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Thumbnail/Cover (Opsional)</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maksimal 5MB.</p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 justify-end">
                    <button type="button" @click="closeUploadModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold transition">
                        <i class="fas fa-upload mr-2"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- TOAST NOTIFICATION SYSTEM -->
<div x-data="toastNotifications()" 
     x-on:notify.window="addNotification($event.detail)"
     x-init="checkSessionFlash()"
     class="pointer-events-none fixed inset-x-8 top-0 z-[999] flex max-w-full flex-col gap-2 bg-transparent px-6 py-6 md:bottom-0 md:left-[unset] md:right-0 md:top-[unset] md:max-w-sm">
    <template x-for="notification in notifications" :key="notification.id">
        <div x-show="notification.show"
             x-transition:enter="transition duration-300 ease-out"
             x-transition:enter-start="translate-y-8 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition duration-300 ease-in"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-24 opacity-0"
             class="pointer-events-auto">
            
            <!-- Success Notification -->
            <template x-if="notification.variant === 'success'">
                <div class="relative rounded-lg border border-green-500 bg-white shadow-lg">
                    <div class="flex w-full items-center gap-2.5 bg-green-50 rounded-lg p-4">
                        <div class="rounded-full bg-green-100 p-2 text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h3 class="text-sm font-semibold text-green-700" x-text="notification.title"></h3>
                            <p class="text-sm text-gray-700" x-text="notification.message"></p>
                        </div>
                        <button type="button" @click="removeNotification(notification.id)" class="ml-auto text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>

            <!-- Danger Notification -->
            <template x-if="notification.variant === 'danger'">
                <div class="relative rounded-lg border border-red-500 bg-white shadow-lg">
                    <div class="flex w-full items-center gap-2.5 bg-red-50 rounded-lg p-4">
                        <div class="rounded-full bg-red-100 p-2 text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h3 class="text-sm font-semibold text-red-700" x-text="notification.title"></h3>
                            <p class="text-sm text-gray-700" x-text="notification.message"></p>
                        </div>
                        <button type="button" @click="removeNotification(notification.id)" class="ml-auto text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
// Alpine Component untuk Data Karya (UPDATE UNTUK TABLE BARU)
function karyaListData() {
    return {
        searchTerm: '',
        filterStatus: '',
        filterTipe: '',
        selectedKontenId: null,
        karyaList: @json($konten->items()),
        stats: @json($stats),
        selectedKarya: {},
        showDeleteModal: false,
        showUploadModal: false,
        deleteId: null,
        
        formatDateTime(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            };
            return date.toLocaleString("id-ID", options) + ' WIB';
        },

        // ✅ NEW: Uploader display logic untuk table baru
        getUploaderDisplay(item) {
            // Siswa uploader
            if (item.id_siswa_uploader && item.siswa_uploader) {
                return `Siswa: ${item.siswa_uploader.nama || item.id_siswa_uploader}`;
            }
            // Admin uploader
            else if (item.id_admin_uploader && item.admin_uploader) {
                return `Admin: ${item.admin_uploader.nama || item.id_admin_uploader}`;
            }
            return 'N/A';
        },

        selectKarya(kontenId) {
            this.selectedKontenId = kontenId;
            const found = this.karyaList.find(k => k.konten_id === kontenId);
            this.selectedKarya = {
                ...found,
                uploader_nama: this.getUploaderDisplay(found) // ← Fix untuk sidebar
            } || {};
        },
        
        openDeleteModal() {
            this.showDeleteModal = true;
        },

        closeDeleteModal() {
            this.showDeleteModal = false;
        },

        openUploadModal() {
            this.showUploadModal = true;
        },
        
        closeUploadModal() {
            this.showUploadModal = false;
        },

        resetFilters() {
            this.searchTerm = '';
            this.filterStatus = '';
            this.filterTipe = '';
        },

        get karyaListFiltered() {
            let filtered = this.karyaList;
            
            // Filter by search term (include uploader names)
            if(this.searchTerm) {
                let term = this.searchTerm.toLowerCase();
                filtered = filtered.filter(karya =>
                    (karya.judul ?? '').toLowerCase().includes(term) ||
                    this.getUploaderDisplay(karya).toLowerCase().includes(term) ||
                    (karya.tipe ?? '').toLowerCase().includes(term)
                );
            }
            
            // Filter by status
            if(this.filterStatus) {
                filtered = filtered.filter(karya => karya.status === this.filterStatus);
            }
            
            // Filter by tipe
            if(this.filterTipe) {
                filtered = filtered.filter(karya => karya.tipe === this.filterTipe);
            }
            
            return filtered;
        }
    };
}

// Toast Notifications (SAMA)
function toastNotifications() {
    return {
        notifications: [],
        displayDuration: 5000,

        addNotification({ variant = 'info', title = '', message = '' }) {
            const id = Date.now();
            const notification = { 
                id, 
                variant, 
                title, 
                message, 
                show: false 
            };

            this.notifications.push(notification);

            this.$nextTick(() => {
                const notif = this.notifications.find(n => n.id === id);
                if (notif) notif.show = true;
            });

            setTimeout(() => {
                this.removeNotification(id);
            }, this.displayDuration);
        },

        removeNotification(id) {
            const notif = this.notifications.find(n => n.id === id);
            if (notif) {
                notif.show = false;
                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }, 300);
            }
        },

        checkSessionFlash() {
            @if(session('success'))
                this.addNotification({
                    variant: 'success',
                    title: 'Berhasil!',
                    message: @json(session('success'))
                });
            @elseif(session('danger'))
                this.addNotification({
                    variant: 'danger',
                    title: 'Perhatian!',
                    message: @json(session('danger'))
                });
            @endif
        }
    };
}
</script>
@endpush