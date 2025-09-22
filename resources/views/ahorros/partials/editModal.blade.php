<div class="modal fade" id="editAhorroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Editar Ahorro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditAhorro" method="POST" action="{{ route('ahorros.update', ['ahorro' => 0]) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editId" name="id">
                    <div class="mb-3">
                        <label for="edit_concepto" class="form-label">Concepto</label>
                        <input type="text" id="edit_concepto" name="concepto" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_monto_meta" class="form-label">Monto Meta</label>
                        <input type="number" id="edit_monto_meta" name="monto_meta" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_frecuencia" class="form-label">Frecuencia</label>
                        <select id="edit_frecuencia" name="frecuencia" class="form-select" required>
                            <option value="Diaria">Diaria</option>
                            <option value="Semanal">Semanal</option>
                            <option value="Quincenal">Quincenal</option>
                            <option value="Mensual">Mensual</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_fecha_meta" class="form-label">Fecha Meta</label>
                        <input type="date" id="edit_fecha_meta" name="fecha_meta" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea id="edit_descripcion" name="descripcion" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estado" class="form-label">Estado</label>
                        <select id="edit_estado" name="estado" class="form-select" required>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Completado">Completado</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
