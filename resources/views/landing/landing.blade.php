<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Compilados por Vite --}}
  @vite(['resources/css/landing.css', 'resources/js/app.js'])

  <title>GASTU</title>
  <link rel="icon" href="{{ asset('icons/gastu_logo.svg') }}" type="image/x-icon">
</head>
<body>
  <header class="header">
    <div class="header-left">
      <a href="{{ url('/funciones') }}">Funciones</a>
      <a href="{{ url('/uso') }}">Especificaciones</a>
    </div>
    <div class="header-right">
      <a href="{{ url('/login') }}">Inicia Sesión</a>
      <a href="{{ url('/registro') }}">Crear Cuenta</a>
    </div>
  </header>

  <div class="hero hero-background">
    <h1>Gestiona tus finanzas sin dolores de cabeza</h1>
    <p>Toma el control de tus finanzas diarias con GASTU. Fácil, intuitivo y hecho para acompañarte en cualquier etapa.</p>
    <a href="{{ url('/login') }}" class="btn-empezar">¡Comienza ya!</a>
  </div>

  <section class="intro">
    {{-- ahora en public/images/ --}}
    <img src="{{ asset('images/1.png') }}" alt="alcancia">
    <div>
      <h2>Tu asistente financiero personal</h2>
      <p>
        GASTU es una plataforma de gestión financiera que te ayuda a registrar ingresos y egastos, 
        controlar tus presupuestos y visualizar tu dinero con claridad. 
        Con un asistente inteligente y un diseño enfocado en la simplicidad, 
        facilitamos tu experiencia financiera.
      </p>
    </div>
  </section>

  <section class="multidispositivo">
    <h2>Multidispositivo</h2>
    <div class="devices">
      {{-- web.png ahora en public/images/ --}}
      <img class="devices-portatil" src="{{ asset('images/web.png') }}" alt="web">
      {{-- app.png ahora en public/images/ --}}
      <img class="devices-cellphone" src="{{ asset('images/app.png') }}" alt="app">
    </div>
  </section>

  <section class="beneficios">
    <h2>Beneficios clave</h2>
    <div class="grid-beneficios">
      <div class="card">Seguimiento de gastos</div>
      <div class="card">Presupuestos inteligentes</div>
      <div class="card">Privacidad garantizada</div>
      <div class="card">Accede desde cualquier dispositivo</div>
    </div>
  </section>

  <footer class="footer">
    <div class="legal">
      <h3>Legal</h3>
      <ul>
        <li>Términos y condiciones</li>
        <li>Política de privacidad</li>
        <li>Política de cookies</li>
      </ul>
    </div>
    <div class="ayuda">
      <h3>Ayuda</h3>
      <ul>
        <li>Preguntas frecuentes</li>
        <li>Contáctanos</li>
        <li>gastuOficial@gmail.com</li>
      </ul>
    </div>
  </footer>
</body>
</html>
