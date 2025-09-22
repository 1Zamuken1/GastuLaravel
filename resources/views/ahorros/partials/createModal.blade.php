<div class="modal fade" id="createAhorroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Agregar Ahorro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCreateAhorro" action="{{ route('ahorros.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="concepto" class="form-label">Concepto</label>
                        <input type="text" id="concepto" name="concepto" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="monto_meta" class="form-label">Monto Meta</label>
                        <input type="number" id="monto_meta" name="monto_meta" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="frecuencia" class="form-label">Frecuencia</label>
                        <select id="frecuencia" name="frecuencia" class="form-select" required>
                            <option value="Diaria">Diaria</option>
                            <option value="Semanal">Semanal</option>
                            <option value="Quincenal">Quincenal</option>
                            <option value="Mensual">Mensual</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_meta" class="form-label">Fecha Meta</label>
                        <div class="input-group">
                            <input type="date" id="fecha_meta" name="fecha_meta" class="form-control" required>
                            <button type="button" id="btnHoy" class="btn btn-outline-warning">Hoy</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select id="estado" name="estado" class="form-select" required>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Completado">Completado</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
