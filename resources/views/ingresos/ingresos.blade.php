<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistema de Gestión Financiera</title>

    {{-- Iconos de FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"/>

    {{-- Tus estilos con Vite --}}
    @vite('resources/css/nav-bar.css')
    @vite('resources/css/ingresos.css')
</head>
<body>
    <div class="layout">
        {{-- Sidebar --}}
        <nav class="side-nav" id="nav-bar"></nav>

        {{-- Contenido principal --}}
        <main class="page-content">
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

                {{-- Tarjetas resumen --}}
                <div class="summary-cards">
    <div class="summary-card">
        <div class="card-label">
            <i class="fas fa-wallet"></i>
            <span>Total Ingresos</span>
        </div>
        <div class="card-value" id="total-income">
            ${{ number_format($totalIngresos, 2) }}
        </div>
    </div>
    <div class="summary-card proyeccion">
        <div class="card-label">
            <i class="fas fa-chart-line"></i>
            <span>Total Proyecciones</span>
        </div>
        <div class="card-value" id="total-projection">
            ${{ number_format($totalProyecciones, 2) }}
        </div>
    </div>
    <div class="summary-card">
        <div class="card-label">
            <i class="fas fa-calendar-check"></i>
            <span>Ingresos este mes</span>
        </div>
        <div class="card-value" id="month-income">
            ${{ number_format($ingresosMes, 2) }}
        </div>
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
                            <tr>
                                <td>{{ $registro['id'] }}</td>
                                <td>{{ $registro['concepto'] }}</td>
                                <td>${{ number_format($registro['monto'], 2) }}</td>
                                <td>{{ $registro['tipo'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($registro['fecha'])->format('d/m/Y') }}</td>
                                <td>{{ $registro['estado'] }}</td>
                                <td>
                                    <!-- Botón Editar -->
                                    <button class="icon-btn edit-btn" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M3 17.25V21h3.75l11.06-11.06-3.75-3.75L3 17.25zM20.71 
                                            7.04a1.003 1.003 0 0 0 0-1.42l-2.34-2.34a1.003 
                                            1.003 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 
                                            1.84-1.82z"/>
                                        </svg>
                                    </button>
                                    <!-- Botón Eliminar -->
                                    <button class="icon-btn delete-btn" title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M6 7h12v14H6z" fill="none"/>
                                            <path d="M19 7V4H5v3H2v2h20V7zM7 9v12h10V9H7z"/>
                                        </svg>
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
        </main>
    </div>

    {{-- Modales --}}
    @include('ingresos.partials.income-modal', ['conceptoIngresos' => $conceptoIngresos])
@include('ingresos.partials.concepto-modal', ['conceptoIngresos' => $conceptoIngresos])
@include('ingresos.partials.delete-modal', ['conceptoIngresos' => $conceptoIngresos])
@include('ingresos.partials.recurrence-modal', ['conceptoIngresos' => $conceptoIngresos])


    {{-- jQuery y DataTables --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    {{-- Scripts con Vite --}}
    @vite('resources/js/nav-bar.js')
    @vite('resources/js/ingresos.js')
    @vite('resources/js/ingresos-datatable.js')
</body>
</html>
