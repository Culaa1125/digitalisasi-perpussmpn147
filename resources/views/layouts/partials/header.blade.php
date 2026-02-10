<header class="bg-white border-b border-gray-200 px-10 py-5 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
    </div>
    <div class="flex items-center gap-4">
        <button class="text-blue-500 text-xl hover:scale-110 transition-transform">
            <i class="fas fa-bell"></i>
        </button>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-2 rounded-lg text-white bg-blue-500 text-sm hover:bg-blue-600 transition-colors">
                <i class="fas fa-sign-out-alt text-sm"></i>
                <span class="font-medium">Log Out</span>
            </button>
        </form>
    </div>
</header>
