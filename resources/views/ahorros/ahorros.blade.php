@extends('layouts.app')

@push('styles')
<!-- Bootstrap-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <h2 class="mb-4">Gestión de Ahorros</h2>

    <!-- Botón para abrir modal de crear ahorro -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createAhorroModal">
        <i class="bi bi-plus-circle"></i> Nuevo Ahorro
    </button>

    <!-- Tabla con DataTables -->
    <table id="ahorrosTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Concepto</th>
                <th>Monto Meta</th>
                <th>Total Acumulado</th>
                <th>Porcentaje Avance</th>
                <th>Fecha Creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ahorros as $ahorro)
                <tr>
                    <td>{{ $ahorro->id }}</td>
                    <td>{{ $ahorro->concepto }}</td>
                    <td>${{ number_format($ahorro->monto_meta, 2) }}</td>
                    <td>${{ number_format($ahorro->total_acumulado, 2) }}</td>
                    <td>
                        @php
                            $porcentaje = $ahorro->monto_meta > 0 
                                ? round(($ahorro->total_acumulado / $ahorro->monto_meta) * 100, 2)
                                : 0;
                        @endphp
                        {{ $porcentaje }}%
                    </td>
                    <td>{{ $ahorro->created_at->format('d/m/Y') }}</td>
                    <td>
                        <!-- Botón Ver -->
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#showAhorroModal{{ $ahorro->id }}">
                            <i class="bi bi-eye"></i>
                        </button>

                        <!-- Botón Editar -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAhorroModal{{ $ahorro->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <!-- Botón Eliminar -->
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAhorroModal{{ $ahorro->id }}">
                            <i class="bi bi-trash"></i>
                        </button>

                        <!-- Botón Aportes -->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#indexAporteModal{{ $ahorro->id }}">
                            <i class="bi bi-wallet2"></i>
                        </button>
                    </td>
                </tr>

                <!-- Incluir modales de cada ahorro -->
                @include('ahorros.partials.showModal', ['ahorro' => $ahorro])
                @include('ahorros.partials.editModal', ['ahorro' => $ahorro])
                @include('ahorros.partials.deleteModal', ['ahorro' => $ahorro])
                @include('ahorros.indexAporteModal', ['ahorro' => $ahorro])
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal de Crear Ahorro -->
@include('ahorros.partials.createModal')

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#ahorrosTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            responsive: true
        });
    });
</script>
@endpush
