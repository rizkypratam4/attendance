<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Attendance Admin Portal') – AttendancePro</title>
    {{-- Apply saved theme immediately to prevent flash --}}
    <script>
        (function () {
            var t = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
     <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="flex h-screen overflow-hidden">
    <div id="overlay" onclick="closeMob()"></div>
    <x-sidebar :active="$active ?? 'dashboard'" />
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
        <x-header :title="$pageTitle ?? ''" />
        <main class="main-pad flex-1 overflow-auto p-4 lg:p-6 xl:p-8 space-y-4 lg:space-y-6">
            @yield('content')
        </main>
    </div>

    <x-ui.modal id="mProfile" title="Setting Profile">
        @include('components.modals.profile')
    </x-ui.modal>

    <x-ui.modal id="mPassword" title="Change Password">
        @include('components.modals.password')
    </x-ui.modal>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/maps.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @include('sweetalert::alert')

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session("success") }}',
                    timer: 3000,
                    showConfirmButton: false,
                    background: '#1e1b2e',
                    color: '#e2e8f0'
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session("error") }}',
                    timer: 3000,
                    showConfirmButton: false,
                    background: '#1e1b2e',
                    color: '#e2e8f0'
                });
            });
        </script>
    @endif

    @stack('scripts')

    <script>
    // ── Global Search ──────────────────────────────────────────────
    (function () {
        const input    = document.getElementById('globalSearchInput');
        const dropdown = document.getElementById('globalSearchDropdown');
        if (!input || !dropdown) return;

        const searchUrl = '{{ route("search.global") }}';
        let timer;

        const icons = {
            user:     '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
            building: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 22V12h6v10M3 9h18"/></svg>',
            clock:    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            calendar: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
            shield:   '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        };

        const groupColors = {
            'Karyawan':   '#a78bfa',
            'Department': '#34d399',
            'Shift Code': '#fb923c',
            'Attendance': '#60a5fa',
            'User':       '#f472b6',
        };

        function render(results) {
            if (!results.length) {
                dropdown.innerHTML = '<div style="padding:20px;text-align:center;font-size:13px;color:var(--text-3)">Tidak ada hasil ditemukan</div>';
                dropdown.style.display = 'block';
                return;
            }

            // Group by category
            const grouped = {};
            results.forEach(r => {
                if (!grouped[r.group]) grouped[r.group] = [];
                grouped[r.group].push(r);
            });

            let html = '';
            Object.entries(grouped).forEach(([group, items]) => {
                const color = groupColors[group] || '#a78bfa';
                html += `<div style="padding:8px 12px 4px;font-size:10.5px;font-weight:700;color:${color};letter-spacing:.08em;text-transform:uppercase">${group}</div>`;
                items.forEach(item => {
                    html += `<a href="${item.url}" style="display:flex;align-items:center;gap:10px;padding:9px 12px;text-decoration:none;transition:background .12s;border-radius:8px;margin:0 4px"
                                onmouseover="this.style.background='rgba(124,58,237,.15)'"
                                onmouseout="this.style.background='transparent'">
                        <span style="color:${color};flex-shrink:0">${icons[item.icon] || ''}</span>
                        <div style="min-width:0">
                            <div style="font-size:13px;font-weight:600;color:var(--text-1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${item.label}</div>
                            <div style="font-size:11.5px;color:var(--text-3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${item.sub}</div>
                        </div>
                    </a>`;
                });
            });

            dropdown.innerHTML = html;
            dropdown.style.display = 'block';
        }

        input.addEventListener('input', function () {
            clearTimeout(timer);
            const q = this.value.trim();
            if (q.length < 2) { dropdown.style.display = 'none'; return; }

            timer = setTimeout(() => {
                fetch(`${searchUrl}?q=${encodeURIComponent(q)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(render)
                .catch(() => { dropdown.style.display = 'none'; });
            }, 250);
        });

        // Tutup saat klik di luar
        document.addEventListener('click', function (e) {
            if (!input.closest('.hdr-search').contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        // Keyboard navigation
        input.addEventListener('keydown', function (e) {
            const items = dropdown.querySelectorAll('a');
            const active = dropdown.querySelector('a:focus');
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                (active ? active.nextElementSibling : items[0])?.focus();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                (active ? active.previousElementSibling : items[items.length - 1])?.focus();
            } else if (e.key === 'Escape') {
                dropdown.style.display = 'none';
                input.blur();
            }
        });
    })();
    </script>
</body>
</html>