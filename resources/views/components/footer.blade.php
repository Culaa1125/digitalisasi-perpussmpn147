<footer class="bg-gray-900 text-white mt-16 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- About -->
            <div>
                <h3 class="text-lg font-bold mb-4">SMPN 147 Jakarta</h3>
                <p class="text-gray-400 text-sm">
                    Perpustakaan digital untuk mendukung pembelajaran siswa dengan koleksi buku dan video edukatif.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('bukudigital.index') }}" class="text-gray-400 hover:text-blue-400">Buku Digital</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-blue-400">Film</a></li>
                    <li><a href="{{ route('siswa.loginsiswa') }}" class="text-gray-400 hover:text-blue-400">Login Siswa</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-lg font-bold mb-4">Contact</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><i class="fas fa-map-marker-alt mr-2"></i>Jakarta, Indonesia</li>
                    <li><i class="fas fa-envelope mr-2"></i>info@smpn147.sch.id</li>
                    <li><i class="fas fa-phone mr-2"></i>(021) 1234-5678</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-6 text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} SMPN 147 Jakarta. All rights reserved.</p>
        </div>
    </div>
</footer>