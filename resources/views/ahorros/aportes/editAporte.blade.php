@extends('layouts.app')

@push('styles')
<!-- Bootstrap-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Aporte</h1>

    <form action="{{ route('aportes.update', $aporte->aporte_ahorro_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Monto *</label>
            <input type="number" name="monto" class="form-control" value="{{ $aporte->monto }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha Registro *</label>
            <input type="date" name="fecha_registro" class="form-control" value="{{ $aporte->fecha_registro->format('Y-m-d') }}" required>
        </div>

        <button type="submit" class="btn btn-success">Actualizar Aporte</button>
        <a href="{{ route('aportes.index', $aporte->ahorro_meta_id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
