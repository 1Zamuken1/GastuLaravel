<header class="app-header">
    {{-- Icono de usuario + nombre a la izquierda --}}
    <div class="header-left">
        <i class="fa-solid fa-user fa-2x"></i>
        <span class="user-name">Bienvenido, <strong>{{ auth()->user()->nombre ?? 'Usuario' }}</strong></span>
    </div>

    {{-- Icono de campana a la derecha --}}
    <div class="header-right">
        <a href="#" class="notification">
            <i class="fa-solid fa-bell"></i>
            <span class="badge">3</span>
        </a>
    </div>
</header>
