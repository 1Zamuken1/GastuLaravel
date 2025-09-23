@extends('layouts.appAdmin')

@section('content')
<div class="container mt-4">
    {{-- Título y botón --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="bi bi-people-fill me-2"></i> Gestión de Usuarios
        </h2>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-success shadow-sm px-3">
            <i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario
        </a>
    </div>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabla de usuarios --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="bi bi-list-ul me-2"></i> Lista de Usuarios
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th style="width: 220px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr class="text-center">
                                <td>
                                    <span class="badge bg-secondary">{{ $usuario->usuario_id }}</span>
                                </td>
                                <td class="fw-semibold">{{ $usuario->nombre }}</td>
                                <td style="word-wrap: break-word; max-width: 220px;">
                                {{ $usuario->correo }}
                                </td>
                                <td>{{ $usuario->telefono ?? '-' }}</td>
                                <td>
                                    <span class="badge text-dark">
                                        {{ $usuario->rol->nombre ?? 'Sin rol' }}
                                    </span>
                                </td>
                                <td>
                                    @if($usuario->activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.usuarios.show', $usuario->usuario_id) }}" 
                                        class="btn btn-sm btn-outline-primary shadow-sm me-1">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('admin.usuarios.edit', $usuario->usuario_id) }}" 
                                        class="btn btn-sm btn-outline-warning shadow-sm me-1">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.usuarios.destroy', $usuario->usuario_id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm"
                                            onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-exclamation-circle"></i> No hay usuarios registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
