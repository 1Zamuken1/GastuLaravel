<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>Gastos</title>
</head>
<body>
    <div class="container" >
        <div class="encabezado">
            <h1 class="titulo">Gastos</h1>
            <div class="agregar-gasto">
                <button id="agregar-gasto">Agregar Gasto</button>
                <div class="modal-agregar-gasto" id="myModal">
                    <span class="cerrar">&times;</span>
                    <h2>Agregar Nuevo Gasto</h2>
                    <form action="/gastos" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="tipo">Tipo</label>
                            <input type="text" name="tipo" id="tipo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="monto">Monto</label>
                            <input type="number" name="monto" id="monto" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion">Descripción</label>
                            <input type="text" name="descripcion" id="descripcion" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="fecha_registro">Fecha de registro</label>
                            <input type="date" name="fecha_registro" id="fecha_registro" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="concepto_egreso_id">Concepto de egreso</label>
                            <select name="concepto_egreso_id" id="concepto_egreso_id" class="form-control" required>
                                @foreach($conceptoEgresos as $concepto)
                                    <option value="{{ $concepto->concepto_egreso_id }}">{{ $concepto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Gasto</button>
                    </form>
                </div>
            </div>
            
        </div>
    
        <div class="conceptos">
            <h2>Conceptos</h2>
            @forelse ($conceptoEgresos as $concepto)
                <div class="tarjeta">
                    <h3>{{ $concepto->nombre }}</h3>
                    <p>Descripción: {{ $concepto->descripcion }}</p>
                </div>
            @empty
                <p>No hay conceptos registrados.</p>
            @endforelse
        </div>

    </div>
    @if(session('success'))
    <script>
        window.gastoSuccess = "{{ session('success') }}";
    </script>
@endif
<script src="{{ asset('js/gastos.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>