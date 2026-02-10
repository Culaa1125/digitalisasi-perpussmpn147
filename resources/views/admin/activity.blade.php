@extends('layouts.admin')

@section('title', 'Activity')
@section('page-title', 'Aktivitas Siswa')

@section('content')
<section class="p-4 md:p-10">
    <!-- TOP BAR WITH FILTERS -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 md:p-6 mb-6">
        <form method="GET" action="{{ route('admin.activity') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input 
                            type="text" 
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nama siswa atau aktivitas" 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        >
                    </div>
                </div>

                <!-- Activity Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Aktivitas</label>
                    <select name="type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Semua Aktivitas</option>
                        <option value="login" {{ request('type') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('type') == 'logout' ? 'selected' : '' }}>Logout</option>
                        <option value="read_book" {{ request('type') == 'read_book' ? 'selected' : '' }}>Membaca Buku</option>
                        <option value="watch_video" {{ request('type') == 'watch_video' ? 'selected' : '' }}>Menonton Video</option>
                    </select>
                </div>

                <!-- Student Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Siswa</label>
                    <select name="nis" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">Semua Siswa</option>
                        @foreach($siswaList as $siswa)
                            <option value="{{ $siswa->nis }}" {{ request('nis') == $siswa->nis ? 'selected' : '' }}>
                                {{ $siswa->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input 
                        type="date" 
                        name="date_from"
                        value="{{ request('date_from') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    >
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input 
                        type="date" 
                        name="date_to"
                        value="{{ request('date_to') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                    >
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.activity') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium transition-colors text-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- STATISTICS CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Total Aktivitas</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_activities'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Login</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['total_logins'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Membaca</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['total_reads'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-600 mb-1">Menonton</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['total_watches'] }}</p>
        </div>
    </div>

    <!-- TABLE SECTION -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 md:px-6 py-4 text-left text-xs md:text-sm font-semibold text-gray-700">Siswa</th>
                        <th class="px-4 md:px-6 py-4 text-left text-xs md:text-sm font-semibold text-gray-700">Aktivitas</th>
                        <th class="px-4 md:px-6 py-4 text-left text-xs md:text-sm font-semibold text-gray-700 hidden lg:table-cell">Deskripsi</th>
                        <th class="px-4 md:px-6 py-4 text-left text-xs md:text-sm font-semibold text-gray-700">Tanggal & Waktu</th>
                        <th class="px-4 md:px-6 py-4 text-left text-xs md:text-sm font-semibold text-gray-700 hidden md:table-cell">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <td class="px-4 md:px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img 
                                    src="https://ui-avatars.com/api/?name={{ urlencode($activity->siswa->nama_lengkap ?? 'Unknown') }}&background=4F46E5&color=fff" 
                                    alt="" 
                                    class="w-10 h-10 rounded-full flex-shrink-0"
                                >
                                <div class="min-w-0">
                                    <p class="font-medium text-sm text-gray-800 truncate">
                                        {{ $activity->siswa->nama_lengkap ?? 'Unknown' }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">{{ $activity->nis }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 md:px-6 py-4">
                            @if($activity->activity_type == 'login')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span class="hidden sm:inline">Login</span>
                                </span>
                            @elseif($activity->activity_type == 'logout')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span class="hidden sm:inline">Logout</span>
                                </span>
                            @elseif($activity->activity_type == 'read_book')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    <i class="fas fa-book"></i>
                                    <span class="hidden sm:inline">Baca Buku</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">
                                    <i class="fas fa-video"></i>
                                    <span class="hidden sm:inline">Nonton Video</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-4 md:px-6 py-4 text-sm text-gray-700 hidden lg:table-cell">
                            <p class="line-clamp-2">{{ $activity->description ?? '-' }}</p>
                        </td>
                        <td class="px-4 md:px-6 py-4 text-xs md:text-sm text-gray-700">
                            <p class="font-medium">{{ $activity->created_at->format('d M Y') }}</p>
                            <p class="text-gray-500">{{ $activity->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-4 md:px-6 py-4 text-xs text-gray-600 hidden md:table-cell">
                            {{ $activity->ip_address ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 block text-gray-300"></i>
                            Tidak ada aktivitas ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if($activities->hasPages())
        <div class="px-4 md:px-6 py-4 border-t border-gray-200">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
</section>
@endsection