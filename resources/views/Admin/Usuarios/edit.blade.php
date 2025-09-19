@extends('layouts.appAdmin')

@section('content')
<div class="container mt-4">
    {{-- Título --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-warning mb-0">
            <i class="bi bi-pencil-fill me-2"></i> Editar Usuario
        </h2>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary shadow-sm px-3">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    {{-- Errores --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Ups!</strong> Hubo algunos problemas:
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <form action="{{ route('usuarios.update', $usuario->usuario_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control shadow-sm"
                           value="{{ old('nombre', $usuario->nombre) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Correo</label>
                    <input type="email" name="correo" class="form-control shadow-sm"
                           value="{{ old('correo', $usuario->correo) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Teléfono</label>
                    <input type="text" name="telefono" class="form-control shadow-sm"
                           value="{{ old('telefono', $usuario->telefono) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Rol</label>
                    <select name="rol_id" class="form-select shadow-sm" required>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->rol_id }}"
                                {{ old('rol_id', $usuario->rol_id) == $rol->rol_id ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Estado --}}
                <div class="mb-3">
                    <label for="activo" class="form-label">Estado</label>
                    <select name="activo" class="form-select" required>
                        <option value="1" {{ old('activo', $usuario->activo) == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('activo', $usuario->activo) == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nueva Contraseña (opcional)</label>
                    <input type="password" name="password" class="form-control shadow-sm">
                    <small class="text-muted">Déjalo vacío si no quieres cambiarla.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control shadow-sm">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-warning shadow-sm px-4">
                        <i class="bi bi-save me-1"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
