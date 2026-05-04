<header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 sticky top-0 z-30 px-4 md:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-pln-blue/10 hover:text-pln-blue flex items-center justify-center text-lg transition-all duration-200 active:scale-95">
                ☰
            </button>
            <div>
                <h1 class="text-lg md:text-xl font-bold text-text-primary tracking-tight">@yield('title', 'Dashboard')</h1>
                @hasSection('subtitle')
                    <p class="text-xs text-text-muted">@yield('subtitle')</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-lg">
                <span class="text-xs text-slate-400">👤</span>
                <span class="text-xs font-medium text-slate-600">{{ auth()->user()->username }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="return confirm('Yakin ingin logout?')"
                        class="btn btn-sm bg-red-50 text-red-600 hover:bg-red-500 hover:text-white border border-red-100 hover:border-red-500 transition-all">
                    🚪 Logout
                </button>
            </form>
        </div>
    </div>
</header>
