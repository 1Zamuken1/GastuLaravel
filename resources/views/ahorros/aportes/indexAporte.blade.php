@extends('layouts.app')

@push('styles')
<!-- Bootstrap-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <h1 class="mb-4">Aportes de: {{ $meta->concepto }}</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <a href="{{ route('ahorros.index') }}" class="btn btn-secondary mb-3"> Volver a Mis Ahorros</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Monto</th>
                <th>Fecha Registro</th>
                <th>Aportar Cuota</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($aportes as $aporte)
                <tr>
                    <td>{{ $aporte->aporte_ahorro_id }}</td>
                    <td>{{ number_format($aporte->monto, 0, ',', '.') }}</td>
                    <td>{{ $aporte->fecha_registro->format('d/m/Y') }}</td>

                    <!-- Botón Aportar Cuota -->
                    <td>
                        <form action="{{ route('aportes.pagarCuota', $aporte->aporte_ahorro_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-sm"
                                @if(!$aporte->fecha_registro->isToday()) disabled @endif
                                onclick="return confirm('¿Deseas aportar esta cuota?')">
                                 Aportar Cuota
                            </button>
                        </form>
                    </td>

                    <!-- Acciones -->
                    <td>
                        <a href="{{ route('aportes.show', $aporte->aporte_ahorro_id) }}" class="btn btn-info btn-sm mb-1">👁 </a>
                        <a href="{{ route('aportes.edit', $aporte->aporte_ahorro_id) }}" class="btn btn-warning btn-sm mb-1">✏ </a>

                        <form action="{{ route('aportes.destroy', $aporte->aporte_ahorro_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mb-1"
                                onclick="return confirm('¿Seguro que deseas eliminar este aporte?')">🗑 </button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay aportes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
