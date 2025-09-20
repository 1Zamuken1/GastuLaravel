@extends('layouts.app')

@push('styles')
<!-- Bootstrap-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Ahorro</h1>

    <form action="{{ route('ahorros.update', $ahorro->ahorro_meta_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Concepto *</label>
            <input type="text" name="concepto" class="form-control" value="{{ $ahorro->concepto }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control">{{ $ahorro->descripcion }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Monto Meta</label>
            <input type="number" name="monto_meta" class="form-control" value="{{ $ahorro->monto_meta }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha Meta</label>
            <input type="date" name="fecha_meta" class="form-control" value="{{ $ahorro->fecha_meta }}">
        </div>

        <hr>
        <h4>Programación del Ahorro</h4>
        @php $programado = $ahorro->ahorroProgramados->first(); @endphp

        <div class="mb-3">
            <label class="form-label">Monto Programado *</label>
            <input type="number" name="monto_programado" class="form-control" 
                   value="{{ $programado->monto_programado ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Frecuencia *</label>
            <select name="frecuencia" class="form-select" required>
                @foreach(['diaria','semanal','quincenal','mensual','trimestral','semestral','anual'] as $opcion)
                    <option value="{{ $opcion }}" {{ ($programado->frecuencia ?? '') == $opcion ? 'selected' : '' }}>
                        {{ ucfirst($opcion) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha Inicio *</label>
            <input type="date" name="fecha_inicio" class="form-control" 
                   value="{{ $programado->fecha_inicio->format('Y-m-d') ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha Fin</label>
            <input type="date" name="fecha_fin" class="form-control" 
                   value="{{ $programado->fecha_fin?->format('Y-m-d') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Número de Cuotas</label>
            <input type="number" name="num_cuotas" class="form-control" value="{{ $programado->num_cuotas ?? '' }}">
        </div>

        <button type="submit" class="btn btn-success"> Actualizar Ahorro</button>
        <a href="{{ route('ahorros.index') }}" class="btn btn-secondary"> Cancelar</a>
    </form>
</div>
@endsection
