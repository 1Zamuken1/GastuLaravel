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
                        <input type="text" id="concepto" name="concepto" class="form-control" required maxlength="60">
                    </div>
                    <div class="mb-3">
                        <label for="monto_meta" class="form-label">Monto Meta</label>
                        <input type="number" id="monto_meta" name="monto_meta" class="form-control" step="0.01" required min="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="frecuencia" class="form-label">Frecuencia</label>
                        <select id="frecuencia" name="frecuencia" class="form-select" required>
                            <option value="Diario">Diario</option>
                            <option value="Semanal">Semanal</option>
                            <option value="Quincenal">Quincenal</option>
                            <option value="Mensual">Mensual</option>
                            <option value="Trimestral">Trimestral</option>
                            <option value="Semestral">Semestral</option>
                            <option value="Anual">Anual</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_meta" class="form-label">Fecha Meta</label>
                        <div class="input-group">
                            <input type="date" id="fecha_meta" name="fecha_meta" class="form-control" required>
                           
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea id="descripcion" name="descripcion" class="form-control" maxlength="100"></textarea>
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
