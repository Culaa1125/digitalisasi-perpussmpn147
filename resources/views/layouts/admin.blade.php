<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Dashboard Admin') - SMPN 147 JAKARTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/session-manager.js') }}" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50"
    x-data="{ 
        profileModal: false, 
        editProfileModal: false, 
        settingsModal: false,
        modalAddKarya: false,
        searchTerm: '',
        fileName: '',
        showUpdateModal: false,
        showDeleteModal: false,
        selectedSiswa: null
    }"
>
    <div class="flex min-h-screen">
        <!-- SIDEBAR -->
        @include('layouts.partials.sidebar')

        <!-- MAIN CONTENT -->
        <main class="ml-60 flex-1">
            <!-- HEADER -->
            @include('layouts.partials.header')

            <!-- PAGE CONTENT -->
            @yield('content')
        </main>
    </div>

    <!-- MODALS -->
    @include('layouts.partials.modals')

    @stack('scripts')

    <script>
        window.sessionConfig = {
            lifetime: {{ config('session.lifetime') }},
            extendUrl: '{{ route('admin.extend-session') }}',
            logoutUrl: '{{ route('admin.logout') }}',
            userType: 'admin'
        };
    </script>
</body>
</html>