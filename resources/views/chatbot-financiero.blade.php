{{-- resources/views/chatbot-financiero.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="chatbot-container">
    <div class="row">
        <!-- Panel de estadísticas -->
        <div class="col-left">
            {{-- <div class="card">
                <div class="card-header primary">
                    <h6>Resumen Financiero</h6>
                </div>
                <div class="card-body" id="estadisticasRapidas">
                    <div class="loading">
                        <div class="spinner"></div>
                        <span>Cargando...</span>
                    </div>
                </div>
            </div> --}}
            
            <div class="card">
                <div class="card-header">
                    <h6>Preguntas frecuentes</h6>
                </div>
                <div class="card-body">
                    <div class="button-grid">
                        <button class="btn pregunta-ejemplo" data-pregunta="Cuanto gaste este mes">
                            ¿Cuánto gasté este mes?
                        </button>
                        <button class="btn pregunta-ejemplo" data-pregunta="Cuales son mis ingresos recientes">
                            ¿Cuáles son mis ingresos recientes?
                        </button>
                        <button class="btn pregunta-ejemplo" data-pregunta="Como van mis metas de ahorro">
                            ¿Cómo van mis metas de ahorro?
                        </button>
                        <button class="btn pregunta-ejemplo" data-pregunta="Dame un resumen de mis finanzas">
                            Dame un resumen de mis finanzas
                        </button>
                        <button class="btn pregunta-ejemplo" data-pregunta="En que gasto mas dinero">
                            ¿En qué gasto más dinero?
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chat principal -->
        <div class="col-right">
            <div class="card">
                <div class="card-header dark">
                    <h5>Asistente Financiero</h5>
                    <button class="btn small" id="limpiarChat">Limpiar</button>
                </div>
                
                <div class="card-body">
                    <div id="chatMessages" class="chat-container">
                        <div class="message-bot">
                            <div class="message-content bot-message">
                                <strong>Asistente:</strong><br>
                                Hola, soy tu asistente financiero. Puedo ayudarte a analizar tus ingresos, gastos y metas de ahorro. ¿En qué puedo ayudarte hoy?
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="input-group">
                        <input type="text" id="messageInput" class="input" placeholder="Escribe tu pregunta aquí..." maxlength="500">
                        <button class="btn primary" id="sendButton">
                            <span id="sendButtonText">Enviar</span>
                            <span id="sendButtonSpinner" class="spinner d-none"></span>
                        </button>
                    </div>
                    <small class="hint">
                        Ejemplo: "¿Cuánto gasté en comida?", "¿Cómo van mis ahorros?"
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
