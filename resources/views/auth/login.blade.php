<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/login.css'])
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg rounded-4">
                
                <!-- Header -->
                <div class="card-header text-center rounded-top-4">
                    <h4 class="mb-0">Iniciar Sesión</h4>
                </div>

                <div class="card-body p-4">
                    <!-- Mostrar errores -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulario -->
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" name="correo" id="correo" class="form-control" value="{{ old('correo') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Ingresar</button>
                    </form>
                </div>

                <div class="card-footer text-center rounded-bottom-4">
                    ¿No tienes cuenta? <a href="{{ route('registro.form') }}" class="fw-bold text-info">Regístrate</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
