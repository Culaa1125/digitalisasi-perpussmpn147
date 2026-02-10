<nav class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg" x-data="{ mobileMenuOpen: false, searchOpen: false }">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo & Brand -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('assets/SMPN147.png') }}" alt="SMPN 147 Logo" class="w-10 h-10 sm:w-12 sm:h-12 object-contain">
                <h1 class="text-base sm:text-xl font-bold">SMPN 147 JAKARTA</h1>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex space-x-6">
                <a href="{{ route('bukudigital.index') }}" class="hover:text-blue-200 transition {{ request()->routeIs('bukudigital.*') ? 'text-yellow-300 border-b-2 border-yellow-300' : '' }}">
                    <i class="fas fa-book mr-2"></i>Buku Digital
                </a>
                <a href="{{ route('film.index') }}" class="hover:text-blue-200 transition {{ request()->routeIs('film.*') ? 'text-yellow-300 border-b-2 border-yellow-300' : '' }}">
                    <i class="fas fa-film mr-2"></i>Film
                </a>
                @auth('siswa')
                    <a href="{{ route('siswa.home') }}" class="hover:text-blue-200 transition {{ request()->routeIs('siswa.home') ? 'text-yellow-300 border-b-2 border-yellow-300' : '' }}">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                @endauth
            </div>

            <!-- Right Actions -->
            <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- Search Bar Desktop -->
                <div class="hidden md:block">
                    <form action="{{ route('bukudigital.search') }}" method="GET" class="flex items-center bg-blue-500 rounded-lg px-4 py-2">
                        <input type="text" name="q" placeholder="Search..." class="bg-transparent text-white placeholder-blue-200 outline-none w-40" value="{{ request('q') }}">
                        <button type="submit">
                            <i class="fas fa-search text-blue-200 ml-2"></i>
                        </button>
                    </form>
                </div>

                <!-- Search Toggle Mobile -->
                <button @click="searchOpen = !searchOpen" class="md:hidden p-2 hover:bg-blue-700 rounded-lg transition">
                    <i class="fas fa-search text-xl"></i>
                </button>

                <!-- User Menu -->
                @auth('siswa')
                    <div x-data="{ userMenuOpen: false }" class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2 p-2 hover:bg-blue-700 rounded-lg transition">
                            <i class="fas fa-user-circle text-2xl"></i>
                            <span class="hidden sm:inline">{{ Auth::guard('siswa')->user()->nama_lengkap }}</span>
                        </button>
                        
                        <!-- Dropdown -->
                        <div x-show="userMenuOpen" 
                             @click.away="userMenuOpen = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50">
                            <a href="{{ route('siswa.home') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-50">
                                <i class="fas fa-home mr-2"></i>Home
                            </a>
                            <form action="{{ route('siswa.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-blue-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('siswa.loginsiswa') }}" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        <span class="hidden sm:inline">Login</span>
                    </a>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 hover:bg-blue-700 rounded-lg transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Search -->
        <div x-show="searchOpen" 
             x-transition
             class="md:hidden mt-4">
            <form action="{{ route('bukudigital.search') }}" method="GET" class="flex items-center bg-blue-500 rounded-lg px-4 py-2">
                <input type="text" name="q" placeholder="Search..." class="bg-transparent text-white placeholder-blue-200 outline-none flex-1" value="{{ request('q') }}">
                <button type="submit">
                    <i class="fas fa-search text-blue-200 ml-2"></i>
                </button>
            </form>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition
             class="md:hidden mt-4 space-y-2">
            <a href="{{ route('bukudigital.index') }}" class="block py-2 px-4 hover:bg-blue-700 rounded {{ request()->routeIs('bukudigital.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-book mr-2"></i>Buku Digital
            </a>
            <a href="{{ route('film.index') }}" class="block py-2 px-4 hover:bg-blue-700 rounded {{ request()->routeIs('film.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-film mr-2"></i>Film
            </a>
            @auth('siswa')
                <a href="{{ route('siswa.home') }}" class="block py-2 px-4 hover:bg-blue-700 rounded {{ request()->routeIs('siswa.home') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            @endauth
        </div>
    </div>
</nav>