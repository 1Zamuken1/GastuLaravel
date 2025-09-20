@extends('layouts.app')

@push('styles')
<!-- Bootstrap-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush


@section('content')
<div class="container">
    <h1 class="mb-4">Crear Nuevo Ahorro</h1>

    <form action="{{ route('ahorros.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Concepto *</label>
            <input type="text" name="concepto" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Monto Meta</label>
            <input type="number" name="monto_meta" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha Meta</label>
            <input type="date" name="fecha_meta" class="form-control">
        </div>

        <hr>
        <h4>Programación del Ahorro</h4>

        <div class="mb-3">
            <label class="form-label">Monto Programado *</label>
            <input type="number" name="monto_programado" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Frecuencia *</label>
            <select name="frecuencia" class="form-select" required>
                <option value="diaria">Diaria</option>
                <option value="semanal">Semanal</option>
                <option value="quincenal">Quincenal</option>
                <option value="mensual">Mensual</option>
                <option value="trimestral">Trimestral</option>
                <option value="semestral">Semestral</option>
                <option value="anual">Anual</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha Inicio *</label>
            <input type="date" name="fecha_inicio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha Fin</label>
            <input type="date" name="fecha_fin" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Número de Cuotas</label>
            <input type="number" name="num_cuotas" class="form-control">
        </div>

        <button type="submit" class="btn btn-success"> Guardar Ahorro</button>
        <a href="{{ route('ahorros.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
