document.addEventListener("DOMContentLoaded", function () {
    // Inicializar DataTable una sola vez
    let table = $("#incomeTable").DataTable({
        pageLength: 10,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        columnDefs: [
            {
                targets: -1, // última columna (acciones)
                orderable: false,
                searchable: false
            }
        ],
        // 🔴 Ocultar controles de DataTables (buscador y length menu)
        dom: 'tip' // t = tabla, i = info, p = paginación
    });

    // =========================
    // Filtros personalizados
    // =========================

    // Filtro por estado (Activo/Inactivo)
    $("#statusFilter").on("change", function () {
        let value = this.value;
        if (value === "all") {
            table.column(5).search("").draw(); // columna Estado
        } else {
            table.column(5).search(value).draw();
        }
    });

    // Filtro por tipo (Ingreso/Proyección)
    $("#typeFilter").on("change", function () {
        let value = this.value;
        if (value === "all") {
            table.column(3).search("").draw(); // columna Tipo
        } else {
            table.column(3).search(value).draw();
        }
    });

    // Buscador general
    $("#searchInput").on("keyup", function () {
        table.search(this.value).draw();
    });

    // =========================
    // ✅ Usar solo tu propio selector
    // =========================
    $("#recordsPerPage").on("change", function () {
        let value = this.value === "Todos" ? -1 : parseInt(this.value);
        table.page.len(value).draw();
    });

    // Actualizar info paginación
    table.on("draw", function () {
        let info = table.page.info();
        $("#paginationInfo").html(
            `Mostrando ${info.start + 1} a ${info.end} de ${info.recordsTotal} registros`
        );
    });

    // Forzar la primera actualización
    table.draw();
});
