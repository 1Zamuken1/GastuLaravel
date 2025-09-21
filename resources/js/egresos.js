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
    // Mostrar/ocultar grupo fecha_fin
    const fecha_finGroup = document.getElementById("fecha_finGroup");
    const fecha_finInput = document.getElementById("fecha_fin");
    if (fecha_finGroup)
        fecha_finGroup.style.display = isProyeccion ? "block" : "none";
    if (fecha_finInput) fecha_finInput.required = isProyeccion;
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





// ===============================
// Botones Editar
// ===============================
document.querySelectorAll(".edit-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
        // Usar DataTables API para obtener los datos correctos
        const table = $("#incomeTable").DataTable();
        const tr = btn.closest("tr");
        const rowIdx = table.row(tr).index();
        const data = table.row(rowIdx).data();

        // data es un array con los valores de las columnas visibles y ocultas
        // Según tu Blade:
        // 0: ID (oculto), 1: Concepto, 2: Monto, 3: Tipo, 4: Fecha, 5: Estado

        const id = data[0];
        const concepto = data[1];
        const monto = data[2].replace(/[^0-9.,-]/g, "").replace(",", ".").trim();
        const tipo = data[3];
        // Fecha en formato d/m/Y, convertir a yyyy-mm-dd
        const fecha = data[4].split("/").reverse().join("-");
        const estado = data[6];
        // Los atributos extra siguen igual
        const descripcion = tr.getAttribute("data-descripcion") || "";
        const conceptoId = tr.getAttribute("data-concepto-id") || "";
        const fecha_fin = tr.getAttribute("data-fecha_fin") || "";

        document.getElementById("editId").value = id;
        document.getElementById("tipo").value = tipo;
        document.getElementById("concepto").value = concepto;
        document.getElementById("concepto_id").value = conceptoId;
        document.getElementById("monto").value = monto;
        document.getElementById("fecha").value = fecha;
        document.getElementById("fecha_fin").value = fecha_fin;
        document.getElementById("descripcion").value = descripcion;

        // Estado solo para proyección
        if (tipo === "Proyección") {
            document.getElementById("estado").value = estado === "Activo" ? "1" : "0";
        } else {
            document.getElementById("estado").value = "";
        }

        if (typeof toggleFieldsByTipo === "function") {
            toggleFieldsByTipo();
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
        const table = $("#incomeTable").DataTable();
        const tr = btn.closest("tr");
        const rowIdx = table.row(tr).index();
        const data = table.row(rowIdx).data();

        deleteId = data[0]; // <-- Esto es el ID real, aunque la columna esté oculta
        tipo = data[3];     // <-- Tipo
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
                : `/egresos/destroy/${deleteId}`;

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

if (tipoSelect) {
    tipoSelect.addEventListener("change", toggleFieldsByTipo);
    toggleFieldsByTipo();
}