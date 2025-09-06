// ===============================
// Manejador de Modales
// ===============================

function openModal(modal) { modal.classList.add("active"); }
function closeModal(modal) { modal.classList.remove("active"); }

// -------------------------------
// Modal Ingreso (Añadir/Editar)
// -------------------------------
const incomeModal = document.getElementById("incomeModal");
const addIncomeBtn = document.getElementById("addIncome");
const closeIncomeBtn = document.getElementById("closeModal");
const cancelIncomeBtn = document.getElementById("cancelModal");

if (addIncomeBtn) addIncomeBtn.addEventListener("click", () => openModal(incomeModal));
if (closeIncomeBtn) closeIncomeBtn.addEventListener("click", () => closeModal(incomeModal));
if (cancelIncomeBtn) cancelIncomeBtn.addEventListener("click", () => closeModal(incomeModal));

// -------------------------------
// Modal Selección de Concepto
// -------------------------------
const conceptoModal = document.getElementById("conceptoModal");
const selectConceptoBtn = document.getElementById("selectConcepto");
const conceptoInput = document.getElementById("concepto");
const conceptosGrid = document.getElementById("conceptosGrid");
const conceptoHidden = document.getElementById("concepto_id");

if (selectConceptoBtn) selectConceptoBtn.addEventListener("click", () => openModal(conceptoModal));
if (conceptoInput) conceptoInput.addEventListener("click", () => openModal(conceptoModal));

const closeConceptoBtn = document.getElementById("closeConceptoModal");
const cancelConceptoBtn = document.getElementById("cancelConceptoModal");
if (closeConceptoBtn) closeConceptoBtn.addEventListener("click", () => closeModal(conceptoModal));
if (cancelConceptoBtn) cancelConceptoBtn.addEventListener("click", () => closeModal(conceptoModal));

if (conceptosGrid) {
  conceptosGrid.addEventListener("click", (e) => {
    const item = e.target.closest(".concepto-item");
    if (!item) return;
    const id = item.getAttribute("data-id");
    const nombre = item.getAttribute("data-nombre");
    if (conceptoInput) conceptoInput.value = nombre;
    if (conceptoHidden) conceptoHidden.value = id;
    closeModal(conceptoModal);
  });
}

// ===============================
// Búsqueda en el Modal de Conceptos
// ===============================
const searchConceptoInput = document.getElementById("searchConcepto");

if (searchConceptoInput && conceptosGrid) {
  searchConceptoInput.addEventListener("input", function () {
    const search = this.value.toLowerCase();
    conceptosGrid.querySelectorAll(".concepto-item").forEach(item => {
      const nombre = item.getAttribute("data-nombre").toLowerCase();
      if (nombre.includes(search)) {
        item.style.display = "";
      } else {
        item.style.display = "none";
      }
    });
  });
}

// ===============================
// Botón "Hoy" -> Poner fecha actual
// ===============================
const btnHoy = document.getElementById("btnHoy");
const inputFecha = document.getElementById("fecha");
if (btnHoy && inputFecha) {
  btnHoy.addEventListener("click", () => {
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, "0");
    const dd = String(hoy.getDate()).padStart(2, "0");
    inputFecha.value = `${yyyy}-${mm}-${dd}`;
  });
}

// ===============================
// Mostrar/Ocultar Recurrencia según Tipo
// ===============================
const tipoSelect = document.getElementById("tipo");
const recurrenciaGroup = document.getElementById("recurrenciaGroup");
const recurrenciaSelect = document.getElementById("recurrencia");
const estadoSelect = document.getElementById("estado");
const descripcionField = document.getElementById("descripcion");

function toggleFieldsByTipo() {
  if (!tipoSelect) return;
  const isProyeccion = tipoSelect.value === "Proyección";
  if (recurrenciaGroup) recurrenciaGroup.style.display = isProyeccion ? "block" : "none";
  if (recurrenciaSelect) recurrenciaSelect.required = isProyeccion;
  // Para cumplir con NOT NULL en proyección:
  if (descripcionField) descripcionField.required = isProyeccion;
  if (estadoSelect) estadoSelect.required = isProyeccion; // para proyección sí se usa "activo"
}
if (tipoSelect) {
  tipoSelect.addEventListener("change", toggleFieldsByTipo);
  toggleFieldsByTipo();
}

// ===============================
// Auto-ajuste del textarea (no redimensionable por el usuario)
// ===============================
if (descripcionField) {
  const autoResize = () => {
    descripcionField.style.height = "auto";
    descripcionField.style.height = descripcionField.scrollHeight + "px";
  };
  descripcionField.addEventListener("input", autoResize);
  autoResize();
}

