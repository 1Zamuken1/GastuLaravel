<div class="modal" id="conceptoModal">
  <div class="modal-backdrop"></div>
  <div class="modal-content" style="max-width: 800px">
    <div class="modal-header">
      <div class="modal-title">
        <i class="fas fa-list"></i>
        <span id="conceptoModalTitle">Seleccionar Concepto</span>
      </div>
      <button class="close-modal" id="closeConceptoModal">&times;</button>
    </div>
    <div class="modal-body">
      <div class="search-container" style="margin-bottom: 20px">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" id="searchConcepto" placeholder="Buscar concepto..."/>
        </div>
      </div>
      <div class="conceptos-grid" id="conceptosGrid">
        @foreach ($conceptoIngresos as $concepto)
          <div class="concepto-item" 
               data-id="{{ $concepto->concepto_ingreso_id }}" 
               data-nombre="{{ $concepto->nombre }}">
            {{ $concepto->nombre }}
          </div>
        @endforeach
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-cancel" id="cancelConceptoModal">Cancelar</button>
    </div>
  </div>
</div>
