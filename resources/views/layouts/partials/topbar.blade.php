<header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 sticky top-0 z-30 px-4 md:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-pln-blue/10 hover:text-pln-blue flex items-center justify-center text-lg transition-all duration-200 active:scale-95">
                ☰
            </button>
            <div>
                <h1 class="text-lg md:text-xl font-bold text-text-primary tracking-tight">@yield('title', 'Dashboard')</h1>
                <div class="flex items-center gap-2 mt-0.5">
                    @hasSection('subtitle')
                        <p class="text-xs text-text-muted">@yield('subtitle')</p>
                    @endif
                    @hasSection('breadcrumb')
                        @hasSection('subtitle')<span class="text-slate-300 text-xs">•</span>@endif
                        <div class="text-xs text-slate-500 font-medium flex items-center gap-1">
                            @yield('breadcrumb')
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="hidden lg:flex flex-col items-end mr-2">
                <span id="dynamic-greeting" class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Loading...</span>
                <span id="live-clock" class="text-sm font-bold text-pln-blue tabular-nums">00:00:00</span>
            </div>
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-lg">
                <span class="text-xs text-slate-400">👤</span>
                <span class="text-xs font-medium text-slate-600">{{ auth()->user()->username ?? 'Admin' }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm bg-red-50 text-red-600 hover:bg-red-500 hover:text-white border border-red-100 hover:border-red-500 transition-all delete-form">
                    🚪 Logout
                </button>
            </form>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateClock() {
        const now = new Date();
        const hours = now.getHours();
        let greeting = 'Selamat Malam';
        
        if (hours >= 5 && hours < 11) greeting = 'Selamat Pagi';
        else if (hours >= 11 && hours < 15) greeting = 'Selamat Siang';
        else if (hours >= 15 && hours < 18) greeting = 'Selamat Sore';

        document.getElementById('dynamic-greeting').textContent = greeting;
        document.getElementById('live-clock').textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    
    updateClock();
    setInterval(updateClock, 1000);
});
</script>
