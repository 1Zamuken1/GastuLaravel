<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite('resources/css/funciones.css')
  <title>Funciones - GASTU</title>
  <link rel="icon" href="{{ asset('icons/gastu_logo.svg') }}" type="image/x-icon">
</head>

<body>
  <header class="header">
    <div class="header-left">
      <a href="{{ url('/') }}">Página principal</a>
      <a href="{{ url('/uso') }}">Especificaciones</a>
    </div>
    <div class="header-right">
      <a href="{{ url('/login') }}">Inicia Sesión</a>
      <a href="{{ url('/registro') }}">Crear Cuenta</a>
    </div>
  </header>

  <div class="inicio">
    <h1>¿Qué puedes hacer con GASTU?</h1>
    <p>Descubre todo lo que nuestra plataforma tiene para ofrecerte</p>
  </div>

  <main class="funciones-grid">
    <div class="funcion">
      <img src="{{ asset('images/salary.png') }}" alt="ingresos">
      <p>Registro y control de ingresos y egresos</p>
    </div>
    <div class="funcion">
      <img src="{{ asset('images/reportes.png') }}" alt="reportes">
      <p>Visualización de reportes y gráficos</p>
    </div>
    <div class="funcion">
      <img src="{{ asset('images/budget.png') }}" alt="egresos">
      <p>Categorización de gastos personalizada</p>
    </div>
    <div class="funcion">
      <img src="{{ asset('images/ahorro.png') }}" alt="ahorro">
      <p>Simulaciones de presupuesto</p>
    </div>
    <div class="funcion">
      <img src="{{ asset('images/ai.png') }}" alt="ia">
      <p>IA que da recomendaciones</p>
    </div>
    <div class="funcion">
      <img src="{{ asset('images/file.png') }}" alt="exportar">
      <p>Exportación en PDF y Excel</p>
    </div>
    <div class="funcion">
      <img src="{{ asset('images/chart.png') }}" alt="grafica">
      <p>Comparación de periodos financieros</p>
    </div>
    <div class="funcion">
      <img src="{{ asset('images/computer.png') }}" alt="chat">
      <p>Chatbot de asistencia financiera</p>
    </div>
    <div class="funcion">
      <img src="{{ asset('images/delete.png') }}" alt="delete">
      <p>Configuración y eliminación de cuenta</p>
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
        <li>Preguntas frecuentes</li>
        <li>Contáctanos</li>
        <li>gastuOficial@gmail.com</li>
      </ul>
    </div>
  </footer>
</body>
</html>
