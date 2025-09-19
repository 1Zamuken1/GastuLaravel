@extends('layouts.app')

@push('styles')
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Mis Ahorros</h1>

    {{-- Botón Crear Nuevo Ahorro --}}
    <a href="{{ route('ahorros.create') }}" class="btn btn-warning mb-3">
        <i class="fas fa-plus"></i> Crear Ahorro
    </a>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Tabla scrollable --}}
    <div class="table-responsive" style="max-height:600px; overflow-y:auto;">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-warning text-dark sticky-top">
                <tr>
                    <th>ID</th>
                    <th>Concepto</th>
                    <th>Monto Meta</th>
                    <th>Total Acumulado</th>
                    <th>% Avance</th>
                    <th>Aportes</th>
                    <th>Fecha Meta</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ahorros as $ahorro)
                    @php
                        $total = $ahorro->total_acumulado ?? 0;
                        $meta = $ahorro->monto_meta ?? 0;
                        $porcentaje = $meta ? round(($total / $meta) * 100) : 0;
                    @endphp
                    <tr>
                        <td>{{ $ahorro->ahorro_meta_id }}</td>
                        <td>{{ $ahorro->concepto }}</td>
                        <td>${{ number_format($meta, 0, ',', '.') }}</td>
                        <td>${{ number_format($total, 0, ',', '.') }}</td>
                        <td>{{ $porcentaje }}%</td>
                        <td class="text-center">
                            <a href="{{ route('aportes.index', $ahorro->ahorro_meta_id) }}" class="btn btn-sm btn-outline-warning" title="Ver aportes">
                                <i class="fas fa-coins"></i>
                            </a>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($ahorro->fecha_meta)->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge {{ $ahorro->activa ? 'bg-success' : 'bg-secondary' }}">
                                {{ $ahorro->activa ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('ahorros.show', $ahorro->ahorro_meta_id) }}" class="btn btn-info btn-sm mb-1" title="Ver"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('ahorros.edit', $ahorro->ahorro_meta_id) }}" class="btn btn-warning btn-sm mb-1" title="Editar"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('ahorros.destroy', $ahorro->ahorro_meta_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mb-1"
                                    onclick="return confirm('¿Seguro que deseas eliminar este ahorro?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No hay ahorros registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
