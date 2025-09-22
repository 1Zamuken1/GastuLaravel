document.addEventListener("DOMContentLoaded", function () {
    let table = $("#savingTable").DataTable({
        pageLength: 10,
        language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" },
        columnDefs: [
            { targets: 0, visible: false, searchable: false }, // ocultar ID
            { targets: -1, orderable: false, searchable: false }, // acciones
        ],
        dom: "tip",
        autoWidth: false,
        responsive: true,
    });

    // Filtro estado
    $("#statusFilter").on("change", function () {
        let value = this.value === "all" ? "" : this.value;
        table.column(5).search(value).draw();
    });

    // Buscador
    $("#searchInput").on("keyup", function () {
        table.search(this.value).draw();
    });

    // Info paginación
    table.on("draw", function () {
        let info = table.page.info();
        $("#paginationInfo").html(
            `Mostrando ${info.start + 1} a ${info.end} de ${info.recordsTotal} registros`
        );
    });

    table.draw();
});
