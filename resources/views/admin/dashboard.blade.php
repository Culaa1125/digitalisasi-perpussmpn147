@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')

<div x-data="dashboardData()">
    <!-- WELCOME SECTION -->
    <section class="border-b border-gray-200 px-4 md:px-10 py-6 md:py-10">
        <h1 class="text-2xl md:text-3xl font-medium text-gray-800">
            Selamat Datang Di Dashboard Admin SMPN 147 JAKARTA
        </h1>
    </section>

    <!-- STATISTICS CARDS -->
    <section class="px-4 md:px-10 py-6 md:py-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <!-- Total Siswa -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Total Siswa</p>
                        <h3 class="text-3xl font-bold">{{ $stats['total_siswa'] }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Konten -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Total Konten</p>
                        <h3 class="text-3xl font-bold">{{ $stats['total_konten'] }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Siswa Aktif Bulan Ini -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Aktif Bulan Ini</p>
                        <h3 class="text-3xl font-bold">{{ $stats['active_students_month'] }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-user-check text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Approval -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm mb-1">Pending Konten</p>
                        <h3 class="text-3xl font-bold">{{ $stats['pending_konten'] }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-full p-3">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHART SECTION -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <h2 class="text-xl font-semibold text-gray-800">Aktivitas Login Siswa</h2>
                <div class="flex gap-2 w-full sm:w-auto">
                    <button @click="chartPeriod = 'day'; updateChart()" 
                            :disabled="isLoading"
                            :class="chartPeriod === 'day' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex-1 sm:flex-none disabled:opacity-50">
                        <span x-show="!isLoading || chartPeriod !== 'day'">30 Hari</span>
                        <span x-show="isLoading && chartPeriod === 'day'">Loading...</span>
                    </button>
                    <button @click="chartPeriod = 'week'; updateChart()" 
                            :disabled="isLoading"
                            :class="chartPeriod === 'week' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex-1 sm:flex-none disabled:opacity-50">
                        <span x-show="!isLoading || chartPeriod !== 'week'">8 Minggu</span>
                        <span x-show="isLoading && chartPeriod === 'week'">Loading...</span>
                    </button>
                    <button @click="chartPeriod = 'month'; updateChart()" 
                            :disabled="isLoading"
                            :class="chartPeriod === 'month' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex-1 sm:flex-none disabled:opacity-50">
                        <span x-show="!isLoading || chartPeriod !== 'month'">12 Bulan</span>
                        <span x-show="isLoading && chartPeriod === 'month'">Loading...</span>
                    </button>
                </div>
            </div>
            <div class="h-64 md:h-80">
                <canvas id="loginChart"></canvas>
            </div>
        </div>

        <!-- MAIN FEATURE CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
            <!-- Card 1: Melihat Aktifitas Siswa -->
            <a href="{{ route('admin.activity') }}" class="bg-white border border-gray-200 rounded-xl p-6 md:p-8 hover:shadow-lg hover:border-blue-500 transition-all hover:-translate-y-1 cursor-pointer block no-underline">
                <div class="flex gap-4 md:gap-6">
                    <div class="text-3xl md:text-4xl text-blue-500 flex-shrink-0">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <h2 class="text-base md:text-lg font-semibold text-gray-800 mb-2">
                            Melihat Aktivitas Siswa
                        </h2>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Admin bisa melihat aktivitas login, membaca, menonton dari siswa
                        </p>
                    </div>
                </div>
            </a>

            <!-- Card 2: Mengelola Reviews -->
            <a href="{{ route('admin.reviews.index') }}" class="bg-white border border-gray-200 rounded-xl p-6 md:p-8 hover:shadow-lg hover:border-blue-500 transition-all hover:-translate-y-1 cursor-pointer block no-underline">
                <div class="flex gap-4 md:gap-6">
                    <div class="text-3xl md:text-4xl text-blue-500 flex-shrink-0">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <h2 class="text-base md:text-lg font-semibold text-gray-800 mb-2">
                            Kelola Review & Komentar
                        </h2>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Pantau dan kelola review serta komentar dari siswa
                        </p>
                    </div>
                </div>
            </a>

            <!-- Card 3: Menambahkan Karya -->
            <a href="{{ route('admin.karya.index') }}" class="bg-white border border-gray-200 rounded-xl p-6 md:p-8 hover:shadow-lg hover:border-blue-500 transition-all hover:-translate-y-1 cursor-pointer block no-underline">
                <div class="flex gap-4 md:gap-6">
                    <div class="text-3xl md:text-4xl text-blue-500 flex-shrink-0">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <h2 class="text-base md:text-lg font-semibold text-gray-800 mb-2">
                            Menambahkan Karya
                        </h2>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Admin bisa menambahkan dan menyetujui karya siswa
                        </p>
                    </div>
                </div>
            </a>

            <!-- Card 4: Melihat Data Siswa -->
            <a href="{{ route('admin.siswa.index') }}" class="bg-white border border-gray-200 rounded-xl p-6 md:p-8 hover:shadow-lg hover:border-blue-500 transition-all hover:-translate-y-1 cursor-pointer block no-underline">
                <div class="flex gap-4 md:gap-6">
                    <div class="text-3xl md:text-4xl text-blue-500 flex-shrink-0">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h2 class="text-base md:text-lg font-semibold text-gray-800 mb-2">
                            Melihat Data Siswa
                        </h2>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Admin bisa melihat data siswa yang sudah terdaftar
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- RECENT ACTIVITIES & NEGATIVE REVIEWS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
            <!-- Recent Activities -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-blue-500"></i>
                    Aktivitas Terbaru
                </h3>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($recentActivities as $activity)
                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex-shrink-0">
                            @if($activity->activity_type == 'login')
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt text-green-600"></i>
                                </div>
                            @elseif($activity->activity_type == 'logout')
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-sign-out-alt text-gray-600"></i>
                                </div>
                            @elseif($activity->activity_type == 'read_book')
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-book text-blue-600"></i>
                                </div>
                            @else
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-video text-purple-600"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">
                                {{ $activity->siswa->nama_lengkap ?? 'Unknown' }}
                            </p>
                            <p class="text-xs text-gray-600 truncate">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8">Belum ada aktivitas</p>
                    @endforelse
                </div>
            </div>

            <!-- Negative Reviews -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-orange-500"></i>
                    Review Negatif Terbaru
                </h3>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($negativeReviews as $review)
                    <div class="p-3 bg-red-50 rounded-lg border border-red-100">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">
                                    {{ $review->siswa->nama_lengkap ?? 'Unknown' }}
                                </p>
                                <p class="text-xs text-gray-600 truncate">
                                    {{ $review->konten->judul ?? 'Unknown' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1 flex-shrink-0">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-gray-700 line-clamp-2">{{ $review->comment }}</p>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                            <a href="{{ route('admin.reviews.index') }}" class="text-xs text-blue-600 hover:text-blue-700">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8">Tidak ada review negatif</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardData', () => ({
        chartPeriod: 'month',
        chart: null,
        isLoading: false,

        init() {
            this.$nextTick(() => {
                this.initChart();
            });
        },

        initChart() {
            const ctx = document.getElementById('loginChart');
            if (!ctx) {
                return;
            }
            
            const monthlyData = @json($monthlyLoginData ?? []);
            
            // Destroy existing chart if exists
            if (this.chart) {
                this.chart.destroy();
            }
            
            this.chart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => item.month || item.label || ''),
                    datasets: [{
                        label: 'Siswa Aktif',
                        data: monthlyData.map(item => item.count || item.value || 0),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
            
        },

        async updateChart() {

            // Prevent multiple simultaneous calls
            if (this.isLoading) {
                return;
            }

            this.isLoading = true;

            try {
                const url = `{{ route('admin.activity.chart') }}?period=${this.chartPeriod}`;
                
                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (!Array.isArray(data) || data.length === 0) {
                    throw new Error('Invalid data format');
                }
                
                // Get canvas element
                const ctx = document.getElementById('loginChart');
                if (!ctx) {
                    throw new Error('Canvas not found');
                }
                
                // Destroy old chart completely
                if (this.chart) {
                    this.chart.destroy();
                    this.chart = null;
                }
                
                // Create new chart with new data
                this.chart = new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.map(item => item.label || ''),
                        datasets: [{
                            label: 'Siswa Aktif',
                            data: data.map(item => item.value || 0),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 0
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                }
                            }
                        }
                    }
                });
                
                
            } catch (error) {
                alert('Gagal memuat data: ' + error.message);
            } finally {
                // Small delay before allowing next update
                setTimeout(() => {
                    this.isLoading = false;
                }, 500);
            }
        }
    }));
});
</script>
@endpush