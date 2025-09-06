<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Gastu')</title>

    {{-- Iconos de FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    {{-- DataTables CSS (si lo necesitas globalmente) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"/>

    {{-- Estilos globales con Vite --}}
    @vite('resources/css/nav-bar.css')
    @vite('resources/js/nav-bar.js')
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/css/header.css')

    {{-- Espacio para estilos específicos de cada vista --}}
    @stack('styles')

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="layout">
        {{-- Sidebar --}}
        @include('components.sidenav')
        @include('components.header')

        {{-- Contenido principal --}}
        <main class="page-content">
            @yield('content')
        </main>
    </div>

    {{-- jQuery y DataTables (si lo quieres global) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    {{-- Script global --}}
    @vite('resources/js/nav-bar.js')

    {{-- Scripts específicos de cada vista --}}
    @stack('scripts')
</body>
</html>
