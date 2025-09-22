@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    @vite('resources/css/Dashboard.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="Principal">
            <!-- ESTADÍSTICAS PRINCIPALES -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div>
                            <div class="stat-number" id="ingresos-stat">${{ number_format($totalIngresos) }}</div>
                            <div class="stat-label">Total Ingresos</div>
                        </div>
                        <div class="stat-icon ingresos">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        {{-- <div>
                            <div class="stat-number" id="egresos-stat">${{ number_format($totalEgresos) }}</div>
                            <div class="stat-label">Total Egresos</div>
                        </div> --}}
                        <div class="stat-icon egresos">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        {{-- <div>
                            <div class="stat-number" id="saldo-stat">${{ number_format($saldoNeto) }}</div>
                            <div class="stat-label">Saldo Neto</div>
                        </div> --}}
                        <div class="stat-icon saldo">
                            <i class="bi bi-calculator"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        {{-- <div>
                            <div class="stat-number" id="ahorros-stat">${{ number_format($totalAhorros) }}</div>
                            <div class="stat-label">Total Ahorros</div>
                        </div> --}}
                        <div class="stat-icon ahorros">
                            <i class="bi bi-piggy-bank"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRÁFICOS PRINCIPALES -->
            <div class="charts-section">
                <div class="chart-container">
                    <div class="chart-title">Balance Mensual</div>
                    <div id="chartBalance"></div>
                </div>
                {{-- <div class="chart-container">
                    <div class="chart-title">Distribución de Ahorros</div>
                    <div id="chartAhorros"></div>
                </div> --}}
            </div>

            <!-- BOTONES DE CONTROL -->
            <div class="controls-section">
                <button type="button" class="control-btn">
                    <i class="bi bi-piggy-bank"></i> Gestionar Ahorros
                </button>
                <button type="button" class="control-btn">
                    <i class="bi bi-arrow-up-circle"></i> Agregar Ingresos
                </button>
                <button type="button" class="control-btn">
                    <i class="bi bi-arrow-down-circle"></i> Registrar Egresos
                </button>
            </div>

            <!-- GRÁFICO DE GASTOS -->
            <div class="chart-container" style="margin-bottom: 30px;">
                <div class="chart-title">Gastos por Categoría</div>
                <div id="treemap-gastos"></div>
            </div>

            <!-- ALERTAS Y RECORDATORIOS -->
            <div class="alerts-section">
                <div class="alert-card">
                    <div class="alert-header">
                        <div class="alert-icon warning">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="alert-title">Alerta de Gastos</div>
                    </div>
                    <div class="alert-content" id="alerta">
                        Cargando alertas...
                    </div>
                </div>
                <div class="alert-card">
                    <div class="alert-header">
                        <div class="alert-icon info">
                            <i class="bi bi-lightbulb"></i>
                        </div>
                        <div class="alert-title">Recomendación de Ahorro</div>
                    </div>
                    <div class="alert-content" id="recordatorio">
                        Cargando recomendaciones...
                    </div>
                </div>
                <div class="alert-card">
                    <div class="alert-header">
                        <div class="alert-icon success">
                            <i class="bi bi-chat-heart"></i>
                        </div>
                        <div class="alert-title">Mensaje Motivacional</div>
                    </div>
                    <div class="alert-content" id="mensaje">
                        Cargando mensaje...
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @vite('resources/js/Dashboard.js')
@endpush