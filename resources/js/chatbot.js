// resources/js/chatbot.js
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const sendButtonText = document.getElementById('sendButtonText');
    const sendButtonSpinner = document.getElementById('sendButtonSpinner');
    const preguntasEjemplo = document.querySelectorAll('.pregunta-ejemplo');
    const limpiarChatBtn = document.getElementById('limpiarChat');

    // 🔥 VARIABLE DE CONTROL PARA EVITAR DOBLE ENVÍO
    let enviandoPreguntaFrecuente = false;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('Token CSRF no encontrado');
        return;
    }

    init();

    function init() {
        cargarEstadisticasRapidas();
        setupEventListeners();
    }

    function setupEventListeners() {
        sendButton.addEventListener('click', handleSendMessage);

        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                // 🔥 VERIFICA LA VARIABLE ANTES DE EJECUTAR
                if (!enviandoPreguntaFrecuente) {
                    handleSendMessage();
                }
            }
        });

        // 🔥 PREGUNTAS FRECUENTES - SOLUCIÓN DEFINITIVA
        preguntasEjemplo.forEach(btn => {
            // 🔥 REMOVER CUALQUIER LISTENER PREVIO
            btn.removeEventListener('click', btn._clickHandler);
            
            // 🔥 CREAR HANDLER UNA SOLA VEZ
            btn._clickHandler = function(e) {
                e.preventDefault();
                e.stopImmediatePropagation(); // 🔥 PARA INMEDIATAMENTE LA PROPAGACIÓN
                
                // 🔥 VERIFICAR SI YA ESTÁ PROCESANDO
                if (btn.disabled || enviandoPreguntaFrecuente) return;
                
                const pregunta = this.getAttribute('data-pregunta');
                console.log('🔥 ENVIANDO PREGUNTA FRECUENTE:', pregunta);
                
                // 🔥 DESHABILITAR BOTÓN TEMPORALMENTE
                btn.disabled = true;
                enviandoPreguntaFrecuente = true;
                messageInput.value = '';
                
                sendMessage(pregunta);
                
                setTimeout(() => {
                    btn.disabled = false;
                    enviandoPreguntaFrecuente = false;
                }, 1000); // 🔥 TIEMPO MÁS LARGO
            };
            
            // 🔥 AGREGAR EL LISTENER UNA SOLA VEZ
            btn.addEventListener('click', btn._clickHandler);
        });

        limpiarChatBtn.addEventListener('click', limpiarChat);
    }

    function cargarEstadisticasRapidas() {
        const container = document.getElementById('estadisticasRapidas');

        fetch('/chatbot/estadisticas', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            return response.json();
        })
        .then(data => mostrarEstadisticas(container, data))
        .catch(error => {
            console.error('Error cargando estadísticas:', error);
            mostrarErrorEstadisticas(container);
        });
    }

    function mostrarEstadisticas(container, data) {
        const balanceClass = data.balance_actual < 0 ? 'negativo' : 'positivo';

        container.innerHTML = `
            <div class="resumen-grid">
                <div class="resumen-card ${balanceClass}">
                    <h6>Balance del Mes</h6>
                    <h3>$${formatNumber(data.balance_actual)}</h3>
                </div>
                <div class="resumen-card ingresos">
                    <h6>Ingresos del Mes</h6>
                    <h4>$${formatNumber(data.ingresos_mes)}</h4>
                </div>
                <div class="resumen-card egresos">
                    <h6>Gastos del Mes</h6>
                    <h4>$${formatNumber(data.egresos_mes)}</h4>
                </div>
                <div class="resumen-card ahorros">
                    <h6>Total Ahorros</h6>
                    <h4>$${formatNumber(data.total_ahorros)}</h4>
                </div>
                <div class="resumen-card metas">
                    <h6>Metas Activas</h6>
                    <h4>${data.metas_activas}</h4>
                </div>
            </div>
        `;
    }

    function mostrarErrorEstadisticas(container) {
        container.innerHTML = `
            <div class="alerta">
                No se pudieron cargar las estadísticas
            </div>
        `;
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('es-CO', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(num || 0);
    }

    // 🔥 FUNCIÓN CON CONDICIONAL PARA BLOQUEAR DOBLE ENVÍO
    function handleSendMessage() {
        if (enviandoPreguntaFrecuente) return; // 🔥 CONDICIONAL QUE BLOQUEA
        
        const message = messageInput.value.trim();
        if (!message) return;

        sendMessage(message);
        messageInput.value = '';
    }

    function sendMessage(mensaje) {
        // 🔥 DEBUG: Verificar si se llama múltiples veces
        console.log('🔥 LLAMANDO sendMessage con:', mensaje);
        
        addMessage(mensaje, true);
        toggleSendButton(true);

        fetch('/chatbot/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: mensaje })
        })
        .then(response => {
            console.log('🔥 RESPUESTA DEL SERVIDOR:', response.status); // 🔥 DEBUG
            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
            return response.json();
        })
        .then(data => {
            console.log('🔥 DATA RECIBIDA:', data); // 🔥 DEBUG
            if (data.response) {
                addMessage(data.response, false);
                setTimeout(() => cargarEstadisticasRapidas(), 1000);
            } else {
                addMessage('No se recibió una respuesta válida', false);
            }
        })
        .catch(error => {
            console.error('Error en el chat:', error);
            addMessage('Error de conexión. Intenta de nuevo.', false);
        })
        .finally(() => toggleSendButton(false));
    }

    function addMessage(message, isUser) {
        const messageDiv = document.createElement('div');
        messageDiv.className = isUser ? 'message-user' : 'message-bot';

        const formattedMessage = processMessage(message);
        const messageClass = isUser ? 'user-message' : 'bot-message';
        const sender = isUser ? 'Tú' : 'Asistente';

        messageDiv.innerHTML = `
            <div class="message-content ${messageClass}">
                <strong>${sender}:</strong><br>${formattedMessage}
            </div>
        `;

        messagesContainer.appendChild(messageDiv);
        scrollToBottom();
    }

    function processMessage(message) {
        return message
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/\n/g, '<br>');
    }

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function toggleSendButton(sending) {
        if (sending) {
            sendButton.disabled = true;
            sendButtonText.classList.add('d-none');
            sendButtonSpinner.classList.remove('d-none');
        } else {
            sendButton.disabled = false;
            sendButtonText.classList.remove('d-none');
            sendButtonSpinner.classList.add('d-none');
        }
    }

    function limpiarChat() {
        messagesContainer.innerHTML = `
            <div class="message-bot">
                <div class="message-content bot-message">
                    <strong>Asistente:</strong><br>
                    Chat limpio. ¿En qué puedo ayudarte con tus finanzas?
                </div>
            </div>
        `;
        scrollToBottom();
    }
});