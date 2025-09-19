<div class="modal" id="recordatorioModal">
    <div class="modal-backdrop"></div>
    <div class="modal-content">
        <div class="modal-header" style="background: #e8f5e9;">
            <div class="modal-title">
                <i class="fas fa-bell text-success"></i>
                <span>Recordatorio de proyección, ¿quieres registrar este ingreso proyectado?</span>
            </div>
            <button class="close-modal" id="closeRecordatorioModal" type="button">&times;</button>
        </div>
        <div class="modal-body">
            <form id="formRecordatorio" method="POST" action="/proyecciones">
                @csrf
                <input type="hidden" name="original_id" id="recordatorio_original_id" />
                <div class="form-group">
                    <label for="recordatorio_concepto">Concepto *</label>
                    <input type="text" id="recordatorio_concepto" class="form-control" readonly />
                    <input type="hidden" id="recordatorio_concepto_id" name="concepto_ingreso_id" />
                </div>
                <div class="form-group">
                    <label for="recordatorio_monto">Monto ($) *</label>
                    <input type="number" id="recordatorio_monto" name="monto_programado" class="form-control" step="0.01" required />
                </div>
                <div class="form-group">
                    <label for="recordatorio_fecha">Fecha inicio *</label>
                    <div class="fecha-container">
                        <input type="date" id="recordatorio_fecha" name="fecha_inicio" class="form-control" required />
                        <button type="button" id="btnHoyRecordatorio" class="btn-hoy">Hoy</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="recordatorio_fecha_fin">Recordatorio *</label>
                    <input type="date" id="recordatorio_fecha_fin" name="fecha_fin" class="form-control" required />
                    <small class="text-danger" id="recordatorio_fecha_fin_error" style="display:none;">
                        La nueva fecha debe ser al menos un día después de hoy.
                    </small>
                </div>
                <div class="form-group">
                    <label for="recordatorio_estado">Estado *</label>
                    <select id="recordatorio_estado" name="activo" class="form-control">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="recordatorio_descripcion">Descripción</label>
                    <textarea id="recordatorio_descripcion" name="descripcion" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-cancel" id="cancelRecordatorio" type="button">Cancelar</button>
                    <button class="btn btn-save" id="saveRecordatorio" type="submit">
                        <i class="fas fa-save"></i> Registrar proyección
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById("formRecordatorio").addEventListener("submit", function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch(form.action, {
            method: "POST",
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 201) {
                // Recarga la página para ver el nuevo registro
                window.location.reload();
            } else if (data.errors) {
                // Muestra errores si los hay
                alert("Error: " + Object.values(data.errors).join("\n"));
            }
        })
        .catch(() => {
            alert("Ocurrió un error al guardar la proyección.");
        });
    });
</script>
