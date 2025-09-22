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
        const form = document.getElementById("formCreateAhorro");
        if (form) {
            form.reset();
            // Limpiar errores de validación previos
            clearValidationErrors();
        }
        const modalBootstrap = new bootstrap.Modal(createAhorroModal);
        modalBootstrap.show();
    });
}

// Función para limpiar errores de validación
function clearValidationErrors() {
    const errorElements = document.querySelectorAll('.invalid-feedback, .text-danger');
    errorElements.forEach(el => el.remove());
    
    const invalidInputs = document.querySelectorAll('.is-invalid');
    invalidInputs.forEach(input => input.classList.remove('is-invalid'));
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
// Botón "Hoy" -> Poner fecha actual (corregido)
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    const btnHoy = document.getElementById("btnHoy");
    const inputFechaMeta = document.getElementById("fecha_meta");
    
    if (btnHoy && inputFechaMeta) {
        btnHoy.addEventListener("click", function(e) {
            e.preventDefault(); // Evitar submit del formulario
            const mañana = new Date();
            mañana.setDate(mañana.getDate() + 1); // Fecha de mañana para cumplir con "after:today"
            
            const yyyy = mañana.getFullYear();
            const mm = String(mañana.getMonth() + 1).padStart(2, "0");
            const dd = String(mañana.getDate()).padStart(2, "0");
            inputFechaMeta.value = `${yyyy}-${mm}-${dd}`;
        });
    }
});

// ===============================
// Validación del formulario antes del envío
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formCreateAhorro');
    if (form) {
        form.addEventListener('submit', function(e) {
            const isValid = validateForm(this);
            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });
    }
    
    const editForm = document.getElementById('formEditAhorro');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const isValid = validateForm(this);
            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });
    }
});

function validateForm(form) {
    clearValidationErrors();
    let isValid = true;
    
    // Validar concepto
    const concepto = form.querySelector('input[name="concepto"]');
    if (!concepto.value.trim()) {
        showFieldError(concepto, 'El concepto es obligatorio');
        isValid = false;
    } else if (concepto.value.trim().length > 60) {
        showFieldError(concepto, 'El concepto no puede exceder 60 caracteres');
        isValid = false;
    }
    
    // Validar monto meta
    const montoMeta = form.querySelector('input[name="monto_meta"]');
    if (!montoMeta.value || parseFloat(montoMeta.value) <= 0) {
        showFieldError(montoMeta, 'El monto meta debe ser mayor a 0');
        isValid = false;
    }
    
    // Validar frecuencia
    const frecuencia = form.querySelector('select[name="frecuencia"]');
    if (!frecuencia.value) {
        showFieldError(frecuencia, 'La frecuencia es obligatoria');
        isValid = false;
    }
    
    // Validar fecha meta
    const fechaMeta = form.querySelector('input[name="fecha_meta"]');
    if (!fechaMeta.value) {
        showFieldError(fechaMeta, 'La fecha meta es obligatoria');
        isValid = false;
    } else {
        const hoy = new Date();
        const fechaSeleccionada = new Date(fechaMeta.value);
        hoy.setHours(0, 0, 0, 0);
        fechaSeleccionada.setHours(0, 0, 0, 0);
        
        if (fechaSeleccionada <= hoy) {
            showFieldError(fechaMeta, 'La fecha meta debe ser posterior a hoy');
            isValid = false;
        }
    }
    
    return isValid;
}

