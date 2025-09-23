@extends('layouts.appAdmin')

@section('content')
<div class="container mt-4">
    {{-- Título --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-info mb-0">
            <i class="bi bi-person-badge-fill me-2"></i> Detalles del Usuario
        </h2>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary shadow-sm px-3">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    {{-- Card de detalles --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $usuario->usuario_id }}</p>
            <p><strong>Nombre:</strong> {{ $usuario->nombre }}</p>
            <p><strong>Correo:</strong> {{ $usuario->correo }}</p>
            <p><strong>Teléfono:</strong> {{ $usuario->telefono ?? 'No registrado' }}</p>
            <p><strong>Rol:</strong> {{ $usuario->rol->nombre ?? 'Sin rol' }}</p>
            <p>
                <strong>Estado:</strong>
                @if($usuario->activo)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-danger">Inactivo</span>
                @endif
            </p>
            <p><strong>Fecha de Registro:</strong> {{ $usuario->fecha_registro->format('d/m/Y H:i') }}</p>
        </div>

        {{-- Footer con acciones --}}
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('admin.usuarios.edit', $usuario->usuario_id) }}" class="btn btn-warning shadow-sm">
                <i class="bi bi-pencil-fill"></i> Editar
            </a>
            <form action="{{ route('admin.usuarios.destroy', $usuario->usuario_id) }}" method="POST" 
                  onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger shadow-sm">
                    <i class="bi bi-trash-fill"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
