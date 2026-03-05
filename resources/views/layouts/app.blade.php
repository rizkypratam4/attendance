<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Attendance Admin Portal') – AttendancePro</title>
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

    @stack('scripts')
</body>
</html>