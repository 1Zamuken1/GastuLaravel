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
// Modal Ahorro (Añadir)
// -------------------------------
const addAhorroBtn = document.getElementById("addAhorroBtn");
const createAhorroModal = document.getElementById("createAhorroModal");

if (addAhorroBtn) {
    addAhorroBtn.addEventListener("click", () => {
        // Limpiar formulario
        document.getElementById("formCreateAhorro").reset();
        const modalBootstrap = new bootstrap.Modal(createAhorroModal);
        modalBootstrap.show();
    });
}

// -------------------------------
// Modal Editar Ahorro
// -------------------------------
const editAhorroModal = document.getElementById("editAhorroModal");

// -------------------------------
// Modal Ver Ahorro
// -------------------------------
const showAhorroModal = document.getElementById("showAhorroModal");

// -------------------------------
// Modal Aportes
// -------------------------------
const indexAporteModal = document.getElementById("indexAporteModal");
const saveAportesBtn = document.getElementById("saveAportesBtn");

// ===============================
// Botón "Hoy" -> Poner fecha actual
// ===============================
const btnHoy = document.getElementById("btnHoy");
const inputFechaMeta = document.getElementById("fecha_meta");
if (btnHoy && inputFechaMeta) {
    btnHoy.addEventListener("click", () => {
        const hoy = new Date();
        const yyyy = hoy.getFullYear();
        const mm = String(hoy.getMonth() + 1).padStart(2, "0");
        const dd = String(hoy.getDate()).padStart(2, "0");
        inputFechaMeta.value = `${yyyy}-${mm}-${dd}`;
    });
}

// ===============================
// Botones Ver
// ===============================
document.addEventListener("DOMContentLoaded", function() {
    const savingTable = document.getElementById("savingTable");
    
    if (savingTable) {
        savingTable.addEventListener("click", function(e) {
            if (e.target.closest('.view-ahorro-btn')) {
                const btn = e.target.closest('.view-ahorro-btn');
                const table = $("#savingTable").DataTable();
                const tr = btn.closest("tr");
                const rowIdx = table.row(tr).index();
                const data = table.row(rowIdx).data();

                // data: 0: ID, 1: Concepto, 2: Monto Meta, 3: Total Acumulado, 4: Avance, 5: Frecuencia, 6: Fecha Meta, 7: Estado
                const id = data[0];
                const concepto = data[1];
                const montoMeta = data[2];
                const totalAcumulado = data[3];
                const avance = data[4];
                const frecuencia = data[5];
                let fechaMeta = data[6];
                const estado = data[7];
                const descripcion = tr.getAttribute("data-descripcion") || "";

                // Convertir fecha si viene como d/m/Y
                if (fechaMeta.includes("/")) {
                    const [d, m, y] = fechaMeta.split("/");
                    fechaMeta = `${d.padStart(2, "0")}/${m.padStart(2, "0")}/${y}`;
                }

                document.getElementById("show_concepto").textContent = concepto;
                document.getElementById("show_descripcion").textContent = descripcion;
                document.getElementById("show_monto_meta").textContent = montoMeta;
                document.getElementById("show_total_acumulado").textContent = totalAcumulado;
                document.getElementById("show_frecuencia").textContent = frecuencia;
                document.getElementById("show_fecha_meta").textContent = fechaMeta;
                document.getElementById("show_estado").textContent = estado.replace(/<[^>]*>/g, ''); // Remover HTML del badge

                // Obtener próxima cuota
                obtenerProximaCuota(id);

                const modalBootstrap = new bootstrap.Modal(showAhorroModal);
                modalBootstrap.show();
            }

            // ===============================
            // Botones Editar
            // ===============================
            if (e.target.closest('.edit-ahorro-btn')) {
                const btn = e.target.closest('.edit-ahorro-btn');
                const table = $("#savingTable").DataTable();
                const tr = btn.closest("tr");
                const rowIdx = table.row(tr).index();
                const data = table.row(rowIdx).data();

                const id = data[0];
                const concepto = data[1];
                const montoMeta = data[2].replace(/[^0-9.,-]/g, "").replace(",", ".").trim();
                const frecuencia = data[5];
                const fechaMeta = data[6].split("/").reverse().join("-");
                const descripcion = tr.getAttribute("data-descripcion") || "";

                document.getElementById("editId").value = id;
                document.getElementById("edit_concepto").value = concepto;
                document.getElementById("edit_monto_meta").value = montoMeta;
                document.getElementById("edit_frecuencia").value = frecuencia;
                document.getElementById("edit_fecha_meta").value = fechaMeta;
                document.getElementById("edit_descripcion").value = descripcion;

                // Cambiar action del formulario
                const form = document.getElementById("formEditAhorro");
                form.action = `/ahorros/${id}`;

                const modalBootstrap = new bootstrap.Modal(editAhorroModal);
                modalBootstrap.show();
            }

            // ===============================
            // Botones Eliminar
            // ===============================
            if (e.target.closest('.delete-ahorro-btn')) {
                const btn = e.target.closest('.delete-ahorro-btn');
                const table = $("#savingTable").DataTable();
                const tr = btn.closest("tr");
                const rowIdx = table.row(tr).index();
                const data = table.row(rowIdx).data();

                const id = data[0];
                const concepto = data[1];

                if (confirm(`¿Estás seguro de eliminar el ahorro "${concepto}"?`)) {
                    // Crear formulario dinámico para eliminar
                    const form = document.createElement("form");
                    form.method = "POST";
                    form.action = `/ahorros/${id}`;

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
                }
            }

            // ===============================
            // Botones Aportes
            // ===============================
            if (e.target.closest('.aporte-btn')) {
                const btn = e.target.closest('.aporte-btn');
                const table = $("#savingTable").DataTable();
                const tr = btn.closest("tr");
                const rowIdx = table.row(tr).index();
                const data = table.row(rowIdx).data();

                const id = data[0];
                const concepto = data[1];
                const montoMeta = data[2];
                const totalAcumulado = data[3];
                const frecuencia = data[5];

                // Cargar información del ahorro en el modal
                document.getElementById("aportes_concepto").textContent = concepto;
                document.getElementById("aportes_monto_meta").textContent = montoMeta;
                document.getElementById("aportes_total_acumulado").textContent = totalAcumulado;
                document.getElementById("aportes_frecuencia").textContent = frecuencia;

                // Cargar aportes
                cargarAportes(id);

                const modalBootstrap = new bootstrap.Modal(indexAporteModal);
                modalBootstrap.show();
            }
        });
    }
});

