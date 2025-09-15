<div class="modal" id="incomeModal">
  <div class="modal-backdrop"></div>
  <div class="modal-content">
    <div class="modal-header">
      <div class="modal-title">
        <i class="fas fa-file-invoice-dollar"></i>
        <span id="modalTitle">Añadir nuevo registro</span>
      </div>
      <button class="close-modal" id="closeModal" type="button">&times;</button>
    </div>

    <div class="modal-body">
      <form id="formIngreso" method="POST" action="{{ route('ingresos.store') }}">
        @csrf

        <input type="hidden" id="editId" name="editId" />

        <div class="form-group">
          <label for="tipo">Tipo *</label>
          <select id="tipo" name="tipo" class="form-control" required>
            <option value="">Seleccione un tipo</option>
            <option value="Ingreso">Ingreso</option>
            <option value="Proyección">Proyección</option>
          </select>
        </div>

        <div class="form-group">
          <label for="concepto">Concepto *</label>
          <div class="concepto-selector">
            <input
              type="text"
              id="concepto"
              class="form-control"
              required
              readonly
              style="cursor: pointer"
            />
            <button type="button" class="btn-select-concepto" id="selectConcepto">
              <i class="fas fa-chevron-down"></i>
            </button>
          </div>
        </div>
        <input type="hidden" id="concepto_id" name="concepto_ingreso_id" />

        <div class="form-group">
          <label for="monto">Monto ($) *</label>
          <input type="number" id="monto" name="monto" class="form-control" step="0.01" required placeholder="Ingrese el monto" />
        </div>

        <div class="form-group">
          <label for="fecha">Fecha *</label>
          <div class="fecha-container">
            <input type="date" id="fecha" name="fecha" class="form-control" required />
            <button type="button" id="btnHoy" class="btn-hoy">Hoy</button>
          </div>
        </div>

        <div class="form-group">
          <label for="estado">Estado *</label>
          <select id="estado" name="estado" class="form-control">
            <option value="">Seleccione un estado</option>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
          </select>
        </div>

        <div class="form-group">
          <label for="descripcion">Descripción</label>
          <textarea id="descripcion" name="descripcion" class="form-control" rows="2" style="resize: none; overflow: auto;"></textarea>
        </div>

        <div class="modal-footer">
          <button class="btn btn-cancel" id="cancelModal" type="button">Cancelar</button>
          <button class="btn btn-save" id="saveIncome" type="submit">
            <i class="fas fa-save"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
