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
    @vite('resources/css/chatbot-financiero.css')

    {{-- Espacio para estilos específicos de cada vista --}}
    @stack('styles')

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="layout">
        {{-- Sidebar --}}
        @include('components.sidenav')
        {{-- @include('components.header') --}}

        {{-- Contenido principal --}}
        <main class="page-content">
            @yield('content')
        </main>
    </div>

    {{-- jQuery y DataTables (si lo quieres global) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<!-- JSZip (para Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- pdfmake (para PDF) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    {{-- Script global --}}
    @vite('resources/js/nav-bar.js')


    {{-- Scripts específicos de cada vista --}}
    @stack('scripts')
</body>
</html>
