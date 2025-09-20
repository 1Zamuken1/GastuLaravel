@extends('layouts.app')

@push('styles')
<!-- Bootstrap-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <h1 class="mb-4">Detalle del Aporte</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>ID Aporte:</strong> {{ $aporte->aporte_ahorro_id }}</p>
            <p><strong>Monto:</strong> {{ number_format($aporte->monto, 0, ',', '.') }}</p>
            <p><strong>Fecha Registro:</strong> {{ $aporte->fecha_registro->format('d/m/Y') }}</p>
            <p><strong>Ahorro Asociado:</strong> {{ $aporte->ahorro_meta->concepto }}</p>
        </div>
    </div>

    <a href="{{ route('aportes.index', $aporte->ahorro_meta_id) }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection
