// ===============================
// Manejador de Modales
// ===============================

function openModal(modal) {
    modal.classList.add("active");
}
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

// if (addIncomeBtn) addIncomeBtn.addEventListener("click", () => openModal(incomeModal));
// if (closeIncomeBtn) closeIncomeBtn.addEventListener("click", () => closeModal(incomeModal));
// if (cancelIncomeBtn) cancelIncomeBtn.addEventListener("click", () => closeModal(incomeModal));

if (addIncomeBtn)
    addIncomeBtn.addEventListener("click", () => openModal(conceptoModal));
if (closeIncomeBtn)
    closeIncomeBtn.addEventListener("click", () => closeModal(incomeModal));
if (cancelIncomeBtn)
    cancelIncomeBtn.addEventListener("click", () => closeModal(incomeModal));

// -------------------------------
// Modal Selección de Concepto
// -------------------------------
const conceptoModal = document.getElementById("conceptoModal");
const selectConceptoBtn = document.getElementById("selectConcepto");
const conceptoInput = document.getElementById("concepto");
const conceptosGrid = document.getElementById("conceptosGrid");
const conceptoHidden = document.getElementById("concepto_id");

//if (selectConceptoBtn) selectConceptoBtn.addEventListener("click", () => openModal(conceptoModal));
//if (conceptoInput) conceptoInput.addEventListener("click", () => openModal(conceptoModal));

const closeConceptoBtn = document.getElementById("closeConceptoModal");
const cancelConceptoBtn = document.getElementById("cancelConceptoModal");
if (closeConceptoBtn)
    closeConceptoBtn.addEventListener("click", () => closeModal(conceptoModal));
if (cancelConceptoBtn)
    cancelConceptoBtn.addEventListener("click", () =>
        closeModal(conceptoModal)
    );

if (conceptosGrid) {
    conceptosGrid.addEventListener("click", (e) => {
        const item = e.target.closest(".concepto-item");
        if (!item) return;

        const id = item.getAttribute("data-id");
        const nombre = item.getAttribute("data-nombre");

        // Pasar valores al formulario del modal
        const conceptoHidden = document.getElementById("concepto_id"); // hidden
        const conceptoInput = document.getElementById("concepto");

        if (conceptoHidden) conceptoHidden.value = id;
        if (conceptoInput) conceptoInput.value = nombre;

        // Abrir modal ya con los datos cargados
        openModal(incomeModal);
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
        conceptosGrid.querySelectorAll(".concepto-item").forEach((item) => {
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
    if (recurrenciaGroup)
        recurrenciaGroup.style.display = isProyeccion ? "block" : "none";
    if (recurrenciaSelect) recurrenciaSelect.required = isProyeccion;
    if (descripcionField) descripcionField.required = isProyeccion;
    // Mostrar/ocultar grupo estado
    const estadoGroup = document.getElementById("estadoGroup");
    if (estadoGroup)
        estadoGroup.style.display = isProyeccion ? "block" : "none";
    if (estadoSelect) estadoSelect.required = isProyeccion;
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
// Botones Ver
// ===============================
document.querySelectorAll(".view-btn").forEach((btn) => {
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

        // Asigna los valores igual que en editar
        document.getElementById("editId").value = id;
        document.getElementById("tipo").value = tipo;
        document.getElementById("concepto").value = concepto;
        document.getElementById("concepto_id").value = conceptoId;
        document.getElementById("monto").value = monto;
        document.getElementById("fecha").value = fecha;
        document.getElementById("estado").value = (tipo === "Proyección") ? (estado === "Activo" ? "1" : "0") : "";
        document.getElementById("descripcion").value = descripcion;

        // Deshabilita todos los campos del formulario
        Array.from(document.querySelectorAll("#formIngreso input, #formIngreso select, #formIngreso textarea")).forEach(el => {
            el.disabled = true;
        });

        // Oculta los botones de guardar/cancelar si existen
        document.querySelectorAll("#formIngreso .modal-footer, #formIngreso button[type=submit]").forEach(el => {
            el.style.display = "none";
        });

        // Cambia el título del modal
        document.getElementById("modalTitle").textContent = "Ver registro";

        openModal(incomeModal);
    });
});

// Al cerrar el modal, vuelve a habilitar los campos y mostrar los botones
incomeModal.addEventListener("hide", function () {
    Array.from(document.querySelectorAll("#formIngreso input, #formIngreso select, #formIngreso textarea")).forEach(el => {
        el.disabled = false;
    });
    document.querySelectorAll("#formIngreso .modal-footer, #formIngreso button[type=submit]").forEach(el => {
        el.style.display = "";
    });
    document.getElementById("modalTitle").textContent = "Añadir nuevo registro";
});




// ===============================
// Botones Editar
// ===============================
document.querySelectorAll(".edit-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
        const row = btn.closest("tr");
        const id = row.children[0].textContent.trim();
        const concepto = row.children[1].textContent.trim();
        const monto =
            row.getAttribute("data-monto") ||
            row.children[2].textContent
                .replace(/[^0-9.,-]/g, "")
                .replace(",", ".")
                .trim();
        const tipo = row.children[3].textContent.trim();
        const estado = row.children[5].textContent.trim();
        const fecha = row.children[4].textContent
            .split("/")
            .reverse()
            .join("-");
        const descripcion = row.getAttribute("data-descripcion") || "";
        const conceptoId = row.getAttribute("data-concepto-id") || "";

        document.getElementById("editId").value = id;
        document.getElementById("tipo").value = tipo; // <-- Asegúrate que el valor sea exactamente "Ingreso" o "Proyección"
        document.getElementById("concepto").value = concepto;
        document.getElementById("concepto_id").value = conceptoId;
        document.getElementById("monto").value = monto;
        document.getElementById("fecha").value = fecha;
        document.getElementById("estado").value = estado;
        document.getElementById("descripcion").value = descripcion;

        if (tipo === "Proyección") {
            document.getElementById("estado").value =
                estado === "Activo" ? "1" : "0";
        } else {
            document.getElementById("estado").value = "";
        }

        // 🔴 Llama a toggleFieldsByTipo después de asignar el tipo
        if (typeof toggleFieldsByTipo === "function") {
            toggleFieldsByTipo();
        }

        const form = document.getElementById("formIngreso");
        form.action =
            tipo === "Proyección"
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

if (closeDeleteModal)
    closeDeleteModal.addEventListener("click", () => closeModal(deleteModal));
if (cancelDeleteBtn)
    cancelDeleteBtn.addEventListener("click", () => closeModal(deleteModal));

// ===============================
// DataTable
// ===============================
$("#miTabla").DataTable({
    language: {
        url: "/datatables/es-ES.json",
    },
});
