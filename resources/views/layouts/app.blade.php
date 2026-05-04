<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — PLN UID JATENG DIY</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full bg-surface font-sans text-text-primary antialiased">

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Sidebar Overlay (mobile) --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden backdrop-blur-sm transition-opacity" onclick="toggleSidebar()"></div>

    {{-- Main Content --}}
    <div id="mainWrapper" class="ml-0 lg:ml-[270px] min-h-screen transition-all duration-300">
        {{-- Topbar --}}
        @include('layouts.partials.topbar')

        {{-- Content --}}
        <main class="p-4 md:p-6 lg:p-8">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success mb-6 animate-fade-in-up" id="flash-success">
                    <span class="text-lg">✅</span>
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-600 hover:text-emerald-800 font-bold text-lg">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error mb-6 animate-fade-in-up" id="flash-error">
                    <span class="text-lg">⚠️</span>
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800 font-bold text-lg">&times;</button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-error mb-6 animate-fade-in-up">
                    <div>
                        <span class="text-lg">⚠️</span>
                        <ul class="mt-1 list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const main = document.getElementById('mainWrapper');

            if (window.innerWidth < 1024) {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            } else {
                sidebar.classList.toggle('-translate-x-full');
                main.classList.toggle('lg:ml-[270px]');
                main.classList.toggle('lg:ml-0');
            }
        }

        function toggleSubmenu(name) {
            const submenu = document.getElementById('submenu-' + name);
            const arrow = document.getElementById('arrow-' + name);
            submenu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-90');
        }

        // Auto-open active submenu
        document.addEventListener('DOMContentLoaded', function() {
            const activeItem = document.querySelector('.submenu-link.active');
            if (activeItem) {
                const submenu = activeItem.closest('.submenu-panel');
                if (submenu) {
                    submenu.classList.remove('hidden');
                    const arrowId = submenu.id.replace('submenu-', 'arrow-');
                    const arrow = document.getElementById(arrowId);
                    if (arrow) arrow.classList.add('rotate-90');
                }
            }

            // Auto-dismiss flash messages
            setTimeout(() => {
                const flash = document.getElementById('flash-success');
                if (flash) flash.style.opacity = '0';
                setTimeout(() => { if (flash) flash.remove(); }, 300);
            }, 4000);
        });
    </script>
    @stack('scripts')
</body>
</html>
