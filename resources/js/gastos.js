document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('myModal');
    var btn = document.getElementById("agregar-gasto");
    var span = document.getElementsByClassName("cerrar")[0];

    // ABRIR MODAL
    if (btn) {
        btn.onclick = function () {
            modal.style.display = "block";
        };
    }

    // CERRAR MODAL CON X
    if (span) {
        span.onclick = function () {
            modal.style.display = "none";
        };
    }

    // CERRAR MODAL AL CLIC FUERA
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    // ALERTA DE ÉXITO CON SWEETALERT2
    if (typeof window.gastoSuccess !== "undefined") {
        Swal.fire({
            title: 'Éxito',
            text: window.gastoSuccess,
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    }
    document.querySelector("form").addEventListener("submit", function(e) {
    const btn = this.querySelector("button[type=submit]");
    btn.disabled = true;
    btn.innerText = "Guardando...";
});
});
