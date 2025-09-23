@extends('layouts.appAdmin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="bi bi-eye-fill me-2"></i> Detalle del Concepto de Egreso
        </h2>
        <a href="{{ route('admin.conceptosEgresos.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{ $conceptoEgreso->concepto_egreso_id }}</dd>
                <dt class="col-sm-3">Nombre</dt>
                <dd class="col-sm-9">{{ $conceptoEgreso->nombre }}</dd>
                <dt class="col-sm-3">Descripción</dt>
                <dd class="col-sm-9">{{ $conceptoEgreso->descripcion }}</dd>
            </dl>
             {{-- Footer con acciones --}}
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('admin.conceptosEgresos.edit', $conceptoEgreso->concepto_egreso_id) }}" class="btn btn-warning shadow-sm">
                <i class="bi bi-pencil-fill"></i> Editar
            </a>
            <form action="{{ route('admin.conceptosEgresos.destroy', $conceptoEgreso->concepto_egreso_id) }}" method="POST" 
                  onsubmit="return confirm('¿Seguro que deseas eliminar este concepto?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger shadow-sm">
                    <i class="bi bi-trash-fill"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
