@extends('layouts.app')

@push('styles')
<!-- Bootstrap-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Detalle del Ahorro</h1>

    <div class="card">
        <div class="card-body">
            <h3>{{ $ahorro->concepto }}</h3>
            <p><strong>Descripción:</strong> {{ $ahorro->descripcion ?? '---' }}</p>
            <p><strong>Monto Meta:</strong> ${{ number_format($ahorro->monto_meta, 0, ',', '.') }}</p>
            <p><strong>Total Acumulado:</strong> ${{ number_format($ahorro->total_acumulado, 0, ',', '.') }}</p>
            <p><strong>% Avance:</strong> {{ $porcentaje }}%</p>
            <p><strong>Fecha Meta:</strong> {{ $ahorro->fecha_meta->format('d/m/Y') }}</p>
            <p><strong>Fecha Creación:</strong> {{ $ahorro->fecha_creacion->format('d/m/Y H:i') }}</p>
            <p><strong>Activo:</strong> {{ $ahorro->activa ? 'Sí' : 'No' }}</p>
            <p><strong>Último aporte generado:</strong> 
                {{ optional($ahorro->ahorroProgramados->first())->ultimo_aporte_generado?->format('d/m/Y') ?? '---' }}
            </p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('ahorros.index') }}" class="btn btn-secondary"> Volver</a>
        <a href="{{ route('ahorros.edit', $ahorro->ahorro_meta_id) }}" class="btn btn-warning">Editar</a>
    </div>
</div>
@endsection
