@extends('layouts.appAdmin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="bi bi-plus-circle-fill me-2"></i> Nuevo Concepto de Ingreso
        </h2>
        <a href="{{ route('admin.conceptoIngresos.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
                <form action="{{ route('admin.conceptoIngresos.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required maxlength="100">
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" maxlength="200">{{ old('descripcion') }}</textarea>
                </div>
                <button type="submit" class="btn btn-success px-4">Guardar</button>
            </form>
        </div>
    </div>
</div>
@endsection
