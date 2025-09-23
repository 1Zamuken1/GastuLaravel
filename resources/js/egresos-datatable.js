document.addEventListener("DOMContentLoaded", function () {
    // Verificar que la tabla existe antes de inicializar
    if (!document.getElementById("expenseTable")) {
        console.error("Tabla expenseTable no encontrada");
        return;
    }

    // Inicializar DataTable con manejo de errores
    try {
        let table = $("#expenseTable").DataTable({
            pageLength: 10,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
            },
            columnDefs: [
                {
                    targets: 0,
                    visible: false,
                    searchable: false,
                },
                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                },
            ],
            order: [[4, "desc"]],
            dom: "tip", // No mostramos los botones aquí
            autoWidth: false,
            responsive: true,
            buttons: [
                {
                    extend: "excelHtml5",
                    title: "Egresos y Proyecciones",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5], // Excluye ID y Acciones
                    },
                },
                {
                    extend: "pdfHtml5",
                    title: "Egresos y Proyecciones",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5],
                    },
                    orientation: "landscape",
                    pageSize: "A4",
                },
            ],
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

        // Filtro por tipo (Egreso/Proyección)
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
                `Mostrando ${info.start + 1} a ${info.end} de ${
                    info.recordsTotal
                } registros`
            );
        });

        // Botones personalizados para exportar
        document.getElementById("btnExportPDF")?.addEventListener("click", function () {
            table.button(1).trigger();
        });
        document.getElementById("btnExportExcel")?.addEventListener("click", function () {
            table.button(0).trigger();
        });

        // Forzar la primera actualización
        table.draw();

    } catch (error) {
        console.error("Error al inicializar DataTable:", error);
    }
});