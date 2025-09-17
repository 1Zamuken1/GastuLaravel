@extends('layouts.app')

@section('title', 'Ingresos y Proyecciones')

@push('styles')
    @vite('resources/css/ingresos.css')
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-money-bill-wave"></i>
                <span id="section-title">Ingresos y Proyecciones</span>
            </div>
            <div class="controls">
                <button class="add-btn" id="addIncome">
                    <i class="fas fa-plus"></i> Añadir nuevo
                </button>
            </div>
        </div>

        {{-- Filtros y búsqueda --}}
        <div class="search-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar por concepto, monto, tipo..."/>
            </div>
            <select class="filter" id="statusFilter">
                <option value="all">Todos los estados</option>
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
            </select>
            <select class="filter" id="typeFilter">
                <option value="all">Todos los tipos</option>
                <option value="Ingreso">Ingreso</option>
                <option value="Proyección">Proyección</option>
            </select>
        </div>

        {{-- Tabla --}}
        <div class="records-per-page">
            Mostrar
            <select id="recordsPerPage">
                <option>10</option>
                <option>25</option>
                <option>50</option>
                <option>Todos</option>
            </select>
            registros
        </div>
        <table id="incomeTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($registros as $registro)
                    <tr data-descripcion="{{ $registro['descripcion'] ?? '' }}"
                        data-concepto-id="{{ $registro['concepto_id'] ?? '' }}"
                        data-fecha_fin="{{ $registro['fecha_fin'] ?? '' }}">
                        <td>{{ $registro['id'] }}</td>
                        <td>{{ $registro['concepto'] }}</td>
                        <td>{{ number_format($registro['monto']) }}</td>
                        <td>{{ $registro['tipo'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($registro['fecha'])->format('d/m/Y') }}</td>
                        <td>
                            @if ($registro['tipo'] === 'Ingreso')
                                --
                            @else
                                {{ $registro['estado'] }}
                            @endif
                        </td>
                        <td>
                            {{-- Botón Ver --}}
                            <button class="icon-btn view-btn" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            {{-- Botón Editar --}}
                            <button class="icon-btn edit-btn" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            {{-- Botón Eliminar --}}
                            <button class="icon-btn delete-btn" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay registros</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-info" id="paginationInfo">
            Mostrando 0 de 0 registros
        </div>
    </div>

    {{-- Modales --}}
    @include('ingresos.partials.income-modal', ['conceptoIngresos' => $conceptoIngresos])
    @include('ingresos.partials.concepto-modal', ['conceptoIngresos' => $conceptoIngresos])
    @include('ingresos.partials.delete-modal', ['conceptoIngresos' => $conceptoIngresos])
    @include('ingresos.partials.recurrence-modal', ['conceptoIngresos' => $conceptoIngresos])
@endsection

@push('scripts')
    @vite('resources/js/ingresos.js')
    @vite('resources/js/ingresos-datatable.js')
@endpush
