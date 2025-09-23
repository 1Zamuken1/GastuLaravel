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
// Variables globales
// ===============================
let currentAhorroId = null;

function getCurrentAhorroId() {
    return currentAhorroId;
}

function setCurrentAhorroId(id) {
    currentAhorroId = id;
}

// ===============================
// Event Listeners Principal - DOMContentLoaded
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    // Validación del formulario antes del envío
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

    // ===============================
    // Event Delegation para todos los botones de la tabla
    // ===============================
    const savingTable = document.getElementById("savingTable");
    
    if (savingTable) {
        savingTable.addEventListener("click", function(e) {
            
            // ===============================
            // Botones Ver
            // ===============================
            if (e.target.closest('.view-ahorro-btn')) {
                const btn = e.target.closest('.view-ahorro-btn');
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
                    const fechaCreacion = tr.getAttribute("data-fecha_creacion") || "";
                    const cantidadCuotas = tr.getAttribute("data-cantidad_cuotas") || "";

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
                    document.getElementById("show_fecha_creacion").textContent = fechaCreacion;
                    document.getElementById("show_cantidad_cuotas").textContent = cantidadCuotas;

                    // Obtener próxima cuota
                    obtenerProximaCuota(id);

                    const modalBootstrap = new bootstrap.Modal(showAhorroModal);
                    modalBootstrap.show();
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
                    form.action = btn.getAttribute('data-update-url');

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

                    if (confirm(`¿Estás seguro de eliminar el ahorro "${data[1]}"?`)) {
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
            // Botones Aportes - CON DEBUG
            // ===============================
            if (e.target.closest('.aporte-btn')) {
                console.log('Botón de aportes clickeado');
                const btn = e.target.closest('.aporte-btn');
                
                // DEBUG: Verificar que DataTable esté inicializada
                console.log('DataTable inicializada:', $.fn.DataTable.isDataTable('#savingTable'));
                
                if ($.fn.DataTable.isDataTable('#savingTable')) {
                    const table = $("#savingTable").DataTable();
                    const tr = btn.closest("tr");
                    
                    // DEBUG: Verificar elementos
                    console.log('Botón:', btn);
                    console.log('Fila TR:', tr);
                    
                    const rowIdx = table.row(tr).index();
                    console.log('Índice de fila:', rowIdx);
                    
                    const data = table.row(rowIdx).data();
                    console.log('Datos completos de la fila:', data);
                    console.log('Tipo de datos:', typeof data);
                    console.log('Es array:', Array.isArray(data));
                    
                    // DEBUG: Verificar cada posición del array
                    if (Array.isArray(data)) {
                        data.forEach((item, index) => {
                            console.log(`data[${index}]:`, item);
                        });
                    }
                    
                    // MÉTODO ALTERNATIVO 1: Obtener ID desde atributo data-id del botón
                    let id = btn.getAttribute('data-id');
                    console.log('ID desde atributo data-id:', id);
                    
                    // MÉTODO ALTERNATIVO 2: Obtener ID desde atributo de la fila
                    if (!id) {
                        id = tr.getAttribute('data-id') || tr.getAttribute('id');
                        console.log('ID desde atributos de fila:', id);
                    }
                    
                    // MÉTODO ALTERNATIVO 3: Obtener ID desde la primera celda visible
                    if (!id && data && Array.isArray(data)) {
                        id = data[0];
                        console.log('ID desde data[0]:', id);
                    }
                    
                    // MÉTODO ALTERNATIVO 4: Buscar en todas las posiciones
                    if (!id && Array.isArray(data)) {
                        // Buscar un valor que parezca un ID (número)
                        for (let i = 0; i < data.length; i++) {
                            if (!isNaN(data[i]) && data[i] !== '' && data[i] !== null) {
                                id = data[i];
                                console.log(`ID encontrado en data[${i}]:`, id);
                                break;
                            }
                        }
                    }
                    
                    console.log('ID FINAL a usar:', id);
                    
                    // Solo continuar si tenemos un ID válido
                    if (!id || id === '' || id === null || id === undefined) {
                        console.error('❌ NO SE PUDO OBTENER UN ID VÁLIDO');
                        alert('Error: No se pudo identificar el ahorro seleccionado. Revisa la consola.');
                        return;
                    }
                    
                    // Resto del código original
                    const concepto = data[1];
                    const montoMeta = data[2];
                    const totalAcumulado = data[3];
                    const frecuencia = data[5];

                    // Cargar información del ahorro en el modal
                    document.getElementById("aportes_concepto").textContent = concepto;
                    
                    // Verificar si estos elementos existen antes de asignar valores
                    const montoMetaElement = document.getElementById("aportes_monto_meta");
                    const totalAcumuladoElement = document.getElementById("aportes_total_acumulado");
                    
                    if (montoMetaElement) montoMetaElement.textContent = montoMeta;
                    if (totalAcumuladoElement) totalAcumuladoElement.textContent = totalAcumulado;
                   
                    // Cargar aportes del ahorro
                    cargarAportes(id);

                    // Obtener próxima cuota
                    obtenerProximaCuota(id);

                    const modalBootstrap = new bootstrap.Modal(indexAporteModal);
                    modalBootstrap.show();
                } else {
                    console.error('❌ DataTable no está inicializada');
                    alert('La tabla no está cargada correctamente');
                }
            }

        }); // Cierre del event listener de savingTable
    }

    // ===============================
    // Event Listener para botón de guardar aportes
    // ===============================
    if (saveAportesBtn) {
        saveAportesBtn.addEventListener('click', function() {
            const inputs = document.querySelectorAll('.aporte-input');
            const aportesParaGuardar = [];

            // Recopilar todos los aportes válidos
            inputs.forEach(input => {
                const valor = parseFloat(input.value);
                if (input.value && !isNaN(valor) && valor > 0) {
                    aportesParaGuardar.push({
                        id: input.getAttribute('data-id'),
                        monto: valor
                    });
                }
            });

            if (aportesParaGuardar.length === 0) {
                showNotification('No hay aportes válidos para guardar', 'error');
                return;
            }

            // Confirmar acción
            const mensaje = `¿Deseas guardar ${aportesParaGuardar.length} aporte(s)?`;
            if (!confirm(mensaje)) {
                return;
            }

            // Deshabilitar botón mientras se procesan
            this.disabled = true;
            const originalHTML = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            // Procesar aportes secuencialmente
            procesarAportesSecuencial(aportesParaGuardar, 0, () => {
                // Callback cuando termine el procesamiento
                this.disabled = false;
                this.innerHTML = originalHTML;
            });
        });
    }
}); // Cierre del DOMContentLoaded

