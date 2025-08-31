// ===============================
// CRUD Ingresos (API Laravel)
// ===============================

const API_URL = "/api/ingresos";
const formIngreso = document.getElementById("formIngreso");
const modalIngreso = document.getElementById("incomeModal");
const btnCerrarModal = document.getElementById("cancelModal");
const tipoIngreso = document.getElementById("tipo");
const selectRecurrencia = document.getElementById("recurrencia");
const recurrenciaContainer = document.getElementById('recurrenciaContainer');
let editandoId = null;

// Variable para almacenar los datos del formulario antes de abrir el modal de conceptos
let formDataBackup = {};

// ===============================
// Utilidades
// ===============================
function openModal(modal) {
  modal.classList.add("active");
  
  // Solo resetear el formulario si es un nuevo registro
  if (modal === modalIngreso && !editandoId) {
    formIngreso.reset();
    // Ocultar recurrencia por defecto al abrir
    recurrenciaContainer.style.display = 'none';
    selectRecurrencia.value = 'ninguna';
  }
}

function closeModal(modal, limpiarFormulario = false) {
  modal.classList.remove("active");

  if(limpiarFormulario) {
    formIngreso.reset();
    editandoId = null;
    // Ocultar recurrencia si es modal nuevo
    selectRecurrencia.style.display = "none";
    selectRecurrencia.disabled = true;
  }
}

// Función para respaldar los datos del formulario
function backupFormData() {
  formDataBackup = {
    tipo: document.getElementById('tipo').value,
    monto: document.getElementById('monto').value,
    fecha: document.getElementById('fecha').value,
    estado: document.getElementById('estado').value,
    recurrencia: document.getElementById('recurrencia').value
  };
}

// Función para restaurar los datos del formulario
function restoreFormData() {
  if (formDataBackup.tipo) document.getElementById('tipo').value = formDataBackup.tipo;
  if (formDataBackup.monto) document.getElementById('monto').value = formDataBackup.monto;
  if (formDataBackup.fecha) document.getElementById('fecha').value = formDataBackup.fecha;
  if (formDataBackup.estado) document.getElementById('estado').value = formDataBackup.estado;
  if (formDataBackup.recurrencia) document.getElementById('recurrencia').value = formDataBackup.recurrencia;
  
  // Mostrar u ocultar recurrencia según tipo
  if (formDataBackup.tipo === 'Proyección') {
    recurrenciaContainer.style.display = 'block';
  }
}

// ===============================
// Crear / Editar (POST o PATCH)
// ===============================
formIngreso.addEventListener("submit", async (e) => {
  e.preventDefault();

  let ingreso = {
    tipo: formIngreso.tipo.value,
    monto: formIngreso.monto.value,
    descripcion: formIngreso.descripcion ? formIngreso.descripcion.value : '',
    fecha_registro: formIngreso.fecha_registro.value,
    concepto_ingreso_id: document.getElementById('concepto_id').value,
    recurrencia: selectRecurrencia.value,
  };

  try {
    let url = API_URL;
    let method = "POST";

    if (editandoId) {
      url = `${API_URL}/${editandoId}`;
      method = "PATCH";
    }

    let res = await fetch(url, {
      method: method,
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(ingreso),
    });

    if (res.ok) {
      alert(editandoId ? "Ingreso actualizado" : "Ingreso creado");
      closeModal(modalIngreso);
      $('#tablaIngresos').DataTable().ajax.reload();
    } else {
      alert("Error al guardar ingreso");
    }
  } catch (error) {
    console.error("Error al guardar ingreso:", error);
  }
});

// ===============================
// Editar (llenar modal y setear ID)
// ===============================
async function editarIngreso(id) {
  try {
    let res = await fetch(`${API_URL}/${id}`);
    if (!res.ok) throw new Error("Ingreso no encontrado");
    let ingreso = await res.json();

    formIngreso.tipo.value = ingreso.tipo;
    formIngreso.monto.value = ingreso.monto;
    if (formIngreso.descripcion) {
      formIngreso.descripcion.value = ingreso.descripcion || '';
    }
    formIngreso.fecha_registro.value = ingreso.fecha_registro;
    document.getElementById('concepto_id').value = ingreso.concepto_ingreso_id;
    selectRecurrencia.value = ingreso.recurrencia || 'ninguna';

    // Mostrar u ocultar recurrencia según tipo
    if (ingreso.tipo === 'Proyección') {
      recurrenciaContainer.style.display = 'block';
    } else {
      recurrenciaContainer.style.display = 'none';
      selectRecurrencia.value = 'ninguna';
    }

    editandoId = id;
    openModal(modalIngreso);
  } catch (error) {
    console.error("Error al editar:", error);
  }
}

// ===============================
// Eliminar (DELETE)
// ===============================
async function eliminarIngreso(id) {
  if (!confirm("¿Seguro que quieres eliminar este ingreso?")) return;

  try {
    let res = await fetch(`${API_URL}/${id}`, { method: "DELETE" });

    if (res.ok) {
      alert("Ingreso eliminado");
      $('#tablaIngresos').DataTable().ajax.reload();
    } else {
      alert("Error al eliminar ingreso");
    }
  } catch (error) {
    console.error("Error al eliminar:", error);
  }
}

