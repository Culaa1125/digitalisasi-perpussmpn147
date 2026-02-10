<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMPN 147 JAKARTA - Perpustakaan Digital')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="{{ asset('js/session-manager.js') }}" defer></script>
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fadeIn { animation: fadeIn 0.2s; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-900">
    <!-- Background Hero -->
    <div class="fixed top-0 left-0 w-full h-screen -z-10" 
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    </div>
    <div class="fixed top-0 left-0 w-full h-screen bg-black/40 -z-10"></div>

    <!-- Header -->
    @include('components.header')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Include Chatbot Component -->
    @auth('siswa')
        @include('components.chatbot')
    @endauth

    <!-- Footer -->
    @include('components.footer')

    @stack('scripts')

    <script>
    window.sessionConfig = {
        lifetime: {{ config('session.lifetime') }},
        extendUrl: '{{ route('siswa.extend-session') }}',
        logoutUrl: '{{ route('siswa.logout') }}',
        userType: 'siswa'
    };
</script>
</body>
</html>