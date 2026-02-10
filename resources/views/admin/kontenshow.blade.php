@extends('layouts.admin')

@section('title', 'Detail Karya')
@section('page-title', 'Detail Konten')

@section('content')
<div class="p-10 max-w-6xl mx-auto">
    <div class="bg-white rounded-xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-8 text-white">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-bold mb-2">{{ $konten->judul }}</h1>
                    <div class="flex flex-wrap gap-4 text-sm opacity-90 mt-2">
                        <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full font-medium">
                            {{ strtoupper($konten->tipe) }}
                        </span>
                        <span class="flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            {{ ucfirst($konten->status) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-hashtag"></i>
                            {{ $konten->konten_id }}
                        </span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.karya.index') }}" 
                       class="px-6 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-xl font-semibold transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                    @if($konten->status === 'pending')
                    <form action="{{ route('admin.konten.approve', $konten->konten_id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-green-500/20 hover:bg-green-500/40 backdrop-blur-sm rounded-xl font-semibold transition-all duration-200 flex items-center gap-2 border border-green-500/30">
                            <i class="fas fa-check"></i>
                            Approve
                        </button>
                    </form>
                    <form action="{{ route('admin.konten.reject', $konten->konten_id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-red-500/20 hover:bg-red-500/40 backdrop-blur-sm rounded-xl font-semibold transition-all duration-200 flex items-center gap-2 border border-red-500/30">
                            <i class="fas fa-times"></i>
                            Reject
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-8 lg:p-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Left Column: Info -->
                <div class="space-y-8">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <i class="fas fa-info-circle text-indigo-500"></i>
                            Informasi Konten
                        </h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-500 mb-2">Judul</label>
                                <p class="text-2xl font-bold text-gray-900">{{ $konten->judul }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-500 mb-2">Deskripsi</label>
                                <p class="text-lg text-gray-700 leading-relaxed">{{ $konten->deskripsi ?: '-' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-500 mb-2">Tipe</label>
                                    <span class="inline-flex px-4 py-2 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800 rounded-full font-semibold">
                                        {{ ucfirst($konten->tipe) }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-500 mb-2">Status</label>
                                    <span class="inline-flex px-4 py-2 
                                        @if($konten->status === 'approved')
                                            bg-green-100 text-green-800
                                        @elseif($konten->status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-red-100 text-red-800
                                        @endif
                                        rounded-full font-semibold">
                                        {{ ucfirst($konten->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Uploader Info -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <i class="fas fa-user-circle text-green-500"></i>
                            Uploader
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($konten->id_siswa_uploader)
                                <div>
                                    <label class="block text-sm font-semibold text-gray-500 mb-3">Siswa Uploader</label>
                                    <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            {{ $konten->siswaUploader->nama ? substr($konten->siswaUploader->nama, 0, 2) : 'S' }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $konten->siswaUploader->nama ?? $konten->id_siswa_uploader }}</p>
                                            <p class="text-sm text-gray-500">NIS: {{ $konten->id_siswa_uploader }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($konten->id_admin_uploader)
                                <div>
                                                                        <label class="block text-sm font-semibold text-gray-500 mb-3">Admin Uploader</label>
                                    <div class="flex items-center gap-4 p-4 bg-green-50 rounded-xl border border-green-100">
                                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            {{ $konten->adminUploader->nama ? substr($konten->adminUploader->nama, 0, 2) : 'A' }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $konten->adminUploader->nama ?? $konten->id_admin_uploader }}</p>
                                            <p class="text-sm text-gray-500">Admin ID: {{ $konten->id_admin_uploader }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Approver Info -->
                    @if($konten->id_approver)
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <i class="fas fa-check-circle text-blue-500"></i>
                            Approver
                        </h3>
                        <div class="flex items-center gap-4 p-4 bg-purple-50 rounded-xl border border-purple-100">
                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ $konten->approver->nama ? substr($konten->approver->nama, 0, 2) : 'A' }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $konten->approver->nama ?? $konten->id_approver }}</p>
                                <p class="text-sm text-gray-500">Approved: {{ $konten->tanggal_approve ? $konten->tanggal_approve->format('d M Y H:i') : '-' }} WIB</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column: Files & Timeline -->
                <div class="space-y-8">
                    <!-- File Download -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <i class="fas fa-download text-orange-500"></i>
                            File Konten
                        </h3>
                        <div class="bg-gradient-to-r from-orange-50 to-yellow-50 p-8 rounded-2xl border-2 border-dashed border-orange-200">
                            <div class="text-center">
                                <div class="w-20 h-20 mx-auto mb-4 bg-orange-100 rounded-2xl flex items-center justify-center">
                                    <i class="{{ $konten->tipe === 'buku' ? 'fas fa-file-pdf text-2xl text-orange-600' : 'fas fa-file-video text-2xl text-purple-600' }}"></i>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ ucfirst($konten->tipe) }}</h4>
                                <p class="text-gray-600 mb-6">Klik untuk download/menonton</p>
                                <a :href="'/storage/{{ $konten->file_path }}'" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    <i class="fas fa-download"></i>
                                    Download / Tonton File
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($konten->thumbnail_path)
                    <!-- Thumbnail -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <i class="fas fa-image text-pink-500"></i>
                            Thumbnail
                        </h3>
                        <div class="overflow-hidden rounded-2xl shadow-lg border-4 border-gray-100">
                            <img src="/storage/{{ $konten->thumbnail_path }}" alt="Thumbnail" class="w-full h-64 object-cover">
                        </div>
                        <div class="mt-4 text-center">
                            <a href="/storage/{{ $konten->thumbnail_path }}" target="_blank" 
                               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                Buka gambar dalam tab baru
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Timeline -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <i class="fas fa-clock text-gray-500"></i>
                            Timeline
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                                <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    📤
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Uploaded</p>
                                    <p class="text-sm text-gray-500">{{ $konten->tanggal_upload->format('d M Y, H:i') }} WIB</p>
                                </div>
                            </div>
                            @if($konten->tanggal_approve)
                            <div class="flex items-center gap-4 p-4 bg-green-50 rounded-xl border-l-4 border-green-400">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    ✅
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Approved</p>
                                    <p class="text-sm text-gray-500">{{ $konten->tanggal_approve->format('d M Y, H:i') }} WIB</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-12 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.karya.index') }}" 
                       class="px-8 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 flex items-center gap-2 justify-center flex-1 sm:flex-none">
                        <i class="fas fa-list"></i>
                        Kembali ke Daftar
                    </a>
                    @if($konten->status !== 'rejected')
                    <form action="{{ route('admin.konten.destroy', $konten->konten_id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-8 py-3 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 flex items-center gap-2 justify-center flex-1 sm:flex-none"
                                onclick="return confirm('Yakin ingin menghapus konten ini? File juga akan terhapus!')">
                            <i class="fas fa-trash"></i>
                            Hapus Konten
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection