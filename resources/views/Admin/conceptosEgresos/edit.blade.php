@extends('layouts.appAdmin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="bi bi-pencil-fill me-2"></i> Editar Concepto de Egreso
        </h2>
        <a href="{{ route('admin.conceptosEgresos.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <form action="{{ route('admin.conceptosEgresos.update', $conceptoEgreso->concepto_egreso_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $conceptoEgreso->nombre) }}" required maxlength="100">
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" maxlength="200">{{ old('descripcion', $conceptoEgreso->descripcion) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary px-4">Actualizar</button>
            </form>
        </div>
    </div>
</div>
@endsection
