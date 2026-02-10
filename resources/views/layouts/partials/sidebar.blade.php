<aside class="w-60 bg-gradient-to-b from-blue-900 to-blue-800 text-white p-6 fixed h-screen overflow-y-auto border-r border-blue-700">
    <!-- LOGO - LINK KE DASHBOARD -->
    <a href="{{ route('admin.dashboard') }}" class="block text-center mb-10 cursor-pointer hover:opacity-80 transition-opacity">
        <div class="w-24 h-24 bg-white rounded-full mx-auto mb-3 flex items-center justify-center shadow-lg">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-red-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                A
            </div>
        </div>
        <h3 class="text-xs font-semibold tracking-tight leading-tight">
            SMPN 147<br>JAKARTA
        </h3>
    </a>

    <!-- NAVIGATION MENU -->
    <nav class="space-y-2 mb-12">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all hover:translate-x-1 
                  {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500 text-white' : 'text-white hover:bg-white/10' }}">
            <i class="fas fa-home w-5"></i>
            <span class="text-sm">Dashboard</span>
        </a>

        <!-- Activity -->
        <a href="{{ route('admin.activity') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all hover:translate-x-1
                  {{ request()->routeIs('admin.activity') ? 'bg-blue-500 text-white' : 'text-white hover:bg-white/10' }}">
            <i class="fas fa-history w-5"></i>
            <span class="text-sm">Activity</span>
            <i class="fas fa-chevron-right w-4 ml-auto text-xs"></i>
        </a>

        <!-- Reviews -->
        <a href="{{ route('admin.reviews.index') }}" 
        class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all hover:translate-x-1
                {{ request()->routeIs('admin.reviews') ? 'bg-blue-500 text-white' : 'text-white hover:bg-white/10' }}">
            <i class="fas fa-star w-5"></i>
            <span class="text-sm">Reviews</span>
        </a>

        <!-- Siswa -->
        <a href="{{ route('admin.siswa.index') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all hover:translate-x-1
                  {{ request()->routeIs('admin.siswa') ? 'bg-blue-500 text-white' : 'text-white hover:bg-white/10' }}">
            <i class="fas fa-users w-5"></i>
            <span class="text-sm">Siswa</span>
        </a>

        <!-- Karya -->
        <a href="{{ route('admin.karya.index') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all hover:translate-x-1
                  {{ request()->routeIs('admin.konten') ? 'bg-blue-500 text-white' : 'text-white hover:bg-white/10' }}">
            <i class="fas fa-building w-5"></i>
            <span class="text-sm">Karya</span>
        </a>
    </nav>

    <!-- SETTINGS -->
    <div class="border-t border-white/20 pt-6 mt-auto">
        <button @click="profileModal = true" class="flex items-center gap-4 px-4 py-3 rounded-lg text-white hover:bg-white/10 transition-all hover:translate-x-1 w-full text-left">
            <i class="fas fa-cog w-5"></i>
            <span class="text-sm">Settings and Profile</span>
        </button>
    </div>
</aside>