function showFieldError(field, message) {
    field.classList.add('is-invalid');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
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
                
                // Verificar si DataTable está inicializado
                if ($.fn.DataTable.isDataTable('#savingTable')) {
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
                } else {
                    // Fallback si no hay DataTable
                    console.warn('DataTable no está inicializado');
                }
            }

            // ===============================
            // Botones Editar
            // ===============================
            if (e.target.closest('.edit-ahorro-btn')) {
                const btn = e.target.closest('.edit-ahorro-btn');
                
                if ($.fn.DataTable.isDataTable('#savingTable')) {
                    const table = $("#savingTable").DataTable();
                    const tr = btn.closest("tr");
                    const rowIdx = table.row(tr).index();
                    const data = table.row(rowIdx).data();

                    const id = data[0];
                    const concepto = data[1];
                    const montoMeta = data[2].toString().replace(/[^0-9.,-]/g, "").replace(",", ".").trim();
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
            }

            // ===============================
            // Botones Eliminar
            // ===============================
            if (e.target.closest('.delete-ahorro-btn')) {
                const btn = e.target.closest('.delete-ahorro-btn');
                
                if ($.fn.DataTable.isDataTable('#savingTable')) {
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
            }

            // ===============================
            // Botones Aportes
            // ===============================
            if (e.target.closest('.aporte-btn')) {
                const btn = e.target.closest('.aporte-btn');
                
                if ($.fn.DataTable.isDataTable('#savingTable')) {
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
document.addEventListener('DOMContentLoaded', function() {
    const descripcionFields = document.querySelectorAll('textarea[name="descripcion"]');
    descripcionFields.forEach(field => {
        const autoResize = () => {
            field.style.height = "auto";
            field.style.height = field.scrollHeight + "px";
        };
        field.addEventListener("input", autoResize);
        // Ejecutar al cargar para ajustar contenido inicial
        setTimeout(autoResize, 100);
    });
});

// ===============================
// Validación de fechas en tiempo real
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
                this.classList.add('is-invalid');
                
                // Remover mensaje de error previo si existe
                const existingError = this.parentNode.querySelector('.invalid-feedback');
                if (existingError) {
                    existingError.remove();
                }
                
                // Agregar nuevo mensaje de error
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'La fecha meta debe ser posterior a hoy';
                this.parentNode.appendChild(errorDiv);
                
                this.value = '';
            } else {
                this.classList.remove('is-invalid');
                const existingError = this.parentNode.querySelector('.invalid-feedback');
                if (existingError) {
                    existingError.remove();
                }
            }
        });
    });
});

// ===============================
// Validación de monto en tiempo real
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    const montoInputs = document.querySelectorAll('input[name="monto_meta"]');
    
    montoInputs.forEach(input => {
        input.addEventListener('input', function() {
            const valor = parseFloat(this.value);
            
            if (this.value && (isNaN(valor) || valor <= 0)) {
                this.classList.add('is-invalid');
                
                // Remover mensaje de error previo si existe
                const existingError = this.parentNode.querySelector('.invalid-feedback');
                if (existingError) {
                    existingError.remove();
                }
                
                // Agregar nuevo mensaje de error
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'El monto debe ser mayor a 0';
                this.parentNode.appendChild(errorDiv);
            } else {
                this.classList.remove('is-invalid');
                const existingError = this.parentNode.querySelector('.invalid-feedback');
                if (existingError) {
                    existingError.remove();
                }
            }
        });
    });
});

// ===============================
// Manejar cambios en frecuencia
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    const frecuenciaSelects = document.querySelectorAll('select[name="frecuencia"]');
    
    frecuenciaSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Remover cualquier error de validación previo
            this.classList.remove('is-invalid');
            const existingError = this.parentNode.querySelector('.invalid-feedback');
            if (existingError) {
                existingError.remove();
            }
        });
    });
});

// ===============================
// Prevenir envío múltiple del formulario
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';
                
                // Rehabilitar botón después de 5 segundos por si hay error
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = submitBtn.getAttribute('data-original-text') || 'Guardar';
                }, 5000);
            }
        });
        
        // Guardar texto original del botón
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.setAttribute('data-original-text', submitBtn.innerHTML);
        }
    });
});

// ===============================
// Mostrar alertas de éxito/error
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si hay mensajes de sesión para mostrar
    const successMessage = document.querySelector('.alert-success');
    const errorMessage = document.querySelector('.alert-danger');
    
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.transition = 'opacity 0.5s';
            successMessage.style.opacity = '0';
            setTimeout(() => {
                successMessage.remove();
            }, 500);
        }, 5000);
    }
    
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.transition = 'opacity 0.5s';
            errorMessage.style.opacity = '0';
            setTimeout(() => {
                errorMessage.remove();
            }, 500);
        }, 8000);
    }
});

// ===============================
// Formateo de números en inputs
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    const montoInputs = document.querySelectorAll('input[type="number"][step="0.01"]');
    
    montoInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value) {
                const valor = parseFloat(this.value);
                if (!isNaN(valor)) {
                    this.value = valor.toFixed(2);
                }
            }
        });
    });
});