// ===============================
// Función para obtener próxima cuota
// ===============================
function obtenerProximaCuota(ahorroId) {
    fetch(`/aportes/${ahorroId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 200 && data.aportes) {
                const proximoAporte = data.aportes.find(aporte => aporte.estado === 'Pendiente');
                if (proximoAporte) {
                    const fecha = new Date(proximoAporte.fecha_limite);
                    const fechaFormateada = fecha.toLocaleDateString('es-ES');
                    document.getElementById("show_proxima_cuota").textContent = fechaFormateada;
                } else {
                    document.getElementById("show_proxima_cuota").textContent = "No hay cuotas pendientes";
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById("show_proxima_cuota").textContent = "Error al cargar";
        });
}

// ===============================
// Función para cargar aportes
// ===============================
function cargarAportes(ahorroId) {
    fetch(`/aportes/${ahorroId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 200 && data.aportes) {
                const tbody = document.getElementById("aportesTableBody");
                tbody.innerHTML = "";

                // Obtener próxima cuota para mostrar en el header del modal
                const proximoAporte = data.aportes.find(aporte => aporte.estado === 'Pendiente');
                if (proximoAporte) {
                    const fecha = new Date(proximoAporte.fecha_limite);
                    const fechaFormateada = fecha.toLocaleDateString('es-ES');
                    document.getElementById("proxima_cuota").textContent = fechaFormateada;
                } else {
                    document.getElementById("proxima_cuota").textContent = "No hay cuotas pendientes";
                }

                data.aportes.forEach((aporte, index) => {
                    const fecha = new Date(aporte.fecha_limite);
                    const fechaFormateada = fecha.toLocaleDateString('es-ES');
                    
                    let badgeClass = '';
                    switch(aporte.estado) {
                        case 'Completada':
                            badgeClass = 'bg-success';
                            break;
                        case 'Perdida':
                            badgeClass = 'bg-danger';
                            break;
                        case 'Pendiente':
                            badgeClass = 'bg-warning';
                            break;
                        default:
                            badgeClass = 'bg-secondary';
                    }

                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td>${index + 1}</td>
                        <td>$${parseFloat(aporte.aporte_asignado || 0).toFixed(2)}</td>
                        <td>
                            ${aporte.estado === 'Pendiente' ? 
                                `<input type="number" class="form-control form-control-sm aporte-input" 
                                       data-id="${aporte.aporte_ahorro_id}" 
                                       value="${aporte.aporte || ''}" 
                                       step="0.01" min="0" 
                                       placeholder="Ingresa tu aporte">` : 
                                `$${parseFloat(aporte.aporte || 0).toFixed(2)}`
                            }
                        </td>
                        <td>${fechaFormateada}</td>
                        <td>
                            <span class="badge ${badgeClass}">${aporte.estado}</span>
                        </td>
                        <td>
                            ${aporte.estado === 'Pendiente' ? 
                                `<button class="btn btn-sm btn-outline-warning aporte-asignado-btn" 
                                         data-id="${aporte.aporte_ahorro_id}">
                                    Aportar Asignado
                                </button>` : 
                                '<span class="text-muted">-</span>'
                            }
                        </td>
                    `;
                    tbody.appendChild(fila);
                });

                // Agregar event listeners para los botones de aporte asignado
                tbody.addEventListener('click', function(e) {
                    if (e.target.classList.contains('aporte-asignado-btn')) {
                        const aporteId = e.target.getAttribute('data-id');
                        aportar(aporteId, 'asignado');
                    }
                });

            } else {
                document.getElementById("aportesTableBody").innerHTML = 
                    '<tr><td colspan="6" class="text-center">No hay aportes registrados</td></tr>';
                document.getElementById("proxima_cuota").textContent = "Sin cuotas";
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById("aportesTableBody").innerHTML = 
                '<tr><td colspan="6" class="text-center">Error al cargar aportes</td></tr>';
        });
}

// ===============================
// Función para realizar aportes
// ===============================
function aportar(aporteId, tipo) {
    let url = '';
    let data = {};

    if (tipo === 'asignado') {
        url = `/aportes/${aporteId}/aportar-asignado`;
    } else {
        // Para aporte personalizado
        const input = document.querySelector(`input[data-id="${aporteId}"]`);
        const monto = input.value;
        
        if (!monto || parseFloat(monto) < 0) {
            alert('Por favor ingresa un monto válido');
            return;
        }

        url = `/aportes/${aporteId}`;
        data = { aporte: parseFloat(monto) };
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch(url, {
        method: tipo === 'asignado' ? 'POST' : 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 200) {
            alert(data.message);
            // Recargar la página para actualizar los datos
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar el aporte');
    });
}

// ===============================
// Guardar cambios de aportes
// ===============================
if (saveAportesBtn) {
    saveAportesBtn.addEventListener('click', function() {
        const inputs = document.querySelectorAll('.aporte-input');
        const aportes = [];

        inputs.forEach(input => {
            if (input.value && parseFloat(input.value) >= 0) {
                aportes.push({
                    id: input.getAttribute('data-id'),
                    monto: parseFloat(input.value)
                });
            }
        });

        if (aportes.length === 0) {
            alert('No hay aportes para guardar');
            return;
        }

        // Procesar cada aporte
        let procesados = 0;
        let errores = 0;

        aportes.forEach(aporte => {
            aportar(aporte.id, 'personalizado');
        });
    });
}

// ===============================
// Auto-ajuste del textarea
// ===============================
const descripcionFields = document.querySelectorAll('textarea[name="descripcion"]');
descripcionFields.forEach(field => {
    const autoResize = () => {
        field.style.height = "auto";
        field.style.height = field.scrollHeight + "px";
    };
    field.addEventListener("input", autoResize);
    autoResize();
});

// ===============================
// Validación de fechas
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    const fechaInputs = document.querySelectorAll('input[name="fecha_meta"]');
    
    fechaInputs.forEach(input => {
        input.addEventListener('change', function() {
            const hoy = new Date();
            const fechaSeleccionada = new Date(this.value);
            
            hoy.setHours(0, 0, 0, 0);
            fechaSeleccionada.setHours(0, 0, 0, 0);
            
            if (fechaSeleccionada <= hoy) {
                alert('La fecha meta debe ser posterior a hoy');
                this.value = '';
            }
        });
    });
});

// ===============================
// Manejar cambios en frecuencia para validaciones
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    const frecuenciaSelects = document.querySelectorAll('select[name="frecuencia"]');
    
    frecuenciaSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Aquí se puede agregar lógica adicional si es necesaria
            // como ajustar automáticamente fechas según la frecuencia
        });
    });
});