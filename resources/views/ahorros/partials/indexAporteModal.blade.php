<div class="modal fade" id="indexAporteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Aportes de Ahorro: <span id="aportes_concepto"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p><strong>Monto Meta:</strong> <span id="aportes_monto_meta"></span></p>
                <p><strong>Total Acumulado:</strong> <span id="aportes_total_acumulado"></span></p>
                <p><strong>Frecuencia:</strong> <span id="aportes_frecuencia"></span></p>
                <p><strong>Próxima cuota vence el:</strong> <span id="proxima_cuota"></span></p>

                <div class="table-responsive">
                    <table class="table table-striped" id="aportesTable">
                        <thead class="table-warning">
                            <tr>
                                <th>#</th>
                                <th>Aporte Asignado</th>
                                <th>Aporte Usuario</th>
                                <th>Fecha Límite</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="aportesTableBody">
                            <!-- Aquí se cargan los aportes desde ahorros.js -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button id="saveAportesBtn" class="btn btn-warning">Guardar Cambios</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
