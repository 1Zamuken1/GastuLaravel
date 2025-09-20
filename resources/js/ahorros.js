function openModal(modal) { modal.classList.add("active"); }
function closeModal(modal) { modal.classList.remove("active"); }

const savingModal = document.getElementById("savingModal");
const addSavingBtn = document.getElementById("addSaving");
const closeSavingBtn = document.getElementById("closeModal");
const cancelSavingBtn = document.getElementById("cancelModal");

if (addSavingBtn) addSavingBtn.addEventListener("click", () => openModal(conceptoModal));
if (closeSavingBtn) closeSavingBtn.addEventListener("click", () => closeModal(savingModal));
if (cancelSavingBtn) cancelSavingBtn.addEventListener("click", () => closeModal(savingModal));

// Modal de concepto
const conceptoModal = document.getElementById("conceptoModal");
const closeConceptoBtn = document.getElementById("closeConceptoModal");
const cancelConceptoBtn = document.getElementById("cancelConceptoModal");

if (closeConceptoBtn) closeConceptoBtn.addEventListener("click", () => closeModal(conceptoModal));
if (cancelConceptoBtn) cancelConceptoBtn.addEventListener("click", () => closeModal(conceptoModal));

// Selección de concepto
const conceptosGrid = document.getElementById("conceptosGrid");
if (conceptosGrid) {
    conceptosGrid.addEventListener("click", (e) => {
        const item = e.target.closest(".concepto-item");
        if (!item) return;
        document.getElementById("concepto_id").value = item.dataset.id;
        document.getElementById("concepto").value = item.dataset.nombre;
        openModal(savingModal);
        closeModal(conceptoModal);
    });
}

// Botones Ver
document.querySelectorAll(".view-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        const tr = btn.closest("tr");
        document.getElementById("view_concepto").textContent = tr.children[1].textContent;
        document.getElementById("view_monto_meta").textContent = tr.children[2].textContent;
        document.getElementById("view_acumulado").textContent = tr.children[3].textContent;
        document.getElementById("view_cuotas").textContent = tr.children[4].textContent;
        document.getElementById("view_estado").textContent = tr.children[5].textContent;
        document.getElementById("view_descripcion").textContent = tr.dataset.descripcion || "";
        openModal(document.getElementById("viewModal"));
    });
});
