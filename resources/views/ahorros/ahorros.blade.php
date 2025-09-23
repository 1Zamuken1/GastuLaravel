@extends('layouts.app')

@push('styles')
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <h1 class="mb-4">Ahorros</h1>

    <!-- Filtros -->
    <div class="d-flex align-items-center gap-3 mb-3">
        <select id="statusFilter" class="form-select w-auto">
            <option value="all">Todos</option>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
            <option value="Completado">Completado</option>
        </select>
        <input type="text" id="searchInput" class="form-control w-25" placeholder="Buscar...">
        <div id="paginationInfo" class="ms-auto text-muted small"></div>
        <button id="addAhorroBtn" class="btn btn-warning">
            <i class="fa fa-plus"></i> Nuevo Ahorro
        </button>
    </div>

    <!-- Tabla de Ahorros -->
    <div class="table-responsive">
        <table id="savingTable" class="table table-hover align-middle">
            <thead class="table-warning">
                <tr>
                    <th>ID</th>
                    <th>Concepto</th>
                    <th>Monto Meta</th>
                    <th>Total Acumulado</th>
                    <th>Avance (%)</th>
                    <th>Frecuencia</th>
                    <th>Fecha Meta</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                    <th>Aportes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ahorros as $ahorro)
                <tr 
                    data-id="{{ $ahorro->ahorro_meta_id }}"
                    data-descripcion="{{ $ahorro->descripcion }}"
                    data-fecha_meta="{{ $ahorro->fecha_meta }}"
                    data-fecha_creacion="{{ $ahorro->fecha_creacion->format('d/m/Y') }}"
                    data-cantidad_cuotas="{{ $ahorro->cantidad_cuotas }}"
                    data-concepto-id="{{ $ahorro->concepto_id }}">
                    <td>{{ $ahorro->id }}</td>
                    <td>{{ $ahorro->concepto }}</td>
                    <td>${{ number_format($ahorro->monto_meta, 2) }}</td>
                    <td>${{ number_format($ahorro->total_acumulado ?? 0, 2) }}</td>
                    <td>{{ $ahorro->avance ?? '0' }}%</td>
                    <td>{{ $ahorro->frecuencia }}</td>
                    <td>{{ $ahorro->fecha_meta->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $ahorro->estado === 'Activo' ? 'success' : ($ahorro->estado === 'Inactivo' ? 'secondary' : 'warning') }}">
                            {{ $ahorro->estado }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning view-ahorro-btn">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-warning edit-ahorro-btn" data-update-url="{{ route('ahorros.update', ['id' => $ahorro->ahorro_meta_id]) }}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <form action="{{ route('ahorros.destroy', $ahorro->ahorro_meta_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este ahorro?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning aporte-btn">
                            <i class="fa fa-coins"></i> Aportes
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- MODALES -->
@include('ahorros.partials.createModal')
@include('ahorros.partials.showModal')
@include('ahorros.partials.editModal')
@include('ahorros.partials.indexAporteModal')


@push('scripts')
    <!-- Bootstrap JS (bundle incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Archivos JS -->
    @vite(['resources/js/ahorros.js', 'resources/js/ahorros-datatable.js'])
@endpush
@endsection

