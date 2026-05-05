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
    <!-- UI Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <style>
        /* TomSelect override to match our forms */
        .ts-control { border-radius: 0.75rem !important; border: 1px solid #e2e8f0 !important; padding: 0.625rem 1rem !important; font-size: 0.875rem !important; min-height: 42px !important; }
        .ts-control.focus { border-color: #3b82f6 !important; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important; }
    </style>
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
            {{-- Validation Errors --}}
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
                sidebar.classList.toggle('lg:translate-x-0');
                sidebar.classList.toggle('lg:-translate-x-full');
                main.classList.toggle('lg:ml-[270px]');
                main.classList.toggle('lg:ml-0');
            }
        }

        function toggleSubmenu(name) {
            const submenu = document.getElementById('submenu-' + name);
            const arrow = document.getElementById('arrow-' + name);
            
            if (submenu.classList.contains('open')) {
                submenu.classList.remove('open');
                submenu.style.maxHeight = '0px';
                arrow.classList.remove('rotate-90');
            } else {
                submenu.classList.add('open');
                submenu.style.maxHeight = submenu.firstElementChild.scrollHeight + 'px';
                arrow.classList.add('rotate-90');
                
                setTimeout(() => {
                    if(submenu.classList.contains('open')) {
                        submenu.style.maxHeight = submenu.firstElementChild.scrollHeight + 'px';
                    }
                }, 150);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Auto-open active submenu
            const activeItem = document.querySelector('.submenu-link.active');
            if (activeItem) {
                const submenu = activeItem.closest('.submenu-wrapper');
                if (submenu) {
                    submenu.classList.add('open');
                    submenu.style.maxHeight = submenu.firstElementChild.scrollHeight + 'px';
                    const arrowId = submenu.id.replace('submenu-', 'arrow-');
                    const arrow = document.getElementById(arrowId);
                    if (arrow) arrow.classList.add('rotate-90');
                }
            }

            // Global UI Initializations
            // 1. Tooltips
            tippy('[data-tippy-content]', {
                theme: 'light-border',
                animation: 'shift-away',
                arrow: true,
            });

            // 2. TomSelect
            document.querySelectorAll('select.form-select').forEach((el) => {
                new TomSelect(el, {
                    create: false,
                    sortField: { field: "text", direction: "asc" }
                });
            });

            // 3. Flatpickr
            flatpickr('input[type="datetime-local"], .flatpickr-datetime', {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true
            });
            flatpickr('input[type="date"], .flatpickr-date', {
                enableTime: false,
                dateFormat: "Y-m-d"
            });

            // 4. SweetAlert2 Toast for Session Messages
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

            // 5. SweetAlert2 Confirmation for .delete-form
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
