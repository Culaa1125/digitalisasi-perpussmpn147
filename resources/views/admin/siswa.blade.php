{{-- resources/views/admin/siswa.blade.php --}}
@extends('layouts.admin')

@section('title', 'Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="flex min-h-screen relative" x-data="siswaListData()">
    <div class="flex-1 p-10">
        <!-- Header dengan Tab Filter -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Kelola Siswa</h1>
                <div class="text-sm text-gray-600">
                    Total: <span class="font-semibold" x-text="siswaList.length"></span> siswa
                </div>
            </div>
            
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button 
                        @click="filterStatus = 'all'" 
                        :class="filterStatus === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        Semua (<span x-text="siswaList.length"></span>)
                    </button>
                    <button 
                        @click="filterStatus = 'pending'" 
                        :class="filterStatus === 'pending' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition relative">
                        Pending 
                        <span x-show="pendingCount > 0" 
                              class="ml-2 bg-yellow-100 text-yellow-800 py-0.5 px-2 rounded-full text-xs"
                              x-text="pendingCount"></span>
                    </button>
                    <button 
                        @click="filterStatus = 'approved'" 
                        :class="filterStatus === 'approved' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        Disetujui (<span x-text="approvedCount"></span>)
                    </button>
                    <button 
                        @click="filterStatus = 'rejected'" 
                        :class="filterStatus === 'rejected' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                        Ditolak (<span x-text="rejectedCount"></span>)
                    </button>
                </nav>
            </div>

            <!-- Search Bar -->
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input
                    type="text"
                    x-model="searchTerm"
                    @input="selectedNIS = null"
                    placeholder="Cari siswa berdasarkan nama, email, atau NIS"
                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                >
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Lengkap</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">NIS</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kelas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal Daftar</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="siswa in siswaListFiltered" :key="siswa.nis">
                            <tr 
                                @click="selectStudent(siswa.nis)" 
                                :class="selectedNIS === siswa.nis ? 'bg-blue-50' : ''"
                                class="border-b border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer"
                            >
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="flex items-center gap-3">
                                        <img 
                                            :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(siswa.nama_lengkap ?? '-')}&size=40&background=4F46E5&color=fff`"
                                            class="w-10 h-10 rounded-full" 
                                            :alt="siswa.nama_lengkap">
                                        <div>
                                            <div class="font-medium" x-text="siswa.nama_lengkap ?? '-'"></div>
                                            <div class="text-xs text-gray-500" x-text="siswa.no_hp ?? '-'"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700" x-text="siswa.nis ?? '-'"></td>
                                <td class="px-6 py-4 text-sm text-gray-700" x-text="siswa.kelas ?? '-'"></td>
                                <td class="px-6 py-4 text-sm text-gray-700" x-text="siswa.email ?? '-'"></td>
                                <td class="px-6 py-4 text-sm">
                                    <template x-if="siswa.status === 'pending'">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 inline-flex items-center gap-1">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    </template>
                                    <template x-if="siswa.status === 'approved'">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 inline-flex items-center gap-1">
                                            <i class="fas fa-check-circle"></i> Disetujui
                                        </span>
                                    </template>
                                    <template x-if="siswa.status === 'rejected'">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 inline-flex items-center gap-1">
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        </span>
                                    </template>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600" x-text="formatDate(siswa.created_at)"></td>
                                <td class="px-6 py-4 text-sm" @click.stop>
                                    <div class="flex gap-2">
                                        <!-- Approval Actions (only for pending) -->
                                        <template x-if="siswa.status === 'pending'">
                                            <div class="flex gap-2">
                                                <button 
                                                    @click="approveStudent(siswa.nis, siswa.nama_lengkap)"
                                                    class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded transition inline-flex items-center gap-1">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                                <button 
                                                    @click="openRejectModal(siswa.nis, siswa.nama_lengkap)"
                                                    class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded transition inline-flex items-center gap-1">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </div>
                                        </template>
                                        
                                        <!-- Standard Actions -->
                                        <button 
                                            @click="selectStudent(siswa.nis)"
                                            class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded transition inline-flex items-center gap-1">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="siswaListFiltered.length === 0">
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fas fa-users text-4xl text-gray-300"></i>
                                        <p class="text-gray-400 font-medium">Tidak ada data siswa ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SIDEBAR DETAIL -->
    <aside
        x-show="selectedNIS"
        @click.away="selectedNIS = null"
        x-transition:enter="transform transition ease-in-out duration-300"
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transform transition ease-in-out duration-200"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0"
        class="fixed top-0 right-0 z-50 h-full w-full sm:w-96 max-w-full bg-white border-l border-gray-200 p-8 shadow-xl overflow-y-auto"
        style="display: none"
    >
        <template x-if="selectedSiswa && selectedNIS">
            <div class="flex flex-col gap-6">
                <!-- Close Button -->
                <button @click="selectedNIS = null" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <!-- Profile Header -->
                <div class="text-center">
                    <img
                        :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(selectedSiswa.nama_lengkap ?? '-')}&size=120&background=4F46E5&color=fff`"
                        alt="Student Profile"
                        class="w-28 h-28 rounded-full mx-auto object-cover shadow-lg mb-4"
                    >
                    <h2 class="text-2xl font-bold text-gray-800 mb-1" x-text="selectedSiswa.nama_lengkap ?? '-'"></h2>
                    <p class="text-sm text-gray-500 mb-3" x-text="'NIS: ' + (selectedSiswa.nis ?? '-')"></p>
                    
                    <!-- Status Badge -->
                    <div class="mb-4">
                        <template x-if="selectedSiswa.status === 'pending'">
                            <span class="px-4 py-2 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800 inline-flex items-center gap-2">
                                <i class="fas fa-clock"></i> Menunggu Persetujuan
                            </span>
                        </template>
                        <template x-if="selectedSiswa.status === 'approved'">
                            <span class="px-4 py-2 text-sm font-medium rounded-full bg-green-100 text-green-800 inline-flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> Akun Disetujui
                            </span>
                        </template>
                        <template x-if="selectedSiswa.status === 'rejected'">
                            <span class="px-4 py-2 text-sm font-medium rounded-full bg-red-100 text-red-800 inline-flex items-center gap-2">
                                <i class="fas fa-times-circle"></i> Akun Ditolak
                            </span>
                        </template>
                    </div>
                </div>

                <!-- Student Info -->
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Informasi Siswa
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kelas:</span>
                            <span class="font-medium text-gray-800" x-text="selectedSiswa.kelas ?? '-'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium text-gray-800 truncate ml-2" x-text="selectedSiswa.email ?? '-'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nomor HP:</span>
                            <span class="font-medium text-gray-800" x-text="selectedSiswa.no_hp ?? '-'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Lahir:</span>
                            <span class="font-medium text-gray-800" x-text="selectedSiswa.tanggal_lahir ?? '-'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jenis Kelamin:</span>
                            <span class="font-medium text-gray-800" x-text="selectedSiswa.jk === 'L' ? 'Laki-laki' : selectedSiswa.jk === 'P' ? 'Perempuan' : '-'"></span>
                        </div>
                    </div>
                </div>

                <!-- Approval Info (if approved) -->
                <template x-if="selectedSiswa.status === 'approved' && selectedSiswa.approved_at">
                    <div class="bg-green-50 rounded-lg p-4">
                        <h3 class="font-semibold text-green-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            Informasi Persetujuan
                        </h3>
                        <div class="space-y-1 text-sm text-green-700">
                            <div>Disetujui pada: <span class="font-medium" x-text="formatDateTime(selectedSiswa.approved_at)"></span></div>
                        </div>
                    </div>
                </template>

                <!-- Rejection Info (if rejected) -->
                <template x-if="selectedSiswa.status === 'rejected'">
                    <div class="bg-red-50 rounded-lg p-4">
                        <h3 class="font-semibold text-red-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            Alasan Penolakan
                        </h3>
                        <p class="text-sm text-red-700" x-text="selectedSiswa.rejection_reason ?? 'Tidak ada alasan yang diberikan'"></p>
                    </div>
                </template>

                <!-- Timestamps -->
                <div class="border-t pt-4 space-y-2 text-xs text-gray-500">
                    <div>Terdaftar: <span x-text="formatDateTime(selectedSiswa.created_at)"></span></div>
                    <div>Terakhir Diubah: <span x-text="formatDateTime(selectedSiswa.updated_at)"></span></div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3 pt-4 border-t">
                    <!-- Approval Actions (only for pending) -->
                    <template x-if="selectedSiswa.status === 'pending'">
                        <div class="grid grid-cols-2 gap-3">
                            <button 
                                @click="approveStudent(selectedSiswa.nis, selectedSiswa.nama_lengkap)" 
                                class="px-4 py-3 rounded-lg bg-green-500 text-white hover:bg-green-600 font-semibold transition inline-flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                            <button 
                                @click="openRejectModal(selectedSiswa.nis, selectedSiswa.nama_lengkap)" 
                                class="px-4 py-3 rounded-lg bg-red-500 text-white hover:bg-red-600 font-semibold transition inline-flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </div>
                    </template>

                    <!-- Standard Actions -->
                    <div class="grid grid-cols-2 gap-3">
                        <button 
                            @click="openUpdateModal()" 
                            class="px-4 py-3 rounded-lg bg-blue-500 text-white hover:bg-blue-600 font-semibold transition inline-flex items-center justify-center gap-2">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button 
                            @click="openDeleteModal()" 
                            class="px-4 py-3 rounded-lg bg-gray-500 text-white hover:bg-gray-600 font-semibold transition inline-flex items-center justify-center gap-2">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </aside>

    <!-- MODAL UPDATE -->
    <div
        x-show="showUpdateModal"
        x-transition.opacity
        @click.away="closeUpdateModal()"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none"
    >
        <div @click.stop class="bg-white rounded-xl p-0 w-full max-w-md max-h-[90vh] flex flex-col shadow-2xl">
            <div class="px-6 pt-6 pb-4 border-b">
                <h2 class="text-xl font-bold text-gray-800">Update Data Siswa</h2>
            </div>
            <div class="overflow-y-auto px-6 py-4" style="max-height:60vh;">
                <form 
                    x-ref="updateForm"
                    method="POST"
                    :action="`{{ url('admin/siswa') }}/${selectedSiswa.nis || ''}`"
                >
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="old_nis" :value="selectedSiswa.nis">

                    <div class="space-y-4">
                        <div>
                            <label class="block mb-2 font-medium text-sm text-gray-700">NIS <span class="text-red-500">*</span></label>
                            <input type="text" 
                                name="nis" 
                                x-model="selectedSiswa.nis" 
                                required 
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan NIS">
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-sm text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" 
                                name="nama_lengkap" 
                                x-model="selectedSiswa.nama_lengkap" 
                                required 
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-sm text-gray-700">Kelas</label>
                            <input type="text" 
                                name="kelas" 
                                x-model="selectedSiswa.kelas" 
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Contoh: 7A, 8B">
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-sm text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" 
                                name="email" 
                                x-model="selectedSiswa.email" 
                                required 
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="email@example.com">
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-sm text-gray-700">Nomor HP</label>
                            <input type="text" 
                                name="no_hp" 
                                x-model="selectedSiswa.no_hp" 
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="08xxxxxxxxxx">
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-sm text-gray-700">Tanggal Lahir</label>
                            <input type="date" 
                                name="tanggal_lahir" 
                                x-model="selectedSiswa.tanggal_lahir" 
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-sm text-gray-700">Jenis Kelamin</label>
                            <select name="jk" 
                                    x-model="selectedSiswa.jk" 
                                    class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 font-medium text-sm text-gray-700">Password <span class="text-gray-500 text-xs">(opsional)</span></label>
                            <input type="password" 
                                name="password" 
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Kosongkan jika tidak ingin diubah">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t">
                        <button type="button" 
                                @click="closeUpdateModal()" 
                                class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DELETE -->
    <div 
        x-show="showDeleteModal"
        x-transition.opacity
        @click.away="closeDeleteModal()"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none">
        
        <div @click.stop class="bg-white rounded-xl p-8 w-full max-w-sm text-center shadow-2xl">
            <div class="w-20 h-20 bg-red-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-3">Hapus Siswa?</h2>
            <p class="text-gray-600 mb-2">Yakin ingin menghapus</p>
            <p class="font-bold text-gray-800 mb-1" x-text="selectedSiswa.nama_lengkap"></p>
            <p class="text-sm text-gray-500 mb-6">(NIS: <span x-text="selectedSiswa.nis"></span>)</p>
            
            <form 
                x-ref="deleteForm"
                method="POST"
                :action="`/admin/siswa/${selectedSiswa.nis}`">
                
                @csrf
                @method('DELETE')
                
                <div class="flex gap-3 justify-center pt-4">
                    <button type="button" 
                            @click="closeDeleteModal()" 
                            class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-xl hover:bg-gray-50 font-semibold transition-all">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-semibold shadow-lg transition-all">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL REJECT -->
    <div 
        x-show="showRejectModal"
        x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none">
        
        <div @click.stop class="bg-white rounded-xl p-8 w-full max-w-md shadow-2xl">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-times-circle text-red-500"></i>
                Tolak Pendaftaran
            </h3>
            <p class="text-sm text-gray-600 mb-1">Siswa:</p>
            <p class="font-semibold text-gray-800 mb-4" x-text="rejectData.nama"></p>
            
            <form method="POST" :action="`/admin/siswa/${rejectData.nis}/reject`">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="rejection_reason" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                        rows="4" 
                        placeholder="Jelaskan alasan penolakan..."
                        required></textarea>
                </div>
                <div class="flex gap-3">
                    <button 
                        type="button"
                        @click="closeRejectModal()" 
                        class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 rounded-lg font-semibold transition">
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition">
                        Tolak Pendaftaran
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
     class="pointer-events-none fixed inset-x-8 top-0 z-[60] flex max-w-full flex-col gap-2 bg-transparent px-6 py-6 md:bottom-0 md:left-[unset] md:right-0 md:top-[unset] md:max-w-sm">
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
                        <div class="flex flex-col gap-1 flex-1">
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

            <!-- Warning Notification -->
            <template x-if="notification.variant === 'warning'">
                <div class="relative rounded-lg border border-yellow-500 bg-white shadow-lg">
                    <div class="flex w-full items-center gap-2.5 bg-yellow-50 rounded-lg p-4">
                        <div class="rounded-full bg-yellow-100 p-2 text-yellow-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex flex-col gap-1 flex-1">
                            <h3 class="text-sm font-semibold text-yellow-700" x-text="notification.title"></h3>
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
                        <div class="flex flex-col gap-1 flex-1">
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
function siswaListData() {
    return {
        searchTerm: '',
        filterStatus: 'all',
        selectedNIS: null,
        siswaList: @json($siswaList ?? []),
        selectedSiswa: {},
        showUpdateModal: false,
        showDeleteModal: false,
        showRejectModal: false,
        rejectData: { nis: '', nama: '' },
        
        init() {
            console.log('Siswa list loaded:', this.siswaList.length);
        },
        
        get pendingCount() {
            return this.siswaList.filter(s => s.status === 'pending').length;
        },
        
        get approvedCount() {
            return this.siswaList.filter(s => s.status === 'approved').length;
        },
        
        get rejectedCount() {
            return this.siswaList.filter(s => s.status === 'rejected').length;
        },
        
        get siswaListFiltered() {
            let filtered = this.siswaList;
            
            // Filter by status
            if (this.filterStatus !== 'all') {
                filtered = filtered.filter(s => s.status === this.filterStatus);
            }
            
            // Filter by search term
            if (this.searchTerm) {
                const term = this.searchTerm.toLowerCase();
                filtered = filtered.filter(siswa =>
                    (siswa.nama_lengkap ?? '').toLowerCase().includes(term) ||
                    (siswa.email ?? '').toLowerCase().includes(term) ||
                    (siswa.nis ?? '').toLowerCase().includes(term) ||
                    (siswa.kelas ?? '').toLowerCase().includes(term)
                );
            }
            
            return filtered;
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString.replace(' ', 'T'));
            return date.toLocaleDateString("id-ID", {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        },
        
        formatDateTime(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString.replace(' ', 'T'));
            return date.toLocaleString("id-ID", {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }) + ' WIB';
        },

        selectStudent(nis) {
            this.selectedNIS = nis;
            this.selectedSiswa = {...this.siswaList.find(s => s.nis === nis)} || {};
        },

        openUpdateModal() {
            this.showUpdateModal = true;
        },
        
        closeUpdateModal() {
            this.showUpdateModal = false;
        },
        
        openDeleteModal() {
            this.showDeleteModal = true;
        },
        
        closeDeleteModal() {
            this.showDeleteModal = false;
        },
        
        openRejectModal(nis, nama) {
            this.rejectData = { nis, nama };
            this.showRejectModal = true;
        },
        
        closeRejectModal() {
            this.showRejectModal = false;
            this.rejectData = { nis: '', nama: '' };
        },
        
        async approveStudent(nis, nama) {
            if (!confirm(`Setujui pendaftaran ${nama}?`)) return;
            
            try {
                const response = await fetch(`/admin/siswa/${nis}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Gagal menyetujui siswa');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        }
    };
}

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
            @elseif(session('warning'))
                this.addNotification({
                    variant: 'warning',
                    title: 'Perhatian!',
                    message: @json(session('warning'))
                });
            @elseif(session('danger'))
                this.addNotification({
                    variant: 'danger',
                    title: 'Terhapus!',
                    message: @json(session('danger'))
                });
            @endif
        }
    };
}
</script>
@endpush