// ===============================
// Botones Editar
// ===============================
document.querySelectorAll(".edit-btn").forEach((btn) => {
  btn.addEventListener("click", function () {
    const row = btn.closest("tr");
    const id = row.children[0].textContent.trim();
    const concepto = row.children[1].textContent.trim();
    const monto = row.children[2].textContent.replace("$", "").trim();
    const tipo = row.children[3].textContent.trim();
    const fecha = row.children[4].textContent.split("/").reverse().join("-");
    const estado = row.children[5].textContent.trim();
    const descripcion = row.getAttribute("data-descripcion") || "";
    const conceptoId = row.getAttribute("data-concepto-id") || "";

    document.getElementById("editId").value = id;
    document.getElementById("tipo").value = tipo;
    document.getElementById("concepto").value = concepto;
    document.getElementById("concepto_id").value = conceptoId;
    document.getElementById("monto").value = monto;
    document.getElementById("fecha").value = fecha;
    document.getElementById("estado").value = estado;
    document.getElementById("descripcion").value = descripcion;

    const form = document.getElementById("formIngreso");
    form.action = tipo === "Proyección"
      ? `/proyecciones/${id}`
      : `/ingresos/update/${id}`;
    form.method = "POST";

    // Manejo del método PUT para proyección
    let methodInput = form.querySelector('input[name="_method"]');
    if (tipo === "Proyección") {
      if (!methodInput) {
        methodInput = document.createElement("input");
        methodInput.type = "hidden";
        methodInput.name = "_method";
        form.appendChild(methodInput);
      }
      methodInput.value = "PUT";
    } else {
      if (methodInput) methodInput.remove();
    }

    openModal(incomeModal);
  });
});

// ===============================
// Botones Eliminar
// ===============================
let deleteId = null;
let tipo = null;
document.querySelectorAll(".delete-btn").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    const row = btn.closest("tr");
    deleteId = row.children[0].textContent.trim();
    tipo = row.children[3].textContent.trim(); // Detecta el tipo correctamente
    openModal(document.getElementById("deleteConfirmationModal"));
  });
});

const confirmDeleteBtn = document.getElementById("confirmDelete");
if (confirmDeleteBtn) {
  confirmDeleteBtn.addEventListener("click", () => {
    if (!deleteId) return;

    // Creamos un form dinámico
    const form = document.createElement("form");
    form.method = "POST";
    form.action =
      tipo === "Proyección"
        ? `/proyecciones/${deleteId}`
        : `/ingresos/destroy/${deleteId}`;

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const tokenInput = document.createElement("input");
    tokenInput.type = "hidden";
    tokenInput.name = "_token";
    tokenInput.value = csrf;

    const methodInput = document.createElement("input");
    methodInput.type = "hidden";
    methodInput.name = "_method";
    methodInput.value = "DELETE";

    form.appendChild(tokenInput);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
  });
}

// ===============================
// Modal de Eliminar
// ===============================
const deleteModal = document.getElementById("deleteConfirmationModal");
const closeDeleteModal = document.getElementById("closeDeleteModal");
const cancelDeleteBtn = document.getElementById("cancelDelete");

if (closeDeleteModal) closeDeleteModal.addEventListener("click", () => closeModal(deleteModal));
if (cancelDeleteBtn) cancelDeleteBtn.addEventListener("click", () => closeModal(deleteModal));

// ===============================
// DataTable
// ===============================
$('#miTabla').DataTable({
    language: {
        url: '/datatables/es-ES.json'
    }
});

// ===============================
// Modal de Recordatorio de Recurrencia
// ===============================
function shouldShowRecurrenceModal() {
  const today = new Date().toISOString().slice(0, 10);
  return localStorage.getItem("recurrenceModalShown") !== today;
}

if (shouldShowRecurrenceModal()) {
  fetch('/proyecciones/para-confirmar')
    .then(res => res.json())
    .then(data => {
      if (data.proyecciones && data.proyecciones.length > 0) {
        // Llena el modal con los datos
        const items = data.proyecciones.map(p =>
          `<div class="recurrence-item" data-id="${p.id}">
            <strong>${p.descripcion}</strong> - $${p.monto_programado} (${p.frecuencia})
          </div>`
        ).join('');
        document.getElementById("recurrenceItems").innerHTML = items;
        openModal(document.getElementById("recurrenceModal"));
      }
    });
}

// Al cerrar el modal (por cancelar o confirmar), guarda la fecha en localStorage
document.getElementById("remindLater").addEventListener("click", () => {
  localStorage.setItem("recurrenceModalShown", new Date().toISOString().slice(0, 10));
  closeModal(document.getElementById("recurrenceModal"));
});
document.getElementById("confirmRecurrences").addEventListener("click", () => {
  // Obtén los IDs de las proyecciones a confirmar
  const items = document.querySelectorAll("#recurrenceItems .recurrence-item");
  const ids = Array.from(items).map(item => item.getAttribute("data-id"));

  fetch('/proyecciones/confirmar', {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ ids })
  })
  .then(res => res.json())
  .then(data => {
    localStorage.setItem("recurrenceModalShown", new Date().toISOString().slice(0, 10));
    closeModal(document.getElementById("recurrenceModal"));
    // Opcional: recargar la tabla o mostrar mensaje de éxito
  });
});