// ===============================
// Funciones de validación
// ===============================
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
// Función para obtener próxima cuota
// ===============================
function obtenerProximaCuota(ahorroId) {
    fetch(`/aportes/${ahorroId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos para próxima cuota:', data);
            const aportes = data.aportes || [];
            const proximoAporte = aportes.find(aporte => aporte.estado === 'Pendiente');
            
            const proximaCuotaElement = document.getElementById("show_proxima_cuota");
            if (proximaCuotaElement) {
                if (proximoAporte) {
                    const fecha = new Date(proximoAporte.fecha_limite);
                    const fechaFormateada = fecha.toLocaleDateString('es-ES');
                    proximaCuotaElement.textContent = fechaFormateada;
                } else {
                    proximaCuotaElement.textContent = "No hay cuotas pendientes";
                }
            }
        })
        .catch(error => {
            console.error('Error al obtener próxima cuota:', error);
            const proximaCuotaElement = document.getElementById("show_proxima_cuota");
            if (proximaCuotaElement) {
                proximaCuotaElement.textContent = "Error al cargar";
            }
        });
}

// ===============================
// Función para cargar aportes
// ===============================
function cargarAportes(ahorroId) {
    console.log('Cargando aportes para ahorro ID:', ahorroId);
    
    // Almacenar el ID del ahorro actual
    setCurrentAhorroId(ahorroId);
    
    // Mostrar indicador de carga
    const tbody = document.getElementById('aportesTableBody');
    if (!tbody) {
        console.error('Elemento aportesTableBody no encontrado');
        return;
    }
    
    tbody.innerHTML = '<tr><td colspan="5" class="text-center">Cargando aportes...</td></tr>';
    
    fetch(`/aportes/${ahorroId}`)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            
            // Manejar diferentes estructuras de respuesta
            let aportes = [];
            if (data.aportes && Array.isArray(data.aportes)) {
                aportes = data.aportes;
            } else if (Array.isArray(data)) {
                aportes = data;
            } else {
                console.warn('Estructura de datos inesperada:', data);
                aportes = [];
            }
            
            console.log('Aportes procesados:', aportes);
            
            // Actualizar número de cuotas si el elemento existe
            const cuotasElement = document.getElementById("aportes_cuotas");
            if (cuotasElement) {
                cuotasElement.textContent = aportes.length;
            }
            
            // Limpiar tabla
            tbody.innerHTML = '';

            if (aportes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No hay aportes disponibles</td></tr>';
                return;
            }

            aportes.forEach((aporte, idx) => {
                console.log(`Procesando aporte ${idx + 1}:`, aporte);
                
                // Determinar el estado y crear el badge
                const estado = aporte.estado || 'Pendiente';
                let estadoBadge = '';
                
                switch(estado) {
                    case 'Pendiente':
                        estadoBadge = '<span class="badge bg-warning text-dark">Pendiente</span>';
                        break;
                    case 'Completado':
                        estadoBadge = '<span class="badge bg-success">Completado</span>';
                        break;
                    case 'Parcial':
                        estadoBadge = '<span class="badge bg-info">Parcial</span>';
                        break;
                    default:
                        estadoBadge = `<span class="badge bg-secondary">${estado}</span>`;
                }

                // Formatear fecha límite
                let fechaLimite = '';
                if (aporte.fecha_limite) {
                    try {
                        const fecha = new Date(aporte.fecha_limite);
                        fechaLimite = fecha.toLocaleDateString('es-ES');
                    } catch (e) {
                        fechaLimite = aporte.fecha_limite;
                    }
                }

                // Obtener el monto del aporte asignado
                const aporteAsignado = parseFloat(aporte.aporte_asignado || 0);
                const aporteRealizado = parseFloat(aporte.aporte || 0);

                // Solo mostrar botones de acción si el aporte está pendiente
                let acciones = '';
                if (estado === 'Pendiente') {
                    acciones = `
                        <div class="d-flex gap-1 align-items-center flex-wrap">
                            <button type="button" class="btn btn-success btn-sm aporte-asignado-btn" 
                                    data-id="${aporte.aporte_ahorro_id || aporte.id}" 
                                    data-monto="${aporteAsignado}">
                                Aportar $${aporteAsignado.toFixed(2)}
                            </button>
                            <div class="input-group" style="width:140px;">
                                <input type="number" class="form-control form-control-sm aporte-input" 
                                       min="0.01" step="0.01" 
                                       placeholder="Monto" value="${aporteRealizado || ''}" 
                                       data-id="${aporte.aporte_ahorro_id || aporte.id}">
                                <button type="button" class="btn btn-primary btn-sm aporte-personalizado-btn" 
                                        data-id="${aporte.aporte_ahorro_id || aporte.id}">
                                    <i class="fas fa-save"></i>
                                </button>
                            </div>
                        </div>
                    `;
                } else if (estado === 'Completado') {
                    acciones = `<span class="text-success fw-bold">$${aporteRealizado.toFixed(2)}</span>`;
                } else {
                    acciones = `<span class="text-muted">$${aporteRealizado.toFixed(2)}</span>`;
                }

                // Crear la fila de la tabla
                const fila = `
                    <tr>
                        <td>${idx + 1}</td>
                        <td class="fw-bold">$${aporteAsignado.toFixed(2)}</td>
                        <td>${acciones}</td>
                        <td>${fechaLimite}</td>
                        <td>${estadoBadge}</td>
                    </tr>
                `;
                
                tbody.innerHTML += fila;
            });

            // Agregar event listeners para los botones
            agregarEventListenersAportes();
            
        })
        .catch(error => {
            console.error('Error al cargar aportes:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle"></i> Error al cargar aportes: ${error.message}
                        <br><small class="text-muted">Revisa la consola para más detalles</small>
                    </td>
                </tr>
            `;
        });
}

