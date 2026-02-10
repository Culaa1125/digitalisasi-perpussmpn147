<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1920, initial-scale=1.0">
    <title>Daftar - SMPN 147 Jakarta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        @media screen and (max-width: 1920px) {
            body { zoom: calc(100vw / 1920); }
        }
        @media screen and (min-width: 1921px) {
            body { zoom: 1; }
        }
        html, body { overflow-x: hidden; }
        
        .form-scroll::-webkit-scrollbar { width: 6px; }
        .form-scroll::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .form-scroll::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 10px; }
        .form-scroll::-webkit-scrollbar-thumb:hover { background: #2563eb; }
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

            <!-- FORM REGISTER -->
            <div class="w-full max-w-md flex items-center">
                <div class="bg-white rounded-3xl shadow-2xl p-8 w-full border-2 border-black">
                    <h2 class="text-center text-3xl font-bold text-gray-800 mb-6">DAFTAR</h2>
                    
                    <!-- ALERTS -->
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- FORM -->
                    <div class="form-scroll max-h-[520px] overflow-y-auto pr-2">
                        <form method="POST" action="{{ route('siswa.daftarsiswa') }}" class="space-y-3.5">
                            @csrf
                            
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Nama Lengkap :
                                </label>
                                <input type="text" 
                                       name="nama_lengkap" 
                                       value="{{ old('nama_lengkap') }}"
                                       placeholder="Masukkan Nama Lengkap" 
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition text-gray-600 text-sm @error('nama_lengkap') border-red-500 bg-red-50 @enderror"
                                       required>
                                @error('nama_lengkap')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A 2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    Email :
                                </label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="info@exsample.com" 
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition text-gray-600 text-sm @error('email') border-red-500 bg-red-50 @enderror"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    Tanggal Lahir :
                                </label>
                                <input type="date" 
                                       name="tanggal_lahir" 
                                       value="{{ old('tanggal_lahir') }}"
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition text-gray-600 text-sm @error('tanggal_lahir') border-red-500 bg-red-50 @enderror">
                                @error('tanggal_lahir')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Jenis Kelamin :
                                </label>
                                <select name="jk" 
                                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition text-gray-600 cursor-pointer text-sm @error('jk') border-red-500 bg-red-50 @enderror">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jk')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor HP -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    Nomor HP :
                                </label>
                                <input type="tel" 
                                       name="no_hp" 
                                       value="{{ old('no_hp') }}"
                                       placeholder="08xx-xxxx-xxxx" 
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition text-gray-600 text-sm @error('no_hp') border-red-500 bg-red-50 @enderror">
                                @error('no_hp')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Password :
                                </label>
                                <input type="password" 
                                       name="password" 
                                       placeholder="Minimal 6 karakter" 
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition text-gray-600 text-sm @error('password') border-red-500 bg-red-50 @enderror"
                                       required>
                                @error('password')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Konfirmasi Password :
                                </label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       placeholder="Ulangi password" 
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition text-gray-600 text-sm">
                            </div>

                            <!-- NIS -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    NIS Siswa :
                                </label>
                                <input type="text" 
                                       name="nis" 
                                       value="{{ old('nis') }}"
                                       placeholder="Masukkan NIS" 
                                       class="w-full px-4 py-2.5 bg-blue-50 border border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-blue-100 transition text-gray-600 text-sm @error('nis') border-red-500 bg-red-50 @enderror"
                                       required>
                                @error('nis')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kelas (Tambahan) -->
                            <div>
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Kelas :
                                </label>
                                <input type="text" 
                                       name="kelas" 
                                       value="{{ old('kelas') }}"
                                       placeholder="Contoh: 7A, 8B" 
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition text-gray-600 text-sm @error('kelas') border-red-500 bg-red-50 @enderror"
                                       required>
                                @error('kelas')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" 
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-xl shadow-lg transition duration-300 transform hover:scale-[1.02] active:scale-[0.98] mt-4">
                                Daftar
                            </button>

                            <div class="text-center text-sm text-gray-600 pt-2 pb-2">
                                Sudah punya akun? 
                                <a href="{{ route('siswa.loginsiswa') }}" class="font-semibold text-gray-800 hover:text-blue-600 transition">
                                    Masuk Sekarang
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
