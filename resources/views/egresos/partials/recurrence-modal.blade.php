<div class="recurrence-modal" id="recurrenceModal">
  <div class="recurrence-modal-backdrop"></div>
  <div class="recurrence-modal-content">
    <div class="recurrence-modal-header">
      <div class="recurrence-modal-title">
        <i class="fas fa-calendar-check"></i>
        <span>Confirmación de Proyecciones Recurrentes</span>
      </div>
    </div>
    <div class="recurrence-modal-body">
      <p>Se han detectado proyecciones recurrentes que deben confirmarse:</p>
      <div id="recurrenceItems">
        {{-- Aquí se cargan las proyecciones recurrentes --}}
      </div>
    </div>
    <div class="recurrence-footer">
      <button class="btn btn-remind" id="remindLater">Recordarme mañana</button>
      <button class="btn btn-confirm" id="confirmRecurrences">Confirmar egresos</button>
    </div>
  </div>
</div>