// Resto de las funciones... (continúa igual)
function agregarEventListenersAportes() {
    document.querySelectorAll('.aporte-asignado-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const aporteId = this.getAttribute('data-id');
            const monto = this.getAttribute('data-monto');
            console.log('Aporte asignado clickeado:', aporteId, monto);
            aportar(aporteId, 'asignado', parseFloat(monto));
        });
    });

    document.querySelectorAll('.aporte-personalizado-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const aporteId = this.getAttribute('data-id');
            console.log('Aporte personalizado clickeado:', aporteId);
            aportar(aporteId, 'personalizado');
        });
    });

    document.querySelectorAll('.aporte-input').forEach(input => {
        input.addEventListener('input', function() {
            const valor = parseFloat(this.value);
            if (this.value && (isNaN(valor) || valor <= 0)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });

        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const aporteId = this.getAttribute('data-id');
                aportar(aporteId, 'personalizado');
            }
        });
    });
}

function aportar(aporteId, tipo, montoAsignado = null) {
    console.log('Realizando aporte:', aporteId, tipo, montoAsignado);
    
    let url = '';
    let data = {};
    let method = 'POST';

    if (tipo === 'asignado') {
        url = `/aportes/${aporteId}/aportar-asignado`;
        data = { aporte: montoAsignado };
    } else {
        const input = document.querySelector(`input[data-id="${aporteId}"]`);
        if (!input) {
            console.error('Input no encontrado para aporte ID:', aporteId);
            return;
        }
        
        const monto = parseFloat(input.value);
        
        if (!input.value || isNaN(monto) || monto <= 0) {
            alert('Por favor ingresa un monto válido mayor a 0');
            input.focus();
            input.classList.add('is-invalid');
            return;
        }

        url = `/aportes/${aporteId}`;
        method = 'PUT';
        data = { aporte: monto };
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('Token CSRF no encontrado');
        alert('Error de seguridad. Recarga la página e intenta nuevamente.');
        return;
    }

    const botones = document.querySelectorAll(`[data-id="${aporteId}"]`);
    botones.forEach(btn => {
        if (btn.tagName === 'BUTTON') {
            btn.disabled = true;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            btn.setAttribute('data-original-html', originalHTML);
        }
    });

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: Error del servidor`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta del servidor:', data);
        
        if (data.status === 200 || data.success) {
            const mensaje = data.message || 'Aporte realizado exitosamente';
            showNotification(mensaje, 'success');
            
            const ahorroId = getCurrentAhorroId();
            if (ahorroId) {
                cargarAportes(ahorroId);
            } else {
                location.reload();
            }
        } else {
            throw new Error(data.message || 'Error desconocido del servidor');
        }
    })
    .catch(error => {
        console.error('Error al procesar el aporte:', error);
        showNotification('Error al procesar el aporte: ' + error.message, 'error');
    })
    .finally(() => {
        botones.forEach(btn => {
            if (btn.tagName === 'BUTTON') {
                btn.disabled = false;
                const originalHTML = btn.getAttribute('data-original-html');
                if (originalHTML) {
                    btn.innerHTML = originalHTML;
                    btn.removeAttribute('data-original-html');
                } else if (btn.classList.contains('aporte-asignado-btn')) {
                    const monto = btn.getAttribute('data-monto');
                    btn.innerHTML = `Aportar $${parseFloat(monto).toFixed(2)}`;
                } else {
                    btn.innerHTML = '<i class="fas fa-save"></i>';
                }
            }
        });
    });
}

function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'error' ? 'alert-danger' : 'alert-info';
    
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

function procesarAportesSecuencial(aportes, indice, callback = null) {
    if (indice >= aportes.length) {
        showNotification('Todos los aportes han sido procesados', 'success');
        
        const ahorroId = getCurrentAhorroId();
        if (ahorroId) {
            cargarAportes(ahorroId);
        } else {
            location.reload();
        }
        
        if (callback) callback();
        return;
    }

    const aporte = aportes[indice];
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (!csrfToken) {
        console.error('Token CSRF no encontrado');
        if (callback) callback();
        return;
    }

    fetch(`/aportes/${aporte.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ aporte: aporte.monto })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status !== 200 && !data.success) {
            console.error(`Error en aporte ${indice + 1}:`, data.message);
        }
        procesarAportesSecuencial(aportes, indice + 1, callback);
    })
    .catch(error => {
        console.error(`Error al procesar aporte ${indice + 1}:`, error);
        procesarAportesSecuencial(aportes, indice + 1, callback);
    });
}