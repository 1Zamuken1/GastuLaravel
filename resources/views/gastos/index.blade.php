@extends('layouts.app')
@section('title', 'Egresos y Proyecciones')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-money-bill-wave"></i>
                <span id="section-title">Egresos y Proyecciones</span>
            </div>
            <div class="controls">
                <button class="add-btn" id="addExpense">
                    <i class="fas fa-plus"></i> Añadir nuevo
                </button>
            </div>
        </div>

        {{-- Tarjetas resumen --}}
        <div class="summary-cards">
            <div class="summary-card">
                <div class="card-label
@endsection()