@extends('layouts.appAdmin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="bi bi-cash-stack me-2"></i> Gestión de Conceptos de Egreso
        </h2>
        <a href="{{ route('admin.conceptosEgresos.create') }}" class="btn btn-success shadow-sm px-3">
            <i class="bi bi-plus-circle-fill me-1"></i> Nuevo Concepto
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="bi bi-list-ul me-2"></i> Lista de Conceptos
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th style="width: 200px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($conceptosEgresos as $concepto)
                            <tr class="text-center">
                                <td>
                                    <span class="badge bg-secondary">{{ $concepto->concepto_egreso_id }}</span>
                                </td>
                                <td class="fw-semibold">{{ $concepto->nombre }}</td>
                                <td style="word-wrap: break-word; max-width: 350px;">
                                    {{ $concepto->descripcion }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.conceptosEgresos.show', $concepto->concepto_egreso_id) }}" 
                                        class="btn btn-sm btn-outline-primary shadow-sm me-1">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('admin.conceptosEgresos.edit', $concepto->concepto_egreso_id) }}" 
                                        class="btn btn-sm btn-outline-warning shadow-sm me-1">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.conceptosEgresos.destroy', $concepto->concepto_egreso_id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm"
                                            onclick="return confirm('¿Seguro que deseas eliminar este concepto?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-exclamation-circle"></i> No hay conceptos registrados
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
