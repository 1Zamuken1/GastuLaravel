<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/uso.css')
  <title>Uso - GASTU</title>
  <link rel="icon" href="{{ asset('icons/gastu_logo.svg') }}" type="image/x-icon">
</head>


<body>
  <header class="header">
    <div>
      <a href="{{ url('/') }}">Página principal</a>
      <a href="{{ url('/funciones') }}">Funciones</a>
    </div>
    <div>
      <a href="{{ url('/login') }}">Inicia Sesión</a>
      <a href="{{ url('/registro') }}">Crear Cuenta</a>
    </div>
  </header>

  <div class="scroll">
    <div class="inicio">
      <h1>¿Qué puedes hacer con GASTU?</h1>
      <p>Descubre todo lo que nuestra plataforma tiene para ofrecerte</p>
    </div>

    <main class="pasos-grid">
      <div class="paso">
        <img src="{{ asset('images/investment.png') }}" alt="plata">
        <p>Registra tus ingresos y egresos</p>
        <p>Campos validados, categorías, fechas.</p>
      </div>
      <div class="paso">
        <img src="{{ asset('images/sum.png') }}" alt="plata">
        <p>Clasifica tus gastos</p>
        <p>Usa etiquetas y prioridades</p>
      </div>
      <div class="paso">
        <img src="{{ asset('images/graficas.png') }}" alt="grafico">
        <p>Revisa tu estado financiero</p>
        <p>Visualiza gráficos y métricas clave</p>
      </div>
      <div class="paso">
        <img src="{{ asset('images/ia.png') }}" alt="ia">
        <p>Recibe recomendaciones con IA</p>
        <p>Alerta de exceso de gasto</p>
      </div>
      <div class="paso">
        <img src="{{ asset('images/metas.png') }}" alt="plata">
        <p>Crea metas de ahorro personalizadas</p>
        <p>Puedes ingresar montos según tu ritmo</p>
      </div>
      <div class="paso">
        <img src="{{ asset('images/exportar.png') }}" alt="plata">
        <p>Descarga reportes PDF/EXCEL</p>
        <p>Ideal para llevar un control externo</p>
      </div>
    </main>

    <footer class="footer">
      <div class="col">
        <h3>Legal</h3>
        <ul>
          <li>Términos y condiciones</li>
          <li>Política de privacidad</li>
          <li>Política de cookies</li>
        </ul>
      </div>
      <div class="col">
        <h3>Ayuda</h3>
        <ul>
          <li>Centro de ayuda</li>
          <li>Contáctanos</li>
          <li>gastuOficial@gmail.com</li>
        </ul>
      </div>
    </footer>
  </div>
</body>
</html>
