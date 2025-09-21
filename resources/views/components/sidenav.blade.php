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
        <li data-page="dashboard">
          <a href="{{ route('dashboard') }}">
            <span class="side-nav-icon"><img src="{{ asset('icons/casa.svg') }}" alt="dashboard" /></span>
            <span class="side-nav-text">Inicio</span>
          </a>
        </li>
        <li data-page="ingresos">
          <a href="{{ route('ingresos.index') }}">
            <span class="side-nav-icon"><img src="{{ asset('icons/ingresos.svg') }}" alt="Ingresos" /></span>
            <span class="side-nav-text">Ingresos</span>
          </a>
        </li>
        <li data-page="egresos">
          <a href="{{ route('egresos.index') }}">
            <span class="side-nav-icon"><img src="{{ asset('icons/egresos.svg') }}" alt="Egresos" /></span>
            <span class="side-nav-text">Egresos</span>
          </a>
        </li>
        {{-- <li data-page="ahorros">
           <a href="{{ route('ahorros.index') }}">
            <span class="side-nav-icon"><img src="{{ asset('icons/ahorro.svg') }}" alt="Ahorro" /></span> 
            <span class="side-nav-text">Ahorro</span>
          </a>
        </li> --}}
        {{-- <li data-page="ahorros">
            <a class="nav-link" href="{{ route('chatbot.index') }}">
            <span class="side-nav-icon"><img src="{{ asset('icons/gastu_logo.svg') }}" alt="Asistente virtual" /></span> 
            <span class="side-nav-text"> Asistente</span>
          </a>
        </li> --}}
        

        <!-- <li data-page="reportes">
          {{-- <a href="{{ route('reportes.index') }}"> --}}
            {{-- <span class="side-nav-icon"><img src="{{ asset('icons/reportes.svg') }}" alt="Reportes" /></span> --}}
            <span class="side-nav-text">Reportes</span>
          </a>
        </li> -->
      </ul>
    </div>
  </div>

  <!-- <div class="side-nav-footer">
    {{-- <a href="{{ route('ayuda') }}" class="side-nav-footer-link" data-page="ayuda"> --}}
      {{-- <span class="side-nav-icon"><img src="{{ asset('icons/pregunta.svg') }}" alt="Ayuda" /></span> --}}
      <span class="side-nav-text">Ayuda</span>
    </a>
    {{-- <a href="{{ route('perfil') }}" class="side-nav-footer-link" data-page="perfil"> --}}
      {{-- <span class="side-nav-icon"><img src="{{ asset('icons/perfil.svg') }}" alt="Perfil" /></span> --}}
      <span class="side-nav-text">Perfil</span>
    </a>
  </div> -->
</nav>
