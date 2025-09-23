{{-- resources/views/components/header.blade.php --}}
<header class="admin-header bg-dark shadow py-3 px-4 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <span class="fw-bold text-light fs-4">
            {{ Auth::user()->nombre ?? 'Usuario' }}
        </span>
    </div>
    <div class="d-flex align-items-center">
        {{-- <span class="me-3 text-light fw-bold">
            <i class="fas fa-user-circle me-2 text-primary"></i>
            {{ Auth::user()->nombre ?? 'Usuario' }}
        </span> --}}
        <form action="{{ route('logout') }}" method="POST" class="d-inline" id="logoutForm">
    @csrf
    <button type="button" class="btn btn-outline-light btn-sm custom-logout-btn" id="logoutBtn">
        <i class="fas fa-sign-out-alt me-1"></i> 
        Cerrar Sesión
    </button>
</form>
    </div>
</header>

{{-- Bootstrap CSS (incluir si no lo tienes globalmente) --}}
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .admin-header {
        position: sticky;
        top: 0;
        z-index: 1020;
        border-bottom: 2px solid #007bff;
    }
    
    .admin-header .custom-logout-btn {
        border-color: white;
        color: white;
        transition: all 0.3s ease;
    }
    
    .admin-header .custom-logout-btn:hover {
        background-color: white;
        border-color: white;
        color: #343a40;
        transform: translateY(-1px);
    }
    
    .admin-header .text-primary {
        color: #007bff !important;
    }
    
    .admin-header .text-light:hover {
        color: #f8f9fa !important;
        transition: color 0.3s ease;
    }
</style>
@endpush

{{-- JavaScript --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutForm = document.getElementById('logoutForm');
    
    if (logoutBtn && logoutForm) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Confirmación antes de cerrar sesión
            if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                // Mostrar loading en el botón
                logoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Cerrando...';
                logoutBtn.disabled = true;
                
                // Enviar formulario después de un pequeño delay
                setTimeout(function() {
                    logoutForm.submit();
                }, 500);
            }
        });
    }
    
    // Efecto hover para el nombre de usuario
    const userInfo = document.querySelector('.admin-header .text-light');
    if (userInfo) {
        userInfo.addEventListener('mouseenter', function() {
            this.style.cursor = 'default';
        });
    }
});
</script>
@endpush