// ===============================
// Inicialización DataTable
// ===============================
$(document).ready(function () {
  const tabla = $('#tablaIngresos').DataTable({
    ajax: {
      url: '/api/ingresos/full',
      dataSrc: ''
    },
    columns: [
      { data: 'id' },          // ID
      { data: 'concepto' },    // Concepto
      { data: 'monto' },       // Monto
      { data: 'tipo' },        // Tipo
      { data: 'fecha' },       // Fecha
      {
        data: 'estado',
        render: function(data, type, row) {
          return row.tipo === "Proyección" ? data : '';
        }
      },
      {
        data: null,            // Acciones
        render: function (data, type, row) {
          return row.tipo === "Ingreso"
            ? `<button class="btn-edit" data-id="${row.id}"><i class="fas fa-edit"></i></button>
               <button class="btn-delete" data-id="${row.id}"><i class="fas fa-trash"></i></button>`
            : '';
        }
      }
    ],
    dom: 'Bfrtip',
    buttons: ['copy', 'excel', 'pdf', 'print'],
    responsive: true,
    language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" }
  });

  // Delegación de eventos
  $('#tablaIngresos tbody').on('click', '.btn-edit', function () {
    editarIngreso($(this).data('id'));
  });
  $('#tablaIngresos tbody').on('click', '.btn-delete', function () {
    eliminarIngreso($(this).data('id'));
  });
});

// ===============================
// Modal close
// ===============================
if (btnCerrarModal) {
  btnCerrarModal.addEventListener("click", () => closeModal(modalIngreso));
}

// ===============================
// Barra de búsqueda personalizada
// ===============================
$('#searchInput').on('keyup', function () {
  $('#tablaIngresos').DataTable().search(this.value).draw();
});

// ===============================
// Filtros personalizados Estado / Tipo
// ===============================
$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
  const estado = $('#statusFilter').val();
  const tipo = $('#typeFilter').val();
  const estadoCol = data[5]; // Estado
  const tipoCol = data[3];   // Tipo

  if (estado !== 'all' && estadoCol !== estado) return false;
  if (tipo !== 'all' && tipoCol !== tipo) return false;
  return true;
});

$('#statusFilter, #typeFilter').on('change', function () {
  $('#tablaIngresos').DataTable().draw();
});

// ===============================
// Conceptos ingreso
// ===============================
let conceptosIngreso = [];

async function cargarConceptosIngreso() {
  try {
    const res = await fetch('/api/conceptos-ingreso');
    const data = await res.json();
    conceptosIngreso = data.conceptosIngreso || data;
    renderConceptosGrid(conceptosIngreso);
  } catch (error) {
    console.error('Error al cargar conceptos:', error);
  }
}

function renderConceptosGrid(conceptos) {
  const grid = document.getElementById('conceptosGrid');
  grid.innerHTML = '';
  conceptos.forEach(concepto => {
    const div = document.createElement('div');
    div.className = 'concepto-item';
    div.innerHTML = `<strong>${concepto.nombre}</strong><br><span class="concepto-desc">${concepto.descripcion || ''}</span>`;
    div.dataset.id = concepto.concepto_ingreso_id || concepto.id;
    div.addEventListener('click', () => seleccionarConcepto(concepto));
    grid.appendChild(div);
  });
}

// Función para seleccionar un concepto desde el modal
function seleccionarConcepto(concepto) {
  const inputConcepto = document.getElementById('concepto');
  const inputConceptoId = document.getElementById('concepto_id');
  const descripcionSpan = document.getElementById('conceptoDescripcion');

  // 1. Mostrar el nombre seleccionado en el input del formulario
  inputConcepto.value = concepto.nombre;

  // 2. Guardar el ID del concepto en el hidden
  inputConceptoId.value = concepto.concepto_ingreso_id || concepto.id;

  // 3. Ocultar la descripción (ya no se mostrará en el formulario)
  descripcionSpan.textContent = '';

  // 4. Restaurar los datos del formulario que estaban antes de abrir el modal
  restoreFormData();

  // 5. Cerrar el modal
  closeModal(document.getElementById('conceptoModal'));
}

// Abrir el modal al hacer clic en el input o el botón
document.getElementById('concepto').addEventListener('click', () => {
  // Respalda los datos del formulario antes de abrir el modal
  backupFormData();
  openModal(document.getElementById('conceptoModal'));
  cargarConceptosIngreso();
});

document.getElementById('selectConcepto').addEventListener('click', () => {
  // Respalda los datos del formulario antes de abrir el modal
  backupFormData();
  openModal(document.getElementById('conceptoModal'));
  cargarConceptosIngreso();
});

// Buscar conceptos en modal
document.getElementById('searchConcepto').addEventListener('input', function() {
  const filtro = this.value.toLowerCase();
  const filtrados = conceptosIngreso.filter(c =>
    c.nombre.toLowerCase().includes(filtro)
  );
  renderConceptosGrid(filtrados);
});

// Cerrar modal de conceptos
document.getElementById('closeConceptoModal').addEventListener('click', () => {
  // Restaurar los datos del formulario al cancelar
  restoreFormData();
  closeModal(document.getElementById('conceptoModal'));
});

document.getElementById('cancelConceptoModal').addEventListener('click', () => {
  // Restaurar los datos del formulario al cancelar
  restoreFormData();
  closeModal(document.getElementById('conceptoModal'));
});

// ===============================
// Mostrar/ocultar recurrencia al cambiar tipo
// ===============================
tipoIngreso.addEventListener('change', function() {
  if (this.value === 'Proyección') {
    recurrenciaContainer.style.display = 'block';
  } else {
    recurrenciaContainer.style.display = 'none';
    selectRecurrencia.value = 'ninguna';
  }
});