// ===============================
// Manejador de Modales
// ===============================

// Utilidad para abrir modal
function openModal(modal) {
  modal.classList.add("active");
}

// Utilidad para cerrar modal
function closeModal(modal) {
  modal.classList.remove("active");
}

// -------------------------------
// Modal Ingreso (Añadir/Editar)
// -------------------------------
const incomeModal = document.getElementById("incomeModal");
const addIncomeBtn = document.getElementById("addIncome");
const closeIncomeBtn = document.getElementById("closeModal");
const cancelIncomeBtn = document.getElementById("cancelModal");

if (addIncomeBtn) {
  addIncomeBtn.addEventListener("click", () => openModal(incomeModal));
}
if (closeIncomeBtn) {
  closeIncomeBtn.addEventListener("click", () => closeModal(incomeModal));
}
if (cancelIncomeBtn) {
  cancelIncomeBtn.addEventListener("click", () => closeModal(incomeModal));
}

// -------------------------------
// Modal Selección de Concepto
// -------------------------------
const conceptoModal = document.getElementById("conceptoModal");
const selectConceptoBtn = document.getElementById("selectConcepto");
const closeConceptoBtn = document.getElementById("closeConceptoModal");
const cancelConceptoBtn = document.getElementById("cancelConceptoModal");

if (selectConceptoBtn) {
  selectConceptoBtn.addEventListener("click", () => openModal(conceptoModal));
}
if (closeConceptoBtn) {
  closeConceptoBtn.addEventListener("click", () => closeModal(conceptoModal));
}
if (cancelConceptoBtn) {
  cancelConceptoBtn.addEventListener("click", () => closeModal(conceptoModal));
}

// -------------------------------
// Modal Confirmación de Eliminación
// -------------------------------
const deleteModal = document.getElementById("deleteConfirmationModal");
const closeDeleteBtn = document.getElementById("closeDeleteModal");
const cancelDeleteBtn = document.getElementById("cancelDelete");

if (closeDeleteBtn) {
  closeDeleteBtn.addEventListener("click", () => closeModal(deleteModal));
}
if (cancelDeleteBtn) {
  cancelDeleteBtn.addEventListener("click", () => closeModal(deleteModal));
}

// -------------------------------
// Modal Recurrencias
// -------------------------------
const recurrenceModal = document.getElementById("recurrenceModal");
const remindLaterBtn = document.getElementById("remindLater");
const confirmRecurrencesBtn = document.getElementById("confirmRecurrences");

if (remindLaterBtn) {
  remindLaterBtn.addEventListener("click", () => closeModal(recurrenceModal));
}
if (confirmRecurrencesBtn) {
  confirmRecurrencesBtn.addEventListener("click", () =>
    closeModal(recurrenceModal)
  );
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
