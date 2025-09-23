{{-- resources/views/chatbot-financiero.blade.php --}}
@extends('layouts.app')

@section('title', 'Asistente Financiero IA')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/chatbot-financiero.css') }}">
@endpush

@section('content')
<div class="chatbot-container">
    <!-- Header -->
    <div class="chatbot-header">
       
        <h1 class="chatbot-title">Búho</h1>
        <p class="chatbot-subtitle">Tu compañero inteligente para decisiones financieras</p>
    </div>
    
    <!-- Área de chat -->
    <div id="chatArea" class="chat-area">
        <div class="message ia-message">
            <div class="message-content">
                <div class="message-author">Búho</div>
                <div class="message-text">
                    Hola {{ Auth::user()->nombre }}! Soy Búho, tu asistente financiero personal. 
                    Puedo ayudarte a analizar tus ingresos, egresos y ahorros de forma precisa. 
                    ¿En qué te puedo ayudar hoy?
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de mensaje -->
    <div class="chat-input-form">
        <div class="input-wrapper">
            <textarea 
                id="mensajeInput" 
                class="chat-input"
                placeholder="Pregúntame sobre tus finanzas..."
                rows="1"
            ></textarea>
        </div>
        <button id="enviarBtn" class="send-button">
            Enviar
        </button>
    </div>

    <!-- Botones de acciones rápidas -->
    <div class="quick-actions">
        <div class="quick-actions-title">Acciones Rápidas</div>
        <div class="quick-actions-grid">
            <button class="quick-action-btn balance" data-mensaje="¿Cuál es mi balance actual?">
                <span>Balance Actual</span>
            </button>
            <button class="quick-action-btn gastos" data-mensaje="¿Cuáles son mis mayores gastos?">
                <span>Mayores Gastos</span>
            </button>
            <button class="quick-action-btn ahorros" data-mensaje="¿Cómo van mis ahorros?">
                <span>Estado Ahorros</span>
            </button>
            <button class="quick-action-btn consejos" data-mensaje="Dame consejos para mejorar mis finanzas">
                <span>Consejos Financieros</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const chatArea = document.getElementById('chatArea');
    const mensajeInput = document.getElementById('mensajeInput');
    const enviarBtn = document.getElementById('enviarBtn');
    const accionesRapidas = document.querySelectorAll('.quick-action-btn');

    // Función para formatear texto con markdown básico
    function formatearMarkdown(texto) {
        return texto
            // Convertir **texto** a <strong>texto</strong>
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            // Convertir *texto* a <em>texto</em>
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            // Convertir saltos de línea a <br>
            .replace(/\n/g, '<br>')
            // Convertir números con formato de lista (1., 2., etc.) a lista HTML
            .replace(/^\d+\.\s(.+)$/gm, '<li>$1</li>')
            // Envolver listas en <ol>
            .replace(/(<li>.*<\/li>)/gs, '<ol>$1</ol>')
            // Convertir guiones de lista (-) a lista HTML
            .replace(/^-\s(.+)$/gm, '<li>$1</li>')
            // Convertir ### a h3, ## a h2, # a h1
            .replace(/^### (.*$)/gm, '<h3>$1</h3>')
            .replace(/^## (.*$)/gm, '<h2>$1</h2>')
            .replace(/^# (.*$)/gm, '<h1>$1</h1>');
    }

    function agregarMensaje(mensaje, esUsuario = false) {
        const div = document.createElement('div');
        div.className = `message ${esUsuario ? 'usuario-message' : 'ia-message'}`;
        
        const autor = esUsuario ? 'Tú' : 'Búho';
        const mensajeFormateado = esUsuario ? mensaje : formatearMarkdown(mensaje);
        
        div.innerHTML = `
            <div class="message-content">
                <div class="message-author">${autor}</div>
                <div class="message-text">${mensajeFormateado}</div>
            </div>
        `;
        
        chatArea.appendChild(div);
        chatArea.scrollTop = chatArea.scrollHeight;
    }

    function mostrarIndicadorEscribiendo() {
        const div = document.createElement('div');
        div.className = 'message ia-message typing-message';
        div.id = 'typing-indicator';
        
        div.innerHTML = `
            <div class="message-content">
                <div class="message-author">Búho</div>
                <div class="typing-indicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        `;
        
        chatArea.appendChild(div);
        chatArea.scrollTop = chatArea.scrollHeight;
    }

    function quitarIndicadorEscribiendo() {
        const indicator = document.getElementById('typing-indicator');
        if (indicator) {
            indicator.remove();
        }
    }

    async function enviarMensaje(mensaje) {
        if (!mensaje.trim()) return;
        
        agregarMensaje(mensaje, true);
        mensajeInput.value = '';
        enviarBtn.disabled = true;
        enviarBtn.textContent = 'Enviando...';
        
        // Mostrar indicador de que está escribiendo
        mostrarIndicadorEscribiendo();
        
        try {
            const response = await fetch('/ia/mensaje', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ mensaje })
            });
            
            const data = await response.json();
            
            // Quitar indicador de escribiendo
            quitarIndicadorEscribiendo();
            
            if (data.success) {
                agregarMensaje(data.respuesta);
            } else {
                agregarMensaje('Error: ' + (data.error || 'No se pudo procesar el mensaje'));
            }
        } catch (error) {
            quitarIndicadorEscribiendo();
            agregarMensaje('Error de conexión. Por favor intenta de nuevo.');
        } finally {
            enviarBtn.disabled = false;
            enviarBtn.textContent = 'Enviar';
            mensajeInput.focus();
        }
    }

    // Auto-resize del textarea
    mensajeInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    enviarBtn.addEventListener('click', () => enviarMensaje(mensajeInput.value));
    mensajeInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            enviarMensaje(mensajeInput.value);
        }
    });
    
    accionesRapidas.forEach(btn => {
        btn.addEventListener('click', () => enviarMensaje(btn.getAttribute('data-mensaje')));
    });

    mensajeInput.focus();
</script>
@endpush