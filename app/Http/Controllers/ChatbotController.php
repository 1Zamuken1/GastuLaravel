<?php
// app/Http/Controllers/ChatbotController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Ingreso;
use App\Models\Egreso;
use App\Models\AhorroMeta;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        try {
            $userMessage = $request->input('message');
            $usuarioId = Auth::id() ?? 1; // Usar ID 1 como fallback para pruebas
            
            // Log para debug
            Log::info('Chatbot: Iniciando consulta para usuario', ['usuario_id' => $usuarioId, 'mensaje' => $userMessage]);
            
            // Obtener contexto financiero del usuario
            $contextoFinanciero = $this->getContextoFinanciero($usuarioId);
            
            // Buscar información específica basada en la pregunta
            $datosEspecificos = $this->buscarDatosEspecificos($userMessage, $usuarioId);
            
            // Generar respuesta con IA
            $response = $this->generarRespuesta($userMessage, $contextoFinanciero, $datosEspecificos);
            
            return response()->json(['response' => $response]);
            
        } catch (\Exception $e) {
            Log::error('Error en chatbot: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'usuario_id' => Auth::id()
            ]);
            
            return response()->json([
                'response' => 'Lo siento, hay un problema técnico. Por favor intenta de nuevo en unos momentos.'
            ], 200); // Devolver 200 para evitar errores en el frontend
        }
    }
    
    private function getContextoFinanciero($usuarioId)
    {
        try {
            // Información del usuario
            $usuario = Usuario::find($usuarioId);
            
            if (!$usuario) {
                throw new \Exception("Usuario no encontrado");
            }
            
            // INGRESOS - Análisis completo
            $ingresosRecientes = Ingreso::where('fecha_registro', '>=', Carbon::now()->subDays(30))
                ->orderBy('fecha_registro', 'desc')
                ->take(10)
                ->get();
            
            // Intentar cargar relación solo si existe
            try {
                $ingresosRecientes->load('ConceptoIngreso');
            } catch (\Exception $e) {
                Log::warning('No se pudo cargar ConceptoIngreso para ingresos');
            }
            
            $totalIngresosMes = Ingreso::whereYear('fecha_registro', Carbon::now()->year)
                ->whereMonth('fecha_registro', Carbon::now()->month)
                ->sum('monto') ?? 0;
            
            $totalIngresosAno = Ingreso::whereYear('fecha_registro', Carbon::now()->year)
                ->sum('monto') ?? 0;
            
            // EGRESOS - Análisis completo
            $egresosRecientes = Egreso::where('fecha_registro', '>=', Carbon::now()->subDays(30))
                ->orderBy('fecha_registro', 'desc')
                ->take(10)
                ->get();
            
            // Intentar cargar relación solo si existe
            try {
                $egresosRecientes->load('ConceptoEgreso');
            } catch (\Exception $e) {
                Log::warning('No se pudo cargar ConceptoEgreso para egresos');
            }
            
            $totalEgresosMes = Egreso::whereYear('fecha_registro', Carbon::now()->year)
                ->whereMonth('fecha_registro', Carbon::now()->month)
                ->sum('monto') ?? 0;
            
            $totalEgresosAno = Egreso::whereYear('fecha_registro', Carbon::now()->year)
                ->sum('monto') ?? 0;
            
            // AHORROS - Análisis completo
            $metasAhorro = AhorroMeta::where('usuario_id', $usuarioId)
                ->where('activa', true)
                ->get();
            
            $totalAhorroAcumulado = AhorroMeta::where('usuario_id', $usuarioId)
                ->sum('total_acumulado') ?? 0;
            
            $metasPorVencer = AhorroMeta::where('usuario_id', $usuarioId)
                ->where('activa', true)
                ->where('fecha_meta', '<=', Carbon::now()->addDays(30))
                ->get();
            
            // BALANCE GENERAL
            $balanceMesActual = $totalIngresosMes - $totalEgresosMes;
            $balanceAnual = $totalIngresosAno - $totalEgresosAno;
            
            return [
                'usuario' => $usuario,
                
                // Datos de ingresos
                'ingresos_recientes' => $ingresosRecientes,
                'total_ingresos_mes' => $totalIngresosMes,
                'total_ingresos_ano' => $totalIngresosAno,
                
                // Datos de egresos
                'egresos_recientes' => $egresosRecientes,
                'total_egresos_mes' => $totalEgresosMes,
                'total_egresos_ano' => $totalEgresosAno,
                
                // Datos de ahorro
                'metas_ahorro' => $metasAhorro,
                'total_ahorro_acumulado' => $totalAhorroAcumulado,
                'metas_por_vencer' => $metasPorVencer,
                
                // Balance general
                'balance_mes_actual' => $balanceMesActual,
                'balance_anual' => $balanceAnual,
            ];
            
        } catch (\Exception $e) {
            Log::error('Error en getContextoFinanciero: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function buscarDatosEspecificos($mensaje, $usuarioId)
    {
        try {
            $mensaje = strtolower($mensaje);
            $datos = [];
            
            // Si pregunta sobre INGRESOS
            if (str_contains($mensaje, 'ingreso') || str_contains($mensaje, 'ganancia') || str_contains($mensaje, 'entrada') || str_contains($mensaje, 'cobr')) {
                $datos['ingresos_detallados'] = Ingreso::orderBy('fecha_registro', 'desc')
                    ->take(20)
                    ->get();
                    
                $datos['ingresos_por_mes'] = Ingreso::selectRaw('YEAR(fecha_registro) as ano, MONTH(fecha_registro) as mes, SUM(monto) as total')
                    ->groupBy('ano', 'mes')
                    ->orderBy('ano', 'desc')
                    ->orderBy('mes', 'desc')
                    ->take(12)
                    ->get();
            }
            
            // Si pregunta sobre EGRESOS/GASTOS
            if (str_contains($mensaje, 'egreso') || str_contains($mensaje, 'gasto') || str_contains($mensaje, 'salida') || str_contains($mensaje, 'pag')) {
                $datos['egresos_detallados'] = Egreso::orderBy('fecha_registro', 'desc')
                    ->take(20)
                    ->get();
                    
                $datos['egresos_por_mes'] = Egreso::selectRaw('YEAR(fecha_registro) as ano, MONTH(fecha_registro) as mes, SUM(monto) as total')
                    ->groupBy('ano', 'mes')
                    ->orderBy('ano', 'desc')
                    ->orderBy('mes', 'desc')
                    ->take(12)
                    ->get();
            }
            
            // Si pregunta sobre AHORROS/METAS
            if (str_contains($mensaje, 'ahorro') || str_contains($mensaje, 'meta') || str_contains($mensaje, 'objetivo')) {
                $datos['metas_detalladas'] = AhorroMeta::where('usuario_id', $usuarioId)
                    ->get();
            }
            
            return $datos;
            
        } catch (\Exception $e) {
            Log::warning('Error en buscarDatosEspecificos: ' . $e->getMessage());
            return [];
        }
    }
    
    private function generarRespuesta($mensaje, $contexto, $datosEspecificos)
    {
        // Si no hay API key configurada, devolver respuesta básica
        if (!env('GROQ_API_KEY')) {
            return $this->respuestaBasica($mensaje, $contexto);
        }
        
        // Usar Groq como IA externa
        return $this->groqResponse($mensaje, $contexto, $datosEspecificos);
    }
    
    private function respuestaBasica($mensaje, $contexto)
    {
        $mensaje = strtolower($mensaje);
        
        if (str_contains($mensaje, 'balance') || str_contains($mensaje, 'resumen')) {
            return "📊 **Resumen Financiero:**\n\n" .
                   "💰 Ingresos del mes: $" . number_format($contexto['total_ingresos_mes'], 2) . "\n" .
                   "💸 Egresos del mes: $" . number_format($contexto['total_egresos_mes'], 2) . "\n" .
                   "📈 Balance del mes: $" . number_format($contexto['balance_mes_actual'], 2) . "\n" .
                   "🏦 Total ahorros: $" . number_format($contexto['total_ahorro_acumulado'], 2);
        }
        
        if (str_contains($mensaje, 'ingreso')) {
            return "💰 Has tenido ingresos por $" . number_format($contexto['total_ingresos_mes'], 2) . " este mes. " .
                   "Tienes " . count($contexto['ingresos_recientes']) . " ingresos en los últimos 30 días.";
        }
        
        if (str_contains($mensaje, 'egreso') || str_contains($mensaje, 'gasto')) {
            return "💸 Has gastado $" . number_format($contexto['total_egresos_mes'], 2) . " este mes. " .
                   "Tienes " . count($contexto['egresos_recientes']) . " egresos en los últimos 30 días.";
        }
        
        if (str_contains($mensaje, 'ahorro')) {
            return "🏦 Tienes un total de $" . number_format($contexto['total_ahorro_acumulado'], 2) . " ahorrado. " .
                   "Tienes " . count($contexto['metas_ahorro']) . " metas de ahorro activas.";
        }
        
        return "Hola! Soy tu asistente financiero. Puedo ayudarte con información sobre tus ingresos, egresos y ahorros. " .
               "Pregúntame cosas como: '¿Cuánto gasté este mes?' o 'Dame un resumen de mis finanzas'.";
    }
    
    private function groqResponse($mensaje, $contexto, $datosEspecificos)
    {
        try {
            $client = new Client(['timeout' => 30]);
            
            $promptSistema = $this->construirPromptFinanciero($contexto, $datosEspecificos);
            
            $response = $client->post('https://api.groq.com/openai/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => [
                        ['role' => 'system', 'content' => $promptSistema],
                        ['role' => 'user', 'content' => $mensaje]
                    ],
                    'max_tokens' => 1000,
                    'temperature' => 0.7
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            return $data['choices'][0]['message']['content'] ?? 'No pude generar una respuesta.';
            
        } catch (\Exception $e) {
            Log::error('Error con Groq API: ' . $e->getMessage());
            return $this->respuestaBasica($mensaje, $contexto);
        }
    }
    
    private function construirPromptFinanciero($contexto, $datosEspecificos)
    {
        $prompt = "Eres un asistente financiero personal especializado. Ayudas a usuarios a entender sus finanzas personales.\n\n";
        
        // Info del usuario
        if (!empty($contexto['usuario'])) {
            $usuario = $contexto['usuario'];
            $prompt .= "USUARIO: {$usuario->nombre} (registrado desde {$usuario->fecha_registro})\n\n";
        }
        
        // Resumen financiero actual
        $prompt .= "RESUMEN FINANCIERO ACTUAL:\n";
        $prompt .= "💰 Ingresos este mes: $" . number_format($contexto['total_ingresos_mes'], 2) . "\n";
        $prompt .= "💸 Egresos este mes: $" . number_format($contexto['total_egresos_mes'], 2) . "\n";
        $prompt .= "📊 Balance del mes: $" . number_format($contexto['balance_mes_actual'], 2) . "\n";
        $prompt .= "🏦 Total ahorrado: $" . number_format($contexto['total_ahorro_acumulado'], 2) . "\n";
        $prompt .= "📈 Balance anual: $" . number_format($contexto['balance_anual'], 2) . "\n\n";
        
        // Ingresos recientes (simplificado)
        if (!empty($contexto['ingresos_recientes']) && count($contexto['ingresos_recientes']) > 0) {
            $prompt .= "INGRESOS RECIENTES (últimos 30 días): " . count($contexto['ingresos_recientes']) . " registros\n";
            $prompt .= "Total de ingresos recientes: $" . number_format($contexto['ingresos_recientes']->sum('monto'), 2) . "\n\n";
        }
        
        // Egresos recientes (simplificado)
        if (!empty($contexto['egresos_recientes']) && count($contexto['egresos_recientes']) > 0) {
            $prompt .= "EGRESOS RECIENTES (últimos 30 días): " . count($contexto['egresos_recientes']) . " registros\n";
            $prompt .= "Total de egresos recientes: $" . number_format($contexto['egresos_recientes']->sum('monto'), 2) . "\n\n";
        }
        
        // Metas de ahorro
        if (!empty($contexto['metas_ahorro']) && count($contexto['metas_ahorro']) > 0) {
            $prompt .= "METAS DE AHORRO ACTIVAS: " . count($contexto['metas_ahorro']) . " metas\n";
            foreach ($contexto['metas_ahorro'] as $meta) {
                $porcentaje = $meta->monto_meta > 0 ? ($meta->total_acumulado / $meta->monto_meta) * 100 : 0;
                $prompt .= "- {$meta->concepto}: $" . number_format($meta->total_acumulado, 2) . " de $" . number_format($meta->monto_meta, 2) . " (" . round($porcentaje, 1) . "%)\n";
            }
            $prompt .= "\n";
        }
        
        $prompt .= "INSTRUCCIONES:\n";
        $prompt .= "- Responde como un asesor financiero personal amigable\n";
        $prompt .= "- Usa los datos específicos para dar respuestas precisas\n";
        $prompt .= "- Da consejos financieros cuando sea apropiado\n";
        $prompt .= "- Formatea los números con símbolos de moneda\n";
        $prompt .= "- Responde en español de manera clara y útil\n";
        $prompt .= "- Mantén las respuestas concisas pero informativas\n";
        
        return $prompt;
    }
    
    // Método adicional para obtener estadísticas rápidas
    public function getEstadisticasRapidas()
    {
        try {
            $usuarioId = Auth::id() ?? 1; // Usar ID 1 como fallback para pruebas
            $contexto = $this->getContextoFinanciero($usuarioId);

            return response()->json([
                'balance_actual' => $contexto['balance_mes_actual'] ?? 0,
                'ingresos_mes' => $contexto['total_ingresos_mes'] ?? 0,
                'egresos_mes' => $contexto['total_egresos_mes'] ?? 0,
                'total_ahorros' => $contexto['total_ahorro_acumulado'] ?? 0,
                'metas_activas' => isset($contexto['metas_ahorro']) ? count($contexto['metas_ahorro']) : 0
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en getEstadisticasRapidas: ' . $e->getMessage());
            
            return response()->json([
                'balance_actual' => 0,
                'ingresos_mes' => 0,
                'egresos_mes' => 0,
                'total_ahorros' => 0,
                'metas_activas' => 0
            ]);
        }
    }
}