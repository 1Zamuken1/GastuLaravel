
<header class="admin-header bg-white shadow-sm py-2 px-4 d-flex align-items-center justify-content-between">
  <div class="d-flex align-items-center">
    <span class="fw-bold text-primary fs-4">Panel de Administración</span>
  </div>
  <div class="d-flex align-items-center">
    <span class="me-3 text-secondary fw-bold">
      <i class="fa fa-user-circle me-1"></i>
      {{ Auth::user()->nombre ?? '' }}
    </span>
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
      @csrf
      <button type="submit" class="btn btn-outline-danger btn-sm">
        <i class="fa fa-sign-out-alt"></i> Salir
      </button>
    </form>
  </div>
</header>

<nav class="side-nav" id="nav-bar">
  <div class="side-nav-header">
    <div class="side-nav-brand">
      <span class="side-nav-logo">
        {{-- Usamos asset() para las imágenes --}}
        <img src="{{ asset('icons/gastu_logo.svg') }}" alt="Logo Gastu" class="gastu-logo"/>
      </span>
      <span class="side-nav-title">Gastu</span>
    </div>
    <hr class="side-nav-separator" />
  </div>

  <div class="side-nav-main">
    <div class="side-nav-top-group">
      <button class="side-nav-menu-btn" id="side-nav-toggle">
        <span class="side-nav-menu-icon">
          <img src="{{ asset('icons/barras.svg') }}" alt="Menú" width="22" height="22" />
        </span>
        <span class="side-nav-text">Menú</span>
      </button>

      <ul class="side-nav-list">      
        <li class="sidebar-item">
            <a href="{{ route('admin.usuarios.index') }}">
                <i class="fa fa-users"></i> Usuarios
            </a>
        </li>

    <li class="sidebar-item">
  <a href="{{ route('admin.conceptoIngresos.index') }}">
        <span class="side-nav-icon"><img src="{{ asset('icons/ingresos.svg') }}" alt="Ingresos" /></span>
        Ingresos</a>
    </li> 

    <li class="sidebar-item">
  <a href="{{ route('admin.conceptosEgresos.index') }}"><span class="side-nav-icon"><img src="{{ asset('icons/egresos.svg') }}" alt="Egresos" /></span>
      Egresos</a>
    </li>
    {{-- <a href="{{ route('ayuda') }}" class="side-nav-footer-link" data-page="ayuda"> --}}
      {{-- <span class="side-nav-icon"><img src="{{ asset('icons/pregunta.svg') }}" alt="Ayuda" /></span> 
      <span class="side-nav-text">Ayuda</span>
    </a>--}}
    {{-- <a href="{{ route('perfil') }}" class="side-nav-footer-link" data-page="perfil"> --}}
      {{-- <span class="side-nav-icon"><img src="{{ asset('icons/perfil.svg') }}" alt="Perfil" /></span> 
      <span class="side-nav-text">Perfil</span>
    </a>
    --}}
  </div> 
</nav>
