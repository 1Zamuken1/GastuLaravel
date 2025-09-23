<div class="modal" id="viewModal">
    <div class="modal-backdrop"></div>
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Detalles del Registro</span>
            </div>
            <button class="close-modal" id="closeViewModal" type="button">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Tipo:</label>
                <span id="view_tipo"></span>
            </div>
            <div class="form-group">
                <label>Concepto:</label>
                <span id="view_concepto"></span>
            </div>
            <div class="form-group">
                <label>Monto ($):</label>
                <span id="view_monto"></span>
            </div>
            <div class="form-group">
                <label>Fecha:</label>
                <span id="view_fecha"></span>
            </div>
            <div class="form-group" id="view_fecha_finGroup" style="display:none">
                <label>Recordatorio:</label>
                <span id="view_fecha_fin"></span>
            </div>
            <div class="form-group">
                <label>Estado:</label>
                <span id="view_estado"></span>
            </div>
            <div class="form-group">
                <label>Descripción:</label>
                <span id="view_descripcion"></span>
            </div>
        </div>
    </div>
</div>