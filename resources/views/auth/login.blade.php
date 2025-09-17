<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Iniciar Sesión</title>
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-while text-center">
                    <h4>Iniciar Sesión</h4>
                </div>
                <div class="card-body">
                    
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
                            <input type="email" name="correo" class="form-control" value="{{ old('correo') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Ingresar</button>
                      </form>
                </div>
                <div class="card-footer text-center">
                    ¿No tienes cuenta? <a href="{{ route('registro.form') }}">Regístrate</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>