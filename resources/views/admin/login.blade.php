<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&display=swap" rel="stylesheet">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
  <style>
    .font-heading{font-family:'Roboto Slab',serif}
    [x-cloak] { display: none !important; }
  </style>
</head>
<body class="relative min-h-screen overflow-hidden flex items-center justify-center" style="background:#4884A8;">
  <svg
    class="absolute left-0 top-0 w-full h-full pointer-events-none z-0"
    viewBox="0 0 1440 900"
    preserveAspectRatio="none"
    aria-hidden="true"
  >
    <!-- Bagian atas -->
    <path
      d="M0 0 H1440 V340 Q1100 430 720 340 Q350 250 0 340 Z"
      fill="#5491B0"
    />
    <!-- Bagian bawah -->
    <path
      d="M0 340 Q350 250 720 340 Q1100 430 1440 340 V900 H0 Z"
      fill="#4884A8"
    />
    <!-- Garis lengkung -->
    <path
      d="M0 340 Q350 250 720 340 Q1100 430 1440 340"
      fill="none"
      stroke="white"
      stroke-width="4"
      stroke-opacity="0.4"
      stroke-linecap="round"
    />
  </svg>

  <!-- Modal Error -->
  <div x-data="{modalIsOpen: false}" 
      @if ($errors->any())
      x-init="modalIsOpen = true"
      @endif
  >
    <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen" x-on:keydown.esc.window="modalIsOpen = false" x-on:click.self="modalIsOpen = false" class="fixed inset-0 z-30 flex items-end justify-center bg-black/20 p-4 pb-8 backdrop-blur-md sm:items-center lg:p-8" role="dialog" aria-modal="true" aria-labelledby="errorModalTitle">
        <!-- Modal Dialog -->
        <div x-show="modalIsOpen" x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity" x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100" class="flex max-w-lg flex-col gap-4 overflow-hidden rounded-lg border border-red-200 bg-white shadow-xl">
            <!-- Dialog Header -->
            <div class="flex items-center justify-between border-b border-red-100 bg-red-50 p-4">
                <h3 id="errorModalTitle" class="font-semibold tracking-wide text-red-900">Username atau Password Salah</h3>
                <button x-on:click="modalIsOpen = false" aria-label="close modal" class="text-red-900 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="1.4" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <!-- Dialog Body -->
            <div class="px-4 py-8">
                <div class="flex items-start gap-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                  <p class="text-gray-700">Mohon periksa kembali username dan password Anda.</p>
                </div>
            </div>
            <!-- Dialog Footer -->
            <div class="flex justify-end border-t border-red-100 bg-red-50 p-4">
                <button x-on:click="modalIsOpen = false" type="button" class="whitespace-nowrap rounded-md bg-red-600 px-4 py-2 text-center text-sm font-medium tracking-wide text-white transition hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 active:bg-red-800">
                  Tutup
                </button>
            </div>
        </div>
    </div>
  </div>

  <div class="relative z-10 w-full max-w-md px-6">
    <h1 class="text-4xl font-heading text-white text-center mb-12">Dashboard Admin</h1>
    <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
      @csrf
      <div>
        <label for="username" class="block text-white text-sm mb-2">Username</label>
        <input name="username" id="username" type="text" value="{{ old('username') }}"
               class="w-full px-4 py-3 rounded-md bg-white/10 border border-white/40 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/60"
               required />
      </div>
      <div>
        <label for="password" class="block text-white text-sm mb-2">Password</label>
        <div class="relative">
          <input name="password" id="password" type="password"
                 class="w-full px-4 py-3 rounded-md bg-white/10 border border-white/40 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/60"
                 required />
          <button type="button" onclick="togglePassword()"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-white/90">
            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </button>
        </div>
      </div>
      <button type="submit"
              class="w-full py-3 rounded-md bg-[#F5F0E8] text-[#2f627d] font-medium hover:bg-[#f5f0e8]/90 shadow">
        Masuk
      </button>
    </form>
  </div>
  <script>
    function togglePassword() {
      const inp = document.getElementById('password');
      const icon = document.getElementById('eyeIcon');
      if (inp.type === 'password') {
        inp.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
      } else {
        inp.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
      }
    }

    // ✅ ULTIMATE HISTORY CONTROL - Prevent any back navigation
    (function() {
      // Method 1: Replace current history state
      if (window.history && window.history.replaceState) {
        window.history.replaceState(null, '', window.location.href);
      }

      // Method 2: Disable popstate (back button)
      window.addEventListener('popstate', function(event) {
        // Immediately push forward again
        window.history.pushState(null, '', window.location.href);
        
        // Optional: Show alert
        // alert('Gunakan tombol logout untuk keluar dari sistem');
      });

      // Method 3: Clear forward/back on page load
      window.addEventListener('load', function() {
        // Push a dummy state to prevent going back
        window.history.pushState(null, '', window.location.href);
      });

      // Method 4: Detect if page loaded from cache (bfcache)
      window.addEventListener('pageshow', function(event) {
        // If page loaded from cache, reload it
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
          window.location.reload();
        }
      });

      // Method 5: Check if user came from logout (session flag)
      @if(session('clear_history'))
      // Clear browser history by replacing state multiple times
      for (let i = 0; i < 10; i++) {
        window.history.pushState(null, '', window.location.href);
      }
      
      // Then replace to clean state
      window.history.replaceState(null, '', window.location.href);
      @endif
    })();
  </script>
</body>
</html>
