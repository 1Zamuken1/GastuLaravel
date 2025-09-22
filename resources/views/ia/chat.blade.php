    {{-- resources/views/chatbot-financiero.blade.php --}}
@extends('layouts.app')

@section('title', 'Asistente Financiero IA')

@push('styles')
    {{-- Estilos específicos del chatbot --}}
    <style>
        .chatbot-container {
            max-width: 800px;
            margin: auto;
        }
    </style>
@endpush

@section('content')
<div class="chatbot-container bg-white rounded-lg shadow-lg p-6">
    <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">
        Asistente Financiero IA
    </h1>
    
    <!-- Área de chat -->
    <div id="chatArea" class="h-96 overflow-y-auto border border-gray-300 rounded-lg p-4 mb-4 bg-gray-50">
        <div class="message ia-message mb-3">
            <div class="bg-blue-100 p-3 rounded-lg">
                <strong>IA:</strong> ¡Hola {{ Auth::user()->nombre }}! 
                Soy tu asistente financiero. Puedo ayudarte a analizar tus ingresos, egresos y ahorros. 
                ¿En qué te puedo ayudar?
            </div>
        </div>
    </div>

    <!-- Formulario de mensaje -->
    <div class="flex gap-2">
        <input 
            type="text" 
            id="mensajeInput" 
            placeholder="Escribe tu pregunta aquí..." 
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <button 
            id="enviarBtn" 
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
            Enviar
        </button>
    </div>

    <!-- Botones de acciones rápidas -->
    <div class="mt-4 flex flex-wrap gap-2">
        <button class="accion-rapida px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm hover:bg-green-200" 
                data-mensaje="¿Cuál es mi balance actual?">
            Balance Actual
        </button>
        <button class="accion-rapida px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200" 
                data-mensaje="¿Cuáles son mis mayores gastos?">
            Mayores Gastos
        </button>
        <button class="accion-rapida px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-sm hover:bg-purple-200" 
                data-mensaje="¿Cómo van mis ahorros?">
            Estado Ahorros
        </button>
        <button class="accion-rapida px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-sm hover:bg-yellow-200" 
                data-mensaje="Dame consejos para mejorar mis finanzas">
            Consejos
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const chatArea = document.getElementById('chatArea');
    const mensajeInput = document.getElementById('mensajeInput');
    const enviarBtn = document.getElementById('enviarBtn');
    const accionesRapidas = document.querySelectorAll('.accion-rapida');

    function agregarMensaje(mensaje, esUsuario = false) {
        const div = document.createElement('div');
        div.className = `message ${esUsuario ? 'usuario-message' : 'ia-message'} mb-3`;
        
        const bgColor = esUsuario ? 'bg-gray-100' : 'bg-blue-100';
        const autor = esUsuario ? 'Tú' : 'IA';
        
        div.innerHTML = `
            <div class="${bgColor} p-3 rounded-lg">
                <strong>${autor}:</strong> ${mensaje}
            </div>
        `;
        
        chatArea.appendChild(div);
        chatArea.scrollTop = chatArea.scrollHeight;
    }

    async function enviarMensaje(mensaje) {
        if (!mensaje.trim()) return;
        
        agregarMensaje(mensaje, true);
        mensajeInput.value = '';
        enviarBtn.disabled = true;
        enviarBtn.textContent = 'Enviando...';
        
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
            
            if (data.success) {
                agregarMensaje(data.respuesta);
            } else {
                agregarMensaje('Error: ' + (data.error || 'No se pudo procesar el mensaje'));
            }
        } catch (error) {
            agregarMensaje('Error de conexión. Por favor intenta de nuevo.');
        } finally {
            enviarBtn.disabled = false;
            enviarBtn.textContent = 'Enviar';
            mensajeInput.focus();
        }
    }

    enviarBtn.addEventListener('click', () => enviarMensaje(mensajeInput.value));
    mensajeInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') enviarMensaje(mensajeInput.value);
    });
    accionesRapidas.forEach(btn => {
        btn.addEventListener('click', () => enviarMensaje(btn.getAttribute('data-mensaje')));
    });

    mensajeInput.focus();
</script>
@endpush
