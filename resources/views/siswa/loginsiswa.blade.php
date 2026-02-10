<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1920, initial-scale=1.0">
    <title>Login - SMPN 147 Jakarta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- ✅ Anti-cache headers -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <style>
        @media screen and (max-width: 1920px) {
            body { zoom: calc(100vw / 1920); }
        }
        @media screen and (min-width: 1921px) {
            body { zoom: 1; }
        }
        html, body { overflow-x: hidden; }
    </style>
</head>
<body class="bg-gradient-to-b from-blue-200 via-blue-100 to-blue-300 min-h-screen overflow-hidden">
    <div class="relative min-h-screen flex items-center justify-center">
        <!-- Header Logo -->
        <div class="absolute top-4 left-6 flex items-center gap-3 z-30">
            <img src="{{ asset('Assets/SMPN147.png') }}" alt="Logo SMPN 147" class="w-11 h-11">
            <h1 class="text-xl font-bold text-gray-800">SMPN 147 JAKARTA</h1>
        </div>

        <!-- Assets -->
        <img src="{{ asset('Assets/pohon.png') }}" alt="Pohon Kiri" class="absolute left-0 top-20 w-[400px] h-auto z-10">
        <img src="{{ asset('Assets/Pohon2.png') }}" alt="Pohon Kanan" class="absolute right-5 bottom-3 w-[33.33%] h-[95vh] z-10 object-contain">
        <img src="{{ asset('Assets/wave.png') }}" alt="Wave" class="absolute bottom-0 left-0 w-full z-20 translate-y-20 h-auto object-cover">

        <!-- Konten -->
        <div class="flex items-stretch justify-between z-20 w-full max-w-7xl px-8 gap-12">
            <!-- Gambar Siswa -->
            <div class="relative flex-1 flex justify-center items-center overflow-hidden">
                <div class="absolute -top-2 left-10 text-yellow-400 text-xl animate-pulse">✨</div>
                <div class="absolute top-4 left-2 text-yellow-300 text-sm">✨</div>
                <div class="absolute top-0 left-28 text-yellow-400 text-base">✨</div>
                <div class="absolute top-8 right-8 text-yellow-300 text-xl animate-pulse">✨</div>
                <div class="absolute top-2 right-2 text-yellow-400 text-sm">✨</div>
                <div class="absolute bottom-16 left-0 text-yellow-300 text-base">✨</div>
                <div class="absolute bottom-24 left-16 text-yellow-400 text-sm animate-pulse">✨</div>
                <img src="{{ asset('Assets/Siswa.png') }}" alt="Siswa SMPN 147" class="absolute left-10 bottom-5 w-[600px] h-auto z-10 translate-y-20 -translate-x-20">
            </div>

            <!-- FORM LOGIN -->
            <div class="w-full max-w-md flex items-center">
                <div class="bg-gradient-to-br from-white/95 to-gray-50/95 backdrop-blur-sm rounded-3xl shadow-2xl p-10 w-full border-2 border-black">
                    <h2 class="text-center text-3xl font-bold text-gray-800 mb-8">MASUK</h2>
                    
                    <!-- ✅ Logout Success Message -->
                    @if(session('logout_success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-5">
                        {{ session('logout_success') }}
                    </div>
                    @endif
                    
                    <!-- ALERTS -->
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-5">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-5">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- FORM -->
                    <form method="POST" action="{{ route('siswa.loginsiswa') }}" class="space-y-5">
                        @csrf
                        
                        <!-- NIS -->
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                NIS Siswa :
                            </label>
                            <input type="text" 
                                   name="nis" 
                                   value="{{ old('nis') }}"
                                   placeholder="Masukkan NIS" 
                                   class="w-full px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-blue-100 transition text-gray-600 @error('nis') border-red-500 @enderror"
                                   required>
                            @error('nis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PASSWORD -->
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                Password :
                            </label>
                            <input type="password" 
                                   name="password" 
                                   placeholder="password" 
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition text-gray-600 @error('password') border-red-500 @enderror"
                                   required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="text-left pt-1">
                            <a href="#" class="text-sm text-gray-600 hover:text-blue-600 hover:underline transition">
                                Lupa Password?
                            </a>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3.5 rounded-xl shadow-lg transition duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
                            Log in
                        </button>

                        <div class="text-center text-sm text-gray-600 pt-2">
                            Belum punya akun? 
                            <a href="{{ route('siswa.daftarsiswa') }}" class="font-semibold text-gray-800 hover:text-blue-600 transition">
                                Daftar Sekarang
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ JavaScript Prevent Back Button -->
    <script>
        (function() {
            // Method 1: Replace history state
            if (window.history && window.history.replaceState) {
                window.history.replaceState(null, '', window.location.href);
            }

            // Method 2: Prevent popstate (back button)
            window.addEventListener('popstate', function(event) {
                window.history.pushState(null, '', window.location.href);
            });

            // Method 3: Push state on load
            window.addEventListener('load', function() {
                window.history.pushState(null, '', window.location.href);
            });

            // Method 4: Detect if page loaded from cache
            window.addEventListener('pageshow', function(event) {
                if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                    window.location.reload();
                }
            });

            // Method 5: Clear history if came from logout
            @if(session('logout_success'))
            for (let i = 0; i < 10; i++) {
                window.history.pushState(null, '', window.location.href);
            }
            window.history.replaceState(null, '', window.location.href);
            @endif
        })();
    </script>
</body>
</html>