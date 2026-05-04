@php
    $user = auth()->user();
    $activeMenu = $activeMenu ?? '';
@endphp

<aside id="sidebar" class="fixed left-0 top-0 w-[270px] h-screen bg-gradient-to-b from-sidebar-light to-sidebar-dark text-white z-50 overflow-y-auto transition-transform duration-300 -translate-x-full lg:translate-x-0 shadow-2xl">

    {{-- Logo & Branding --}}
    <div class="p-5 bg-black/20 border-b border-white/10">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-11 h-11 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center shadow-lg overflow-hidden p-1">
                <img src="{{ asset('images/Logo_pln.png') }}" alt="PLN" class="w-full h-full object-contain">
            </div>
            <div>
                <h2 class="text-base font-bold tracking-tight">PLN UID</h2>
                <p class="text-xs text-sky-300 font-medium">JATENG & DIY</p>
            </div>
        </div>
        <div class="bg-white/10 backdrop-blur rounded-xl p-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-pln-blue to-sky-400 flex items-center justify-center text-sm font-bold shadow">
                    {{ strtoupper(substr($user->username, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">{{ $user->username }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide {{ $user->isAdmin() ? 'bg-pln-yellow/20 text-pln-yellow' : 'bg-emerald-400/20 text-emerald-300' }}">
                        {{ $user->isAdmin() ? '👑 Admin' : '👤 User' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="py-3 px-3 space-y-1">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                  {{ $activeMenu === 'dashboard' ? 'bg-pln-blue/20 text-white border-l-4 border-pln-blue-light shadow-lg shadow-pln-blue/10' : 'text-slate-300 hover:bg-white/5 hover:text-white border-l-4 border-transparent' }}">
            <span class="text-lg">📊</span>
            <span>Dashboard</span>
        </a>

        {{-- IT Support Section --}}
        @php
            $itMenus = [
                'data-jadwal' => ['icon' => '📅', 'text' => 'Jadwal Ruangan'],
                'booking-zoom' => ['icon' => '🎥', 'text' => 'Booking Zoom'],
                'data-server' => ['icon' => '🖥️', 'text' => 'Data Server'],
                'it-support-jateng' => ['icon' => '👨‍💻', 'text' => 'IT Support Jateng'],
                'stock-perangkat' => ['icon' => '📦', 'text' => 'Stock Perangkat'],
                'perangkat-aplikasi' => ['icon' => '🗂️', 'text' => 'Perangkat Aplikasi'],
            ];
            $visibleIt = collect($itMenus)->filter(fn($_, $slug) => $user->hasPermission($slug))->count();
        @endphp

        @if($visibleIt > 0)
            <div class="pt-4 pb-1 px-4">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">IT Support</p>
            </div>

            <button onclick="toggleSubmenu('it-support')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-all">
                <span class="text-lg">💻</span>
                <span class="flex-1 text-left">IT Support</span>
                <span id="arrow-it-support" class="text-xs transition-transform duration-200">▸</span>
            </button>

            <div id="submenu-it-support" class="hidden space-y-0.5 ml-3 pl-4 border-l-2 border-white/10">
                @foreach($itMenus as $slug => $menu)
                    @if($user->hasPermission($slug))
                        <a href="{{ route($slug . '.index') }}"
                           class="submenu-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200
                                  {{ $activeMenu === $slug ? 'active text-pln-blue-light bg-pln-blue/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <span>{{ $menu['icon'] }}</span>
                            <span>{{ $menu['text'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Admin Section --}}
        @if($user->isAdmin())
            <div class="pt-4 pb-1 px-4">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Administrator</p>
            </div>

            <button onclick="toggleSubmenu('admin')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-all">
                <span class="text-lg">⚙️</span>
                <span class="flex-1 text-left">Administrator</span>
                <span id="arrow-admin" class="text-xs transition-transform duration-200">▸</span>
            </button>

            <div id="submenu-admin" class="hidden space-y-0.5 ml-3 pl-4 border-l-2 border-white/10">
                <a href="{{ route('admin.users.index') }}" class="submenu-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ $activeMenu === 'master-user' ? 'active text-pln-yellow bg-pln-yellow/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <span>👥</span><span>Master User</span>
                </a>
                <a href="{{ route('admin.ruangan.index') }}" class="submenu-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ $activeMenu === 'master-ruangan' ? 'active text-pln-yellow bg-pln-yellow/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <span>🏢</span><span>Master Ruangan</span>
                </a>
                <a href="{{ route('admin.it-support.index') }}" class="submenu-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ $activeMenu === 'master-it-support' ? 'active text-pln-yellow bg-pln-yellow/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <span>👨‍💻</span><span>Master IT Support</span>
                </a>
                <a href="{{ route('admin.zoom.index') }}" class="submenu-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ $activeMenu === 'master-zoom' ? 'active text-pln-yellow bg-pln-yellow/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <span>🎥</span><span>Master Zoom</span>
                </a>
                <a href="{{ route('admin.master-perangkat-aplikasi.index') }}" class="submenu-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all {{ $activeMenu === 'master-perangkat-aplikasi' ? 'active text-pln-yellow bg-pln-yellow/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <span>🖥️</span><span>Master Perangkat</span>
                </a>
            </div>
        @endif

        {{-- Logout --}}
        <div class="mt-6 pt-4 border-t border-white/10 px-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="return confirm('Yakin ingin logout?')"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-300 hover:bg-red-500/10 hover:text-red-200 transition-all">
                    <span class="text-lg">🚪</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>
